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
 * Footer settings tab.
 *
 * @package     theme_active
 * @copyright   2024 Jhon Rangel <jrangelardila@gmail.com>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$page = new admin_settingpage('theme_active_footer', get_string('footersettings', 'theme_active'));

// Social media URLs.
foreach (['facebook', 'twitter', 'instagram', 'linkedin', 'youtube', 'tiktok'] as $network) {
    $name = 'theme_active/' . $network . '_url';
    $title = get_string($network . '_url', 'theme_active');
    $description = get_string($network . '_url_desc', 'theme_active');
    $setting = new admin_setting_configtext($name, $title, $description, '', PARAM_URL);
    $page->add($setting);
}

// Moodle app — Android.
$name = 'theme_active/moodleapp_android_url';
$title = get_string('moodleapp_android_url', 'theme_active');
$description = get_string('moodleapp_android_url_desc', 'theme_active');
$setting = new admin_setting_configtext($name, $title, $description,
    'https://play.google.com/store/apps/details?id=com.moodle.moodlemobile&pcampaignid=web_share', PARAM_URL);
$page->add($setting);

// Moodle app — iOS.
$name = 'theme_active/moodleapp_ios_url';
$title = get_string('moodleapp_ios_url', 'theme_active');
$description = get_string('moodleapp_ios_url_desc', 'theme_active');
$setting = new admin_setting_configtext($name, $title, $description,
    'https://apps.apple.com/bo/app/moodle/id633359593', PARAM_URL);
$page->add($setting);

$settings->add($page);
