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

$THEME->name = 'active';

$THEME->doctype = 'html5';

$THEME->parents = array(
    'boost',
);


$THEME->scss = function ($theme) {
    $pre = theme_active_get_scss($theme);
    $main = theme_boost_get_main_scss_content($theme);
    return $pre . "\n" . $main;
};


// The theme needs to be added to all Moodle layouts.
$THEME->layouts = array(

    // Most backwards compatible layout without the blocks.
    // This is the layout used by default.
    'base' => array(
        'file' => 'drawers.php',
        'regions' => array(),
    ),

    // Standard layout with blocks, recommended for most pages with general information.
    'standard' => array(
        'file' => 'drawers.php',
        'regions' => array('side-pre', 'side-top'),
        'defaultregion' => 'side-pre',
    ),

    // Main course page.
    'course' => array(
        'file' => 'drawers.php',
        'regions' => array('side-pre', 'side-top'),
        'defaultregion' => 'side-pre',
    ),

    'coursecategory' => array(
        'file' => 'drawers.php',
        'regions' => array('side-pre'),
        'defaultregion' => 'side-pre',
    ),

    // Part of course, typical for modules. Default page layout if $cm specified in require_login().
    'incourse' => array(
        'file' => 'drawers.php',
        'regions' => array('side-pre', 'side-top'),
        'defaultregion' => 'side-pre',
    ),

    // The site home page.
    'frontpage' => array(
        'file' => 'drawers.php',
        'regions' => array('side-pre', 'side-top'),
        'defaultregion' => 'side-pre',
    ),

    // Server administration scripts.
    'admin' => array(
        'file' => 'drawers.php',
        'regions' => array('side-pre', 'side-top'),
        'defaultregion' => 'side-pre',
    ),

    // My dashboard page.
    'mydashboard' => array(
        'file' => 'drawers.php',
        'regions' => array('side-pre', 'side-top'),
        'defaultregion' => 'side-pre',
    ),

    // My public page.
    'mypublic' => array(
        'file' => 'drawers.php',
        'regions' => array('side-pre', 'side-top'),
        'defaultregion' => 'side-pre',
    ),

    'login' => array(
        'file' => 'login.php',
        'regions' => array(),
    ),

    // Pages that appear in pop-up windows - no navigation, no blocks, no header.
    'popup' => array(
        'file' => 'layout11',
        'regions' => array(),
        'options' => array(
            'nofooter' => true,
            'noblocks' => true,
            'nonavbar' => true,
            'nocourseheaderfooter' => true
        ),
    ),

    // No blocks and minimal footer - used for legacy frame layouts.
    'frametop' => array(
        'file' => 'layout12',
        'regions' => array(),
        'options' => array(
            'nofooter' => true,
            'nocoursefooter' => true
        ),
    ),

    // Used during upgrade and install, and for the 'This site is undergoing maintenance message.
    // This must not have any blocks, and it is a good idea if it does not have links to other pages.
    'maintenance' => array(
        'file' => 'maintenance.php',
        'regions' => array(),
        'options' => array(
            'noblocks' => true,
            'nofooter' => true,
            'nonavbar' => true,
            'nocourseheaderfooter' => true
        ),
    ),

    // Embedded pages, like iframe/object embedded in moodleform. Needs as much space as possible.
    'embedded' => array(
        'file' => 'layout14',
        'regions' => array('side-pre'),
        'options' => array(
            'nofooter' => true,
            'nonavbar' => true,
            'nocourseheaderfooter' => true
        ),
    ),

    // Should display the content and basic headers only.
    'print' => array(
        'file' => 'layout15',
        'regions' => array(),
        'options' => array(
            'nofooter' => true,
            'nonavbar' => false,
            'noblocks' => true,
            'nocourseheaderfooter' => true
        ),
    ),

    // The pagelayout used when a redirection is occuring.
    'redirect' => array(
        'file' => 'layout16',
        'regions' => array(),
        'options' => array(
            'nofooter' => true,
            'nonavbar' => true,
            'nocourseheaderfooter' => true
        ),
    ),

    // The page layout used for reports.
    'report' => array(
        'file' => 'layout17',
        'regions' => array('side-pre', 'side-top'),
        'defaultregion' => 'side-pre',
    ),

    // The pagelayout used for safebrowser and securewindow.
    'secure' => array(
        'file' => 'layout18',
        'regions' => array('side-pre', 'side-top'),
        'options' => array(
            'nofooter' => true,
            'nonavbar' => true,
            'nocustommenu' => true,
            'nologinlinks' => true,
            'nocourseheaderfooter' => true
        ),
        'defaultregion' => 'side-pre',
    ),
);
