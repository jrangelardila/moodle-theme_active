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
 * Myprofile renderer override for theme_active.
 *
 * Replaces the plain course list in "Course details" with course cards.
 *
 * @package     theme_active
 * @copyright   2026 Jhon Rangel <jrangelardila@gmail.com>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace theme_active\output\core_user\myprofile;

use core_user\output\myprofile\category;
use core_user\output\myprofile\node;
use html_writer;
use moodle_url;
use core_course_list_element;
use core_text;

defined('MOODLE_INTERNAL') || die();

/**
 * Overridden myprofile renderer that shows enrolled courses as cards.
 */
class renderer extends \core_user\output\myprofile\renderer {

    /**
     * Render a category, replacing the coursedetails node content with cards.
     *
     * @param category $category
     * @return string
     */
    public function render_category(category $category) {
        $nodes = $category->nodes;
        if (empty($nodes)) {
            return '';
        }

        // Check if this is the coursedetails category with courseprofiles node.
        if ($category->name === 'coursedetails') {
            foreach ($nodes as $node) {
                if ($node->name === 'courseprofiles') {
                    return $this->render_course_cards_category($category, $node);
                }
            }
        }

        return parent::render_category($category);
    }

    /**
     * Render the coursedetails category with course cards instead of a plain list.
     *
     * @param category $category
     * @param node $coursenode The courseprofiles node.
     * @return string
     */
    protected function render_course_cards_category(category $category, node $coursenode): string {
        $userid = $this->page->url->get_param('id');
        if (empty($userid)) {
            return parent::render_category($category);
        }

        $mycourses = enrol_get_all_users_courses($userid, true, null);
        if (empty($mycourses)) {
            return parent::render_category($category);
        }

        $showallcourses = optional_param('showallcourses', 0, PARAM_INT);

        // Separate visible and hidden courses.
        $visiblecourses = [];
        $hiddencourses = [];
        foreach ($mycourses as $mycourse) {
            if (!$mycourse->category) {
                continue;
            }
            \context_helper::preload_from_record($mycourse);
            $ccontext = \context_course::instance($mycourse->id);
            if (!$mycourse->visible && !has_capability('moodle/course:viewhiddencourses', $ccontext)) {
                continue;
            }

            if ($mycourse->visible) {
                $visiblecourses[] = $mycourse;
            } else {
                $hiddencourses[] = $mycourse;
            }
        }

        // always show visible courses
        $cardshtml = html_writer::start_tag('div', ['class' => 'tal-course-grid row']);
        foreach ($visiblecourses as $mycourse) {
            $cardshtml .= $this->render_profile_course_card($mycourse);
        }

        // Show hidden courses only when showallcourses is active
        if ($showallcourses && !empty($hiddencourses)) {
            foreach ($hiddencourses as $mycourse) {
                $cardshtml .= $this->render_profile_course_card($mycourse);
            }
        }
        $cardshtml .= html_writer::end_tag('div');

        // Toggle button: show/hide hidden courses
        $viewmore = '';
        if (!empty($hiddencourses)) {
            if (!$showallcourses) {
                $url = new moodle_url('/user/profile.php', ['id' => $userid, 'showallcourses' => 1]);
                $label = get_string('showallcourses', 'theme_active', count($hiddencourses));
                $viewmore = html_writer::tag('div',
                    html_writer::link($url, $label, ['class' => 'btn btn-outline-secondary btn-sm mt-2']),
                    ['class' => 'text-center mb-3']
                );
            } else {
                $url = new moodle_url('/user/profile.php', ['id' => $userid]);
                $label = get_string('showvisibleonly', 'theme_active');
                $viewmore = html_writer::tag('div',
                    html_writer::link($url, $label, ['class' => 'btn btn-outline-secondary btn-sm mt-2']),
                    ['class' => 'text-center mb-3']
                );
            }
        }

        // Render other nodes in this category
        $othernodes = '';
        foreach ($category->nodes as $node) {
            if ($node->name !== 'courseprofiles') {
                $othernodes .= $this->render_node($node);
            }
        }

        // Build the section.
        $return = html_writer::start_tag('section', ['class' => 'node_category card d-inline-block w-100 mb-3']);
        $return .= html_writer::start_tag('div', ['class' => 'card-body']);
        $return .= html_writer::tag('h3', $category->title, ['class' => 'lead']);
        $return .= $cardshtml;
        $return .= $viewmore;
        if (!empty($othernodes)) {
            $return .= html_writer::tag('ul', $othernodes);
        }
        $return .= html_writer::end_tag('div');
        $return .= html_writer::end_tag('section');

        return $return;
    }

    /**
     * Render a single course as a card (same style as category cards).
     *
     * @param \stdClass $course
     * @return string
     */
    protected function render_profile_course_card(\stdClass $course): string {
        global $CFG;

        $courseel = new core_course_list_element($course);
        $courseurl = new moodle_url('/course/view.php', ['id' => $course->id]);
        $coursename = format_string(get_course_display_name_for_list($courseel), true, [
            'context' => \context_course::instance($course->id),
        ]);
        $dimmed = $course->visible ? '' : ' dimmed';

        // Course image
        $imagehtml = '';
        foreach ($courseel->get_course_overviewfiles() as $file) {
            if ($file->is_valid_image()) {
                $imgurl = moodle_url::make_file_url(
                    "$CFG->wwwroot/pluginfile.php",
                    '/' . $file->get_contextid() . '/' . $file->get_component() . '/' .
                    $file->get_filearea() . $file->get_filepath() . $file->get_filename()
                );
                $imginner = html_writer::empty_tag('img', [
                    'src' => $imgurl,
                    'alt' => '',
                    'class' => 'tal-course-img',
                ]);
                $imagehtml = html_writer::link($courseurl,
                    html_writer::tag('div', $imginner, ['class' => 'tal-course-img-wrap'])
                );
                break;
            }
        }
        if (empty($imagehtml)) {
            $shortlabel = format_string($courseel->shortname);
            $placeholderinner = html_writer::tag('i', '', ['class' => 'fa fa-graduation-cap fa-3x mb-2'])
                . html_writer::tag('span', $shortlabel, ['class' => 'tal-placeholder-shortname']);
            $imagehtml = html_writer::link($courseurl,
                html_writer::tag('div',
                    html_writer::tag('div', $placeholderinner,
                        ['class' => 'tal-course-img-placeholder d-flex flex-column align-items-center justify-content-center']),
                    ['class' => 'tal-course-img-wrap'])
            );
        }

        // Summary
        $summaryhtml = '';
        if ($courseel->has_summary()) {
            $plain = strip_tags($courseel->summary);
            if (core_text::strlen($plain) > 150) {
                $plain = core_text::substr($plain, 0, 150) . '…';
            }
            $summaryhtml = html_writer::tag('p', $plain, ['class' => 'card-text tal-course-summary small text-muted']);
        }

        // Shortname badge
        $shortnamehtml = html_writer::tag('span', format_string($courseel->shortname),
            ['class' => 'badge badge-light tal-course-shortname']);

        // Card assembly
        $cardbody = html_writer::start_tag('div', ['class' => 'card-body d-flex flex-column']);
        $cardbody .= html_writer::tag('h5',
            html_writer::link($courseurl, $coursename, ['class' => 'stretched-link tal-course-title' . $dimmed]),
            ['class' => 'card-title']
        );
        $cardbody .= html_writer::tag('div', $shortnamehtml, ['class' => 'mb-2']);
        $cardbody .= $summaryhtml;
        $cardbody .= html_writer::end_tag('div');

        // Hidden course badge
        $hiddenbadge = '';
        if (!$course->visible) {
            $hiddenbadge = html_writer::tag('span',
                html_writer::tag('i', '', ['class' => 'fa fa-eye-slash mr-1']) .
                get_string('coursehidden', 'theme_active'),
                ['class' => 'badge badge-dark tal-course-hidden-badge']
            );
        }

        $card = html_writer::start_tag('div', ['class' => 'card h-100 tal-course-card position-relative'
            . ($course->visible ? '' : ' tal-course-dimmed')]);
        $card .= $hiddenbadge;
        $card .= $imagehtml;
        $card .= $cardbody;
        $card .= html_writer::end_tag('div');

        return html_writer::tag('div', $card, [
            'class' => 'col-12 col-sm-6 col-lg-4 mb-4',
            'data-courseid' => $course->id,
        ]);
    }
}
