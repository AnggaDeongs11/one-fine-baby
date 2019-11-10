<?php

/**
 * Store opening hours form
 *
 * This file is used to load the store opening hours form
 *
 * @link       http://www.wcvendors.com
 * @since      1.5.9
 * @version    1.6.3
 * @author     Lindeni Mahlalela
 *
 * @package    WCVendors_Pro
 * @subpackage WCVendors_Pro/public/partials/settings
 */

$time_format = apply_filters( 'wcv_opening_hours_time_format', wc_time_format() );
?>

<div class="wcv-column-group wcv-horizontal-gutters wcv-opening-hours-wrapper">
	<?php echo is_admin() ? '<h3>' : '<p>'; ?>
	<?php _e( 'Store Opening Hours', 'wcvendors-pro' ); ?>
	<?php echo is_admin() ? '</h3>' : '</p>'; ?>

	<table class="form-table">
		<thead>
		<tr>
			<th style="width:10%;"><?php _e( '&nbsp;', 'wcvendors-pro' ); ?></th>
			<th style="width:30%;"><?php _e( 'Day', 'wcvendors-pro' ); ?></th>
			<th style="width:20%;"><?php _e( 'Open', 'wcvendors-pro' ); ?></th>
			<th style="width:20%;"><?php _e( 'Closing', 'wcvendors-pro' ); ?></th>
			<th style="width:20%;">
				<a href="#" id="add-work-hours" title="<?php _e( 'Add New Row', 'wcvendors-pro' ); ?>">
					<svg class="wcv-icon wcv-icon-md">
						<use xlink:href="<?php echo WCV_PRO_PUBLIC_ASSETS_URL; ?>svg/wcv-icons.svg#wcv-icon-plus"></use>
					</svg>
				</a>
			</th>
		</tr>
		</thead>
		<tbody id="opening-hours">
		<?php foreach ( $hours as $opening ) : ?>
			<tr>
				<td>
					<input type="checkbox" name="status[]" class="status"
						   value="1" <?php checked( $opening['status'] ); ?> />
				</td>
				<td>
					<label class="days-label"><?php echo ucwords( $opening['day'], ' ' ); ?></label>
					<input type="hidden" name="days[]" class="days-hidden" value="<?php echo $opening['day']; ?>"/>
					<span class="edit-days"></span>
				</td>
				<td>
					<label class="open-label"><?php echo ( 'open' == $opening['open'] || 'closed' == $opening['open'] ) ? ucfirst( $opening['open'] ) :  date( $time_format, strtotime( $opening['open'] ) ); ?></label>
					<input type="hidden" name="open[]" class="open-hidden" value="<?php echo $opening['open']; ?>"
						   data-list="newday"/>
					<span class="edit-opening"></span>
				</td>
				<td>
					<label class="close-label"><?php echo ( 'open' == $opening['close'] || 'closed' == $opening['close'] ) ? ucfirst( $opening['close'] ) : date( $time_format, strtotime( $opening['close'] ) ); ?></label>
					<input type="hidden" name="close[]" class="close-hidden" value="<?php echo $opening['close']; ?>"/>
					<span class="edit-closing"></span>
				</td>
				<td>
					<a href="#" data-action="edit" class="edit" title="<?php _e( 'Edit Row', 'wcvendors-pro' ); ?>">
						<svg class="wcv-icon wcv-icon-md">
							<use xlink:href="<?php echo WCV_PRO_PUBLIC_ASSETS_URL; ?>svg/wcv-icons.svg#wcv-icon-pen-square"></use>
						</svg>
					</a>

					<a href="#" data-action="done" class="done hidden"
					   title="<?php _e( 'Done Editing', 'wcvendors-pro' ); ?>">
						<svg class="wcv-icon wcv-icon-md">
							<use xlink:href="<?php echo WCV_PRO_PUBLIC_ASSETS_URL; ?>svg/wcv-icons.svg#wcv-icon-check-square"></use>
						</svg>
					</a>

					<a href="#" class="remove-row" title="<?php _e( 'Remove this Row', 'wcvendors-pro' ); ?>">
						<svg class="wcv-icon wcv-icon-md">
							<use xlink:href="<?php echo WCV_PRO_PUBLIC_ASSETS_URL; ?>svg/wcv-icons.svg#wcv-icon-times"></use>
						</svg>
					</a>
				</td>
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
</div>
