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


