<?php
/**
 * Single Product Price
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/price.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.0.0
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

global $product;
?>
<!--<div class="main-product-info">
        <p class="product-price <?php echo esc_attr(apply_filters('woocommerce_product_price_class', 'price')); ?>"><?php echo $product->get_price_html(); ?></p>
        <h1 class="vendor-name"><?php the_field('vendor'); ?></h1>
        <h2 class="product-name"><?php the_title(); ?></h1>
</div>-->

<div class="main-product-info"> 
    <p class="product-price <?php echo esc_attr(apply_filters('woocommerce_product_price_class', 'price')); ?>">
        <?php
        if ($product->is_type('simple')) {
            if ($product->is_on_sale()) {
                echo '<b><span class="woocommerce-Price-amount amount" style="margin-right: 5px;"><strike><small>' . get_woocommerce_currency_symbol() . $product->get_regular_price() . '</span></small></strike>&nbsp;|&nbsp;&nbsp;&nbsp;';
                echo '<span class="woocommerce-Price-amount amount">' . wc_price($product->get_sale_price()) . '</span></b>';
            } else {

                echo '<b><span class="woocommerce-Price-amount amount">' . wc_price($product->get_regular_price()) . '</span></b>';
            }
        } elseif ($product->is_type('variable')) {
            if ($product->get_variation_sale_price()) {
                echo '<b><span class="woocommerce-Price-amount amount" style="margin-right: 5px;"><strike><small>' . get_woocommerce_currency_symbol() . $product->get_variation_regular_price('max', true) . '</span></small></strike>&nbsp;|&nbsp;&nbsp;&nbsp;';
                echo '<span class="woocommerce-Price-amount amount">' . wc_price($product->get_variation_sale_price('min', true)) . '</span></b>';
            } else {

                echo '<b><span class="woocommerce-Price-amount amount">' . wc_price($product->get_variation_regular_price('max', true)) . '</span></b>';
            }
        } else {

            if ($product->is_on_sale()) {
                echo '<b><span class="woocommerce-Price-amount amount" style="margin-right: 5px;"><strike><small>' . get_woocommerce_currency_symbol() . $product->get_regular_price() . '</span></small></strike>&nbsp;|&nbsp;&nbsp;&nbsp;';
                echo '<span class="woocommerce-Price-amount amount">' . wc_price($product->get_sale_price()) . '</span></b>';
            } else {

                echo '<b><span class="woocommerce-Price-amount amount">' . wc_price($product->get_regular_price()) . '</span></b>';
            }
        };
        ?>
    </p>
    <?php $brands = get_the_terms($post->ID, 'pwb-brand'); ?>
    <?php if ($brands == null): ?>
    <h1 class="vendor-name"><?php the_title(); ?></h1>
    <?php else: ?>
        <h1 class="vendor-name"><a href="<?php echo get_term_link($brands[0]->term_id, 'pwb-brand');?>"><?php echo $brands[0]->name; ?></a></h1>
        <h2 class="product-name"><?php the_title(); ?></h2>
    <?php endif; ?>
</div>