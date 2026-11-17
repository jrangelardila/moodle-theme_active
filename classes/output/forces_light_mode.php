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
 * Fuerza el modo claro cuando no hay sesion iniciada.
 *
 * El plugin local_boost_dark escucha el hook before_html_attributes y escribe
 * data-bs-theme. Sin sesion emite "auto", que no significa "claro" sino "sigue la
 * preferencia del sistema operativo": un visitante con el equipo en oscuro ve el sitio
 * oscuro sin haberlo pedido. Lo mismo con una cookie darkmode heredada o con
 * ?darkmode=dark en la URL.
 *
 * El trait existe porque hace falta el mismo ajuste en dos renderers distintos: el normal
 * y el de mantenimiento. Moodle usa core_renderer_maintenance en cuanto el pagelayout es
 * 'maintenance', y ese no hereda del renderer del tema, de modo que sin esto la pagina de
 * mantenimiento se quedaba en "auto".
 *
 * Con sesion iniciada se respeta la preferencia guardada del usuario. Si nunca ha usado
 * el interruptor, el plugin tambien emite "auto": se cambia por "light" para que las
 * cuentas nuevas empiecen en claro y el interruptor coincida con lo que se ve.
 *
 * @package    theme_active
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
trait forces_light_mode {

    /**
     * Atributos del <html>, con el modo claro forzado para anonimos e invitados.
     *
     * @return string
     */
    public function htmlattributes() {
        $attributes = parent::htmlattributes();

        if (isloggedin() && !isguestuser()) {
            // Sin preferencia guardada, "auto" pasa a "light". Cualquier otro valor
            // (preferencia "dark"/"light" o ?darkmode= en la URL) se deja tal cual.
            if (get_user_preferences('darkmode') === null) {
                $attributes = str_replace('data-bs-theme="auto"', 'data-bs-theme="light"', $attributes);
            }
            return $attributes;
        }

        // Se quita el data-bs-theme que venga del plugin y se fija en claro.
        $attributes = preg_replace('/\s*data-bs-theme="[^"]*"/', '', $attributes);

        return $attributes . ' data-bs-theme="light"';
    }
}
