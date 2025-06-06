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
 * Plugin administration pages are defined here.
 *
 * @package     theme_active
 * @category    admin
 * @copyright   2024 Jhon Rangel <jrangelardila@gmail.com>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

if ($hassiteconfig) {
    // dash for the configs
    $settings = new theme_boost_admin_settingspage_tabs('themesettingactive', get_string('configtitle', 'theme_active'));
    //Tab: Color config
    require_once('settings/colorsettings.php');
    //Tab: Login config
    require_once('settings/loginconfig.php');
    //Tab: Header config
    require_once('settings/headersettings.php');
    //Tab: Footer config
    require_once('settings/footersettings.php');
}
