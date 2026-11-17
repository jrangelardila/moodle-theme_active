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
 * Event observers for theme_active.
 *
 * @package     theme_active
 * @copyright   2026 Jhon Rangel <jrangelardila@gmail.com>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace theme_active;

/**
 * Keeps the theme caches in sync: course progress, course image and course teachers.
 */
class event_observer {
    /**
     * Drop the cached progress of the affected user when an activity completion changes.
     *
     * @param \core\event\course_module_completion_updated $event
     */
    public static function completion_updated(\core\event\course_module_completion_updated $event): void {
        global $CFG;
        require_once($CFG->dirroot . '/theme/active/lib.php');

        $userid = (int) $event->relateduserid;
        $courseid = (int) $event->courseid;
        if (!$userid || !$courseid) {
            return;
        }
        $cacherev = (int) get_course($courseid)->cacherev;
        \cache::make('theme_active', 'courseprogress')
            ->delete(theme_active_course_progress_cache_key($courseid, $cacherev, $userid));
    }

    /**
     * Curso editado, restaurado o borrado: su imagen y sus docentes pueden haber cambiado.
     *
     * @param \core\event\base $event
     */
    public static function course_changed(\core\event\base $event): void {
        $courseid = (int) $event->courseid;
        if ($courseid) {
            \cache::make('theme_active', 'courseimage')->delete($courseid);
            \cache::make('theme_active', 'courseteachers')->delete($courseid);
        }
    }

    /**
     * Cambio de roles o matrículas en un curso: se recalcula su lista de docentes.
     *
     * @param \core\event\base $event
     */
    public static function teachers_changed(\core\event\base $event): void {
        $courseid = (int) $event->courseid;
        if ($courseid && $courseid != SITEID) {
            \cache::make('theme_active', 'courseteachers')->delete($courseid);
        }
    }

    /**
     * Usuario editado (nombre, foto, correo) o borrado: puede ser docente de varios cursos, se vacía la caché.
     *
     * @param \core\event\base $event
     */
    public static function user_changed(\core\event\base $event): void {
        \cache::make('theme_active', 'courseteachers')->purge();
    }
}
