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
 * Return scss
 *
 * @param $theme
 * @return string
 * @throws dml_exception
 */
/**
 * Return scss
 *
 * @param $theme
 * @return string
 * @throws dml_exception
 */
function theme_active_get_scss($theme)
{

    $settings = new \theme_active\util\settings();
    $ctx = $settings->get_navbar();
    $primary = get_config("theme_active", "primarycolor");
    $second = get_config("theme_active", "secondarycolor");

    return <<<SCSS
\$primary: {$primary};
\$secondary: {$second};

//Drawer div
.drawer {
    background-color: mix(white, \$primary, 90%) !important;
    box-shadow: 0 0 60px rgba(0, 0, 0, 0.25);
}

.drawer-toggles .drawer-toggler .btn{
    background-color: {$ctx['navbarbg']} !important;
    color: {$ctx['textcolornavbar']} !important;
}

// Navbar container background
.navbar > .container,
.navbar > .container-fluid,
.navbar > .container-sm,
.navbar > .container-md,
.navbar > .container-lg,
.navbar > .container-xl,
.navbar > .container-xxl {
    background-color: {$ctx['navbarbg']};
}

// Primary nav links
.primary-navigation .navigation .nav-link {
    color: {$ctx['textcolornavbar']} !important;
}

// Hover on nav links
.navigation .nav-link:hover {
    color: {$ctx['hovertextcolornavbar']} !important;
    background-color: \$secondary !important;
}

// Active links hover and dropdowns
.moremenu .nav-link.active:hover,
.dropdown-item:hover,
.dropdown-item:focus {
    border-bottom-color: {$ctx['navbarbg']} !important;
    background-color: \$secondary !important;
    color: {$ctx['navbarbg']} !important;
}

// Active nav link
.nav-link.active {
    border-bottom-color: \$primary !important;
}

// General link styling
.nav-link,
.a,
a {
    color: {$ctx['navbarbg']};
    border-bottom-color: \$secondary !important;
}

// Edit mode label
.editmode-switch-form {
    color: {$ctx['textcolornavbar']} !important;
}

// Icons, labels and toggle
.me-2.mb-0.form-check-label,
.navbar.fixed-top .nav-link .icon,
#user-menu-toggle {
    color: {$ctx['textcolornavbar']} !important;
}
SCSS;

}



