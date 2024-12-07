define(['jquery'], function($) {
    return {
        init: function() {
            $('.delete-face-image').click(function(e) {
                e.preventDefault();
                const imageId = $(this).data('image-id');
                if (confirm(M.util.get_string('confirmdeleteimage', 'auth_faceauth'))) {
                    $.ajax({
                        url: 'delete_image.php',
                        type: 'POST',
                        data: { imageId: imageId },
                        success: function(response) {
                            if (response.success) {
                                alert(M.util.get_string('imagedeletedsuccessfully', 'auth_faceauth'));
                                location.reload();
                            } else {
                                alert(M.util.get_string('failedtodeleteimage', 'auth_faceauth') + ': ' + response.error);
                            }
                        },
                        error: function() {
                            alert(M.util.get_string('erroroccurredwhiledeletingimage', 'auth_faceauth'));
                        }
                    });
                }
            });
        }
    };
});