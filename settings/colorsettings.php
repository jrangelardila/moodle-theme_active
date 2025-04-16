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

$page = new admin_settingpage('theme_active_colors', get_string('colorsettings', 'theme_active'));

// Primary color
$setting = new admin_setting_configcolourpicker(
    'theme_active/primarycolor',
    get_string('primarycolor', 'theme_active'),
    get_string('primarycolor_desc', 'theme_active'),
    '#920020');
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

// Secondary color
$setting = new admin_setting_configcolourpicker(
    'theme_active/secondarycolor',
    get_string('secondarycolor', 'theme_active'),
    get_string('secondarycolor_desc', 'theme_active'),
    '#ffffff');
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

// Add colors page to settings
$settings->add($page);
