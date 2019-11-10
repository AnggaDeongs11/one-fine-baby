jQuery(document).ready(function () {
    var $ = jQuery;
    if ($('#insert-my-media').length > 0) {
        if (typeof wp !== 'undefined' && wp.media && wp.media.editor) {
            $('#insert-my-media').click(function (e) {
                // var current_link = "";
                e.preventDefault();
                var button = $(this);
                wp.media.editor.send.attachment = function (props, attachment) {
                    if(checkURL(attachment.url)){
                        $('#giftregistry_share_image_url').val(attachment.url);
                        $('#giftregistry_share_image_url').trigger('change_image');
                    } else{
                        alert('Invalid image format!');
                    }
                };
                wp.media.string.image = function(props, attachment){
                    // get image url from Insert from URL
                    props = wp.media.string.props( props, attachment );
                    var image_url = ! _.isUndefined( attachment ) ? attachment.url : props.url;
                    if(checkURL(image_url)){
                        $('#giftregistry_share_image_url').val( image_url);
                        $('#giftregistry_share_image_url').trigger('change_image');
                    } else{
                        alert('Invalid image format!');
                    }
                };

                wp.media.editor.open(button);

                return false;
            });

        }
    }
    function checkURL(url) {
        return(url.match(/\.(jpeg|jpg|gif|png|jfif|bat|exif|bmp)$/) != null);
    }

    $('#giftregistry_share_image_url').on('change_image', function (event) {
        var img = $('<img>').attr({
            'src': $(this).val(),
            'width': '30%',
        });
        $('#social_image').html(img);
    });

    $('input[class=giftregistry_notify]').change(function () {
        if ($('#giftregistry_notify_owner').is(':checked')) {
            $('.owner').attr('style', 'display:visibale');
        }
        else {
            $('.owner').attr('style', 'display:none');
        }
        if ($('#giftregistry_notify_registrant').is(':checked')) {
            $('.registrant').attr('style', 'display:visibale');
        }
        else {
            $('.registrant').attr('style', 'display:none');
        }
        if ($('#giftregistry_notify_coregistrant').is(':checked')) {
            $('.coregistrant').attr('style', 'display:visibale');
        }
        else {
            $('.coregistrant').attr('style', 'display:none');
        }
        if ($('#giftregistry_notify_admin').is(':checked')) {
            $('.admin').attr('style', 'display:visibale');
        }
        else {
            $('.admin').attr('style', 'display:none');
        }
    });
});