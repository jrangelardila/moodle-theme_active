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

namespace theme_active\output\core;

defined('MOODLE_INTERNAL') || die();

use html_writer;
use moodle_url;
use core_text;
use core_course_list_element;
use coursecat_helper;

/**
 * Course renderer override for theme_active.
 *
 * Replaces the default coursebox list with a responsive Bootstrap card grid.
 *
 * @package     theme_active
 * @copyright   2024 Jhon Rangel <jrangelardila@gmail.com>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class course_renderer extends \core_course_renderer {

    /**
     * Renders the course grid wrapper and iterates over courses to produce cards.
     *
     * @param coursecat_helper $chelper
     * @param array            $courses
     * @param int|null         $totalcount
     * @return string
     */
    protected function coursecat_courses(coursecat_helper $chelper, $courses, $totalcount = null) {
        global $CFG;

        if ($totalcount === null) {
            $totalcount = count($courses);
        }
        if (!$totalcount) {
            return '';
        }

        // Force expanded mode so we get images and summaries.
        if ($chelper->get_show_courses() == self::COURSECAT_SHOW_COURSES_AUTO ||
                $chelper->get_show_courses() < self::COURSECAT_SHOW_COURSES_EXPANDED) {
            $chelper->set_show_courses(self::COURSECAT_SHOW_COURSES_EXPANDED);
        }

        // Pagination.
        $paginationurl    = $chelper->get_courses_display_option('paginationurl');
        $paginationallowall = $chelper->get_courses_display_option('paginationallowall');
        $paginghtml = '';
        if ($totalcount > count($courses)) {
            if ($paginationurl) {
                $perpage = $chelper->get_courses_display_option('limit', $CFG->coursesperpage);
                $page    = $chelper->get_courses_display_option('offset', 0) / $perpage;
                $pagingbar = $this->paging_bar(
                    $totalcount, $page, $perpage,
                    $paginationurl->out(false, ['perpage' => $perpage])
                );
                if ($paginationallowall) {
                    $pagingbar .= html_writer::tag('div', html_writer::link(
                        $paginationurl->out(false, ['perpage' => 'all']),
                        get_string('showall', '', $totalcount)
                    ), ['class' => 'paging paging-showall']);
                }
                $paginghtml = $pagingbar;
            }
        } else if ($paginationallowall && ($totalcount > $CFG->coursesperpage)) {
            $paginghtml = html_writer::tag('div', html_writer::link(
                $paginationurl->out(false, ['perpage' => $CFG->coursesperpage]),
                get_string('showperpage', '', $CFG->coursesperpage)
            ), ['class' => 'paging paging-showperpage']);
        }

        $content  = $paginghtml;
        $content .= html_writer::start_tag('div', ['class' => 'tal-course-grid row']);

        $i = 0;
        foreach ($courses as $course) {
            $content .= $this->coursecat_coursebox($chelper, $course, $i % 2 == 0 ? 'even' : 'odd');
            $i++;
        }

        $content .= html_writer::end_tag('div'); // .tal-course-grid
        $content .= $paginghtml;

        return $content;
    }

    /**
     * Renders a single course as a Bootstrap card.
     *
     * @param coursecat_helper $chelper
     * @param stdClass|core_course_list_element $course
     * @param string $additionalclasses
     * @return string
     */
    protected function coursecat_coursebox(coursecat_helper $chelper, $course, $additionalclasses = '') {
        if ($chelper->get_show_courses() <= self::COURSECAT_SHOW_COURSES_COUNT) {
            return '';
        }
        if ($course instanceof \stdClass) {
            $course = new core_course_list_element($course);
        }

        $courseurl  = new moodle_url('/course/view.php', ['id' => $course->id]);
        $coursename = $chelper->get_course_formatted_name($course);
        $dimmed     = $course->visible ? '' : ' dimmed';

        // --- Course image ---
        global $CFG;
        $imagehtml = '';
        foreach ($course->get_course_overviewfiles() as $file) {
            if ($file->is_valid_image()) {
                $imgurl = moodle_url::make_file_url(
                    "$CFG->wwwroot/pluginfile.php",
                    '/' . $file->get_contextid() . '/' . $file->get_component() . '/' .
                    $file->get_filearea() . $file->get_filepath() . $file->get_filename()
                );
                $imginner = html_writer::empty_tag('img', [
                    'src'   => $imgurl,
                    'alt'   => '',
                    'class' => 'tal-course-img',
                ]);
                $imagehtml = html_writer::link($courseurl,
                    html_writer::tag('div', $imginner, ['class' => 'tal-course-img-wrap'])
                );
                break;
            }
        }
        if (empty($imagehtml)) {
            $shortlabel = format_string($course->shortname);
            $placeholderinner = html_writer::tag('i', '', ['class' => 'fa fa-graduation-cap fa-3x mb-2'])
                . html_writer::tag('span', $shortlabel, ['class' => 'tal-placeholder-shortname']);
            $imagehtml = html_writer::link($courseurl,
                html_writer::tag('div',
                    html_writer::tag('div', $placeholderinner,
                        ['class' => 'tal-course-img-placeholder d-flex flex-column align-items-center justify-content-center']),
                    ['class' => 'tal-course-img-wrap'])
            );
        }

        // --- Summary ---
        $summaryhtml = '';
        if ($course->has_summary()) {
            $summary = $chelper->get_course_formatted_summary($course, [
                'overflowdiv' => false,
                'noclean'     => true,
                'para'        => false,
            ]);
            $plain = strip_tags($summary);
            if (core_text::strlen($plain) > 150) {
                $plain = core_text::substr($plain, 0, 150) . '…';
            }
            $summaryhtml = html_writer::tag('p', $plain, ['class' => 'card-text tal-course-summary small text-muted']);
        }

        // --- Shortname ---
        $shortnamehtml = html_writer::tag('span', format_string($course->shortname),
            ['class' => 'badge badge-light tal-course-shortname']);

        // --- Teachers ---
        $teachershtml = '';
        $teachers = $this->get_course_teachers($course->id);
        if (!empty($teachers)) {
            $avatars = '';
            foreach ($teachers as $teacher) {
                $profileurl = new moodle_url('/user/profile.php', ['id' => $teacher->id]);
                $userpic = $this->output->user_picture($teacher, [
                    'size' => 32,
                    'class' => 'tal-teacher-avatar',
                    'link' => false,
                ]);
                $avatars .= html_writer::link($profileurl, $userpic, [
                    'class' => 'tal-teacher-link',
                    'title' => fullname($teacher),
                ]);
            }
            $teachershtml = html_writer::tag('div', $avatars, ['class' => 'tal-teachers']);
        }

        // Card
        $cardbody  = html_writer::start_tag('div', ['class' => 'card-body d-flex flex-column']);
        $cardbody .= html_writer::tag('h5',
            html_writer::link($courseurl, $coursename, ['class' => 'stretched-link tal-course-title' . $dimmed]),
            ['class' => 'card-title']
        );
        $cardbody .= html_writer::tag('div', $shortnamehtml, ['class' => 'mb-2']);
        $cardbody .= $summaryhtml;
        if ($teachershtml) {
            $cardbody .= $teachershtml;
        }
        $cardbody .= html_writer::end_tag('div');

        $card  = html_writer::start_tag('div', ['class' => 'card h-100 tal-course-card position-relative' . ($course->visible ? '' : ' tal-course-dimmed')]);
        $card .= $imagehtml;
        $card .= $cardbody;
        $card .= html_writer::end_tag('div');

        return html_writer::tag('div', $card, [
            'class'           => 'col-12 col-sm-6 col-lg-4 mb-4 ' . $additionalclasses,
            'data-courseid'   => $course->id,
            'data-type'       => self::COURSECAT_TYPE_COURSE,
        ]);
    }

    /**
     * Get users with teacher or editingteacher role in a course.
     *
     * @param int $courseid
     * @return array
     */
    protected function get_course_teachers(int $courseid): array {
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
}
