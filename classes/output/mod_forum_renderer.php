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
 * Forum renderer override for theme_active.
 *
 * Replaces the default prev/next discussion navigation with a premium
 * card-based design including forum context and discussion names.
 *
 * @package    theme_active
 * @copyright  2026 Jhon Rangel <jrangelardila@gmail.com>
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace theme_active\output;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/mod/forum/renderer.php');

/**
 * Forum renderer for theme_active.
 *
 * Overrides the neighbouring discussion navigation to render a premium
 * template with enhanced UX.
 */
class mod_forum_renderer extends \mod_forum_renderer {

    /**
     * Returns enhanced prev/next discussion navigation.
     *
     * @param mixed $prev Previous discussion record, or false.
     * @param mixed $next Next discussion record, or false.
     * @return string The rendered HTML.
     */
    public function neighbouring_discussion_navigation($prev, $next) {
        if (!$prev && !$next) {
            return '';
        }

        $data = [
            'hasprev' => !empty($prev),
            'hasnext' => !empty($next),
        ];

        if ($prev) {
            $data['prev'] = [
                'url'  => (new \moodle_url('/mod/forum/discuss.php', ['d' => $prev->id]))->out(false),
                'name' => format_string($prev->name),
            ];
        }

        if ($next) {
            $data['next'] = [
                'url'  => (new \moodle_url('/mod/forum/discuss.php', ['d' => $next->id]))->out(false),
                'name' => format_string($next->name),
            ];
        }

        // Add forum back-link from current page context
        try {
            $cm = $this->page->cm;
            if ($cm) {
                $data['forumurl'] = (new \moodle_url('/mod/forum/view.php', ['id' => $cm->id]))->out(false);
                $data['forumname'] = format_string($cm->name);
                $data['hasforumlink'] = true;
            }
        } catch (\Throwable $e) {
            $data['hasforumlink'] = false;
        }

        return $this->render_from_template('theme_active/forum_discussion_nav', $data);
    }
}
