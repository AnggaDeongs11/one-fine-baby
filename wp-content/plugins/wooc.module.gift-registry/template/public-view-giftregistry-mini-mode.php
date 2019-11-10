<?php
if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

wc_print_notices();

if (!$id)
    return;

$wishlist = Magenest_Giftregistry_Model::get_wishlist($id);

$registrantname = $wishlist->registrant_firstname . ' ' . $wishlist->registrant_lastname;
$coregistrantname = $wishlist->coregistrant_firstname . ' ' . $wishlist->coregistrant_lastname;

$registry_name = $registrantname;

if ($coregistrantname != ' ')
    $registry_name .= __(' and', GIFTREGISTRY_TEXT_DOMAIN) . " " . $coregistrantname;


$items = Magenest_Giftregistry_Model::get_items_in_giftregistry($id);
?>
<script type="text/javascript">
    function giftit(obj) {
        var qty_ele = jQuery(obj).attr('name');
        console.log(qty_ele);
        var qty = jQuery('#qty' + qty_ele).val();
        console.log('qty ');
        console.log(qty);

        var message_ele = jQuery(obj).attr('name');
        var message = jQuery('#message' + message_ele).val();

        var submit_link = jQuery(obj).attr('data-buy') + '&quantity=' + qty + '&message=' + message;
        console.log(submit_link);

        var base_url = window.location.origin;
        console.log('origin');
        console.log(base_url);

        var url_string = window.location.pathname;
        var url = base_url + url_string;
        console.log(url);

        var link = url + submit_link;
        console.log('links');
        console.log(link);

        jQuery.ajax(
            {
                method: 'post',
                url: link,
            }
        ).done(function () {
            window.location.href = url + '/cart/';
        });
    }
</script>
<h2><?php echo $registry_name ?></h2>
<hr>
<?php
if ($wishlist->image != '') :
    ?>
    <img class="wishlist-image" src="<?php echo $wishlist->image ?>"/>
<?php endif; ?>

<!-- Event date -->
<?php
if ($wishlist->event_date_time != '') :
    ?>
    <p><strong><?php echo __('Event date') ?> </strong>:</p>
    <span><?php echo date('F j, Y', strtotime($wishlist->event_date_time)); ?></span>
<?php endif; ?>

<!-- Note -->
<?php
if ($wishlist->message != '') :
    ?>
    <p><strong><?php echo __('Message') ?> </strong>:</p>
    <span><?php echo str_replace('\\', '', $wishlist->message) ?></span>
<?php endif; ?>

<table class="shop_table cart" cellspacing="0">
    <thead>
    <tr>
        <th class="product-thumbnail"><?=__('Image', GIFTREGISTRY_TEXT_DOMAIN); ?></th>
        <th class="product-name"><?=__('Product', GIFTREGISTRY_TEXT_DOMAIN); ?></th>
        <th class="product-price"><?=__('Price', GIFTREGISTRY_TEXT_DOMAIN); ?></th>
        <th class="product-quantity"><?=__('Desired Quantity', GIFTREGISTRY_TEXT_DOMAIN); ?></th>
        <th class="product-priority"><?=__('Priority', GIFTREGISTRY_TEXT_DOMAIN); ?></th>
        <th class="product-quantity"><?=__('Quantity', GIFTREGISTRY_TEXT_DOMAIN); ?></th>
        <th class="product-message"><?=__('Message', GIFTREGISTRY_TEXT_DOMAIN); ?></th>
        <th class="product-buy">&nbsp;</th>
    </tr>
    </thead>
    <tbody>
    <?php
    if (!empty ($items)) {
        foreach ($items as $item) {
            $_product = wc_get_product($item ['product_id']);

            $request = unserialize($item['info_request']);

            $request_st = Magenest_Giftregistry_Model::show_info_request($item, $id);


            ?>
            <tr>
                <td class="product-thumbnail" style="width: 100%">
                    <?php
                    $thumbnail = $_product->get_image();
                    printf('<a href="%s">%s</a>', $_product->get_permalink(), $thumbnail);
                    ?>
                </td>
                <td class="product-name">
                    <?php
                    echo sprintf('<a href="%s">%s</a>', $_product->get_permalink(), $_product->get_title())
                    ?>
                </td>
                <td class="product-price">
                    <?php
                    echo $_product->get_price_html();
                    ?>
                </td>
                <td style="width: 30px;" class="product-quantity">
                    <?php if (isset($item['quantity'])) {
                        $receive_qty = 0;
                        if (isset($item['received_qty']))
                            $receive_qty = $item['received_qty'];
                        $remain_qty = $item['quantity'] - $receive_qty;//received_qty
                        if ($remain_qty < 0)
                            $remain_qty = 0;
                        echo $remain_qty;
                    }
                    ?>
                </td>
                <td class="product-price">
                    <?php
                    $priority = $item['priority'];
                    if ($priority == 1) {
                        echo __('High', GIFTREGISTRY_TEXT_DOMAIN);
                    } elseif ($priority == 2) {
                        echo __('Medium', GIFTREGISTRY_TEXT_DOMAIN);
                    } else {
                        echo __('Low', GIFTREGISTRY_TEXT_DOMAIN);
                    }
                    ?>
                </td>
                <td>
                    <input style="width: 40px" type="text" id="qty<?php echo $item['id'] ?>"/>
                </td>
                <td class="product-message">
                    <textarea cols="10" style="width: 100px;" id="message<?php echo $item['id'] ?>"></textarea>
                </td>
                <td>
                    <button data-buy="<?php echo $request_st ?>"
                            name="<?php echo $item['id'] ?>"
                            class="single_add_to_cart_button button alt"
                            onclick="giftit(this)"
                    >
                        <?php echo __('Buy',GIFTREGISTRY_TEXT_DOMAIN) ?>
                    </button>
                </td>


            </tr>
        <?php }
    } ?>
    </tbody>
</table>