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

    // _____________________________________________________________________________Tab: Login config
    $page = new admin_settingpage('theme_active_login', get_string('loginsettings', 'theme_active'));

    // login background image
    $name = 'theme_active/loginbackground';
    $title = get_string('loginbackground', 'theme_active');
    $description = get_string('loginbackgrounddesc', 'theme_active');
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'loginbackground', 0,
        array('maxfiles' => 1, 'accepted_types' => array('.png', '.jpg', '.jpeg')));
    $page->add($setting);

    // login alignment
    $name = 'theme_active/loginalignment';
    $title = get_string('loginalignment', 'theme_active');
    $description = get_string('loginalignment_desc', 'theme_active');
    $choices = array(
        'mr-auto' => get_string('left', 'theme_active'),
        'mx-auto' => get_string('center', 'theme_active'),
        'ml-auto' => get_string('right', 'theme_active')
    );


    $setting = new admin_setting_configselect($name, $title, $description, 'mx-auto', $choices);
    $page->add($setting);

    // Add login page
    $settings->add($page);

    // _____________________________________________________________________________Tab: Header config
    $page = new admin_settingpage('theme_active_header', get_string('headersettings', 'theme_active'));

    //Add settings for logo, to the /admin/settings.php?section=logos

    $name = 'core_admin/logo';
    $title = get_string('logo_settings', 'theme_active');
    $description = get_string('logo_settingsdesc', 'theme_active');
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'logo', 0,
        array('maxfiles' => 1, 'accepted_types' => array('.png', '.jpg', '.jpeg')));
    $page->add($setting);

    $name = 'core_admin/logocompact';
    $title = get_string('logocompact_settings', 'theme_active');
    $description = get_string('logo_settingsdesc', 'theme_active');
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'logocompact', 0,
        array('maxfiles' => 1, 'accepted_types' => array('.png', '.jpg', '.jpeg')));
    $page->add($setting);

    // Add header page
    $settings->add($page);

    // _____________________________________________________________________________Tab: Footer config
    $page = new admin_settingpage('theme_active_footer', get_string('footersettings', 'theme_active'));


    // Add footer page
    $settings->add($page);
}
