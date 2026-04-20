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

require_once(__DIR__ . '/lib.php');

$THEME->name = 'active';

$THEME->parents = array(
    'boost',
);

$THEME->rendererfactory = 'theme_overridden_renderer_factory';
$THEME->usefallback = true;
$THEME->iconsystem = \core\output\icon_system::FONTAWESOME;
$THEME->haseditswitch = true;
$THEME->usescourseindex = true;
$THEME->requiredblocks = '';
$THEME->addblockposition = BLOCK_ADDBLOCK_POSITION_FLATNAV;
$THEME->scss = function($theme) {
    return theme_active_get_main_scss_content($theme);
};
$THEME->extrascsscallback = 'theme_active_get_extra_scss';
$THEME->prescsscallback = 'theme_active_get_pre_scss';

$THEME->layouts = array(

    // Most backwards compatible layout without the blocks.
    // This is the layout used by default.
    'base' => array(
        'theme' => 'active',
        'file' => 'drawers.php',
        'regions' => array(),
    ),

    // Standard layout with blocks, recommended for most pages with general information.
    'standard' => array(
        'theme' => 'active',
        'file' => 'drawers.php',
        'regions' => array('side-pre'),
        'defaultregion' => 'side-pre',
    ),

    // Main course page.
    'course' => array(
        'theme' => 'active',
        'file' => 'drawers.php',
        'regions' => array('side-pre', 'course-top'),
        'defaultregion' => 'side-pre',
        'options' => array('langmenu' => true),
    ),

    'coursecategory' => array(
        'theme' => 'active',
        'file' => 'drawers.php',
        'regions' => array('side-pre', 'course-top'),
        'defaultregion' => 'side-pre',
    ),

    // Part of course, typical for modules. Default page layout if $cm specified in require_login().
    'incourse' => array(
        'theme' => 'active',
        'file' => 'drawers.php',
        'regions' => array('side-pre', 'course-top'),
        'defaultregion' => 'side-pre',
    ),

    // The site home page.
    'frontpage' => array(
        'theme' => 'active',
        'file' => 'drawers.php',
        'regions' => array('side-pre'),
        'defaultregion' => 'side-pre',
    ),

    // Server administration scripts.
    'admin' => array(
        'theme' => 'active',
        'file' => 'drawers.php',
        'regions' => array('side-pre'),
        'defaultregion' => 'side-pre',
    ),

    // My dashboard page.
    'mydashboard' => array(
        'theme' => 'active',
        'file' => 'drawers.php',
        'regions' => array('side-pre'),
        'defaultregion' => 'side-pre',
        'options' => array('langmenu' => true),
    ),

    // My public page.
    'mypublic' => array(
        'theme' => 'active',
        'file' => 'drawers.php',
        'regions' => array('side-pre'),
        'defaultregion' => 'side-pre',
    ),

    'login' => array(
        'theme' => 'active',
        'file' => 'login.php',
        'regions' => array(),
        'options' => array('langmenu' => true),
    ),

    // Pages that appear in pop-up windows - no navigation, no blocks, no header.
    'popup' => array(
        'file' => 'columns1.php',
        'regions' => array(),
        'options' => array(
            'nofooter' => true,
            'nonavbar' => true,
        ),
    ),

    // No blocks and minimal footer - used for legacy frame layouts.
    'frametop' => array(
        'file' => 'columns1.php',
        'regions' => array(),
        'options' => array(
            'nofooter' => true,
            'nocoursefooter' => true,
        ),
    ),

    // Used during upgrade and install, and for the 'This site is undergoing maintenance' message.
    // This must not have any blocks, and it is a good idea if it does not have links to other pages.
    'maintenance' => array(
        'file' => 'maintenance.php',
        'regions' => array(),
    ),

    // Embedded pages, like iframe/object embedded in moodleform. Needs as much space as possible.
    'embedded' => array(
        'file' => 'embedded.php',
        'regions' => array('side-pre'),
        'defaultregion' => 'side-pre',
    ),

    // Should display the content and basic headers only.
    'print' => array(
        'file' => 'columns1.php',
        'regions' => array(),
        'options' => array(
            'nofooter' => true,
            'nonavbar' => false,
            'noactivityheader' => true,
        ),
    ),

    // The pagelayout used when a redirection is occuring.
    'redirect' => array(
        'file' => 'embedded.php',
        'regions' => array(),
    ),

    // The page layout used for reports.
    'report' => array(
        'file' => 'drawers.php',
        'regions' => array('side-pre'),
        'defaultregion' => 'side-pre',
    ),

    // The pagelayout used for safebrowser and securewindow.
    'secure' => array(
        'file' => 'secure.php',
        'regions' => array('side-pre'),
        'defaultregion' => 'side-pre',
    ),
);
