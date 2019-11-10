jQuery(document).ready(function ($) {
    // console.log('sadsd');
    // var variation_id = jQuery('.variation_id').val();
    // if(variation_id){
    //     jQuery('#giftregistry_variation_id').attr('style','display: inline-block;');
    //     jQuery('#giftregistry_variation_id').attr('value',variation_id);
    // }
    jQuery('.qty').on('input',function () {
        var qty = jQuery(this).val();
        jQuery('#add-registry-qty').attr('value',qty);
    });

    if(typeof product_variable !== 'undefined'){
        var url = product_variable.url;
    }

    jQuery('.variation_id').change(function () {
        var variation_id = jQuery(this).val();
        jQuery('#giftregistry_variation_id').attr('style','display: inline-block;');
        jQuery('#giftregistry_variation_id').val(variation_id);
        for(var i = 0;i < product_variable.array_product_id.length;i ++){
            if(product_variable.array_product_id[i] == variation_id){
                var product_id = product_variable.array_product_variation;
                $('.button-add-giftregistry').attr('style','display:none !important');
                $('.giftregistry-detail').attr('style','display:table-cell !important');
                if (product_variable.array_product_priority[i] == '1') {
                    $("#priority_" + product_id).attr('src', url + 'assets/img/high-priority.png');
                }
                else {
                    $("#priority_" + product_id).attr('src', url + 'assets/img/low-priority.png');
                }
                break;
            }else {
                $('.button-add-giftregistry').attr('style','display:table-cell !important');
                $('.giftregistry-detail').attr('style','display:none !important');
            }
        }
    });

    jQuery('#add-to-giftregistry-list').click(function () {
        $('#loader').show();
        $('body').addClass('overlay');
        var data_giftregistry = {};
        data_giftregistry['add-registry'] = 1;
        data_giftregistry['add-to-giftregistry'] = jQuery('#add-to-giftregistry').val();
        data_giftregistry['quantity'] = jQuery('#add-registry-qty').val();
        data_giftregistry['giftregistry_variation_id'] = jQuery('#giftregistry_variation_id').val();
        // var data = {
        //
        // };
        jQuery.ajax({
            type: "post",
            url: send_ajax_giftregistry.ajax_url,
            data: {
                action: 'add_giftregifttry_to_list',
                data_giftregistry: data_giftregistry,
            },
            success: function(){
                if(send_ajax_giftregistry.is_user_logged_in == 1){
                    location.reload();
                }
                else{
                    window.location.href = send_ajax_giftregistry.myaccount_url + '?request_login=true';
                }
            }
        })
    });
});