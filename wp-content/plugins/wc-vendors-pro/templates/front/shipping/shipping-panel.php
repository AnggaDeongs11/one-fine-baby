<?php
/**
 * The Template for displaying the product shipping details
 *
 * Override this template by copying it to yourtheme/wc-vendors/front/shipping
 *
 * @package    WCVendors_Pro
 * @version    1.6.3
 */
?>

<h3><?php _e( 'Shipping costs', 'wcvendors-pro' ); ?></h3>

<?php if ( ! empty( $shipping_flat_rates ) ) : ?>

	<?php if ( ! empty( $shipping_flat_rates['national'] ) || ! empty( $shipping_flat_rates['international'] ) || ( array_key_exists( 'national_free', $shipping_flat_rates ) && $shipping_flat_rates['national_free'] == 'yes' ) || ( array_key_exists( 'international_free', $shipping_flat_rates ) && $shipping_flat_rates['international_free'] == 'yes' ) ) : ?>

		<table>

			<?php if ( $shipping_flat_rates['national_disable'] !== 'yes' ) : ?>
				<?php if ( strlen( $shipping_flat_rates['national'] ) >= 0 || strlen( $shipping_flat_rates['national_free'] ) >= 0 ) : ?>
					<?php $free = ( array_key_exists( 'national_free', $shipping_flat_rates ) && $shipping_flat_rates['national_free'] == 'yes' ) ? true : false; ?>
					<?php $price = $free ? __( 'Free', 'wcvendors-pro' ) : wc_price( $shipping_flat_rates['national'] . $product->get_price_suffix() ); ?>
					<tr>
						<td width="60%">
							<strong><?php _e( 'Within ', 'wcvendors-pro' ); ?><?php echo $countries[ strtoupper( $store_country ) ]; ?></strong>
						</td>
						<td width="40%"><?php echo $price; ?></td>
					</tr>
				<?php endif; ?>
			<?php endif; ?>

			<?php if ( $shipping_flat_rates['international_disable'] !== 'yes' ) : ?>
				<?php if ( strlen( $shipping_flat_rates['international'] ) > 0 || strlen( $shipping_flat_rates['international_free'] ) > 0 ) : ?>
					<?php $free = ( array_key_exists( 'international_free', $shipping_flat_rates ) && $shipping_flat_rates['international_free'] == 'yes' ) ? true : false; ?>
					<?php $price = $free ? __( 'Free', 'wcvendors-pro' ) : wc_price( $shipping_flat_rates['international'] . $product->get_price_suffix() ); ?>
					<tr>
						<td width="60%">
							<strong><?php _e( 'Outside ', 'wcvendors-pro' ); ?><?php echo $countries[ strtoupper( $store_country ) ]; ?></strong>
						</td>
						<td width="40%"><?php echo $price; ?></td>
					</tr>
				<?php endif; ?>
			<?php endif; ?>
		</table>

	<?php else : ?>

		<h5><?php _e( 'No shipping rates are available for this product.', 'wcvendors-pro' ); ?></h5>

	<?php endif; ?>

<?php else : ?>

	<?php if ( ! empty( $shipping_table_rates ) ) : ?>

		<table>

			<thead>
			<tr>
				<th><?php _e( 'Country', 'wcvendors-pro' ); ?></th>
				<th><?php _e( 'State', 'wcvendors-pro' ); ?></th>
				<th><?php _e( 'Cost', 'wcvendors-pro' ); ?></th>
			</tr>
			</thead>
			<?php foreach ( $shipping_table_rates as $rate ) : ?>

				<tr>
					<td width="40%"><?php echo ( $rate['country'] != '' ) ? $countries[ strtoupper( $rate['country'] ) ] : __( 'Any', 'wcvendors-pro' ); ?></td>
					<td width="40%"><?php echo ( $rate['state'] != '' ) ? WC()->countries->get_states( $rate['country'] )[$rate['state']] : __( 'Any', 'wcvendors-pro' ); ?></td>
					<td width="20%"><?php echo wc_price( $rate['fee'] . $product->get_price_suffix() ); ?></td>
				</tr>
			<?php endforeach; ?>

		</table>

	<?php else : ?>

		<?php if ( ! empty( $shipping_flat_rates ) ) : ?>

			<table>
				<tr>
					<td width="60%">
						<strong><?php _e( 'Within ', 'wcvendors-pro' ); ?><?php echo $countries[ strtoupper( $store_country ) ]; ?></strong>
					</td>
					<td width="40%"><?php echo wc_price( $shipping_flat_rates['national'] . $product->get_price_suffix() ); ?></td>
				</tr>
				<tr>
					<td width="60%">
						<strong><?php _e( 'Outside ', 'wcvendors-pro' ); ?><?php echo $countries[ strtoupper( $store_country ) ]; ?></strong>
					</td>
					<td width="40%"><?php echo wc_price( $shipping_flat_rates['international'] . $product->get_price_suffix() ); ?></td>
				</tr>
			</table>

		<?php else : ?>

			<h5><?php _e( 'No shipping rates are available for this product.', 'wcvendors-pro' ); ?></h5>

		<?php endif; ?>

	<?php endif; ?>

<?php endif; ?>


<h3><?php _e( 'Shipping Details', 'wcvendors-pro' ); ?></h3>

<table>
	<tr>
		<td><strong> <?php _e( 'Shipping from ', 'wcvendors-pro' ); ?></strong></td>
		<td><?php echo $countries[ strtoupper( $store_country ) ]; ?></td>
	</tr>
	<?php if ( $max_charge ) : ?>
		<tr>
			<td><strong> <?php _e( 'Maximum shipping charge per order ', 'wcvendors-pro' ); ?></strong></td>
			<td><?php echo $max_charge; ?></td>
		</tr>
	<?php endif; ?>
	<?php if ( $min_charge ) : ?>
		<tr>
			<td><strong> <?php _e( 'Minimum shipping charge per order ', 'wcvendors-pro' ); ?></strong></td>
			<td><?php echo $min_charge; ?></td>
		</tr>
	<?php endif; ?>
	<?php if ( $free_shipping_order ) : ?>
		<tr>
			<td><strong> <?php _e( 'Free shipping for order spend over ', 'wcvendors-pro' ); ?></strong></td>
			<td><?php echo $free_shipping_order; ?></td>
		</tr>
	<?php endif; ?>
	<?php if ( $free_shipping_product ) : ?>
		<tr>
			<td>
				<strong> <?php _e( 'Free shipping if the spend for the product is over this amount', 'wcvendors-pro' ); ?></strong>
			</td>
			<td><?php echo $free_shipping_product; ?></td>
		</tr>
	<?php endif; ?>
	<?php if ( $max_charge_product ) : ?>
		<tr>
			<td>
				<strong> <?php _e( 'The maximum shipping charged for this product no matter the quantity: ', 'wcvendors-pro' ); ?></strong>
			</td>
			<td><?php echo $max_charge_product; ?></td>
		</tr>
	<?php endif; ?>
	<?php if ( $product_handling_fee ) : ?>
		<tr>
			<td><strong> <?php _e( 'Product handling fee: ', 'wcvendors-pro' ); ?></strong></td>
			<td><?php echo $product_handling_fee; ?></td>
		</tr>
	<?php endif; ?>
</table>
