<?php
// This file is part of Moodle - http://moodle.org/
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
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

namespace theme_active\output;

defined('MOODLE_INTERNAL') || die();

/**
 * Forces light mode for guests and logged-out users.
 *
 * local_boost_dark sets data-bs-theme. Without a session it uses "auto", which follows the
 * operating system, so visitors with a dark system see a dark site they did not ask for.
 * The same happens with an old darkmode cookie or ?darkmode=dark in the URL.
 *
 * This is a trait because two renderers need it: the normal one and the maintenance one
 * (core_renderer_maintenance does not extend the theme renderer).
 *
 * Logged-in users keep their saved preference. If they never used the toggle, the plugin
 * also sends "auto": it is changed to "light" so new accounts start in light mode.
 *
 * @package    theme_active
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
trait forces_light_mode {

    /**
     * Attributes for the <html> tag, with light mode forced for guests and logged-out users.
     *
     * @return string
     */
    public function htmlattributes() {
        $attributes = parent::htmlattributes();

        if (isloggedin() && !isguestuser()) {
            // No saved preference: "auto" becomes "light". Any other value
            // (a "dark"/"light" preference or ?darkmode= in the URL) is kept.
            if (get_user_preferences('darkmode') === null) {
                $attributes = str_replace('data-bs-theme="auto"', 'data-bs-theme="light"', $attributes);
            }
            return $attributes;
        }

        // Remove the plugin's data-bs-theme and set it to light.
        $attributes = preg_replace('/\s*data-bs-theme="[^"]*"/', '', $attributes);

        return $attributes . ' data-bs-theme="light"';
    }
}
