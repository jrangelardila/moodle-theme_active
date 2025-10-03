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

$page = new admin_settingpage('theme_active_login', get_string('loginsettings', 'theme_active'));

// login background image
$setting = new admin_setting_configstoredfile('theme_active/loginbackground',
    get_string('loginbackground', 'theme_active'),
    get_string('loginbackgrounddesc', 'theme_active'),
    'loginbackground', 0,
    array('maxfiles' => 1, 'accepted_types' => array('.png', '.jpg', '.jpeg')));
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

// login alignment
$choices = array(
    'mr-auto' => get_string('left', 'theme_active'),
    'mx-auto' => get_string('center', 'theme_active'),
    'ml-auto' => get_string('right', 'theme_active')
);
$setting = new admin_setting_configselect('theme_active/loginalignment',
    get_string('loginalignment', 'theme_active'),
    get_string('loginalignment_desc', 'theme_active'),
    'mx-auto', $choices);
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

// Add login page
$settings->add($page);