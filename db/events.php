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

defined('MOODLE_INTERNAL') || die();

$observers = [
    // Course image and teachers: when a course is updated, restored or deleted.
    ['eventname' => '\core\event\course_updated', 'callback' => '\theme_active\event_observer::course_changed'],
    ['eventname' => '\core\event\course_restored', 'callback' => '\theme_active\event_observer::course_changed'],
    ['eventname' => '\core\event\course_deleted', 'callback' => '\theme_active\event_observer::course_changed'],
    // Course teachers: role and enrolment changes.
    ['eventname' => '\core\event\role_assigned', 'callback' => '\theme_active\event_observer::teachers_changed'],
    ['eventname' => '\core\event\role_unassigned', 'callback' => '\theme_active\event_observer::teachers_changed'],
    ['eventname' => '\core\event\user_enrolment_created', 'callback' => '\theme_active\event_observer::teachers_changed'],
    ['eventname' => '\core\event\user_enrolment_updated', 'callback' => '\theme_active\event_observer::teachers_changed'],
    ['eventname' => '\core\event\user_enrolment_deleted', 'callback' => '\theme_active\event_observer::teachers_changed'],
    ['eventname' => '\core\event\enrol_instance_updated', 'callback' => '\theme_active\event_observer::teachers_changed'],
    ['eventname' => '\core\event\enrol_instance_deleted', 'callback' => '\theme_active\event_observer::teachers_changed'],
    // User name or picture: a user may teach many courses, so the whole teacher cache is purged.
    ['eventname' => '\core\event\user_updated', 'callback' => '\theme_active\event_observer::user_changed'],
    ['eventname' => '\core\event\user_deleted', 'callback' => '\theme_active\event_observer::user_changed'],
    [
        'eventname' => '\core\event\course_module_completion_updated',
        'callback'  => '\theme_active\event_observer::completion_updated',
    ],
];
