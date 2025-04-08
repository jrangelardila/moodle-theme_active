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

/**
 * Return files
 *
 * @param $course
 * @param $cm
 * @param $context
 * @param $filearea
 * @param $args
 * @param $forcedownload
 * @param array $options
 * @return false|void
 * @throws coding_exception|moodle_exception
 */
function theme_active_pluginfile($course, $cm, $context, $filearea, $args, $forcedownload, $options)
{
    global $CFG, $DB;

    //Get itemid and path the file
    $itemid = array_shift($args);
    $relativepath = implode('/', $args);

    // Ger file for file_storage
    $fs = get_file_storage();
    $file = $fs->get_file($context->id, 'theme_active', $filearea, $itemid, '/', $relativepath);

    // If file not exists, return 404
    if (!$file) {
        send_file_not_found();
    }

    // Send the file
    send_stored_file($file, 0, 0, $forcedownload, $options);
}

/**
 * Return social media pages
 *
 * @return array
 * @throws dml_exception
 */
function theme_active_getsocialmedia()
{
    $socialmedia = [];

    $btn = theme_active_get_btnbg(get_config('theme_active', 'bgcolorfooter'));

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

/**
 * Retornar logo del footer
 *
 * @return \core\url|string
 * @throws dml_exception
 */
function theme_active_getlogofooter()
{
    $context = context_system::instance();
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

    return $backgroundurl;
}

/**
 * Return color to the text footer
 *
 * @param $bg
 * @return string
 */
function theme_active_get_textfooter($bg)
{
    [$r, $g, $b] = sscanf(ltrim($bg, '#'), '%02x%02x%02x');
    $luminance = (0.2126 * $r + 0.7152 * $g + 0.0722 * $b) / 255;
    return $luminance > 0.6 ? 'text-dark' : 'text-white';
}

/**
 * Bgcolor fot the social media buttons
 *
 * @param $bg
 * @return string
 */
function theme_active_get_btnbg(string $bg): string
{
    [$r, $g, $b] = sscanf(ltrim($bg, '#'), '%02x%02x%02x');
    $luminance = (0.2126 * $r + 0.7152 * $g + 0.0722 * $b) / 255;
    return $luminance > 0.6 ? 'btn-outline-dark' : 'btn-outline-light';
}


