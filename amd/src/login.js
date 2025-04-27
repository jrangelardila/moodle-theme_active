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
 * @module     theme_active/login
 * @copyright  2024 jrangelardila@gmail.com
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


define(['jquery'], function ($) {
    return {
        init: function () {
            let pass = $('#password');

            // Created the structure for the button
            let wrapper = $('<div class="input-group mb-3"></div>');
            let toggleBtn = $(`
                <button type="button" class="btn btn-outline-secondary password-toggle">
                    <i class="fas fa-eye-slash"></i>
                </button>
            `);

            // Envolve the button
            pass.wrap(wrapper).addClass('form-control');
            pass.after(toggleBtn);

            // Evento for change visbilitiy and input
            toggleBtn.on('click', function () {
                let isPassword = $('#password').attr("type") === "password";
                if (isPassword) {
                    $("#password").attr({"type": "text"});
                } else {
                    $("#password").attr({"type": "password"});
                }
                $(this).find('i').toggleClass('fa-eye fa-eye-slash');
            });
        }
    };
});


