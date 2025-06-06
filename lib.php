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
 * Return pre scss
 *
 * @param $theme
 * @return string
 * @throws dml_exception
 */
function theme_active_get_pre_scss($theme)
{
    $scss = '';
    $settings = new \theme_active\util\settings();
    $ctx = $settings->get_navbar();

    $configurable = [
        'primarycolor' => ['primary'],
        'secondarycolor' => ['secondary'],
        'navbarbg' => ['navbarbg'],
        'textcolornavbar' => ['textcolornavbar'],
        'hovertextcolornavbar' => ['hovertextcolornavbar'],
    ];

    foreach ($configurable as $configkey => $targets) {
        $value = $theme->settings->{$configkey} ?? $ctx[$configkey] ?? get_config('theme_active', $configkey);

        if (empty($value)) {
            continue;
        }

        array_map(function ($target) use (&$scss, $value, $configkey) {
            $scss .= '$' . $target . ': ' . $value . ";\n";
        }, (array)$targets);
    }

    if (!empty($theme->settings->scsspre)) {
        $scss .= $theme->settings->scsspre;
    }

    return $scss;
}

/**
 * Get main scss
 *
 * @param $theme
 * @return string
 * @throws dml_exception
 */
function theme_active_get_main_scss_content($theme)
{
    global $CFG;
    $scss = theme_active_get_pre_scss($theme);
    $scss .= file_get_contents($CFG->dirroot . '/theme/active/scss/active.scss');
    $scss .= theme_boost_get_main_scss_content($theme);
    return $scss;
}



