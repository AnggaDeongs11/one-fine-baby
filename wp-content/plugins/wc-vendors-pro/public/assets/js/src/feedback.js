(function( $ ) {
    $( window ).load( function() {
        
        $.each( $('.star-rating-input'), function(i,e){
            var star_rating = $(this).val();
            var fn = $(this).data('fn');
            for( var i = 1; i <= star_rating; i++ ) {                
                $('#star-'+fn+'-'+ i).find('use').attr('xlink:href', $('#wcv-star-rating-'+ fn +'-label').data('star-closed') );
            }
        });

        $('.wcv-form').on('submit', function(event){
            var valid_stars = true;
            $.each( $('.star-rating-input'), function(i,e){
                if ( $(this).val() == '' ) {
                    valid_stars = false;
                    alert(wcv_frontend_feedback.select_stars_message);
                    event.preventDefault();
                    return false;
                }
            });

            return valid_stars;
        });

        $('.star-icon').on('click', function(e){
            e.preventDefault();
            var star_rating = $(this).data('index' );
            var fn = $(this).data('fn');
            $('#wcv-star-rating-' + fn + '-input').val( star_rating );

            $('#wcv-star-rating-'+ fn +'-label').find('use').attr('xlink:href', $('#wcv-star-rating-'+ fn +'-label').data('star-open') );

            for( var i = 1; i <= star_rating; i++ ) {                
                $('#star-'+fn+'-'+ i).find('use').attr('xlink:href', $('#wcv-star-rating-'+ fn +'-label').data('star-closed') );
            }
        });
    });
})( jQuery );