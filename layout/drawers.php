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
 * Drawer-based layout for theme_active.
 *
 * Extends Boost's drawers layout and injects the footer template context
 * so that the overridden footer.mustache can render social links, app
 * buttons and site information from theme settings.
 *
 * @package     theme_active
 * @copyright   2024 Jhon Rangel <jrangelardila@gmail.com>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/behat/lib.php');
require_once($CFG->dirroot . '/course/lib.php');
require_once($CFG->dirroot . '/theme/active/lib.php');

$addblockbutton = $OUTPUT->addblockbutton();

if (isloggedin()) {
    $courseindexopen = (get_user_preferences('drawer-open-index', true) == true);
    $blockdraweropen = (get_user_preferences('drawer-open-block') == true);
} else {
    $courseindexopen = false;
    $blockdraweropen = false;
}

if (defined('BEHAT_SITE_RUNNING') && get_user_preferences('behat_keep_drawer_closed') != 1) {
    $blockdraweropen = true;
}

$extraclasses = ['uses-drawers'];
if ($courseindexopen) {
    $extraclasses[] = 'drawer-open-index';
}

$blockshtml = $OUTPUT->blocks('side-pre');
$hasblocks = (strpos($blockshtml, 'data-block=') !== false || !empty($addblockbutton));

$coursetopblockshtml = $OUTPUT->blocks('course-top');
$coursetopaddblockbutton = $OUTPUT->addblockbutton('course-top');
$hascoursetopblocks = strpos($coursetopblockshtml, 'data-block=') !== false || !empty($coursetopaddblockbutton);
if (!$hasblocks) {
    $blockdraweropen = false;
}
// Use our custom course index that shows ALL activities (stealth + restricted)
// except teacher-hidden ones. Falls back to Moodle's default if not in a course.
$courseindex = '';
if (!empty($COURSE->id) && $COURSE->id != SITEID && isloggedin()) {
    $courseindex = theme_active_build_course_index((int) $COURSE->id, $PAGE);
    if (!empty($courseindex)) {
        $PAGE->requires->js_call_amd('theme_active/courseindex_controls', 'init');
    }
}
if (empty($courseindex)) {
    $courseindex = core_course_drawer();
}
if (!$courseindex) {
    $courseindexopen = false;
}

$bodyattributes = $OUTPUT->body_attributes($extraclasses);
$forceblockdraweropen = $OUTPUT->firstview_fakeblocks();

// Don't force-open the block drawer on book pages (theme_active has its own TOC navigation).
if ($PAGE->pagetype === 'mod-book-view') {
    $forceblockdraweropen = false;
}

// Calendar date-based color coding (past=red, today=orange, future=green).
$PAGE->requires->js_call_amd('theme_active/calendar_colors', 'init');


$secondarynavigation = false;
$overflow = '';
if ($PAGE->has_secondary_navigation()) {
    // Inject the course calendar tab right after "participants" inside a real course.
    if (!empty($COURSE->id) && $COURSE->id != SITEID) {
        $calendarkey = 'coursecalendar';
        if (!$PAGE->secondarynav->get($calendarkey)) {
            $beforekey = null;
            $foundparticipants = false;
            foreach ($PAGE->secondarynav->children as $child) {
                if ($foundparticipants) {
                    $beforekey = $child->key;
                    break;
                }
                if ($child->key === 'participants') {
                    $foundparticipants = true;
                }
            }

            $calendarlabel = get_string('coursecalendar', 'theme_active');
            $calendartext = '<i class="fa fa-calendar mr-1" aria-hidden="true"></i>' . $calendarlabel;
            $calendarnode = \navigation_node::create(
                $calendartext,
                new moodle_url('/calendar/view.php', [
                    'view' => 'month',
                    'course' => $COURSE->id,
                ]),
                \navigation_node::TYPE_CUSTOM,
                null,
                $calendarkey,
                new pix_icon('i/calendar', $calendarlabel)
            );
            $PAGE->secondarynav->add_node($calendarnode, $beforekey);
        }
    }

    $tablistnav = $PAGE->has_tablist_secondary_navigation();
    $moremenu = new \core\navigation\output\more_menu($PAGE->secondarynav, 'nav-tabs', true, $tablistnav);
    $secondarynavigation = $moremenu->export_for_template($OUTPUT);
    $overflowdata = $PAGE->secondarynav->get_overflow_menu_data();
    if (!is_null($overflowdata)) {
        $overflow = $overflowdata->export_for_template($OUTPUT);
    }
}

$primary = new core\navigation\output\primary($PAGE);
$renderer = $PAGE->get_renderer('core');
$primarymenu = $primary->export_for_template($renderer);
$buildregionmainsettings = !$PAGE->include_region_main_settings_in_header_actions()
    && !$PAGE->has_secondary_navigation();
$regionmainsettingsmenu = $buildregionmainsettings ? $OUTPUT->region_main_settings_menu() : false;

$header = $PAGE->activityheader;
$headercontent = $header->export_for_template($renderer);

$templatecontext = [
    'sitename' => format_string(
        $SITE->shortname,
        true,
        ['context' => context_course::instance(SITEID), 'escape' => false]
    ),
    'output' => $OUTPUT,
    'navbar' => $OUTPUT->navbar(),
    'hasnavbar' => $PAGE->has_navbar(),
    'sidepreblocks' => $blockshtml,
    'hasblocks' => $hasblocks,
    'bodyattributes' => $bodyattributes,
    'courseindexopen' => $courseindexopen,
    'blockdraweropen' => $blockdraweropen,
    'courseindex' => $courseindex,
    'primarymoremenu' => $primarymenu['moremenu'],
    'secondarymoremenu' => $secondarynavigation ?: false,
    'mobileprimarynav' => $primarymenu['mobileprimarynav'],
    'usermenu' => $primarymenu['user'],
    'langmenu' => $primarymenu['lang'],
    'forceblockdraweropen' => $forceblockdraweropen,
    'regionmainsettingsmenu' => $regionmainsettingsmenu,
    'hasregionmainsettingsmenu' => !empty($regionmainsettingsmenu),
    'overflow' => $overflow,
    'headercontent' => $headercontent,
    'addblockbutton' => $addblockbutton,
    'coursetopblocks' => $coursetopblockshtml,
    'hascoursetopblocks' => $hascoursetopblocks,
    'coursetopaddblockbutton' => $coursetopaddblockbutton,
];

// inject footer data into the context
$templatecontext = array_merge($templatecontext, theme_active_get_footer_context());

// inject course completion progress for students
$templatecontext = array_merge($templatecontext, theme_active_get_course_progress_context());

// inject custom activity navigation
$templatecontext['tal_activity_nav'] = theme_active_get_activity_navigation($PAGE, $OUTPUT);

echo $OUTPUT->render_from_template('theme_boost/drawers', $templatecontext);
