<?php
/**
 * Single Product Meta
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/meta.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @package 	WooCommerce/Templates
 * @version     3.0.0
 */
if (!defined('ABSPATH')) {
    exit;
}

global $product;
?>
<div class="product_meta">

    <?php do_action('woocommerce_product_meta_start'); ?>
    <?php // var_dump($product->get_attributes()); ?>

    <?php if (wc_product_sku_enabled() && ( $product->get_sku() || $product->is_type('variable') )) : ?>

        <span class="sku_wrapper"><?php esc_html_e('SKU:', 'woocommerce'); ?> <span class="sku"><?php echo ( $sku = $product->get_sku() ) ? $sku : esc_html__('N/A', 'woocommerce'); ?></span></span>

    <?php endif; ?>

    <?php echo wc_get_product_category_list($product->get_id(), ', ', '<span class="posted_in">' . _n('Category:', 'Categories:', count($product->get_category_ids()), 'woocommerce') . ' ', '</span>'); ?>

    <?php echo wc_get_product_tag_list($product->get_id(), ', ', '<span class="tagged_as">' . _n('Tag:', 'Tags:', count($product->get_tag_ids()), 'woocommerce') . ' ', '</span>'); ?>

    <?php do_action('woocommerce_product_meta_end'); ?>

</div>

<div class="wpb_wrapper">
    <div class="single-accordion">
        <?php 
        $product_info = get_post_meta( get_the_ID(), 'st_custom_product_information', true );
        $shipping_from = get_post_meta( get_the_ID(), 'st_custom_shipping_form', true );
        $delivery = get_post_meta( get_the_ID(), 'st_custom_delivery_and_return', true );
        if ($product_info !== ''): ?>
            <div class="accordion">
                <label for="tm" class="accordionitem"><h4>Product Information<i class="fas fa-chevron-down"></i></h4></label>
                <input type="checkbox" id="tm"/>
                <p class="hiddentext"><?php  echo $product_info;?></p>
            </div>
        <?php endif; ?>
        <?php if ($shipping_from !== ''): ?>
            <div class="accordion">
                <label for="tn" class="accordionitem"><h4>Shipping From<i class="fas fa-chevron-down"></i></h4></label>
                <input type="checkbox" id="tn"/>
                <p class="hiddentext"><?php  echo $shipping_from;?></p>
            </div>
        <?php endif; ?>
        <?php if ($delivery !== ''): ?>
            <div class="accordion">
                <label for="to" class="accordionitem"><h4>Delivery &amp; Returns<i class="fas fa-chevron-down"></i></h4></label>
                <input type="checkbox" id="to"/>
                <p class="hiddentext"><?php  echo $delivery;?></p>
            </div>
        <?php endif; ?>
    </div>
    <!--    <div class="vc_toggle vc_toggle_default vc_toggle_color_default  vc_toggle_size_md">
            <div class="vc_toggle_title"><h4>Product Information</h4><i class="vc_toggle_icon"></i></div>
            <div class="vc_toggle_content" style="display: none;"><?php the_field('product_information'); ?></div>
        </div>
        <div class="vc_toggle vc_toggle_default vc_toggle_color_default  vc_toggle_size_md">
            <div class="vc_toggle_title"><h4>Shipping From</h4><i class="vc_toggle_icon"></i></div
            ><div class="vc_toggle_content" style="display: none;"><?php the_field('shipping_from'); ?></div>
        </div>
        <div class="vc_toggle vc_toggle_default vc_toggle_color_default  vc_toggle_size_md">
            <div class="vc_toggle_title"><h4>Delivery &amp; Returns</h4><i class="vc_toggle_icon"></i></div>
            <div class="vc_toggle_content" style="display: none;"><?php the_field('shipping_from'); ?></div>
        </div>-->
</div>