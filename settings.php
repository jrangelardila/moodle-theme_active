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
    $settings = new admin_settingpage('theme_active_settings', new lang_string('pluginname', 'theme_active'));

    // phpcs:ignore Generic.CodeAnalysis.EmptyStatement.DetectedIf
    if ($ADMIN->fulltree) {
        //Login background image
        $name = 'theme_active/loginbackground';
        $title = get_string('loginbackground', 'theme_active');
        $description = get_string('loginbackgrounddesc', 'theme_active');
        $default = '';
        $setting = new admin_setting_configstoredfile($name, $title, $description, 'loginbackground', 0,
            array('maxfiles' => 1, 'accepted_types' => array('.png,.jpg,.jpeg')));
        $settings->add($setting);


        //Login alignement
        $name = 'theme_active/loginalignment';
        $title = get_string('loginalignment', 'theme_active');
        $description = get_string('loginalignment_desc', 'theme_active');
        $choices = array(
            'mr-auto' => get_string('left', 'theme_active'),
            'mx-auto' => get_string('center', 'theme_active'),
            'ml-auto' => get_string('right', 'theme_active')
        );
        $setting = new admin_setting_configselect($name, $title, $description, 'center', $choices);
        $settings->add($setting);
    }
}
