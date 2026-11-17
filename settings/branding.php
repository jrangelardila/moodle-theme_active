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
 * Branding settings tab.
 *
 * @package     theme_active
 * @copyright   2024 Jhon Rangel <jrangelardila@gmail.com>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$page = new admin_settingpage('theme_active_branding', get_string('brandingsettings', 'theme_active'));

// Favicon
$setting = new admin_setting_configstoredfile('core_admin/favicon',
    get_string('favicon', 'theme_active'),
    get_string('favicon_desc', 'theme_active'),
    'favicon', 0,
    ['maxfiles' => 1, 'accepted_types' => ['.ico', '.png']]);
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

// Logo
$setting = new admin_setting_configstoredfile('core_admin/logocompact',
    get_string('logo', 'theme_active'),
    get_string('logo_desc', 'theme_active'),
    'logocompact', 0,
    ['maxfiles' => 1, 'accepted_types' => ['.png', '.jpg', '.gif', '.svg', '.webp']]);
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

// Login logo
$setting = new admin_setting_configstoredfile('core_admin/logo',
    get_string('loginlogo', 'theme_active'),
    get_string('loginlogo_desc', 'theme_active'),
    'logo', 0,
    ['maxfiles' => 1, 'accepted_types' => ['.png', '.jpg', '.gif', '.svg', '.webp']]);
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

// Imagen del encabezado para los cursos sin imagen propia.
$setting = new admin_setting_configstoredfile('theme_active/courseheaderimage',
    get_string('courseheaderimage', 'theme_active'),
    get_string('courseheaderimage_desc', 'theme_active'),
    'courseheaderimage', 0,
    ['maxfiles' => 1, 'accepted_types' => ['.png', '.jpg', '.jpeg', '.webp']]);
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

$settings->add($page);
