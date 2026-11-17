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
 * Cache definitions for theme_active.
 *
 * @package     theme_active
 * @category    cache
 * @copyright   2026 Jhon Rangel <jrangelardila@gmail.com>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$definitions = [
    // Docentes con matrícula activa de cada curso (campos de nombre, foto y correo), por curso.
    // La invalida el observador con los cambios de roles, matrículas y usuarios; caduca a los 10 minutos por las fechas.
    'courseteachers' => [
        'mode' => cache_store::MODE_APPLICATION,
        'simplekeys' => true,
        'simpledata' => false,
        'staticacceleration' => true,
        'staticaccelerationsize' => 30,
        'ttl' => 600,
    ],
    // URL de la imagen de cada curso ('' si no tiene), por curso. La invalida el observador al editar el curso.
    'courseimage' => [
        'mode' => cache_store::MODE_APPLICATION,
        'simplekeys' => true,
        'simpledata' => true,
        'staticacceleration' => true,
        'staticaccelerationsize' => 100,
        'ttl' => 86400,
    ],
    // Avance del usuario en un curso (porcentaje, completadas, total), por usuario y curso.
    // La clave incluye el cacherev del curso; los cambios de avance los invalida el observador.
    'courseprogress' => [
        'mode' => cache_store::MODE_APPLICATION,
        'simplekeys' => true,
        'simpledata' => false,
        'staticacceleration' => true,
        'staticaccelerationsize' => 50,
        'ttl' => 900,
    ],
];
