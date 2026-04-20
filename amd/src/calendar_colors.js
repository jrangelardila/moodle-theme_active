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
 * Calendar date-based color coding for theme_active.
 *
 * Adds colored badges (red=past, orange=today, green=future) to calendar
 * events in all views: month grid, day detail, and upcoming list.
 *
 * @module     theme_active/calendar_colors
 * @copyright  2024 Jhon Rangel <jrangelardila@gmail.com>
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

define(['core/str'], function(Str) {
    'use strict';

    var STRINGS = {};

    /**
     * Get the start of today (midnight) and end of today as Unix timestamps.
     * @return {Object} {todayStart, todayEnd} in seconds.
     */
    function getTodayBounds() {
        var now = new Date();
        var todayStart = new Date(now.getFullYear(), now.getMonth(), now.getDate());
        var todayEnd = new Date(now.getFullYear(), now.getMonth(), now.getDate() + 1);
        return {
            todayStart: Math.floor(todayStart.getTime() / 1000),
            todayEnd: Math.floor(todayEnd.getTime() / 1000)
        };
    }

    /**
     * Determine the status of a timestamp relative to today.
     * @param {Number} timestamp Unix timestamp in seconds.
     * @param {Object} bounds {todayStart, todayEnd}.
     * @return {String} 'past', 'today', or 'future'.
     */
    function getDateStatus(timestamp, bounds) {
        if (timestamp < bounds.todayStart) {
            return 'past';
        }
        if (timestamp < bounds.todayEnd) {
            return 'today';
        }
        return 'future';
    }

    /**
     * Create a badge element for the given status.
     * @param {String} status 'past', 'today', or 'future'.
     * @return {HTMLElement}
     */
    function createBadge(status) {
        var badge = document.createElement('span');
        badge.className = 'ta-cal-badge ta-cal-badge--' + status;
        badge.textContent = STRINGS[status] || status;
        badge.setAttribute('data-ta-cal-status', status);
        return badge;
    }

    /**
     * Remove all previously added badges inside a container.
     * @param {HTMLElement} container
     */
    function clearBadges(container) {
        var old = container.querySelectorAll('[data-ta-cal-status]');
        old.forEach(function(el) {
            el.remove();
        });
    }

    /**
     * Colorize events inside the month grid view.
     */
    function colorizeMonthView() {
        var bounds = getTodayBounds();
        var days = document.querySelectorAll('td.day[data-day-timestamp]');

        days.forEach(function(td) {
            var dayTimestamp = parseInt(td.getAttribute('data-day-timestamp'), 10);
            if (isNaN(dayTimestamp)) {
                return;
            }
            var status = getDateStatus(dayTimestamp, bounds);

            // Add status class to the day cell.
            td.classList.remove('ta-cal-day--past', 'ta-cal-day--today', 'ta-cal-day--future');
            td.classList.add('ta-cal-day--' + status);

            // Add badge to each event in this day cell.
            var events = td.querySelectorAll('[data-region="event-item"]');
            events.forEach(function(ev) {
                clearBadges(ev);
                var link = ev.querySelector('.eventname');
                if (link) {
                    link.parentNode.insertBefore(createBadge(status), link);
                }
            });
        });
    }

    /**
     * Colorize event cards (day view, upcoming view).
     */
    function colorizeEventCards() {
        var bounds = getTodayBounds();
        var events = document.querySelectorAll('.event[data-timestart]');

        events.forEach(function(ev) {
            var timestart = parseInt(ev.getAttribute('data-timestart'), 10);
            if (isNaN(timestart)) {
                return;
            }
            var duration = parseInt(ev.getAttribute('data-timeduration'), 10) || 0;
            // Use the event end time for status: if event ends today, it's still "today".
            var eventEnd = timestart + duration;
            var status = getDateStatus(eventEnd, bounds);

            // Add status class to the event card.
            ev.classList.remove('ta-cal-event--past', 'ta-cal-event--today', 'ta-cal-event--future');
            ev.classList.add('ta-cal-event--' + status);

            // Add badge next to the event name.
            var badge = ev.querySelector('[data-region="event-date-badge"]');
            if (badge) {
                badge.className = 'ta-cal-badge ta-cal-badge--' + status;
                badge.textContent = STRINGS[status] || status;
                badge.setAttribute('data-ta-cal-status', status);
            }
        });
    }

    /**
     * Run all colorization.
     */
    function colorizeAll() {
        colorizeMonthView();
        colorizeEventCards();
    }

    return {
        /**
         * Initialize calendar color coding.
         */
        init: function() {
            // Load translated strings.
            Str.get_strings([
                {key: 'calendar_past', component: 'theme_active'},
                {key: 'calendar_today', component: 'theme_active'},
                {key: 'calendar_future', component: 'theme_active'}
            ]).then(function(strings) {
                STRINGS.past = strings[0];
                STRINGS.today = strings[1];
                STRINGS.future = strings[2];

                // Initial colorization.
                colorizeAll();

                // Observe DOM changes for AJAX calendar navigation.
                var calendarContainers = document.querySelectorAll('.calendarwrapper, #region-main, [data-region="calendar"]');
                var observing = false;
                var observer = new MutationObserver(function() {
                    if (observing) {
                        return;
                    }
                    observing = true;
                    observer.disconnect();
                    colorizeAll();
                    calendarContainers.forEach(function(container) {
                        observer.observe(container, {childList: true, subtree: true});
                    });
                    observing = false;
                });
                calendarContainers.forEach(function(container) {
                    observer.observe(container, {childList: true, subtree: true});
                });

                // Also listen for calendar navigation events.
                document.addEventListener('calendar-events-loaded', colorizeAll);

                return;
            }).catch(function() {
                // Fallback without translations.
                STRINGS.past = 'Vencido';
                STRINGS.today = 'Hoy';
                STRINGS.future = 'Pendiente';
                colorizeAll();
            });
        }
    };
});
