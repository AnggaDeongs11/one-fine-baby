<?php
/**
 * Checkout Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-checkout.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.5.0
 */
if (!defined('ABSPATH')) {
    exit;
}

do_action('woocommerce_before_checkout_form', $checkout);

// If checkout registration is disabled and not logged in, the user cannot checkout.
if (!$checkout->is_registration_enabled() && $checkout->is_registration_required() && !is_user_logged_in()) {
    echo esc_html(apply_filters('woocommerce_checkout_must_be_logged_in_message', __('You must be logged in to checkout.', 'woocommerce')));
    return;
}
?>

<form name="checkout" method="post" class="checkout woocommerce-checkout st-container" action="<?php echo esc_url(wc_get_checkout_url()); ?>" enctype="multipart/form-data">

    <div class="col2-set" id="customer_details">
        <div class="col-1">
            <?php do_action('woocommerce_checkout_before_order_review_heading'); ?>

            <h3 id="order_review_heading"><?php esc_html_e('Your Cart', 'woocommerce'); ?></h3>

            <?php do_action('woocommerce_checkout_before_order_review'); ?>

            <div id="order_review" class="woocommerce-checkout-review-order">
                <?php do_action('woocommerce_checkout_order_review'); ?>
            </div>

            <?php do_action('woocommerce_checkout_after_order_review'); ?>
        </div>

        <?php if ($checkout->get_checkout_fields()) : ?>


            <div class="col-2">
                <div class="st-accordion active" id="checkout-address">
                    <label><h3><?php esc_html_e('Your Details', 'woocommerce'); ?></h3></label>
                    <div class="st-accordion-content">
                        <?php do_action('woocommerce_checkout_before_customer_details'); ?>
                        <?php do_action('woocommerce_checkout_billing'); ?>
                        <?php do_action('woocommerce_checkout_shipping'); ?>
                        <?php do_action('woocommerce_checkout_after_customer_details'); ?>
                        <div class="button-sec">
                                <button class="st-accordion-btn" id="address-submit" data-target="#checkout-shipping">Continue to Shipping</button>
                        </div>
                    </div>
                </div>
                <div class="st-accordion" id="checkout-shipping">
                    <label><h3><?php esc_html_e('Shipping', 'woocommerce'); ?></h3></label>
                    <div class="st-accordion-content" style="display: none;">
                        <?php if (WC()->cart->needs_shipping() && WC()->cart->show_shipping()) : ?>

                            <?php do_action('woocommerce_review_order_before_shipping'); ?>

                            <?php wc_cart_totals_shipping_html(); ?>


                        <?php endif; ?>
                         <div class="button-sec">
                                <button class="st-accordion-btn" id="address-submit" data-target="#checkout-payment">Continue to Payment</button>
                        </div>
                    </div>
                </div>
                <div class="st-accordion" id="checkout-payment">
                    <label><h3><?php esc_html_e('Payment', 'woocommerce'); ?></h3></label>
                    <div class="st-accordion-content" style="display: none;">
                            <?php do_action('woocommerce_review_order_after_shipping'); ?>
                    </div>
                </div>
            </div>
        </div>


    <?php endif; ?>

</form>

<?php do_action('woocommerce_after_checkout_form', $checkout); ?>
