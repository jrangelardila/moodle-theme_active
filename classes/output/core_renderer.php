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
 * Core renderer overrides for theme_active.
 *
 * @package    theme_active
 * @copyright  2026 Jhon Rangel <jrangelardila@gmail.com>
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace theme_active\output;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/theme/active/lib.php');

/**
 * Core renderer for theme_active.
 *
 * Extends Boost's core renderer to inject course teacher avatars
 * into the page header banner.
 */
class core_renderer extends \theme_boost\output\core_renderer {

    /**
     * Override full_header to inject teacher avatars into the header context.
     *
     * @return string HTML for the full header.
     */
    public function full_header() {
        $pagetype = $this->page->pagetype;
        $homepage = get_home_page();
        $homepagetype = null;
        if ($homepage == HOMEPAGE_MY || $homepage == HOMEPAGE_MYCOURSES) {
            $homepagetype = 'my-index';
        } else if ($homepage == HOMEPAGE_SITE) {
            $homepagetype = 'site-index';
        }
        if ($this->page->include_region_main_settings_in_header_actions() &&
                !$this->page->blocks->is_block_present('settings')) {
            $this->page->add_header_action(\html_writer::div(
                $this->region_main_settings_menu(),
                'd-print-none',
                ['id' => 'region-main-settings-menu']
            ));
        }

        $header = new \stdClass();
        $header->settingsmenu = $this->context_header_settings_menu();
        $header->contextheader = $this->context_header();
        $header->hasnavbar = empty($this->page->layout_options['nonavbar']);
        $header->navbar = $this->navbar();
        $header->pageheadingbutton = $this->page_heading_button();
        $header->courseheader = $this->course_header();
        $header->headeractions = $this->page->get_header_actions();
        if (!empty($pagetype) && !empty($homepagetype) && $pagetype == $homepagetype) {
            $header->welcomemessage = \core_user::welcome_message();
        }

        // course teacher avatars
        $teacherctx = theme_active_get_header_teachers_context($this->page, $this);
        $header->hasheaderteachers = $teacherctx['hasheaderteachers'];
        $header->headerteachers = $teacherctx['headerteachers'];

        // course overview image for header background
        $header->courseimageurl = $this->get_course_header_image();

        return $this->render_from_template('core/full_header', $header);
    }

    /**
     * Get the course overview image URL for the header background.
     *
     * @return string|null The image URL or null if no image exists.
     */
    protected function get_course_header_image(): ?string {
        global $COURSE, $CFG;

        if (empty($COURSE->id) || $COURSE->id == SITEID) {
            return null;
        }

        $course = new \core_course_list_element($COURSE);
        foreach ($course->get_course_overviewfiles() as $file) {
            if ($file->is_valid_image()) {
                return \moodle_url::make_file_url(
                    "$CFG->wwwroot/pluginfile.php",
                    '/' . $file->get_contextid() . '/' . $file->get_component() . '/' .
                    $file->get_filearea() . $file->get_filepath() . $file->get_filename()
                );
            }
        }

        return null;
    }
}
