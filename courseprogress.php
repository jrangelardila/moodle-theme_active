<?php
// This file is part of Moodle - https://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/**
 * Course progress landing page for students.
 *
 * Displays a professional overview of the student's completion status:
 * activities grouped by section, completion requirements, deadlines,
 * and teacher contact information.
 *
 * @package     theme_active
 * @copyright   2024 Jhon Rangel <jrangelardila@gmail.com>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__ . '/../../config.php');
require_once($CFG->libdir . '/completionlib.php');
require_once(__DIR__ . '/lib.php');

$courseid = required_param('id', PARAM_INT);
$print = optional_param('print', 0, PARAM_BOOL);

$course = $DB->get_record('course', ['id' => $courseid], '*', MUST_EXIST);

$PAGE->set_url('/theme/active/courseprogress.php', ['id' => $courseid]);
$PAGE->set_pagelayout($print ? 'print' : 'standard');

require_login($course);

$context = context_course::instance($course->id);

$completion = new completion_info($course);
if (!$completion->is_enabled()) {
    throw new moodle_exception('completionnotenabled', 'completion');
}
if (!$completion->is_tracked_user($USER->id)) {
    throw new moodle_exception('nopermissions', 'error', '', 'view this page');
}

$PAGE->set_title(get_string('courseprogress', 'theme_active') . ': ' . format_string($course->fullname));
$PAGE->set_heading(format_string($course->fullname));


$activities = $completion->get_activities();
$modinfo = get_fast_modinfo($course);
$totalactivities = count($activities);
$completedcount = 0;

$activitylist = [];

foreach ($activities as $cminfo) {
    if (!$cminfo->visible) {
        continue;
    }

    $cmdetails = \core_completion\cm_completion_details::get_instance($cminfo, $USER->id);
    $data = $completion->get_data($cminfo, true, $USER->id);

    $state = (int)$data->completionstate;
    $isdone = ($state == COMPLETION_COMPLETE || $state == COMPLETION_COMPLETE_PASS);
    $isfailed = ($state == COMPLETION_COMPLETE_FAIL);

    if ($isdone) {
        $completedcount++;
    }

    $section = $modinfo->get_section_info($cminfo->sectionnum);
    $sectionname = format_string(get_section_name($course, $section));

    $requirements = [];
    if ($cmdetails->is_automatic()) {
        $details = $cmdetails->get_details();
        foreach ($details as $detail) {
            $reqcomplete = ($detail->status == COMPLETION_COMPLETE || $detail->status == COMPLETION_COMPLETE_PASS);
            $reqfailed = ($detail->status == COMPLETION_COMPLETE_FAIL);
            $requirements[] = [
                'description' => $detail->description,
                'reqcomplete' => $reqcomplete,
                'reqfailed' => $reqfailed,
                'reqpending' => !$reqcomplete && !$reqfailed,
            ];
        }
    } else if ($cmdetails->is_manual()) {
        $requirements[] = [
            'description' => get_string('manualcompletion', 'theme_active'),
            'reqcomplete' => $isdone,
            'reqfailed' => false,
            'reqpending' => !$isdone,
        ];
    }

    // Activity dates.
    $dates = \core\activity_dates::get_dates_for_module($cminfo, $USER->id);
    $activitydates = [];
    $hasdeadline = false;
    $isoverdue = false;
    foreach ($dates as $dateinfo) {
        if (empty($dateinfo['timestamp'])) {
            continue;
        }
        $timestamp = $dateinfo['timestamp'];
        $formatted = userdate($timestamp, get_string('strftimedaydatetime', 'langconfig'));
        $ispast = ($timestamp < time());
        $dateid = $dateinfo['dataid'] ?? '';
        $isclosingdate = in_array($dateid, ['duedate', 'timeclose', 'cutoffdate', 'gradingduedate']);

        if ($isclosingdate) {
            $hasdeadline = true;
            if ($ispast && !$isdone) {
                $isoverdue = true;
            }
        }

        $activitydates[] = [
            'label' => $dateinfo['label'] ?? '',
            'datestr' => $formatted,
            'ispast' => $ispast && $isclosingdate && !$isdone,
        ];
    }

    $isrestricted = !$cminfo->uservisible;
    $url = '';
    if (!$isrestricted && $cminfo->url) {
        $url = $cminfo->url->out(false);
    }
    $iconurl = $cminfo->get_icon_url()->out(false);

    $restrictioninfo = '';
    if ($isrestricted && !empty($cminfo->availableinfo)) {
        $restrictioninfo = \core_availability\info::format_info($cminfo->availableinfo, $cminfo->get_course());
    }

    $activitylist[] = [
        'cmid' => $cminfo->id,
        'name' => format_string($cminfo->name),
        'url' => $url,
        'iconurl' => $iconurl,
        'modname' => format_string($cminfo->modfullname),
        'sectionname' => $sectionname,
        'sectionnum' => $cminfo->sectionnum,
        'isdone' => $isdone,
        'isfailed' => $isfailed,
        'ispending' => !$isdone && !$isfailed,
        'isrestricted' => $isrestricted,
        'restrictioninfo' => $restrictioninfo,
        'hasrequirements' => !empty($requirements),
        'requirements' => $requirements,
        'hasdates' => !empty($activitydates),
        'dates' => $activitydates,
        'hasdeadline' => $hasdeadline,
        'isoverdue' => $isoverdue,
    ];
}

$percent = $totalactivities > 0 ? (int) floor(($completedcount / $totalactivities) * 100) : 0;

$sections = [];
$sectionmap = [];
foreach ($activitylist as $act) {
    $sname = $act['sectionname'];
    if (!isset($sectionmap[$sname])) {
        $sectionmap[$sname] = count($sections);
        $sections[] = [
            'sectionname' => $sname,
            'sectionnum' => $act['sectionnum'],
            'activities' => [],
            'sectiondone' => 0,
            'sectiontotal' => 0,
        ];
    }
    $idx = $sectionmap[$sname];
    $sections[$idx]['activities'][] = $act;
    $sections[$idx]['sectiontotal']++;
    if ($act['isdone']) {
        $sections[$idx]['sectiondone']++;
    }
}

foreach ($sections as &$sec) {
    $sec['sectioncomplete'] = ($sec['sectiondone'] == $sec['sectiontotal'] && $sec['sectiontotal'] > 0);
    $sec['sectionprogress'] = get_string('progressof', 'theme_active', (object)[
        'completed' => $sec['sectiondone'],
        'total' => $sec['sectiontotal'],
    ]);
    if ($sec['sectiontotal'] > 0) {
        $sec['sectionpercent'] = (int)floor(($sec['sectiondone'] / $sec['sectiontotal']) * 100);
    } else {
        $sec['sectionpercent'] = 0;
    }
}
unset($sec);


$teachers = theme_active_get_course_teachers($courseid);
$teacherlist = [];
$renderer = $PAGE->get_renderer('core');
$canseeallemails = has_capability('moodle/course:useremail', $context);

foreach ($teachers as $teacher) {
    $userpicture = new user_picture($teacher);
    $userpicture->size = 120;
    $avatarurl = $userpicture->get_url($PAGE, $renderer)->out(false);

    $profileurl = (new moodle_url('/user/profile.php', ['id' => $teacher->id, 'course' => $courseid]))->out(false);
    $messageurl = (new moodle_url('/message/index.php', ['id' => $teacher->id]))->out(false);

    $teacherlist[] = [
        'fullname' => format_string(fullname($teacher)),
        'email' => ($canseeallemails
            || in_array((int)$teacher->maildisplay, [\core_user::MAILDISPLAY_EVERYONE, \core_user::MAILDISPLAY_COURSE_MEMBERS_ONLY]))
            ? $teacher->email : '',
        'avatarurl' => $avatarurl,
        'profileurl' => $profileurl,
        'messageurl' => $messageurl,
    ];
}

$studentpicture = new user_picture($USER);
$studentpicture->size = 60;
$studentavatarurl = $studentpicture->get_url($PAGE, $renderer)->out(false);

$courseimageurl = theme_active_get_course_image_url($courseid);

$courseurl = (new moodle_url('/course/view.php', ['id' => $courseid]))->out(false);
$printurl = (new moodle_url('/theme/active/courseprogress.php', ['id' => $courseid, 'print' => 1]))->out(false);

$templatecontext = [
    // Course info
    'coursename' => format_string($course->fullname),
    'courseshortname' => format_string($course->shortname),
    'courseurl' => $courseurl,
    'hascourseimage' => !empty($courseimageurl),
    'courseimageurl' => $courseimageurl,

    // Student info
    'studentname' => format_string(fullname($USER)),
    'studentavatarurl' => $studentavatarurl,
    'reportdate' => userdate(time(), get_string('strftimedaydatetime', 'langconfig')),

    // Progress
    'progresspercent' => $percent,
    'progresscount' => $completedcount,
    'progresstotal' => $totalactivities,
    'progressof' => get_string('progressof', 'theme_active', (object)[
        'completed' => $completedcount,
        'total' => $totalactivities,
    ]),
    'iscomplete' => ($percent >= 100),

    // Sections with activities
    'hassections' => !empty($sections),
    'sections' => $sections,

    // Teachers
    'hasteachers' => !empty($teacherlist),
    'teachers' => $teacherlist,

    // Print
    'isprint' => $print,
    'printurl' => $printurl,

    // Labels
    'lbl_courseprogress' => get_string('courseprogress', 'theme_active'),
    'lbl_backtocourse' => get_string('backtocourse', 'theme_active'),
    'lbl_exportprint' => get_string('exportprint', 'theme_active'),
    'lbl_activitylist' => get_string('activitylist', 'theme_active'),
    'lbl_overdue' => get_string('overdue', 'theme_active'),
    'lbl_manualcompletion' => get_string('manualcompletion', 'theme_active'),
    'lbl_teachers' => get_string('teacherscontact', 'theme_active'),
    'lbl_sendmessage' => get_string('sendmessage', 'theme_active'),
    'lbl_viewprofile' => get_string('viewprofile', 'theme_active'),
    'lbl_generatedon' => get_string('generatedon', 'theme_active'),
    'lbl_student' => get_string('student', 'theme_active'),
    'lbl_instructor' => get_string('instructor', 'theme_active'),
    'lbl_restricted' => get_string('restricted', 'theme_active'),
];

echo $OUTPUT->header();
echo $OUTPUT->render_from_template('theme_active/courseprogress_page', $templatecontext);
echo $OUTPUT->footer();
