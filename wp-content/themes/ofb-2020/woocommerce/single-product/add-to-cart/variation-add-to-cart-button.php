<?php
/**
 * Single variation cart button
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.4.0
 */
defined('ABSPATH') || exit;

global $product;
?>
<div class="woocommerce-variation-add-to-cart variations_button">
    <?php do_action('woocommerce_before_add_to_cart_button'); ?>

    <button type="submit" class="single_add_to_cart_button button alt"><?php echo esc_html($product->single_add_to_cart_text()); ?></button>
    <div class='action wishlist'>    
        <span class='ti-add-to-wishlist'>
            <?php do_action('woocommerce_after_add_to_cart_button'); ?>
        </span>
        <span class='ti-remove-from-wishlist' style="display:none;">
            <a href="#" class="ti-remove-wishlist-btn tinvwl-product-in-list tinvwl-icon-heart" data-wishlist-id="0" > Remove from wishlist</a>
        </span>
    </div>

    <input type="hidden" name="add-to-cart" value="<?php echo absint($product->get_id()); ?>" />
    <input type="hidden" name="product_id" value="<?php echo absint($product->get_id()); ?>" />
    <input type="hidden" name="variation_id" class="variation_id" value="0" />
</div>
