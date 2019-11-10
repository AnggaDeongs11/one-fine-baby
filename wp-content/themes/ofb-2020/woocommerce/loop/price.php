<?php
/**
 * Loop Price
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/price.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

global $product;
?>
<!--<div class="main-product-info"> 
    <h1 class="vendor-name"><?php the_field('vendor'); ?></h1>
    <h2 class="product-name"><?php the_title(); ?></h2>
</div>-->
<?php // if ($price_html = $product->get_price_html()) : ?>
<!--<span class="price"><?php echo $price_html; ?></span>-->
<?php // endif; ?>
<?php
if ($product->is_type('simple')) {
    if ($product->is_on_sale()) {
        echo '<div class="price-l"><b><span class="woocommerce-Price-amount amount" style="margin-right: 5px;"><strike><small>' . get_woocommerce_currency_symbol() . $product->get_regular_price() . '</span></small></strike>&nbsp;|&nbsp;&nbsp;&nbsp;';
        echo '<span class="woocommerce-Price-amount amount">Now ' . wc_price($product->get_sale_price()) . '</span></b></div>';
    } else {

        echo '<div class="price-l"><b><span class="woocommerce-Price-amount amount">' . wc_price($product->get_regular_price()) . '</span></b></div>';
    }
} elseif ($product->is_type('variable')) {
    if ($product->get_variation_sale_price()) {
        echo '<div class="price-l"><b><span class="woocommerce-Price-amount amount" style="margin-right: 5px;"><strike><small>' . get_woocommerce_currency_symbol() . $product->get_variation_regular_price('max', true) . '</span></small></strike>&nbsp;|&nbsp;&nbsp;&nbsp;';
        echo '<span class="woocommerce-Price-amount amount">Now ' . wc_price($product->get_variation_sale_price('min', true)) . '</span></b></div>';
    } else {

        echo '<div class="price-l"><b><span class="woocommerce-Price-amount amount">' . wc_price($product->get_variation_regular_price('max', true)) . '</span></b></div>';
    }
} else {
    if ($product->is_on_sale()) {
        echo '<div class="price-l"><b><span class="woocommerce-Price-amount amount" style="margin-right: 5px;"><strike><small>' . get_woocommerce_currency_symbol() . $product->get_regular_price() . '</span></small></strike>&nbsp;|&nbsp;&nbsp;&nbsp;';
        echo '<span class="woocommerce-Price-amount amount">Now ' . wc_price($product->get_sale_price()) . '</span></b></div>';
    } else {

        echo '<div class="price-l"><b><span class="woocommerce-Price-amount amount">' . wc_price($product->get_regular_price()) . '</span></b></div>';
    }
};
?>