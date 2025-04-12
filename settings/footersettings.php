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

$page = new admin_settingpage('theme_active_footer', get_string('footersettings', 'theme_active'));

//Social media
$pagedefault = 'https://example.page/';;

$setting = new admin_setting_configtext('theme_active/facebook_page',
    get_string('facebook_page', 'theme_active'),
    get_string('page_desc_footer', 'theme_active'), $pagedefault);
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

$setting = new admin_setting_configtext('theme_active/instagram_page',
    get_string('instagram_page', 'theme_active'),
    get_string('page_desc_footer', 'theme_active'), $pagedefault);
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

$setting = new admin_setting_configtext('theme_active/tiktok_page',
    get_string('tiktok_page', 'theme_active'),
    get_string('page_desc_footer', 'theme_active'), $pagedefault);
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

$setting = new admin_setting_configtext('theme_active/x_page',
    get_string('x_page', 'theme_active'),
    get_string('page_desc_footer', 'theme_active'), $pagedefault);
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

$setting = new admin_setting_configtext('theme_active/youtube_page',
    get_string('youtube_page', 'theme_active'),
    get_string('page_desc_footer', 'theme_active'), $pagedefault);
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

$setting = new admin_setting_configtext('theme_active/linkedin_page',
    get_string('linkedin_page', 'theme_active'),
    get_string('page_desc_footer', 'theme_active'), $pagedefault);
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

//address
$setting = new admin_setting_configtext('theme_active/institutionaddress',
    get_string('institutionaddress', 'theme_active'),
    get_string('institutionaddress_desc', 'theme_active'), 'Example Address');
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

//mail
$setting = new admin_setting_configtext('theme_active/emailcontact',
    get_string('emailcontact', 'theme_active'),
    get_string('emailcontact_desc', 'theme_active'), 'noreply@gmail.com');
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

//pbx
$setting = new admin_setting_configtext('theme_active/pbxcontact',
    get_string('pbxcontact', 'theme_active'),
    get_string('pbxcontact_desc', 'theme_active'), '1234567');
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

//whatsapp
$setting = new admin_setting_configtext('theme_active/whatsappcontact',
    get_string('whatsappcontact', 'theme_active'),
    get_string('whatsappcontact_desc', 'theme_active'), '+57 number');
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

//footer logo
$setting = new admin_setting_configstoredfile('theme_active/footerlogo',
    get_string('footerlogo', 'theme_active'),
    get_string('footerlogo_desc', 'theme_active'), 'footerlogo', 0,
    array('maxfiles' => 1, 'accepted_types' => array('.png', '.jpg', '.jpeg')));
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

//footer description
$setting = new admin_setting_confightmleditor(
    'theme_active/footerdescription',
    get_string('footerdescription', 'theme_active'),
    get_string('footerdescription_desc', 'theme_active'),
    get_string('footerdescription_desc', 'theme_active')
);
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

//important documents
$setting = new  admin_setting_configtextarea(
    'theme_active/customlinks',
    get_string('customlinks', 'theme_active'),
    get_string('customlinks_desc', 'theme_active'),
    '[
    {"name": "YouTube", "url": "https://youtube.com"},
    {"name": "GitHub", "url": "https://github.com/jrangelardila"}
]'
);
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

//Bgcolor to the footer
$setting = new admin_setting_configcolourpicker(
    'theme_active/bgcolorfooter',
    get_string('footerbgcolor', 'theme_active'),
    get_string('footerbgcolor_desc', 'theme_active'),
    '#212529'
);
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

// Add footer page
$settings->add($page);