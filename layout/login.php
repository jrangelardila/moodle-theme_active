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
 * Login page layout for theme_active.
 *
 * @package     theme_active
 * @copyright   2024 Jhon Rangel <jrangelardila@gmail.com>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$theme = theme_config::load('active');
$loginposition  = $theme->settings->loginposition ?? 'center';
$loginimageurl  = $theme->setting_file_url('loginimage', 'loginimage');

// Las tres opciones deben producir una clase: con 'center' antes se devolvia una cadena
// vacia y el formulario acababa pegado al borde izquierdo en lugar de centrado.
$positionclass = [
    'left'   => 'justify-content-start',
    'center' => 'justify-content-center',
    'right'  => 'justify-content-end',
][$loginposition] ?? 'justify-content-center';

$bodyattributes = $OUTPUT->body_attributes();

$templatecontext = [
    'sitename'           => format_string($SITE->shortname, true, ['context' => context_course::instance(SITEID), 'escape' => false]),
    'output'             => $OUTPUT,
    'bodyattributes'     => $bodyattributes,
    'loginpositionclass' => $positionclass,
    'loginimageurl'      => $loginimageurl ?: '',
    'hasloginimage'      => !empty($loginimageurl),
];

echo $OUTPUT->render_from_template('theme_active/login', $templatecontext);
