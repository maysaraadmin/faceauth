define(['jquery'], function($) {
    return {
        init: function() {
            $('#face-enrollment-form').submit(function(e) {
                const fileInput = $('#face-image')[0];
                const file = fileInput.files[0];
                const allowedTypes = ['image/jpeg', 'image/png'];

                if (!file) {
                    alert(M.util.get_string('pleaseselectanimage', 'auth_faceauth'));
                    e.preventDefault();
                    return;
                }

                if (!allowedTypes.includes(file.type)) {
                    alert(M.util.get_string('invalidfiletype', 'auth_faceauth'));
                    e.preventDefault();
                    return;
                }

                if (file.size > 5000000) {
                    alert(M.util.get_string('filesizeexceedslimit', 'auth_faceauth'));
                    e.preventDefault();
                    return;
                }
            });
        }
    };
});