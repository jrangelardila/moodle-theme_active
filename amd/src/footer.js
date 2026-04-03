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
 * Footer animations for theme_active.
 *
 * Handles scroll-reveal entrance animations for footer elements.
 *
 * @module     theme_active/footer
 * @copyright  2024 Jhon Rangel <jrangelardila@gmail.com>
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

define([], function() {
    'use strict';

    /**
     * Reveal elements with [data-taf-reveal] when they scroll into view.
     * Uses IntersectionObserver with a staggered delay for each element.
     */
    function initScrollReveal() {
        var items = document.querySelectorAll('[data-taf-reveal]');
        if (!items.length) {
            return;
        }

        var delay = 120;

        var observer = new IntersectionObserver(function(entries) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting) {
                    var el = entry.target;
                    var idx = parseInt(el.getAttribute('data-taf-idx') || '0', 10);
                    setTimeout(function() {
                        el.classList.add('taf-in');
                    }, idx * delay);
                    observer.unobserve(el);
                }
            });
        }, {threshold: 0.1});

        items.forEach(function(el, i) {
            el.setAttribute('data-taf-idx', i);
            observer.observe(el);
        });
    }

    return {
        /**
         * Initialise footer animations.
         */
        init: function() {
            initScrollReveal();
        }
    };
});
