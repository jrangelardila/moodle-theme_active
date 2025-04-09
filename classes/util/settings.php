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
 * The configuration for theme_active is defined here.
 *
 * @package     theme_active
 * @copyright   2024 Jhon Rangel <jrangelardila@gmail.com>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace theme_active\util;

use context_system;
use core\exception\coding_exception;
use moodle_url;
use stdClass;
use theme_config;

defined('MOODLE_INTERNAL') || die;

/**
 * Return settings
 */
class settings
{
    /**
     * Config manager
     * @var \core\output\theme_config
     */
    protected $theme;

    /**
     * @throws coding_exception
     */
    public function __construct()
    {
        $this->theme = theme_config::load('active');
    }

    /**
     * Return footer
     *
     * @return array
     * @throws \dml_exception
     */
    public function get_footer()
    {
        $templatecontext = [
            'addrescontact' => get_config('theme_active', 'institutionaddress'),
            'emailcontact' => get_config('theme_active', 'emailcontact'),
            'pbxcontact' => get_config('theme_active', 'pbxcontact'),
            'whatsappcontact' => get_config('theme_active', 'whatsappcontact'),
            'customlinks' => json_decode(get_config('theme_active', 'customlinks'), true),
        ];

        $context = context_system::instance();
        //footerlogo
        $loginbackground = get_config('theme_active', 'footerlogo');
        $backgroundurl = '';
        $fs = get_file_storage();
        $file = $fs->get_file($context->id, 'theme_active', 'footerlogo', 0, '/', $loginbackground);
        if ($loginbackground) {
            $backgroundurl = moodle_url::make_pluginfile_url(
                $context->id,
                $file->get_component(),
                $file->get_filearea(),
                $file->get_itemid(),
                $file->get_filepath(),
                $file->get_filename()
            );
        }
        $templatecontext['footerlogo'] = $backgroundurl;
        //footerdescription
        $templatecontext['footerdescription'] = get_config('theme_active', 'footerdescription');
        //bgcolorfooter
        $templatecontext['bgcolorfooter'] = get_config('theme_active', 'bgcolorfooter');
        //textcolorfooter
        [$r, $g, $b] = sscanf(ltrim($templatecontext['bgcolorfooter'], '#'), '%02x%02x%02x');
        $luminance = (0.2126 * $r + 0.7152 * $g + 0.0722 * $b) / 255;
        $templatecontext['textcolorfooter'] = $luminance > 0.6 ? 'text-dark' : 'text-white';
        //socialmedia
        $templatecontext['socialmedia'] = $this->get_socialmedia_footer();

        return $templatecontext;
    }

    /**
     * Return top blocks
     *
     * @return mixed
     */
    public function get_top_blocks()
    {
        global $OUTPUT;

        return $OUTPUT->blocks('side-top');
    }

    /**
     * Return social media
     *
     * @return array
     * @throws \dml_exception
     */
    function get_socialmedia_footer()
    {
        $socialmedia = [];

        [$r, $g, $b] = sscanf(ltrim(get_config('theme_active', 'bgcolorfooter'), '#'), '%02x%02x%02x');
        $luminance = (0.2126 * $r + 0.7152 * $g + 0.0722 * $b) / 255;
        $btn = $luminance > 0.6 ? 'btn-outline-dark' : 'btn-outline-light';

        if (get_config('theme_active', 'facebook_page') != "") {
            $obj = new stdClass();
            $obj->url = get_config('theme_active', 'facebook_page');
            $obj->label = 'Facebook';
            $obj->class = 'fa-facebook';
            $obj->btnclass = $btn;

            $socialmedia[] = $obj;
        }
        if (get_config('theme_active', 'instagram_page') != "") {
            $obj = new stdClass();
            $obj->url = get_config('theme_active', 'instagram_page');
            $obj->label = 'Instagram';
            $obj->class = 'fa-instagram';
            $obj->btnclass = $btn;

            $socialmedia[] = $obj;
        }

        if (get_config('theme_active', 'x_page') != "") {
            $obj = new stdClass();
            $obj->url = get_config('theme_active', 'x_page');
            $obj->label = 'Twitter';
            $obj->class = 'fa-twitter';
            $obj->btnclass = $btn;

            $socialmedia[] = $obj;
        }

        if (get_config('theme_active', 'tiktok_page') != "") {
            $obj = new stdClass();
            $obj->url = get_config('theme_active', 'tiktok_page');
            $obj->label = 'TikTok';
            $obj->class = 'fa-tiktok';
            $obj->btnclass = $btn;

            $socialmedia[] = $obj;
        }

        if (get_config('theme_active', 'youtube_page') != "") {
            $obj = new stdClass();
            $obj->url = get_config('theme_active', 'youtube_page');
            $obj->label = 'YouTube';
            $obj->class = 'fa-youtube';
            $obj->btnclass = $btn;

            $socialmedia[] = $obj;
        }

        if (get_config('theme_active', 'linkedin_page') != "") {
            $obj = new stdClass();
            $obj->url = get_config('theme_active', 'linkedin_page');
            $obj->label = 'LinkedIn';
            $obj->class = 'fa-linkedin-in';
            $obj->btnclass = $btn;

            $socialmedia[] = $obj;
        }

        return $socialmedia;
    }

    public function get_navbar()
    {
        $templatecontext = [
            'navbarbg' => get_config('theme_active', 'bgcolornavbar'),
        ];
        //textcolornavbar
        [$r, $g, $b] = sscanf(ltrim($templatecontext['navbarbg'], '#'), '%02x%02x%02x');
        $luminance = (0.2126 * $r + 0.7152 * $g + 0.0722 * $b) / 255;
        $templatecontext['textcolornavbar'] = $luminance > 0.6 ? '#000000' : '#ffffff';
        //secondcolor navbar
        $templatecontext['secondcolornavbar'] = get_config('theme_active', 'secondcolornavbar');
        //hovercolornavbar
        [$r, $g, $b] = sscanf(ltrim($templatecontext['secondcolornavbar'], '#'), '%02x%02x%02x');
        $luminance = (0.2126 * $r + 0.7152 * $g + 0.0722 * $b) / 255;
        $templatecontext['hovertextcolornavbar'] = $luminance > 0.6 ? '#000000' : '#ffffff';




        return $templatecontext;
    }

}