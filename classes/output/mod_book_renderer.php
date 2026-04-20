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
 * Theme override for the mod_book renderer.
 *
 * Enriches the book chapter navigation with chapter titles, hierarchy
 * indicators, and positional context for a premium navigation experience.
 *
 * @package     theme_active
 * @copyright   2024 Jhon Rangel <jrangelardila@gmail.com>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace theme_active\output;

use mod_book\output\main_action_menu;

/**
 * Overridden mod_book renderer for theme_active.
 */
class mod_book_renderer extends \mod_book\output\renderer {

    /**
     * Render the main action menu with enriched chapter navigation data.
     *
     * @param main_action_menu $actionmenu Main action menu object.
     * @return string The rendered HTML.
     */
    public function render_main_action_menu(main_action_menu $actionmenu): string {
        global $DB, $CFG;

        // Get base data
        $data = $actionmenu->export_for_template($this);

        // Load book and chapter info from the current page context
        try {
            $cm = $this->page->cm;
        } catch (\Throwable $e) {
            return parent::render_main_action_menu($actionmenu);
        }

        $book = $DB->get_record('book', ['id' => $cm->instance]);
        if (!$book) {
            return parent::render_main_action_menu($actionmenu);
        }

        // Get chapterid from the page URL
        $chapterid = $this->page->url->get_param('chapterid');
        if (empty($chapterid)) {
            $chapterid = optional_param('chapterid', 0, PARAM_INT);
        }

        // Load all chapters
        require_once($CFG->dirroot . '/mod/book/locallib.php');
        $chapters = book_preload_chapters($book);

        if (empty($chapters)) {
            return parent::render_main_action_menu($actionmenu);
        }

        $viewhidden = has_capability('mod/book:viewhiddenchapters', \context_module::instance($cm->id));

        // Build ordered list of visible chapters with parent grouping
        $visiblechapters = [];
        foreach ($chapters as $ch) {
            if (!$ch->hidden || $viewhidden) {
                $visiblechapters[] = $ch;
            }
        }

        // Find current chapter position
        $currentpos = -1;
        $currentchapter = null;
        foreach ($visiblechapters as $idx => $ch) {
            if ($ch->id == $chapterid) {
                $currentpos = $idx;
                $currentchapter = $ch;
                break;
            }
        }

        if ($currentpos === -1 || !$currentchapter) {
            return parent::render_main_action_menu($actionmenu);
        }

        $totalchapters = count($visiblechapters);
        $progresspercent = ($totalchapters > 0) ? round(($currentpos + 1) / $totalchapters * 100) : 0;

        // Build
        $toc = [];
        foreach ($visiblechapters as $idx => $ch) {
            $iscurrent = ($ch->id == $chapterid);
            $ispast = ($idx < $currentpos);
            $toc[] = [
                'title' => format_string($ch->title),
                'url' => (new \moodle_url('/mod/book/view.php', [
                    'id' => $cm->id,
                    'chapterid' => $ch->id,
                ]))->out(false),
                'issubchapter' => !empty($ch->subchapter),
                'iscurrent' => $iscurrent,
                'ispast' => $ispast,
                'isfuture' => !$iscurrent && !$ispast,
                'number' => $ch->number ?? '',
                'position' => $idx + 1,
            ];
        }

        // Enrich previous data
        if (!empty($data['previous']) && $currentpos > 0) {
            $prevch = $visiblechapters[$currentpos - 1];
            $data['previous']['chaptername'] = format_string($prevch->title);
            $data['previous']['issubchapter'] = !empty($prevch->subchapter);
        }

        // Enrich next data
        if (!empty($data['next']) && $currentpos < $totalchapters - 1) {
            $nextch = $visiblechapters[$currentpos + 1];
            $data['next']['chaptername'] = format_string($nextch->title);
            $data['next']['issubchapter'] = !empty($nextch->subchapter);
        }

        // Add positional
        $data['currenttitle'] = format_string($currentchapter->title);
        $data['currentissubchapter'] = !empty($currentchapter->subchapter);
        $data['position'] = $currentpos + 1;
        $data['totalchapters'] = $totalchapters;
        $data['progresspercent'] = $progresspercent;
        $data['toc'] = $toc;
        $data['hastoc'] = ($totalchapters > 1);
        $data['bookname'] = format_string($book->name);
        $data['hasprevious'] = !empty($data['previous']);
        $data['hasnext'] = !empty($data['next']);
        $data['isfirst'] = ($currentpos === 0);
        $data['islast'] = ($currentpos === $totalchapters - 1);

        return $this->render_from_template('mod_book/main_action_menu', $data);
    }
}
