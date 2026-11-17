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
 * Core renderer overrides for theme_active.
 *
 * @package    theme_active
 * @copyright  2026 Jhon Rangel <jrangelardila@gmail.com>
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace theme_active\output;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/theme/active/lib.php');

/**
 * Core renderer for theme_active.
 *
 * Extends Boost's core renderer to inject course teacher avatars
 * into the page header banner.
 */
class core_renderer extends \theme_boost\output\core_renderer {

    // Fuerza el modo claro para anonimos e invitados. La logica vive en el trait porque
    // la pagina de mantenimiento usa otro renderer, core_renderer_maintenance, que no
    // hereda de este. Se hace en el tema, y no en el plugin, para no tocar codigo ajeno.
    use forces_light_mode;

    /**
     * Contenido que los plugins anaden a la barra de navegacion.
     *
     * Se omite el conmutador de modo oscuro de local_boost_dark cuando no hay sesion
     * iniciada: su preferencia se guarda contra la cuenta del usuario, asi que no tiene
     * sentido ofrecerlo en la pagina de entrada ni a los invitados.
     *
     * @return string
     */
    public function navbar_plugin_output() {
        if (isloggedin() && !isguestuser()) {
            return parent::navbar_plugin_output();
        }

        // Misma logica que core_renderer::navbar_plugin_output(), pero saltandose el
        // conmutador de local_boost_dark. Se omite el plugin entero en lugar de recortar
        // su HTML a posteriori, que es fragil.
        $output = '';

        foreach (\core_component::get_core_subsystems() as $name => $path) {
            if ($path) {
                $output .= component_callback($name, 'render_navbar_output', [$this], '');
            }
        }

        if ($pluginsfunction = get_plugins_with_function('render_navbar_output')) {
            foreach ($pluginsfunction as $plugintype => $plugins) {
                foreach ($plugins as $pluginname => $pluginfunction) {
                    if ($plugintype === 'local' && $pluginname === 'boost_dark') {
                        continue;
                    }
                    $output .= $pluginfunction($this);
                }
            }
        }

        return $output;
    }

    public function full_header() {
        $pagetype = $this->page->pagetype;
        $homepage = get_home_page();
        $homepagetype = null;
        if ($homepage == HOMEPAGE_MY || $homepage == HOMEPAGE_MYCOURSES) {
            $homepagetype = 'my-index';
        } else if ($homepage == HOMEPAGE_SITE) {
            $homepagetype = 'site-index';
        }
        if ($this->page->include_region_main_settings_in_header_actions() &&
                !$this->page->blocks->is_block_present('settings')) {
            $this->page->add_header_action(\html_writer::div(
                $this->region_main_settings_menu(),
                'd-print-none',
                ['id' => 'region-main-settings-menu']
            ));
        }

        $header = new \stdClass();
        $header->settingsmenu = $this->context_header_settings_menu();
        $header->contextheader = $this->context_header();
        $header->hasnavbar = empty($this->page->layout_options['nonavbar']);
        $header->navbar = $this->navbar();
        $header->pageheadingbutton = $this->page_heading_button();
        $header->courseheader = $this->course_header();
        $header->headeractions = $this->page->get_header_actions();
        // Desde Moodle 5.2 las fechas de la actividad (apertura, cierre...) llegan como extras del encabezado.
        $header->headerextras = $this->page->get_header_extras();
        if (!empty($pagetype) && !empty($homepagetype) && $pagetype == $homepagetype) {
            $header->welcomemessage = \core_user::welcome_message();
        }

        // course teacher avatars
        $teacherctx = theme_active_get_header_teachers_context($this->page, $this);
        foreach ($teacherctx as $key => $value) {
            $header->$key = $value;
        }

        // Símbolo de la institución en blanco, en el recuadro del título (pix/logo_symbol_white.png).
        $header->headerlogourl = $this->image_url('logo_symbol_white', 'theme')->out(false);

        // course overview image for header background
        $header->courseimageurl = $this->get_course_header_image();
        // Sin imagen de curso (o fuera de un curso) se usa el banner de la institución, con su logo.
        $header->institutionalbanner = false;
        if (empty($header->courseimageurl)) {
            $header->courseimageurl = $this->get_institutional_banner();
            $header->institutionalbanner = !empty($header->courseimageurl);
        }

        // Back to course button on the calendar view inside a course.
        global $COURSE;
        $header->hasbacktocourse = false;
        if ($this->page->pagetype === 'calendar-view'
            && !empty($COURSE->id)
            && $COURSE->id != SITEID) {
            $header->hasbacktocourse = true;
            $header->backtocourseurl = (new \moodle_url('/course/view.php', [
                'id' => $COURSE->id,
            ]))->out(false);
            $header->backtocourselabel = get_string('backtocourse', 'theme_active');
        }

        // Quick links on dashboard only.
        if ($this->page->pagelayout === 'mydashboard') {
            $qlctx = theme_active_get_quicklinks_context();
            $header->hasquicklinks = $qlctx['hasquicklinks'];
            $header->quicklinks = $qlctx['quicklinks'];
        }

        return $this->render_from_template('core/full_header', $header);
    }

    /**
     * Banner de la institución para el encabezado cuando no hay imagen de curso.
     *
     * Usa la imagen del ajuste courseheaderimage y, si no hay, la que trae el tema (pix/default/courseheader.jpg).
     * Aplica en todas las páginas: portada, área personal, calendario, perfil y cursos sin imagen.
     *
     * @return string|null
     */
    protected function get_institutional_banner(): ?string {
        $url = $this->page->theme->setting_file_url('courseheaderimage', 'courseheaderimage');
        if (empty($url)) {
            $url = $this->image_url('default/courseheader', 'theme')->out(false);
        }
        return $url;
    }

    /**
     * Get the course overview image URL for the header background.
     *
     * @return string|null The image URL or null if no image exists.
     */
    protected function get_course_header_image(): ?string {
        global $COURSE;

        if (empty($COURSE->id) || $COURSE->id == SITEID) {
            return null;
        }
        // En caché por curso: sin consultar los archivos del curso en cada página.
        $url = theme_active_get_course_image_url((int) $COURSE->id);
        return $url !== '' ? $url : null;
    }
}
