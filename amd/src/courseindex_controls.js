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
 * Course index controls: expand/collapse all + auto-scroll to active item.
 *
 * @module     theme_active/courseindex_controls
 * @copyright  2026 Jhon Rangel <jrangelardila@gmail.com>
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

import $ from 'jquery';

export const init = () => {

    const expandBtn = document.querySelector('[data-action="expandallcourseindexsections"]');
    const collapseBtn = document.querySelector('[data-action="collapseallcourseindexsections"]');

    if (expandBtn) {
        expandBtn.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            $('#tal-courseindex .collapse').collapse('show');
            $('#tal-courseindex .tal-ci-sec-head').removeClass('collapsed')
                .attr('aria-expanded', 'true');
        });
    }

    if (collapseBtn) {
        collapseBtn.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            $('#tal-courseindex .collapse').collapse('hide');
            $('#tal-courseindex .tal-ci-sec-head').addClass('collapsed')
                .attr('aria-expanded', 'false');
        });
    }

    setTimeout(() => {
        const active = document.querySelector('.tal-ci-item.is-active');
        if (active) {
            active.scrollIntoView({behavior: 'smooth', block: 'center'});
        }
    }, 400);
};
