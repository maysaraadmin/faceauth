define(['jquery'], function($) {
    return {
        init: function() {
            $('a.lightbox').on('click', function(e) {
                e.preventDefault();
                const imageUrl = $(this).attr('href');
                const lightbox = $('<div id="lightbox"><img src="' + imageUrl + '"></div>');
                $('body').append(lightbox);

                lightbox.on('click', function(event) {
                    if (event.target.id === 'lightbox') {
                        lightbox.remove();
                    }
                });
            });
        }
    };
});