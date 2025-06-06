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
 * The layout/maintenance.php for theme_active is defined here.
 *
 * @package     theme_active
 * @copyright   2024 Jhon Rangel <jrangelardila@gmail.com>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$templatecontext = [
    'sitename' => format_string($SITE->shortname, true, ["escape" => false]),
    'output' => $OUTPUT,
    'maintenance' => $OUTPUT->image_url('maintenance', 'theme_active'),
];

$maintenance_mode = get_config('core', 'maintenance_enabled');

if ($maintenance_mode) {
    echo $OUTPUT->render_from_template('theme_active/maintenance', $templatecontext);
} else {
    echo $OUTPUT->render_from_template('theme_boost/maintenance', $templatecontext);
}