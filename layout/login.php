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
 * The layout/login.php for theme_active is defined here.
 *
 * @package     theme_active
 * @copyright   2024 Jhon Rangel <jrangelardila@gmail.com>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


$bodyattributes = $OUTPUT->body_attributes();

$context = context_system::instance();
$loginbackground = get_config('theme_active', 'loginbackground');
$backgroundurl = '';

$fs = get_file_storage();
$file = $fs->get_file($context->id, 'theme_active', 'loginbackground', 0, '/', $loginbackground);
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

$templatecontext = [
    'sitename' => format_string($SITE->shortname, true, ['context' => context_course::instance(SITEID), "escape" => false]),
    'output' => $OUTPUT,
    'bodyattributes' => $bodyattributes,
    'backgroundurl' => $backgroundurl,
    'loginalignment' => get_config('theme_active', 'loginalignment'),
];

$PAGE->requires->js_call_amd('theme_active/login', 'init');
echo $OUTPUT->render_from_template('theme_active/login', $templatecontext);