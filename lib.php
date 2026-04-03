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
 * Theme functions.
 *
 * @package     theme_active
 * @copyright   2024 Jhon Rangel <jrangelardila@gmail.com>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();


/**
 * Serve theme_active stored files.
 *
 * @param stdClass $course
 * @param stdClass $cm
 * @param context  $context
 * @param string   $filearea
 * @param array    $args
 * @param bool     $forcedownload
 * @param array    $options
 * @return bool
 */
function theme_active_pluginfile($course, $cm, $context, $filearea, $args, $forcedownload, array $options = []) {
    if ($context->contextlevel == CONTEXT_SYSTEM && $filearea === 'loginimage') {
        $theme = theme_config::load('active');
        if (!array_key_exists('cacheability', $options)) {
            $options['cacheability'] = 'public';
        }
        return $theme->setting_file_serve($filearea, $args, $forcedownload, $options);
    }
    send_file_not_found();
}

/**
 * Returns the main SCSS content.
 *
 * @param theme_config $theme The theme config object.
 * @return string
 */
function theme_active_get_main_scss_content($theme) {
    global $CFG;

    $scss = '';
    $filename = !empty($theme->settings->preset) ? $theme->settings->preset : null;
    $fs = get_file_storage();

    $context = context_system::instance();
    if ($filename == 'default.scss') {
        $scss .= file_get_contents($CFG->dirroot . '/theme/boost/scss/preset/default.scss');
    } else if ($filename == 'plain.scss') {
        $scss .= file_get_contents($CFG->dirroot . '/theme/boost/scss/preset/plain.scss');
    } else if ($filename && ($presetfile = $fs->get_file($context->id, 'theme_active', 'preset', 0, '/', $filename))) {
        $scss .= $presetfile->get_content();
    } else {
        $scss .= file_get_contents($CFG->dirroot . '/theme/boost/scss/preset/default.scss');
    }

    $scss .= file_get_contents($CFG->dirroot . '/theme/active/scss/footer.scss');
    $scss .= file_get_contents($CFG->dirroot . '/theme/active/scss/login.scss');
    $scss .= file_get_contents($CFG->dirroot . '/theme/active/scss/navbar.scss');
    $scss .= file_get_contents($CFG->dirroot . '/theme/active/scss/header.scss');
    $scss .= file_get_contents($CFG->dirroot . '/theme/active/scss/coursecards.scss');
    $scss .= file_get_contents($CFG->dirroot . '/theme/active/scss/courseprogress.scss');
    $scss .= file_get_contents($CFG->dirroot . '/theme/active/scss/activitynav.scss');
    $scss .= file_get_contents($CFG->dirroot . '/theme/active/scss/booknav.scss');
    $scss .= file_get_contents($CFG->dirroot . '/theme/active/scss/forumnav.scss');
    $scss .= file_get_contents($CFG->dirroot . '/theme/active/scss/courseindex.scss');
    $scss .= file_get_contents($CFG->dirroot . '/theme/active/scss/layout.scss');
    $scss .= file_get_contents($CFG->dirroot . '/theme/active/scss/navicons.scss');
    $scss .= file_get_contents($CFG->dirroot . '/theme/active/scss/calendar.scss');

    return $scss;
}

/**
 * Get SCSS to prepend.
 *
 * @param theme_config $theme The theme config object.
 * @return string
 */
function theme_active_get_pre_scss($theme) {
    $scss = '';

    $configurable = [
        'primarycolor'   => ['primary'],
        'secondarycolor' => ['secondary'],
    ];

    foreach ($configurable as $configkey => $targets) {
        $value = isset($theme->settings->{$configkey}) ? $theme->settings->{$configkey} : null;
        if (empty($value)) {
            continue;
        }
        array_map(function($target) use (&$scss, $value) {
            $scss .= '$' . $target . ': ' . $value . ";\n";
        }, (array) $targets);
    }

    // Widen the default content
    $contentwidth = $theme->settings->contentwidth ?? 100;
    $scss .= '$course-content-maxwidth: ' . $contentwidth . '%;' . "\n";
    $scss .= '$drawer-bg-color: mix($secondary, #fff, 10%);' . "\n";

    if (!empty($theme->settings->scsspre)) {
        $scss .= $theme->settings->scsspre;
    }

    return $scss;
}

/**
 * Inject additional SCSS.
 *
 * @param theme_config $theme The theme config object.
 * @return string
 */
function theme_active_get_extra_scss($theme) {
    $content = '';

    $loginimageurl = $theme->setting_file_url('loginimage', 'loginimage');
    if (!empty($loginimageurl)) {
        $content .= 'html.pagelayout-login-index, body.pagelayout-login {'
            . 'height: auto !important;'
            . 'min-height: 100vh !important; }';
        $content .= 'body.pagelayout-login {'
            . 'background: url("' . $loginimageurl . '") no-repeat center center !important;'
            . 'background-size: cover !important;'
            . 'background-attachment: fixed !important; }';
        $content .= 'body.pagelayout-login #page-wrapper {'
            . 'height: auto !important;'
            . 'min-height: 100vh !important;'
            . 'background: transparent !important; }';
        $content .= 'body.pagelayout-login #page,'
            . 'body.pagelayout-login #region-main-box,'
            . 'body.pagelayout-login #region-main {'
            . 'background: none !important;'
            . 'background-color: transparent !important; }';
    }

    return !empty($theme->settings->scss) ? $theme->settings->scss . ' ' . $content : $content;
}

/**
 * Build the template context array for the theme_active footer.
 *
 * Reads social-media URLs, app-store URLs and site info from theme settings
 * and returns a structured array ready for use in mustache templates.
 *
 * @return array
 */
function theme_active_get_footer_context(): array {
    $theme = \theme_config::load('active');

    // Social networks with Font Awesome icon class and brand colour.
    $networks = [
        'facebook'  => ['icon' => 'fa-facebook-f',  'color' => '#1877F2'],
        'twitter'   => ['icon' => 'fa-twitter',      'color' => '#1DA1F2'],
        'instagram' => ['icon' => 'fa-instagram',    'color' => '#E4405F'],
        'linkedin'  => ['icon' => 'fa-linkedin-in',  'color' => '#0A66C2'],
        'youtube'   => ['icon' => 'fa-youtube',      'color' => '#FF0000'],
        'tiktok'    => ['icon' => 'fa-tiktok',       'color' => '#010101'],
    ];

    $sociallinks = [];
    foreach ($networks as $key => $meta) {
        $url = $theme->settings->{$key . '_url'} ?? '';
        if (empty($url)) {
            continue;
        }
        $sociallinks[] = [
            'url'   => $url,
            'icon'  => $meta['icon'],
            'color' => $meta['color'],
            'name'  => get_string($key . '_url', 'theme_active'),
        ];
    }

    // App download links.
    $androidurl = $theme->settings->moodleapp_android_url ?? '';
    $iosurl     = $theme->settings->moodleapp_ios_url ?? '';

    $apps = [];
    if (!empty($androidurl)) {
        $apps[] = [
            'url'   => $androidurl,
            'icon'  => 'fa-google-play',
            'label' => get_string('moodleapp_android', 'theme_active'),
        ];
    }
    if (!empty($iosurl)) {
        $apps[] = [
            'url'   => $iosurl,
            'icon'  => 'fa-apple',
            'label' => get_string('moodleapp_ios', 'theme_active'),
        ];
    }

    // Info links.
    $infolinks = [
        [
            'url'   => (new \moodle_url('/'))->out(false),
            'icon'  => 'fa-house',
            'label' => format_string(get_site()->fullname),
        ],
        [
            'url'   => (new \moodle_url('/admin/tool/policy/viewall.php'))->out(false),
            'icon'  => 'fa-shield-halved',
            'label' => get_string('privacy', 'theme_active'),
        ],
    ];

    // Site info.
    $site = get_site();

    return [
        'sitename'    => format_string($site->shortname),
        'sitefullname' => format_string($site->fullname),
        'sitesummary' => format_string($site->summary ?? ''),
        'hassummary'  => !empty($site->summary),
        'hassocial'   => !empty($sociallinks),
        'sociallinks' => $sociallinks,
        'hasapps'     => !empty($apps),
        'apps'        => $apps,
        'infolinks'   => $infolinks,
        'lbl_social'  => get_string('follow_social_media', 'theme_active'),
        'lbl_apps'    => get_string('downloadapp', 'theme_active'),
        'lbl_info'    => get_string('information', 'theme_active'),
        'currentyear' => date('Y'),
    ];
}

/**
 * Build the template context for the course completion progress bar.
 *
 * Returns progress data only when ALL conditions are met:
 * - We are inside a course (not the site home).
 * - The course has completion tracking enabled.
 * - The current user is a tracked student (enrolled with completion reporting capability).
 *
 * @return array Template context with 'hasprogress', 'progresspercent', 'progresslabel', etc.
 */
function theme_active_get_course_progress_context(): array {
    global $COURSE, $USER, $PAGE;

    $empty = ['hasprogress' => false];

    if (empty($COURSE->id) || $COURSE->id == SITEID) {
        return $empty;
    }

    $courselayouts = ['course'];
    if (!in_array($PAGE->pagelayout, $courselayouts)) {
        return $empty;
    }

    $completion = new \completion_info($COURSE);
    if (!$completion->is_enabled()) {
        return $empty;
    }

    if (!$completion->is_tracked_user($USER->id)) {
        return $empty;
    }

    $percentage = \core_completion\progress::get_course_progress_percentage($COURSE, $USER->id);
    if ($percentage === null) {
        return $empty;
    }

    $percent = (int) floor($percentage);

    $activities = $completion->get_activities();
    $total = count($activities);
    $completed = 0;
    foreach ($activities as $activity) {
        $data = $completion->get_data($activity, true, $USER->id);
        if ($data->completionstate != COMPLETION_INCOMPLETE
            && $data->completionstate != COMPLETION_COMPLETE_FAIL) {
            $completed++;
        }
    }

    $progressurl = (new \moodle_url('/theme/active/courseprogress.php', ['id' => $COURSE->id]))->out(false);

    return [
        'hasprogress'     => true,
        'progresspercent' => $percent,
        'progresscount'   => $completed,
        'progresstotal'   => $total,
        'progresslabel'   => get_string('courseprogress', 'theme_active'),
        'progressof'      => get_string('progressof', 'theme_active', (object)[
            'completed' => $completed,
            'total'     => $total,
        ]),
        'iscomplete'      => ($percent >= 100),
        'progressurl'     => $progressurl,
        'lbl_viewdetails' => get_string('viewdetails', 'theme_active'),
    ];
}

/**
 * Build and render the activity navigation (prev/next) for the current page.
 *
 * Works on 'incourse' layout when viewing a module, regardless of whether
 * the theme uses the course index drawer.
 *
 * @param moodle_page $page
 * @param renderer_base $output
 * @return string Rendered HTML or empty string.
 */
function theme_active_get_activity_navigation(\moodle_page $page, \renderer_base $output): string {
    if ($page->pagelayout !== 'incourse') {
        return '';
    }

    if ($page->context->contextlevel != CONTEXT_MODULE) {
        return '';
    }

    try {
        $cm = $page->cm;
    } catch (\Throwable $e) {
        return '';
    }

    if (!$cm) {
        return '';
    }

    $course = $cm->get_course();
    $modules = get_fast_modinfo($course->id)->get_cms();

    $mods = [];
    foreach ($modules as $module) {
        if (!empty($module->url) && $module->uservisible) {
            $mods[$module->id] = $module;
        }
    }

    if (!isset($mods[$cm->id]) && !empty($cm->url)) {
        $mods[$cm->id] = $cm;
        $sorted = [];
        foreach ($modules as $module) {
            if (isset($mods[$module->id])) {
                $sorted[$module->id] = $mods[$module->id];
            }
        }
        $mods = $sorted;
    }

    if (count($mods) <= 1) {
        return '';
    }

    $modids = array_keys($mods);
    $position = array_search($cm->id, $modids);
    if ($position === false) {
        return '';
    }

    $data = [
        'hasprev'  => ($position > 0),
        'hasnext'  => ($position < count($mods) - 1),
        'position' => $position + 1,
        'total'    => count($mods),
    ];

    if ($data['hasprev']) {
        $prev = $mods[$modids[$position - 1]];
        $data['prev'] = [
            'url'     => (new \moodle_url($prev->url, ['forceview' => 1]))->out(false),
            'name'    => $prev->get_formatted_name(),
            'iconurl' => $prev->get_icon_url()->out(false),
            'modname' => $prev->modfullname,
        ];
    }

    if ($data['hasnext']) {
        $next = $mods[$modids[$position + 1]];
        $data['next'] = [
            'url'     => (new \moodle_url($next->url, ['forceview' => 1]))->out(false),
            'name'    => $next->get_formatted_name(),
            'iconurl' => $next->get_icon_url()->out(false),
            'modname' => $next->modfullname,
        ];
    }

    return $output->render_from_template('theme_active/activity_navigation', $data);
}

/**
 * Get users with teacher or editingteacher role in a course.
 *
 * @param int $courseid
 * @return array
 */
function theme_active_get_course_teachers(int $courseid): array {
    global $DB;

    $sql = "SELECT DISTINCT u.*
              FROM {user} u
              JOIN {role_assignments} ra ON ra.userid = u.id
              JOIN {context} ctx ON ctx.id = ra.contextid AND ctx.contextlevel = :contextlevel
              JOIN {role} r ON r.id = ra.roleid AND r.shortname IN ('editingteacher', 'teacher')
             WHERE ctx.instanceid = :courseid AND u.deleted = 0
          ORDER BY u.lastname, u.firstname";

    return $DB->get_records_sql($sql, [
        'contextlevel' => CONTEXT_COURSE,
        'courseid' => $courseid,
    ]);
}

/**
 * Build template context for teacher avatars in the course header banner.
 *
 * Returns an array with 'hasheaderteachers' (bool) and 'headerteachers' (list)
 * only when the current page belongs to a real course.
 *
 * @param moodle_page $page
 * @param renderer_base $output
 * @return array
 */
function theme_active_get_header_teachers_context(\moodle_page $page, \renderer_base $output): array {
    global $COURSE;

    if (empty($COURSE->id) || $COURSE->id == SITEID) {
        return ['hasheaderteachers' => false, 'headerteachers' => []];
    }

    $teachers = theme_active_get_course_teachers((int)$COURSE->id);
    if (empty($teachers)) {
        return ['hasheaderteachers' => false, 'headerteachers' => []];
    }

    $list = [];
    foreach ($teachers as $teacher) {
        $userpicture = new \user_picture($teacher);
        $userpicture->size = 100;
        $avatarurl = $userpicture->get_url($page, $output)->out(false);

        $profileurl = (new \moodle_url('/user/profile.php', [
            'id' => $teacher->id,
            'course' => $COURSE->id,
        ]))->out(false);

        $list[] = [
            'fullname'   => format_string(fullname($teacher)),
            'avatarurl'  => $avatarurl,
            'profileurl' => $profileurl,
        ];
    }

    return [
        'hasheaderteachers' => true,
        'headerteachers'    => $list,
    ];
}

/**
 * Find activities that Moodle hides from the course index but are NOT
 * teacher-hidden.
 *
 * This covers two cases:
 * - Stealth activities (visible=1, visibleoncoursepage=0): accessible via
 *   direct link but missing from the course page and course index.
 * - Restricted "Entirely hidden" activities (visible=1, available=0,
 *   showavailability=0): hidden by availability conditions, not by teacher.
 *
 * Only activities explicitly hidden by the teacher (visible=0) are excluded.
 *
 * @param int $courseid
 * @return array  Two keys: 'cms' (array of cm state objects) and
 *                'sectioncms' (map sectionid → array of cmids to append).
 */
/**
 * Build a complete custom course index that includes ALL activities
 * (normal, stealth, restricted) except those explicitly hidden by the teacher.
 *
 * This replaces Moodle's default course index which omits stealth and
 * "entirely hidden" restricted activities.
 *
 * @param int $courseid The course ID.
 * @param \moodle_page $page The current page object.
 * @return string The rendered HTML for the course index, or empty string.
 */
function theme_active_build_course_index(int $courseid, \moodle_page $page): string {
    global $USER, $OUTPUT;

    $modinfo = get_fast_modinfo($courseid, $USER->id);
    $format = course_get_format($courseid);
    $course = $format->get_course();

    // Completion info
    $completioninfo = new \completion_info($course);
    $trackcompletion = $completioninfo->is_enabled() && isloggedin() && !isguestuser();

    // Determine the currently active cm
    $activecmid = 0;
    if ($page->cm) {
        $activecmid = (int) $page->cm->id;
    }

    // Determine active section
    $activesection = 0;
    if ($page->cm) {
        $activesection = (int) $page->cm->sectionnum;
    }

    // Check editing capability once.
    $coursecontext = \context_course::instance($courseid);
    $canedit = has_capability('moodle/course:manageactivities', $coursecontext);

    $allcmids = [];

    // Build sections array
    $sections = [];
    $sectioninfos = $modinfo->get_section_info_all();

    foreach ($sectioninfos as $section) {
        if (!$format->is_section_visible($section)) {
            continue;
        }

        $sectiondata = [
            'id' => (int) $section->id,
            'number' => (int) $section->section,
            'title' => $format->get_section_name($section),
            'sectionurl' => (new \moodle_url('/course/view.php', [
                'id' => $courseid,
            ], "section-{$section->section}"))->out(false),
            'current' => ($course->marker == $section->section && $section->section > 0),
            'visible' => (bool) $section->visible,
            'cms' => [],
            'hascms' => false,
            'sectioncompleted' => 0,
            'sectiontotal' => 0,
            'sectionpercent' => 0,
            'hassectionprogress' => false,
        ];

        if (!empty($modinfo->sections[$section->section])) {
            foreach ($modinfo->sections[$section->section] as $cmid) {
                $cm = $modinfo->cms[$cmid];

                if ($cm->deletioninprogress) {
                    continue;
                }

                if (!$canedit) {
                    if (!$cm->visible) {
                        continue;
                    }
                    if (!$cm->uservisible && empty($cm->availableinfo)) {
                        continue;
                    }
                }

                $isstealth = !$cm->visibleoncoursepage;
                $hasrestrictions = !$cm->uservisible && !empty($cm->availableinfo);
                $accessvisible = $cm->uservisible;
                $hasurl = !empty($cm->url);
                $cmurl = $hasurl
                    ? $cm->url->out(false)
                    : (new \moodle_url('/course/view.php', [
                        'id' => $courseid,
                        'section' => $section->section,
                    ], "module-{$cm->id}"))->out(false);

                // Completion state.
                $completionstate = 'none';
                if ($trackcompletion && $cm->completion != COMPLETION_TRACKING_NONE && $cm->uservisible) {
                    $cdata = $completioninfo->get_data($cm, true, $USER->id);
                    if ($cdata->completionstate == COMPLETION_COMPLETE
                        || $cdata->completionstate == COMPLETION_COMPLETE_PASS) {
                        $completionstate = 'complete';
                        $sectiondata['sectioncompleted']++;
                    } elseif ($cdata->completionstate == COMPLETION_COMPLETE_FAIL) {
                        $completionstate = 'fail';
                    } else {
                        $completionstate = 'incomplete';
                    }
                    $sectiondata['sectiontotal']++;
                }

                $cmdata = [
                    'id' => (int) $cm->id,
                    'name' => format_string($cm->name, true, ['context' => $cm->context]),
                    'url' => $cmurl,
                    'hasurl' => $hasurl,
                    'uservisible' => (bool) $cm->uservisible,
                    'accessvisible' => (bool) $accessvisible,
                    'hascmrestrictions' => $hasrestrictions,
                    'stealth' => $isstealth,
                    'isactive' => ($activecmid === (int) $cm->id),
                    'modname' => $cm->modname,
                    'iconurl' => $cm->get_icon_url()->out(false),
                    'completionstate' => $completionstate,
                    'iscomplete' => ($completionstate === 'complete'),
                    'isfail' => ($completionstate === 'fail'),
                    'isincomplete' => ($completionstate === 'incomplete'),
                    'hascompletion' => ($completionstate !== 'none'),
                ];

                $sectiondata['cms'][] = $cmdata;
                $allcmids[] = (int) $cm->id;
            }
        }

        $sectiondata['hascms'] = !empty($sectiondata['cms']);
        $sectiondata['expanded'] = ($activesection === (int) $section->section && $activesection > 0);

        // Section progress
        if ($sectiondata['sectiontotal'] > 0) {
            $sectiondata['sectionpercent'] = (int) round(
                ($sectiondata['sectioncompleted'] / $sectiondata['sectiontotal']) * 100
            );
            $sectiondata['sectionremaining'] = 100 - $sectiondata['sectionpercent'];
            $sectiondata['hassectionprogress'] = true;
            $sectiondata['sectionprogresslabel'] = $sectiondata['sectioncompleted'] . '/' . $sectiondata['sectiontotal'];
        }

        $sections[] = $sectiondata;
    }

    $prevcm = null;
    $nextcm = null;
    if ($activecmid) {
        $pos = array_search($activecmid, $allcmids);
        if ($pos !== false) {
            $allcms = [];
            foreach ($sections as $s) {
                foreach ($s['cms'] as $c) {
                    $allcms[$c['id']] = $c;
                }
            }
            if ($pos > 0) {
                $previd = $allcmids[$pos - 1];
                $prevcm = $allcms[$previd] ?? null;
            }
            if ($pos < count($allcmids) - 1) {
                $nextid = $allcmids[$pos + 1];
                $nextcm = $allcms[$nextid] ?? null;
            }
        }
    }

    $data = [
        'sections' => $sections,
        'hassections' => !empty($sections),
        'courseid' => $courseid,
        'hasactive' => ($activecmid > 0),
        'hasprev' => !empty($prevcm),
        'hasnext' => !empty($nextcm),
        'prev' => $prevcm,
        'next' => $nextcm,
    ];

    return $OUTPUT->render_from_template('theme_active/courseindex', $data);
}
