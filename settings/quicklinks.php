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
 * Quick links settings tab.
 *
 * @package     theme_active
 * @copyright   2026 Jhon Rangel <jrangelardila@gmail.com>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$page = new admin_settingpage('theme_active_quicklinks', get_string('quicklinks', 'theme_active'));

$page->add(new admin_setting_heading(
    'theme_active/quicklinks_heading',
    get_string('quicklinks', 'theme_active'),
    get_string('quicklinks_desc', 'theme_active')
));

$count = 6;

for ($i = 1; $i <= $count; $i++) {

    $page->add(new admin_setting_heading(
        "theme_active/quicklink{$i}_heading",
        get_string('quicklink_number', 'theme_active', $i),
        ''
    ));

    // Label.
    $name = "theme_active/quicklink{$i}_label";
    $title = get_string('quicklink_label', 'theme_active');
    $description = get_string('quicklink_label_desc', 'theme_active');
    $setting = new admin_setting_configtext($name, $title, $description, '', PARAM_TEXT);
    $page->add($setting);

    // URL.
    $name = "theme_active/quicklink{$i}_url";
    $title = get_string('quicklink_url', 'theme_active');
    $description = get_string('quicklink_url_desc', 'theme_active');
    $setting = new admin_setting_configtext($name, $title, $description, '', PARAM_URL);
    $page->add($setting);

    // Icon
    $name = "theme_active/quicklink{$i}_icon";
    $title = get_string('quicklink_icon', 'theme_active');
    $description = get_string('quicklink_icon_desc', 'theme_active');
    $setting = new admin_setting_configtext($name, $title, $description, '', PARAM_TEXT);
    $page->add($setting);

    // Target.
    $name = "theme_active/quicklink{$i}_target";
    $title = get_string('quicklink_target', 'theme_active');
    $description = get_string('quicklink_target_desc', 'theme_active');
    $choices = [
        '_blank' => get_string('quicklink_target_blank', 'theme_active'),
        '_self'  => get_string('quicklink_target_self', 'theme_active'),
    ];
    $setting = new admin_setting_configselect($name, $title, $description, '_blank', $choices);
    $page->add($setting);
}

$settings->add($page);
