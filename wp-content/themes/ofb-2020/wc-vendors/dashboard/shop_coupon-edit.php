<?php
/**
 * The template for displaying the shop coupon form
 *
 * Override this template by copying it to yourtheme/wc-vendors/dashboard/
 *
 * @package    WCVendors_Pro
 * @version    1.5.9
 *
 * /**
 *   DO NOT EDIT ANY OF THE LINES BELOW UNLESS YOU KNOW WHAT YOU'RE DOING
 */

$title  = ( is_numeric( $object_id ) ) ? __( 'Save Changes', 'wcvendors-pro' ) : __( 'Add Coupon', 'wcvendors-pro' );
$coupon = ( is_numeric( $object_id ) ) ? get_post( $object_id ) : null;

// Get basic information for the coupon
$coupon_title       = ( is_object( $coupon ) ) ? $coupon->post_title : '';
$coupon_description = ( is_object( $coupon ) ) ? $coupon->post_excerpt : '';
$coupon_meta        = array();

if ( $object_id ) {
	$coupon_meta = get_post_meta( $object_id );
}


/**
 *  Ok, You can edit the template below but be careful!
 */
?>

<h2><?php echo $title; ?></h2>

<!-- Product Edit Form -->
<form method="post" action="" id="wcv-shop_coupon-edit" class="wcv-form wcv-formvalidator">

	<!-- Coupon Code -->
	<?php WCVendors_Pro_Coupon_Form::coupon_code( $coupon_title ); ?>
	<!-- Coupon description -->
	<?php WCVendors_Pro_Coupon_Form::coupon_description( $coupon_description ); ?>

	<?php do_action( 'wcv_coupon_editor_after_coupon_description', $coupon ); ?>

	<div class="wcv-tabs top" data-prevent-url-change="true">
		<ul class="tabs-nav">
			<?php do_action( 'wcv_coupon_editor_tabs_start', $coupon ); ?>
			<li><a class="tabs-tab" href="#general"><?php _e( 'General', 'wcvendors-pro' ); ?></a></li>
			<li><a class="tabs-tab" href="#usage"><?php _e( 'Usage Restrictions', 'wcvendors-pro' ); ?></a></li>
			<li><a class="tabs-tab" href="#limits"><?php _e( 'Usage Limits', 'wcvendors-pro' ); ?></a></li>
			<?php do_action( 'wcv_coupon_editor_tabs_end', $coupon ); ?>
		</ul>

		<?php do_action( 'wcv_coupon_editor_before_general_content', $coupon ); ?>
		<div class="wcv-coupon-general tabs-content" id="general">
			<?php do_action( 'wcv_coupon_editor_general_start', $coupon ); ?>
			<!-- Discount Type -->
			<?php WCVendors_Pro_Coupon_Form::discount_type( ( array_key_exists( 'discount_type', $coupon_meta ) ) ? $coupon_meta['discount_type'] : '' ); ?>
			<!-- Apply to all products -->
			<?php WCVendors_Pro_Coupon_Form::apply_to_all_products( ( array_key_exists( 'apply_to_all_products', $coupon_meta ) ) ? $coupon_meta['apply_to_all_products'] : '' ); ?>
			<!-- Coupon Amount  -->
			<?php WCVendors_Pro_Coupon_Form::coupon_amount(
					( array_key_exists( 'coupon_amount', $coupon_meta ) ) ? $coupon_meta['coupon_amount'] : '',
					 ( array_key_exists( 'discount_type', $coupon_meta ) ) ? $coupon_meta['discount_type'] : 'fixed_product'
			); ?>
			<!-- Allow Free Shipping -->
			<?php WCVendors_Pro_Coupon_Form::free_shipping( ( array_key_exists( 'vendor_free_shipping', $coupon_meta ) ) ? $coupon_meta['vendor_free_shipping'] : '' ); ?>
			<!-- Coupon Expiry -->
			<?php WCVendors_Pro_Coupon_Form::expiry_date( ( array_key_exists( 'expiry_date', $coupon_meta ) ) ? $coupon_meta['expiry_date'] : '' ); ?>

			<?php do_action( 'wcv_coupon_editor_general_end', $coupon ); ?>
		</div>

		<?php do_action( 'wcv_coupon_editor_before_usage_content', $coupon ); ?>
		<div class="wcv-coupon-usage-restrictions tabs-content" id="usage">
			<?php do_action( 'wcv_coupon_editor_usage_end', $coupon ); ?>
			<!-- Min spend -->
			<?php WCVendors_Pro_Coupon_Form::minimum_amount( ( array_key_exists( 'minimum_amount', $coupon_meta ) ) ? $coupon_meta['minimum_amount'] : '' ); ?>
			<!-- Max spend  -->
			<?php WCVendors_Pro_Coupon_Form::maximum_amount( ( array_key_exists( 'maximum_amount', $coupon_meta ) ) ? $coupon_meta['maximum_amount'] : '' ); ?>
			<!-- individual use -->
			<?php WCVendors_Pro_Coupon_Form::individual_use( ( array_key_exists( 'individual_use', $coupon_meta ) ) ? $coupon_meta['individual_use'] : '' ); ?>
			<!-- exclude sale -->
			<?php WCVendors_Pro_Coupon_Form::exclude_sale_items( ( array_key_exists( 'exclude_sale_items', $coupon_meta ) ) ? $coupon_meta['exclude_sale_items'] : '' ); ?>
			<!-- Products  -->
			<?php WCVendors_Pro_Coupon_Form::products( ( array_key_exists( 'product_ids', $coupon_meta ) ) ? $coupon_meta['product_ids'] : '' ); ?>
			<!-- exclude Products -->
			<?php WCVendors_Pro_Coupon_Form::exclude_products( ( array_key_exists( 'exclude_product_ids', $coupon_meta ) ) ? $coupon_meta['exclude_product_ids'] : '' ); ?>
			<!-- Email restrictions -->
			<?php WCVendors_Pro_Coupon_Form::email_addresses( ( array_key_exists( 'email_addresses', $coupon_meta ) ) ? $coupon_meta['email_addresses'] : '' ); ?>
			<?php do_action( 'wcv_coupon_editor_usage_end', $coupon ); ?>
		</div>

		<?php do_action( 'wcv_coupon_editor_before_limits_content', $coupon ); ?>
		<div class="wcv-coupon-usage-limits tabs-content" id="limits">
			<?php do_action( 'wcv_coupon_editor_limits_start', $coupon ); ?>
			<!-- Usage limit per coupon -->
			<?php WCVendors_Pro_Coupon_Form::usage_limit( ( array_key_exists( 'usage_limit', $coupon_meta ) ) ? $coupon_meta['usage_limit'] : '' ); ?>
			<!-- Limit usage to X items -->
			<?php WCVendors_Pro_Coupon_Form::limit_usage_to_x_items( ( array_key_exists( 'usage_limit_per_user', $coupon_meta ) ) ? $coupon_meta['usage_limit_per_user'] : '' ); ?>

			<!-- Usage limit per user -->
			<?php WCVendors_Pro_Coupon_Form::usage_limit_per_user( ( array_key_exists( 'usage_limit_per_user', $coupon_meta ) ) ? $coupon_meta['usage_limit_per_user'] : '' ); ?>

			<?php do_action( 'wcv_coupon_editor_limits_end', $coupon ); ?>
		</div>
		<?php do_action( 'wcv_coupon_editor_after_limits_content', $coupon ); ?>


		<?php do_action( 'wcv_coupon_editor_form_end', $coupon ); ?>
		<hr/>
		<br/>


		<br/>

		<!-- Form data -->
		<?php WCVendors_Pro_Coupon_Form::form_data( $title, $object_id ); ?>

</form>
