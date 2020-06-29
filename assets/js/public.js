(function( $ ) {
    'use strict';

    $(function() {

        function getBaseUrl() {
            let pathArray = location.href.split('/');
            let protocol = pathArray[0];
            let host = pathArray[2];
            let url = protocol + '//' + host + '/wp-json/';

            return url;
        }

        $('.bookmark-button').on('click', function(event){
            event.preventDefault();
            
            let holder = $(this);
            let data = {
                post_id: $(this).attr('id')
            };

            $.ajax({
                method: "PUT",
                url: getBaseUrl() + 'log-favorite-posts/v1/favorite-posts',
                data: data
            })
            .done(function( msg ) {
                if (msg.status == 'unmarked') {
                    holder.text('BOOKMARK THIS POST');
                } else {
                    holder.text('UNMARK THIS POST AS FAVORITE');
                }
            });
        });

    });

})( jQuery );