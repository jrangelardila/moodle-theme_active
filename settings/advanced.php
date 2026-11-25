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
 * Advanced settings tab: raw SCSS before and after the theme styles.
 *
 * theme_active_get_pre_scss() and theme_active_get_extra_scss() read scsspre and scss; this tab lets admins edit them.
 *
 * @package     theme_active
 * @copyright   2026 Jhon Rangel <jrangelardila@gmail.com>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$page = new admin_settingpage('theme_active_advanced', get_string('advancedsettings', 'theme_active'));

// Raw initial SCSS: variables applied before the theme is compiled.
$setting = new admin_setting_scsscode('theme_active/scsspre',
    get_string('rawscsspre', 'theme_active'), get_string('rawscsspre_desc', 'theme_active'), '', PARAM_RAW);
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

// Raw SCSS: rules added at the end of the theme CSS.
$setting = new admin_setting_scsscode('theme_active/scss',
    get_string('rawscss', 'theme_active'), get_string('rawscss_desc', 'theme_active'), '', PARAM_RAW);
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

$settings->add($page);
