function addit(obj) {
    $ = jQuery;
    var product_id = jQuery(obj).attr('name');

    var url = jQuery(obj).attr('url');

    var data_giftregistry = {};
    data_giftregistry['add-registry'] = 1;
    data_giftregistry['add-to-giftregistry'] = product_id;
    data_giftregistry['quantity'] = 1;
    data_giftregistry['giftregistry_variation_id'] = 0;
    $('#add_loader_' + product_id).show();
    $('#add_product_' + product_id).attr('style','width:78%');
    $('#add_product_' + product_id).ajaxStart(function () {
        $('#img_add_' + product_id).attr('style', 'display:inline-block');
        $(this).attr('style', 'color:#eeeeee !important;background-color:#eeeeee !important;');
    });

    /* remove add button => must reset page*/
    $('#add_product_' + product_id).ajaxComplete(function () {
        $(this).remove();
        $("#set-giftregistry-detail-" + product_id).attr('style', 'display:block');
        $('#add_loader_' + product_id).hide();
    });

    jQuery.ajax({
        type: "post",
        url: url,
        data: {
            action: 'add_giftregifttry_from_shop_page',
            data_giftregistry: data_giftregistry,
        }
    })
}

function removeit(obj) {
    $ = jQuery;
    $('#loader').show();
    $ = jQuery;
    var product_id = jQuery(obj).attr('name');

    var url = jQuery(obj).attr('url');

    var data_giftregistry = {};
    data_giftregistry['product-id-remove'] = product_id;

    $('#remove_product_' + product_id).ajaxStart(function () {
        $('#img_remove_' + product_id).attr('style', 'display:inline-block');
    });

    jQuery.ajax({
        type: "post",
        url: url,
        data: {
            action: 'remove_giftregifttry_form_product_detail',
            data_giftregistry: data_giftregistry,
        },
        success:function () {
            $('#loader').hide();
            $('body').removeClass('overlay');
            location.reload();
        }
    })
}

function priorityit(obj) {
    $ = jQuery;
    var src = jQuery(obj).attr('src');
    var product_id = jQuery(obj).attr('name');
    var url = jQuery(obj).attr('url');
    var GIFTREGISTRY_URL = src.slice(0, src.search("assets"));

    var data_giftregistry = {};
    if (src.search('low-priority') > 0) {
        $("#priority_" + product_id).attr('src', GIFTREGISTRY_URL + 'assets/img/high-priority.png');
        data_giftregistry['priority'] = 1;
    }
    else if (src.search('high-priority') > 0) {
        $("#priority_" + product_id).attr('src', GIFTREGISTRY_URL + 'assets/img/low-priority.png');
        data_giftregistry['priority'] = 3;
    }

    data_giftregistry['product-id-set-priority'] = product_id;

    jQuery.ajax({
        type: "post",
        url: url,
        data: {
            action: 'set_priority_giftregifttry',
            data_giftregistry: data_giftregistry,
        }
    })
}