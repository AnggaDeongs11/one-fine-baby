<?php
/**
 * The template for displaying the vendor store information including total sales, orders, products and commission
 *
 * Override this template by copying it to yourtheme/wc-vendors/dashboard/report
 *
 * @package    WCVendors_Pro
 * @version    1.4.4
 */
$give_tax = 'yes' == get_option( 'wcvendors_vendor_give_taxes' ) ? true : false;

$commission_due_total  = ( $give_tax ) ? $store_report->commission_due + $store_report->commission_shipping_due + $store_report->commission_tax_due : $store_report->commission_due + $store_report->commission_shipping_due;
$commission_paid_total = ( $give_tax ) ? $store_report->commission_paid + $store_report->commission_shipping_paid + $store_report->commission_tax_paid : $store_report->commission_paid + $store_report->commission_shipping_paid;

?>

<div class="wcv_dashboard_overview wcv-cols-group wcv-horizontal-gutters dashboard-section-hr-top mt-5">

	<div class="xlarge-50 large-50 medium-100 small-100 tiny-100">
		<h3 class="font-default"><?php _e( 'Sales Total', 'wcvendors-pro' ); ?></h3>
        <div class="blue-border-1"></div>
		<table role="grid" class="wcvendors-table wcvendors-table-recent_order wcv-table">

			<tbody>
			<tr>
				<td class="table-label"><?php _e( 'Products', 'wcvendors-pro' ); ?></td>
				<td class="table-value"><?php echo wc_price( $store_report->commission_due ); ?></td>
			</tr>
			<tr>
				<td class="table-label"><?php _e( 'Shipping', 'wcvendors-pro' ); ?></td>
				<td class="table-value"><?php echo wc_price( $store_report->commission_shipping_due ); ?></td>
			</tr>
			<?php if ( $give_tax && false) : ?>
				<tr>
					<td><?php _e( 'Tax', 'wcvendors-pro' ); ?></td>
					<td><?php echo wc_price( $store_report->commission_tax_due ); ?></td>
				</tr>
			<?php endif; ?>
			<tr style="border-top: 1px solid #F5F5F5">
				<td class="table-total"><strong><?php _e( 'Totals', 'wcvendors-pro' ); ?></strong></td>
				<td class="table-total-value"><?php echo wc_price( $commission_due_total ); ?></td>
			</tr>
			</tbody>

		</table>
	</div>

	<div class="xlarge-50 large-50 medium-100 small-100 tiny-100">
		<h3 class="font-default"><?php _e( 'Net Sales', 'wcvendors-pro' ); ?></h3>
        <div class="blue-border-1"></div>
		<table role="grid" class="wcvendors-table wcvendors-table-recent_order wcv-table">
			<tbody>
			<tr>
				<td class="table-label"><?php _e( 'Products', 'wcvendors-pro' ); ?></td>
				<td class="table-value"><?php echo wc_price( $store_report->commission_paid ); ?></td>
			</tr>
			<tr>
				<td class="table-label"><?php _e( 'Shipping', 'wcvendors-pro' ); ?></td>
				<td class="table-value"><?php echo wc_price( $store_report->commission_shipping_paid ); ?></td>
			</tr>
			<?php if ( $give_tax && false) : ?>
				<tr>
					<td><?php _e( 'Tax', 'wcvendors-pro' ); ?></td>
					<td><?php echo wc_price( $store_report->commission_tax_paid ); ?></td>
				</tr>
			<?php endif; ?>
			<tr style="border-top: 1px solid #F5F5F5">
				<td class="table-total"><strong><?php _e( 'Totals', 'wcvendors-pro' ); ?></strong></td>
				<td class="table-total-value"><?php echo wc_price( $commission_paid_total ); ?></td>
			</tr>
			</tbody>

		</table>
	</div>

</div>

<div class="wcv_dashboard_overview wcv-cols-group wcv-horizontal-gutters dashboard-section-hr-top mt-5">

	<div class="xlarge-50 large-50 medium-100 small-100 tiny-100">
		<h3 class="font-default"><?php _e( 'Commission Due', 'wcvendors-pro' ); ?></h3>
        <div class="blue-border-1"></div>
		<table role="grid" class="wcvendors-table wcvendors-table-recent_order wcv-table">

			<tbody>
			<tr>
				<td class="table-label"><?php _e( 'Products', 'wcvendors-pro' ); ?></td>
				<td class="table-value"><?php echo wc_price( $store_report->commission_due ); ?></td>
			</tr>
			<tr>
				<td class="table-label"><?php _e( 'Shipping', 'wcvendors-pro' ); ?></td>
				<td class="table-value"><?php echo wc_price( $store_report->commission_shipping_due ); ?></td>
			</tr>
			<?php if ( $give_tax && false) : ?>
				<tr>
					<td><?php _e( 'Tax', 'wcvendors-pro' ); ?></td>
					<td><?php echo wc_price( $store_report->commission_tax_due ); ?></td>
				</tr>
			<?php endif; ?>
			<tr style="border-top: 1px solid #F5F5F5">
				<td class="table-total"><strong><?php _e( 'Totals', 'wcvendors-pro' ); ?></strong></td>
				<td class="table-total-value"><?php echo wc_price( $commission_due_total ); ?></td>
			</tr>
			</tbody>

		</table>
	</div>

	<div class="xlarge-50 large-50 medium-100 small-100 tiny-100">
		<h3 class="font-default"><?php _e( 'Commission Paid', 'wcvendors-pro' ); ?></h3>
        <div class="blue-border-1"></div>
		<table role="grid" class="wcvendors-table wcvendors-table-recent_order wcv-table">
			<tbody>
			<tr>
				<td class="table-label"><?php _e( 'Products', 'wcvendors-pro' ); ?></td>
				<td class="table-value"><?php echo wc_price( $store_report->commission_paid ); ?></td>
			</tr>
			<tr>
				<td class="table-label"><?php _e( 'Shipping', 'wcvendors-pro' ); ?></td>
				<td class="table-value"><?php echo wc_price( $store_report->commission_shipping_paid ); ?></td>
			</tr>
			<?php if ( $give_tax && false) : ?>
				<tr>
					<td><?php _e( 'Tax', 'wcvendors-pro' ); ?></td>
					<td><?php echo wc_price( $store_report->commission_tax_paid ); ?></td>
				</tr>
			<?php endif; ?>
			<tr style="border-top: 1px solid #F5F5F5">
				<td class="table-total"><strong><?php _e( 'Totals', 'wcvendors-pro' ); ?></strong></td>
				<td class="table-total-value"><?php echo wc_price( $commission_paid_total ); ?></td>
			</tr>
			</tbody>

		</table>
	</div>

</div>
