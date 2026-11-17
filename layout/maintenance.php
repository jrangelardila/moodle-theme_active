<?php
// This file is part of Moodle - http://moodle.org/
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
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Maintenance layout for theme_active.
 *
 * @package    theme_active
 * @copyright  2026 Jhon Rangel <jrangelardila@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

// Ojo: este layout tambien se usa durante la instalacion y la actualizacion, cuando aun no
// hay tablas en la base de datos. Por eso no se llama a format_string() con contexto ni se
// toca nada que dependa de la BD sin comprobarlo antes.
$templatecontext = [
    'sitename' => format_string($SITE->shortname, true, ['escape' => false]),
    'output' => $OUTPUT,
];

// El logotipo solo se pide con el sitio ya instalado: get_logo_url() lee configuracion y
// el area de archivos, que durante la instalacion todavia no existen.
if (!during_initial_install()) {
    $logourl = $OUTPUT->get_logo_url(null, 120);
    if ($logourl) {
        $templatecontext['logourl'] = $logourl->out(false);
    }
}

echo $OUTPUT->render_from_template('theme_active/maintenance', $templatecontext);
