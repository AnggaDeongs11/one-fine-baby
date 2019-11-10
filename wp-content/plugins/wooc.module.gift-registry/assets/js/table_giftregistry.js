function giftit(obj) {
    var qty_ele = jQuery(obj).attr('name');
    var qty = jQuery('#qty' + qty_ele).val();

    var desired_qty_ele = jQuery(obj).attr('name');
    var desired_qty = jQuery('#desired_qty' + desired_qty_ele).val();

    var message_ele = jQuery(obj).attr('name');
    var message = jQuery('#message' + message_ele).val();

    var submit_link = jQuery(obj).attr('data-buy') + '&quantity=' + qty + '&message=' + message;

    var buy_for_giftregistry_id = parseInt(window.location.search.slice(window.location.search.search('=') + 1));

    var base_url = window.location.origin;

    var url_string = window.location.pathname;
    var url = base_url + url_string;

    var link = url + submit_link;

    jQuery.ajax(
        {
            method: 'post',
            url: link,
        }
    ).done(function () {
        window.location.href = url + 'giftregistry/?giftregistry_id=' + buy_for_giftregistry_id + '&buy=true';
    });
}

jQuery(document).ready(function ($) {
   $('#btn_filter').on('click',function () {
       $('#loader').show();
       $('body').addClass('overlay');
      $.ajax(
          {
              type : 'post',
              dataType: 'html',
              url: send_ajax_giftregistry.ajax_url,
              data: {
                  filter: $('#filter-giftregistry').val(),
                  level: $('#level_filter').val(),
                  action: 'filter_giftregistry',
                  id : parseInt(window.location.search.slice(window.location.search.search('=') + 1))
              },
              success: function (data) {
                  $('#loader').hide();
                  $('body').removeClass('overlay');
                  $('#table').html(data);
              }
          }
      )
   });
});