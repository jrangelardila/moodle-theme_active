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

    //Social media
    $pagedefault = 'https://example.page/';

    $name = 'theme_active/facebook_page';
    $title = get_string('facebook_page', 'theme_active');
    $description = get_string('page_desc_footer', 'theme_active');
    $setting = new admin_setting_configtext($name, $title, $description, $pagedefault);
    $page->add($setting);

    $name = 'theme_active/instagram_page';
    $title = get_string('instagram_page', 'theme_active');
    $description = get_string('page_desc_footer', 'theme_active');
    $setting = new admin_setting_configtext($name, $title, $description, $pagedefault);
    $page->add($setting);

    $name = 'theme_active/tiktok_page';
    $title = get_string('tiktok_page', 'theme_active');
    $description = get_string('page_desc_footer', 'theme_active');
    $setting = new admin_setting_configtext($name, $title, $description, $pagedefault);
    $page->add($setting);

    $name = 'theme_active/x_page';
    $title = get_string('x_page', 'theme_active');
    $description = get_string('page_desc_footer', 'theme_active');
    $setting = new admin_setting_configtext($name, $title, $description, $pagedefault);
    $page->add($setting);

    $name = 'theme_active/youtube_page';
    $title = get_string('youtube_page', 'theme_active');
    $description = get_string('page_desc_footer', 'theme_active');
    $setting = new admin_setting_configtext($name, $title, $description, $pagedefault);
    $page->add($setting);

    $name = 'theme_active/linkedin_page';
    $title = get_string('linkedin_page', 'theme_active');
    $description = get_string('page_desc_footer', 'theme_active');
    $setting = new admin_setting_configtext($name, $title, $description, $pagedefault);
    $page->add($setting);

    //Bgcolor to the footer
    $page->add(new admin_setting_configselect(
        'theme_active/bgcolorfooter',
        get_string('footerbgcolor', 'theme_active'),
        get_string('footerbgcolor_desc', 'theme_active'),
        'bg-light',
        [
            'bg-primary' => get_string('bg_primary', 'theme_active'),
            'bg-secondary' => get_string('bg_secondary', 'theme_active'),
            'bg-success' => get_string('bg_success', 'theme_active'),
            'bg-danger' => get_string('bg_danger', 'theme_active'),
            'bg-warning' => get_string('bg_warning', 'theme_active'),
            'bg-info' => get_string('bg_info', 'theme_active'),
            'bg-light' => get_string('bg_light', 'theme_active'),
            'bg-dark' => get_string('bg_dark', 'theme_active')
        ]
    ));

    //address
    $name = 'theme_active/institutionaddress';
    $title = get_string('institutionaddress', 'theme_active');
    $description = get_string('institutionaddress_desc', 'theme_active');
    $setting = new admin_setting_configtext($name, $title, $description, 'Example Address');
    $page->add($setting);

    //mail
    $name = 'theme_active/emailcontact';
    $title = get_string('emailcontact', 'theme_active');
    $description = get_string('emailcontact_desc', 'theme_active');
    $setting = new admin_setting_configtext($name, $title, $description, 'noreply@gmail.com');
    $page->add($setting);

    //pbx
    $name = 'theme_active/pbxcontact';
    $title = get_string('pbxcontact', 'theme_active');
    $description = get_string('pbxcontact_desc', 'theme_active');
    $setting = new admin_setting_configtext($name, $title, $description, '1234567');
    $page->add($setting);

    //whatsapp
    $name = 'theme_active/whatsappcontact';
    $title = get_string('whatsappcontact', 'theme_active');
    $description = get_string('whatsappcontact_desc', 'theme_active');
    $setting = new admin_setting_configtext($name, $title, $description, '+57 number');
    $page->add($setting);

    //footer logo
    $name = 'theme_active/footerlogo';
    $title = get_string('footerlogo', 'theme_active');
    $description = get_string('footerlogo_desc', 'theme_active');
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'footerlogo', 0,
        array('maxfiles' => 1, 'accepted_types' => array('.png', '.jpg', '.jpeg')));
    $page->add($setting);

    //footer description
    $name = 'theme_active/footerdescription';
    $title = get_string('footerdescription', 'theme_active');
    $description = get_string('footerdescription_desc', 'theme_active');
    $page->add(new admin_setting_confightmleditor(
        $name, $title, $description, $description
    ));

    //important documents
    $page->add(new admin_setting_configtextarea(
        'theme_active/customlinks',
        get_string('customlinks', 'theme_active'),
        get_string('customlinks_desc', 'theme_active'),
        '[
    {"name": "YouTube", "url": "https://youtube.com"},
    {"name": "GitHub", "url": "https://github.com/jrangelardila"}
]'
    ));


    // Add footer page
    $settings->add($page);
}
