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
 * Enhancements to Bootstrap components for accessibility.
 *
 * @module     theme_active/footer_btn_floating
 * @copyright  2024 jrangelardila@gmail.com
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

define([], function() {
    return {
        init: function() {
                var btnInfo = document.getElementById('footer-btn-info');
                var window = document.getElementById('window_footer');

                btnInfo.addEventListener('click', function(event) {
                    event.stopPropagation();
                    this.toggleVentanaEmergente(window);
                }.bind(this));

                document.addEventListener('click', function(event) {
                    if (!window.contains(event.target) && event.target !== btnInfo) {
                        window.style.display = 'none';
                    }
                });
        },
        toggleVentanaEmergente: function(window) {
            if (window.style.display === 'none' || window.style.display === '') {
                window.style.display = 'block';
            } else {
                window.style.display = 'none';
            }
        }
    };
});
