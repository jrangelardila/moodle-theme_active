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

if ($ADMIN->fulltree) {

    $settings = new theme_boost_admin_settingspage_tabs('themesettingactive',
    get_string('configtitle', 'theme_active'), 'theme/active:changesettings');

    $page = new admin_settingpage('theme_active_general', get_string('generalsettings', 'theme_active'));

    // Content width.
    $name = 'theme_active/contentwidth';
    $title = get_string('contentwidth', 'theme_active');
    $description = get_string('contentwidth_desc', 'theme_active');
    $choices = [];
    for ($i = 60; $i <= 100; $i += 5) {
        $choices[$i] = $i . '%';
    }
    $setting = new admin_setting_configselect($name, $title, $description, 100, $choices);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Primary color.
    $name = 'theme_active/primarycolor';
    $title = get_string('primarycolor', 'theme_active');
    $description = get_string('primarycolor_desc', 'theme_active');
    $setting = new admin_setting_configcolourpicker($name, $title, $description, '#0f6cbf');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Secondary color.
    $name = 'theme_active/secondarycolor';
    $title = get_string('secondarycolor', 'theme_active');
    $description = get_string('secondarycolor_desc', 'theme_active');
    $setting = new admin_setting_configcolourpicker($name, $title, $description, '#6c757d');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Course cards progress.
    $name = 'theme_active/showcardprogress';
    $title = get_string('showcardprogress', 'theme_active');
    $description = get_string('showcardprogress_desc', 'theme_active');
    $setting = new admin_setting_configcheckbox($name, $title, $description, 1);
    $page->add($setting);

    $settings->add($page);

    require_once('settings/branding.php');
    require_once('settings/login.php');
    require_once('settings/quicklinks.php');
    require_once('settings/footer.php');
}
