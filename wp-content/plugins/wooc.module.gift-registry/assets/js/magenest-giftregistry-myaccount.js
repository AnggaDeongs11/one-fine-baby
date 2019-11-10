function removeItProduct(obj) {
    $ = jQuery;
    var product_id = jQuery(obj).attr('name');

    var url = jQuery(obj).attr('url');

    var data_giftregistry = {};
    data_giftregistry['product-id-remove'] = product_id;

    jQuery.ajax({
        type: "post",
        url: url,
        data: {
            action: 'remove_giftregifttry_form_product_detail',
            data_giftregistry: data_giftregistry,
        },
        success: function () {
            location.reload();
        }
    })
}

function removeItProductVariable(obj) {
    $ = jQuery;
    var variation_id = $('.variation_id').val();
    var product_id = jQuery(obj).attr('name');

    var url = jQuery(obj).attr('url');

    var data_giftregistry = {};
    data_giftregistry['product-id-remove'] = variation_id;

    jQuery.ajax({
        type: "post",
        url: url,
        data: {
            action: 'remove_giftregifttry_form_product_detail',
            data_giftregistry: data_giftregistry,
        },
        success: function () {
            location.reload();
        }
    })
}

function priorityItProduct(obj) {
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

function priorityItProductVariable(obj) {
    $ = jQuery;
    var variation_id = $('.variation_id').val();
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

    data_giftregistry['product-id-set-priority'] = variation_id;

    jQuery.ajax({
        type: "post",
        url: url,
        data: {
            action: 'set_priority_giftregifttry',
            data_giftregistry: data_giftregistry,
        }
    })
}