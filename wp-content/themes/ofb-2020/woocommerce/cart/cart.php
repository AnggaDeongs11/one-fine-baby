<?php
/**
 * Cart Page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.5.0
 */
defined('ABSPATH') || exit;

do_action('woocommerce_before_cart');
?>
<?php
$st_w = TInvWL_Public_Wishlist_View::instance();
$wishlist = $st_w->get_current_wishlist();
if (!$st_w->wishlist_products_helper) {
    $wlp = null;
    if (isset($wishlist['ID']) && 0 === $wishlist['ID']) {
        $wlp = TInvWL_Product_Local::instance();
    } else {
        $wlp = new TInvWL_Product($wishlist);
    }
    $st_w->wishlist_products_helper = $wlp;
} else {
    $wlp = $st_w->wishlist_products_helper;
}
$product_data = array(
    'external' => true,
    'order_by' => 'date',
    'order' => 'DESC',
);

$wishlist_items = $wlp->get_wishlist($product_data);
?>

<form class="woocommerce-cart-form padded-row st-customized-cart" action="<?php echo esc_url(wc_get_cart_url()); ?>" method="post">
    <?php do_action('woocommerce_before_cart_table'); ?>

    <table class="shop_table shop_table_responsive cart woocommerce-cart-form__contents" cellspacing="0">
        <tbody>
            <?php do_action('woocommerce_before_cart_contents'); ?>

            <?php
            foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
                $_product = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
                $product_id = apply_filters('woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key);
                $product_meta = get_post_meta($product_id);
                $variation_id = $cart_item['variation_id'];
                $brand = get_the_terms($_product->id, 'pwb-brand');

                $exists_in_wishlist = false;
                foreach ($wishlist_items as $choosen) {
                    if ($product_id === $choosen['product_id'] && $variation_id === $choosen['variation_id']) {
                        $exists_in_wishlist = $choosen;
                        break;
                    }
                }

                if ($_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters('woocommerce_cart_item_visible', true, $cart_item, $cart_item_key)) {
                    $product_permalink = apply_filters('woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink($cart_item) : '', $cart_item, $cart_item_key);
                    ?>
                    <tr class="woocommerce-cart-form__cart-item <?php echo esc_attr(apply_filters('woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key)); ?>">
                        <td class="product-thumbnail">
                            <?php
                            $thumbnail = apply_filters('woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key);

                            if (!$product_permalink) {
                                echo $thumbnail; // PHPCS: XSS ok.
                            } else {
                                printf('<a href="%s">%s</a>', esc_url($product_permalink), $thumbnail); // PHPCS: XSS ok.
                            }
                            ?>
                        </td>

                        <td class="product-name" data-title="<?php esc_attr_e('Product', 'woocommerce'); ?>">
                            <?php if (!empty($brand[0])): ?>
                                <a class="product-vendor" href="<?php echo get_term_link($brand[0]->term_id, 'pwb-brand');?>">
                                    <?php echo wp_kses_post($brand[0]->name); ?>
                                </a>
                                <?php
                            endif;
                            ?>
                            <div class="product-title">
                                <?php
                                if (!$product_permalink) {
                                    echo wp_kses_post(apply_filters('woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key) . '&nbsp;');
                                } else {
                                    echo wp_kses_post(apply_filters('woocommerce_cart_item_name', sprintf('<a href="%s">%s</a>', esc_url($product_permalink), $_product->get_name()), $cart_item, $cart_item_key));
                                }
                                ?>
                            </div>
                            <?php if ($product_meta['st_custom_shipping_form'][0]): ?>
                                <div class="shipping">
                                    <small>Ships from: <span><?php echo wp_kses_post($brand[0]->name); ?></span></small>

                                </div>
                            <?php endif; ?>
                            <?php
                            do_action('woocommerce_after_cart_item_name', $cart_item, $cart_item_key);

                            // Meta data.
//						echo wc_get_formatted_cart_item_data( $cart_item ); // PHPCS: XSS ok.
                            // Backorder notification.
                            if ($_product->backorders_require_notification() && $_product->is_on_backorder($cart_item['quantity'])) {
                                echo wp_kses_post(apply_filters('woocommerce_cart_item_backorder_notification', '<p class="backorder_notification">' . esc_html__('Available on backorder', 'woocommerce') . '</p>', $product_id));
                            }
                            ?>
                            <div class="variations">
                                <?php
                                if ($_product->is_sold_individually()) {
                                    $product_quantity = sprintf('1 <input type="hidden" class="st-quantity" name="cart[%s][qty]" value="1" />', $cart_item_key);
                                } else {
                                    $product_quantity = woocommerce_quantity_input(array(
                                        'input_name' => "cart[{$cart_item_key}][qty]",
                                        'input_value' => $cart_item['quantity'],
                                        'max_value' => $_product->get_max_purchase_quantity(),
                                        'min_value' => '1',
                                        'classes' => ['st-quantity'],
                                        'product_name' => $_product->get_name(),
                                            ), $_product, false);
                                }

                                echo apply_filters('woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item); // PHPCS: XSS ok.
                                ?>
                                <!--                                <div class="st-size variation">
                                <?php
                                // Get all Product data
                                // $pdt = new WC_Product_Variable($product_id);
                                //$variations = $pdt->get_available_variations();
//                                $pt = wc_get_product($product_id);
//                                $attributes = $pt->get_attributes();
//                                    $attributes = [];
//                                    foreach ($variations as $attrs) {
//                                        foreach ($attrs['attributes'] as $attr_name => $attr_value) {
//                                            $attr_key = str_replace('attribute_', '', $attr_name);
//                                            if (!in_array($attr_value, $attributes[$attr_key]))
//                                                $attributes[$attr_key][] = $attr_value;
//                                        }
//                                    }
//                                    foreach ($attributes as $attribute_name => $options) :
//                                        if ($attribute_name === 'pa_size') {
//                                            wc_dropdown_variation_attribute_options(array(
//                                                'options' => $options,
//                                                'attribute' => $attribute_name,
//                                                'product' => $pdt,
//                                            ));
//                                        }
//                                    endforeach;get
                                ?>                       
                                                                </div>-->
                            </div>
                            <div class="cart-action">
                                <div class="action wishlist">
                                    <?php if ($exists_in_wishlist !== null && !empty($exists_in_wishlist)): ?>
                                        <span class='ti-add-to-wishlist' style="display:none;">
                                            <?php echo do_shortcode('[ti_wishlists_addtowishlist product_id="' . $_product->id . '" variation_id="' . $cart_item['variation_id'] . '"]'); ?>
                                        </span>
                                        <span class='ti-remove-from-wishlist' >
                                            <a href="#" class="ti-remove-wishlist-btn tinvwl-icon-heart tinvwl-product-in-list" data-wishlist-id="<?php echo $exists_in_wishlist['ID']; ?>?>" > Remove from wishlist</a>
                                        </span>
                                    <?php else: ?>
                                        <span class='ti-add-to-wishlist' >
                                            <?php echo do_shortcode('[ti_wishlists_addtowishlist product_id="' . $_product->id . '" variation_id="' . $cart_item['variation_id'] . '"]'); ?>
                                            <!--tinvwl_add_to_wishlist_button tinvwl-icon-heart  tinvwl-position-after inited-add-wishlist tinvwl-product-in-list-->
                                        </span>
                                        <span class='ti-remove-from-wishlist' style="display:none;">
                                            <a href="#" class="ti-remove-wishlist-btn tinvwl-product-in-list tinvwl-icon-heart" data-wishlist-id="<?php echo $exists_in_wishlist['ID']; ?>" > Remove from wishlist</a>
                                        </span>
                                    <?php endif; ?>

                                </div>
                                <div class="action remove">
                                    <?php
                                    // @codingStandardsIgnoreLine
                                    echo apply_filters('woocommerce_cart_item_remove_link', sprintf(
                                                    '<a href="%s" class="st-cart-item-remove" aria-label="%s" data-product_id="%s" data-product_sku="%s" data-cart-item-key="%s"><i class="fa fa-trash-o"></i>%s</a>', esc_url(wc_get_cart_remove_url($cart_item_key)), __('Remove this item', 'woocommerce'), esc_attr($product_id), esc_attr($_product->get_sku()), $cart_item_key, __('Remove', 'woocommerce')
                                            ), $cart_item_key);
                                    ?>
                                </div>
                            </div>
                        </td>

                        <td class="product-price" data-title="<?php esc_attr_e('Price', 'woocommerce'); ?>">
                            <?php
//                             apply_filters('woocommerce_cart_item_price', WC()->cart->get_product_price($_product), $cart_item, $cart_item_key); // PHPCS: XSS ok.                                
                            ?>
                            <?php
                            if ($_product->is_type('simple')) {
                                if ($_product->is_on_sale()) {
                                    echo '<span class="woocommerce-Price-amount amount sales-price">' . wc_price($_product->get_sale_price()) . '</span>&nbsp;&nbsp';
                                    echo '<span class="woocommerce-Price-amount amount" style="margin-right: 5px;"><strike>' . get_woocommerce_currency_symbol() . number_format($_product->get_regular_price(), 2, '.', ',') . '</span></strike>';
                                } else {

                                    echo '<span class="woocommerce-Price-amount amount">' . wc_price($_product->get_regular_price()) . '</span>';
                                }
                            } elseif ($_product->is_type('variable')) {
                                if ($_product->get_variation_sale_price()) {
                                    echo '<span class="woocommerce-Price-amount amount sales-price">' . wc_price($_product->get_variation_sale_price('min', true)) . '</span>&nbsp;&nbsp';
                                    echo '<span class="woocommerce-Price-amount amount" style="margin-right: 5px;"><strike>' . get_woocommerce_currency_symbol() . number_format($_product->get_variation_regular_price('max', true), 2, '.', ',') . '</strike></span>';
                                } else {

                                    echo '<span class="woocommerce-Price-amount amount">' . wc_price($_product->get_variation_regular_price('max', true)) . '</span>';
                                }
                            } else {

                                if ($_product->is_on_sale()) {
                                    echo '<span class="woocommerce-Price-amount amount sales-price">' . wc_price($_product->get_sale_price()) . '</span>&nbsp;&nbsp';
                                    echo '<span class="woocommerce-Price-amount amount" style="margin-right: 5px;"><strike>' . get_woocommerce_currency_symbol() . number_format($_product->get_regular_price(), 2, '.', ',') . '</strike></span>';
                                } else {

                                    echo '<span class="woocommerce-Price-amount amount">' . wc_price($_product->get_regular_price()) . '</span>';
                                }
                            };
                            ?>
                        </td>                        
                    </tr>
                    <tr class="space"><td colspan="3"></td></tr>
                    <?php
                }
            }
            ?>

            <?php do_action('woocommerce_cart_contents'); ?>

            <tr>
                <td colspan="6" class="actions">

                    <?php if (wc_coupons_enabled()) { ?>
                        <div class="coupon">
                            <label for="coupon_code"><?php esc_html_e('Coupon:', 'woocommerce'); ?></label> <input type="text" name="coupon_code" class="input-text" id="coupon_code" value="" placeholder="<?php esc_attr_e('Coupon code', 'woocommerce'); ?>" /> <button type="submit" class="button" name="apply_coupon" value="<?php esc_attr_e('Apply coupon', 'woocommerce'); ?>"><?php esc_attr_e('Apply coupon', 'woocommerce'); ?></button>
                            <?php do_action('woocommerce_cart_coupon'); ?>
                        </div>
                    <?php } ?>

                    <button type="submit" class="button" name="update_cart" value="<?php esc_attr_e('Update cart', 'woocommerce'); ?>"><?php esc_html_e('Update cart', 'woocommerce'); ?></button>

                    <?php do_action('woocommerce_cart_actions'); ?>

                    <?php wp_nonce_field('woocommerce-cart', 'woocommerce-cart-nonce'); ?>
                </td>
            </tr>

            <?php do_action('woocommerce_after_cart_contents'); ?>
        </tbody>
    </table>
    <?php do_action('woocommerce_after_cart_table'); ?>
</form>

<div class="cart-collaterals padded-row">
    <?php
    /**
     * Cart collaterals hook.
     *
     * @hooked woocommerce_cross_sell_display
     * @hooked woocommerce_cart_totals - 10
     */
    do_action('woocommerce_cart_collaterals');
    ?>
</div>

<?php do_action('woocommerce_after_cart'); ?>
