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

$page = new admin_settingpage('theme_active_header', get_string('headersettings', 'theme_active'));

//Add settings for logo, to the /admin/settings.php?section=logos
$setting = new admin_setting_configstoredfile('core_admin/logo',
    get_string('logo_settings', 'theme_active'),
    get_string('logo_settingsdesc', 'theme_active'), 'logo', 0,
    array('maxfiles' => 1, 'accepted_types' => array('.png', '.jpg', '.jpeg')));
$setting->set_updatedcallback('theme_reset_all_caches');

$page->add($setting);

$setting = new admin_setting_configstoredfile('core_admin/logocompact',
    get_string('logocompact_settings', 'theme_active'),
    get_string('logo_settingsdesc', 'theme_active'), 'logocompact', 0,
    array('maxfiles' => 1, 'accepted_types' => array('.png', '.jpg', '.jpeg')));
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

//Bgcolor to the navbar
$setting = new admin_setting_configcolourpicker(
    'theme_active/bgcolornavbar',
    get_string('navbarbgcolor', 'theme_active'),
    get_string('navbarbgcolor_desc', 'theme_active'),
    '#212529');
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

// Add header page
$settings->add($page);