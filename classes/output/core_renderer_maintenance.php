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

namespace theme_active\output;

defined('MOODLE_INTERNAL') || die();

/**
 * Renderer que Moodle usa mientras el sitio esta en mantenimiento.
 *
 * En cuanto el pagelayout es 'maintenance', moodle_page::get_renderer() fuerza el destino
 * a RENDERER_TARGET_MAINTENANCE y la fabrica busca core_renderer_maintenance, no
 * core_renderer. Como el tema no lo definia, se usaba el del core, que hereda del
 * core_renderer del core y no del de theme_active: el ajuste de modo claro se saltaba y la
 * pagina de mantenimiento salia con data-bs-theme="auto". Con el equipo del visitante en
 * oscuro, el texto quedaba casi ilegible.
 *
 * @package    theme_active
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class core_renderer_maintenance extends \core\output\core_renderer_maintenance {

    use forces_light_mode;
}
