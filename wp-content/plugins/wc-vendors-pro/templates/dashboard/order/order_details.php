<?php
/**
 * The template for displaying the order details
 *
 * Override this template by copying it to yourtheme/wc-vendors/dashboard/order
 *
 * @package    WCVendors_Pro
 * @version    1.6.4
 */

$total_colspan = wc_tax_enabled() ? count( $order->get_taxes() ) : 1;
$label_colspan = wc_tax_enabled() ? 6 : 5;

?>

<div class="wcv-shade wcv-fade">

	<div id="order-details-modal-<?php echo $order_id; ?>" class="wcv-modal wcv-fade"
		 data-trigger="#open-order-details-modal-<?php echo $order_id; ?>" data-width="80%" data-height="90%"
		 data-reveal aria-labelledby="modalTitle-<?php echo $order_id; ?>" aria-hidden="true" role="dialog">

		<div class="modal-header">
			<button class="modal-close wcv-dismiss">
				<svg class="wcv-icon wcv-icon-sm">
					<use xlink:href="<?php echo WCV_PRO_PUBLIC_ASSETS_URL; ?>svg/wcv-icons.svg#wcv-icon-times"></use>
				</svg>
			</button>
			<h3 id="modal-title"><?php echo sprintf( __( 'Order #%d Details', 'wcvendors-pro' ), $order->get_order_number() ); ?>
				- <?php echo date_i18n( wc_date_format(), strtotime( $order_date ) ); ?></h3>
		</div>

		<div class="modal-body wcv-order-details" id="modalContent">

			<?php do_action( 'wcvendors_order_before_customer_detail' ); ?>

			<div class="wcv-order-customer-details wcv-cols-group wcv-horizontal-gutters">

				<div class="all-50">
					<h4><?php _e( 'Billing Details', 'wcvendors-pro' ); ?></h4>
					<?php
					// Display values
					echo '<div class="wcv-order-address">';

					if ( $order->get_formatted_billing_address() ) {
						echo '<p><strong>' . __( 'Address', 'wcvendors-pro' ) . ':</strong>' . wp_kses( $order->get_formatted_billing_address(), array( 'br' => array() ) ) . '</p>';
					} else {
						echo '<p class="none_set"><strong>' . __( 'Address', 'wcvendors-pro' ) . ':</strong> ' . __( 'No billing address set.', 'wcvendors-pro' ) . '</p>';
					}

					echo '</div>';
					?>
				</div>  <!-- // billing details  -->

				<div class="all-50">
					<h4><?php _e( 'Shipping Details', 'wcvendors-pro' ); ?></h4>
					<?php
					// Display values
					echo '<div class="wcv-order-address">';

					if ( $order->get_formatted_shipping_address() ) {
						echo '<p><strong>' . __( 'Address', 'wcvendors-pro' ) . ':</strong>' . wp_kses( $order->get_formatted_shipping_address(), array( 'br' => array() ) ) . '</p>';
					} else {
						echo '<p class="none_set"><strong>' . __( 'Address', 'wcvendors-pro' ) . ':</strong> ' . __( 'No shipping address set.', 'wcvendors-pro' ) . '</p>';
					}

					echo '</div>';
					?>
				</div> <!-- //shipping details  -->

			</div>

			<hr/>

			<?php do_action( 'wcvendors_order_before_items_detail' ); ?>

			<div class=" wcv-order-items-details wcv-cols-group wcv-horizontal-gutters">

				<div class="all-100">

					<h4><?php _e( 'Order Items', 'wcvendors-pro' ); ?></h4>

					<table cellpadding="0" cellspacing="0" class="wcv-table wcv-order-table">
						<thead>
						<tr>
							<th colspan="2"><?php _e( 'Item', 'wcvendors-pro' ); ?></th>
							<th><?php _e( 'Commission', 'wcvendors-pro' ); ?></th>
							<th><?php _e( 'Cost', 'wcvendors-pro' ); ?></th>
							<th><?php _e( 'Qty', 'wcvendors-pro' ); ?></th>
							<th><?php _e( 'Total', 'wcvendors-pro' ); ?></th>

							<?php
							if ( ! empty( $order_taxes ) ) :
								foreach ( $order_taxes as $tax_id => $tax_item ) :
									$tax_class      = wc_get_tax_class_by_tax_id( $tax_item['rate_id'] );
									$tax_class_name = isset( $classes_options[ $tax_class ] ) ? $classes_options[ $tax_class ] : __( 'Tax', 'wcvendors-pro' );
									$column_label   = ! empty( $tax_item['label'] ) ? $tax_item['label'] : __( 'Tax', 'wcvendors-pro' );
									?>
									<th class="line_tax tips" data-tip="
									<?php
									echo esc_attr( $tax_item['name'] . ' (' . $tax_class_name . ')' );
									?>
									">
										<?php echo esc_attr( $column_label ); ?>
										<input type="hidden" class="order-tax-id"
											   name="order_taxes[<?php echo $tax_id; ?>]"
											   value="<?php echo esc_attr( $tax_item['rate_id'] ); ?>">
										<a class="delete-order-tax" href="#" data-rate_id="<?php echo $tax_id; ?>"></a>
									</th>
									<?php
								endforeach;
							endif;
							?>
						</tr>
						</thead>

						<tbody id="order_line_items">
						<?php
						foreach ( $line_items as $item_id => $item ) {

							$product_id = ! empty( $item['variation_id'] ) ? $item['variation_id'] : $item['product_id'];
							// Check if this is a variation and get the parent id, this ensures that the correct vendor id is retrieved
							if ( get_post_type( $product_id ) === 'product_variation' ) {
								$product_id = get_post_field( 'post_parent', $product_id );
							}

							$_product          = $order->get_product_from_item( $item );
							$item_qty          = $item->get_quantity();
							$product_commision = ( $item_qty > 1 ) ? $_order->product_commissions[ $product_id ] / $item_qty : $_order->product_commissions[ $product_id ];

							?>
							<tr class="item-id-<?php echo $item->get_id(); ?>">
								<td class="wcv-order-thumb">
									<?php if ( $_product ) : ?>
										<?php echo $_product->get_image( 'shop_thumbnail', array( 'title' => '' ) ); ?>
									<?php else : ?>
										<?php echo wc_placeholder_img( 'shop_thumbnail' ); ?>
									<?php endif; ?>
								</td>
								<td class="name">

									<?php echo ( $_product && $_product->get_sku() ) ? esc_html( $_product->get_sku() ) . ' &ndash; ' : ''; ?>

									<?php echo esc_html( $item->get_name() ); ?>

									<div class="view">
										<?php

										do_action( 'woocommerce_order_item_meta_start', $item->get_id(), $item, $order );
										wc_display_item_meta( $item, [ 'echo' => false ] );
										do_action( 'woocommerce_order_item_meta_end', $item->get_id(), $item, $order );
										?>
									</div>
								</td>

								<td class="item_cost" width="1%">
									<div class="view">
										<?php echo wc_price( $product_commision, array( 'currency' => $order_currency ) ); ?>
									</div>
								</td>

								<td class="item_cost" width="1%">
									<div class="view">
										<?php
										if ( isset( $item['line_total'] ) ) {
											if ( isset( $item['line_subtotal'] ) && $item['line_subtotal'] != $item['line_total'] ) {
												echo '<del>' . wc_price( $order->get_item_subtotal( $item, false, true ), array( 'currency' => $order_currency ) ) . '</del> ';
											}
											echo wc_price( $order->get_item_total( $item, false, true ), array( 'currency' => $order_currency ) );
										}
										?>
									</div>
								</td>

								<td class="quantity" width="1%">
									<div class="view">
										<?php echo ( isset( $item['qty'] ) ) ? esc_html( $item['qty'] ) : ''; ?>
									</div>
								</td>

								<td class="line_cost" width="1%"
									data-sort-value="<?php echo esc_attr( isset( $item['line_total'] ) ? $item['line_total'] : '' ); ?>">
									<div class="view">
										<?php
										if ( isset( $item['line_total'] ) ) {
											if ( isset( $item['line_subtotal'] ) && $item['line_subtotal'] != $item['line_total'] ) {
												echo '<del>' . wc_price( $item['line_subtotal'], array( 'currency' => $order_currency ) ) . '</del> ';
											}
											echo wc_price( $item['line_total'], array( 'currency' => $order_currency ) );
										}
										?>
									</div>

								</td>

								<?php
								if ( wc_tax_enabled() ) :
									$line_tax_data = isset( $item['line_tax_data'] ) ? $item['line_tax_data'] : '';
									$tax_data      = maybe_unserialize( $line_tax_data );

									foreach ( $order_taxes as $tax_item ) :
										$tax_item_id       = $tax_item['rate_id'];
										$tax_item_total    = isset( $tax_data['total'][ $tax_item_id ] ) ? $tax_data['total'][ $tax_item_id ] : '';
										$tax_item_subtotal = isset( $tax_data['subtotal'][ $tax_item_id ] ) ? $tax_data['subtotal'][ $tax_item_id ] : '';

										?>
										<td class="line_tax" width="1%">
											<div class="view">
												<?php
												if ( '' != $tax_item_total ) {
													if ( isset( $tax_item_subtotal ) && $tax_item_subtotal != $tax_item_total ) {
														echo '<del>' . wc_price( wc_round_tax_total( $tax_item_subtotal ), array( 'currency' => $order_currency ) ) . '</del> ';
													}

													echo wc_price( wc_round_tax_total( $tax_item_total ), array( 'currency' => $order_currency ) );
												} else {
													echo '&ndash;';
												}
												?>
											</div>
										</td>
										<?php
									endforeach;
								endif;
								?>
							</tr>
						<?php } ?>
						</tbody>

						<tbody class="wcv-order-totals">
						<tr class="shipping">
							<td class="wcv-order-totals-label" colspan="<?php echo $label_colspan; ?>"><?php _e( 'Shipping', 'wcvendors-pro' ); ?>
								:
							</td>
							<td class="total" colspan="<?php echo $total_colspan; ?>"><?php echo wc_price( $_order->total_shipping, array( 'currency' => $order_currency ) ); ?></td>
						</tr>

						<?php if ( wc_tax_enabled() ) : ?>
							<?php foreach ( $order->get_tax_totals() as $code => $tax ) : ?>
								<tr>
									<td class="wcv-order-totals-label" colspan="<?php echo $label_colspan; ?>"><?php echo $tax->label; ?>:</td>
									<td class="total" colspan="<?php echo $total_colspan; ?>"><?php echo wc_price( $_order->total_tax, array( 'currency' => $order_currency ) ); ?></td>
								</tr>
							<?php endforeach; ?>
						<?php endif; ?>

						<tr>
							<td class="wcv-order-totals-label"
								colspan="<?php echo $label_colspan; ?>"><?php _e( 'Commission Total', 'wcvendors-pro' ); ?>:
							</td>
							<td class="total" colspan="<?php echo $total_colspan; ?>">
								<div class="view"><?php echo wc_price( $_order->commission_total, array( 'currency' => $order_currency ) ); ?></div>
							</td>
						</tr>
						<tr>
							<td class="wcv-order-totals-label"
								colspan="<?php echo $label_colspan; ?>"><?php _e( 'Order Total', 'wcvendors-pro' ); ?>:
							</td>
							<td class="total" colspan="<?php echo $total_colspan; ?>">
								<div class="view"><?php echo wc_price( $_order->total, array( 'currency' => $order_currency ) ); ?></div>
							</td>
						</tr>

						</tbody>
					</table>

				</div>

			</div>

			<hr/>

			<?php do_action( 'wcvendors_order_after_items_detail' ); ?>

			<div class="wcv-cols-group wcv-horizontal-gutters">

				<div class="all-100">
					<h4><?php _e( 'Customer Note', 'wcvendors-pro' ); ?></h4>

					<?php
					$customer_note = $order->get_customer_note();
					if ( $customer_note ) {
						echo '<p>' . wp_kses( $order->get_customer_note(), array( 'br' => array() ) ) . '</p>';
					} else {
						echo '<p>' . _e( 'No customer notes.', 'wcvendors-pro' ) . '</p>';
					}
					?>
				</div>

			</div>

		</div>

	</div>

</div>
