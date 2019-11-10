jQuery(document).ready(function () {
    var $ = jQuery;
    if ($('#set-background-image').length > 0) {
        if (typeof wp !== 'undefined' && wp.media && wp.media.editor) {
            $('#set-background-image').click(function (e) {
                e.preventDefault();
                var button = $(this);
                wp.media.editor.send.attachment = function (props, attachment) {
                    if(checkURL(attachment.url)){
                        $('#background-image').val(attachment.url);
                        $('#background-image').trigger('change_image');
                    } else{
                        alert('Invalid image format!');
                    }
                };
                wp.media.string.image = function (props, attachment) {
                    // get image url from Insert from URL
                    props = wp.media.string.props(props, attachment);
                    var image_url = !_.isUndefined(attachment) ? attachment.url : props.url;
                    if(checkURL(attachment.url)){
                        $('#background-image').val(image_url);
                        $('#background-image').trigger('change_image');
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

    if ($('#remove-background-image').length > 0) {
        $('#remove-background-image').click(function (e) {
            $('#show-background-image').remove();
            $('#background-image').val('');
            $('.display-if-remove-image').attr('style','display:block');
            $('.hide-if-remove-image').attr('style','display:none');
        });
    }
    $('#background-image').on('change_image', function (event) {
        var img = $('<img>').attr({
            'src': $(this).val(),
            'width': '100%',
        });
        $('#set-background-image').html(img);
        $('.hide-if-remove-image').attr('style','display:block');
    });
});