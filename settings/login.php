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
 * Login settings tab.
 *
 * @package     theme_active
 * @copyright   2024 Jhon Rangel <jrangelardila@gmail.com>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$page = new admin_settingpage('theme_active_login', get_string('loginsettings', 'theme_active'));

// Login form position.
$name = 'theme_active/loginposition';
$title = get_string('loginposition', 'theme_active');
$description = get_string('loginposition_desc', 'theme_active');
$choices = [
    'left'   => get_string('loginposition_left', 'theme_active'),
    'center' => get_string('loginposition_center', 'theme_active'),
    'right'  => get_string('loginposition_right', 'theme_active'),
];
$setting = new admin_setting_configselect($name, $title, $description, 'center', $choices);
$page->add($setting);

// Login image.
$name = 'theme_active/loginimage';
$title = get_string('loginimage', 'theme_active');
$description = get_string('loginimage_desc', 'theme_active');
$setting = new admin_setting_configstoredfile($name, $title, $description, 'loginimage', 0,
    ['maxfiles' => 1, 'accepted_types' => 'web_image']);
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

$settings->add($page);
