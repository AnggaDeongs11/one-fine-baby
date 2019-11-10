function showsharegiftregistryform() {
    jQuery('#share_via_email_form').show();

    jQuery('html, body').animate({
        scrollTop: jQuery("#share-email").offset().top
    }, 2000);
    jQuery('#recipient').focus();
    jQuery('.giftregistry-share li:last-child').attr('style', 'display:block !important;width:100% !important;list-style-type: none');
    jQuery('.giftregistry-share li:last-child textarea').attr('style', 'width:70% !important');
    jQuery('.giftregistry-share li:last-child input').attr('style', 'width:70% !important');
}

/*copy text into clipboard*/
jQuery('#icon-copy').click(function () {
    var $temp = jQuery("<input>");
    jQuery("body").append($temp);
    $temp.val(jQuery('#link-text').text()).select();
    document.execCommand("copy");
    jQuery('#icon-copy').attr('title','Copied');
    $temp.remove();
})