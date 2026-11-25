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
 * Plugin upgrade steps are defined here.
 *
 * @package     theme_active
 * @category    upgrade
 * @copyright   2024 Jhon Rangel <jrangelardila@gmail.com>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Execute theme_active upgrade from the given old version.
 *
 * @param int $oldversion
 * @return bool
 */
function xmldb_theme_active_upgrade($oldversion) {
    global $CFG, $DB;

    $dbman = $DB->get_manager();

    // For further information please read {@link https://docs.moodle.org/dev/Upgrade_API}.
    //
    // You will also have to create the db/install.xml file by using the XMLDB Editor.
    // Documentation for the XMLDB Editor can be found at {@link https://docs.moodle.org/dev/XMLDB_editor}.

    if ($oldversion < 2026100601) {
        // The "upcoming deadlines" card was removed: drop its settings.
        foreach (['upcoming_enabled', 'upcoming_days', 'upcoming_maxitems', 'upcoming_showoverdue'] as $name) {
            unset_config($name, 'theme_active');
        }
        upgrade_plugin_savepoint(true, 2026100601, 'theme', 'active');
    }

    if ($oldversion < 2026112401) {
        // Load the default banner and symbol into their settings (only if they are empty).
        require_once($CFG->dirroot . '/theme/active/lib.php');
        theme_active_load_default_images();
        upgrade_plugin_savepoint(true, 2026112401, 'theme', 'active');
    }

    return true;
}
