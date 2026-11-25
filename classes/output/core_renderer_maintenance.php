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
 * Renderer used by Moodle while the site is in maintenance mode.
 *
 * With the 'maintenance' page layout, Moodle looks for core_renderer_maintenance instead of
 * core_renderer. Without this class the core one was used, which skips the theme's light mode
 * fix, so the page showed data-bs-theme="auto" and was hard to read on dark systems.
 *
 * @package    theme_active
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class core_renderer_maintenance extends \core\output\core_renderer_maintenance {

    use forces_light_mode;
}
