<?php

/**
 * Table data template
 *
 * This file is used to display the table data
 *
 * @link       http://www.wcvendors.com
 * @since      1.0.0
 *
 * @package    WCVendors_Pro
 * @subpackage WCVendors_Pro/public/partials/helpers/table
 */

?>

<tbody>
<?php foreach ( $this->rows as $row ) : ?>

	<?php

	if ( isset( $row->action_before ) ) {
		echo $row->action_before;
	}
	$orderId = null;
	?>

	<tr>
		<?php foreach ( $this->columns as $key => $column ) : ?>

			<?php
			if ( strtolower( $column ) == 'id' ) {
                $orderId = $row->$key;
				continue;
			}
			?>

			<td><?php echo $row->$key; ?>
				<!-- Row Action output -->
				<?php if ( $this->action_column == $key ) : ?>
					<?php
					if ( isset( $row->row_actions ) ) {
						$this->actions = $row->row_actions;
					}
					?>
<!--					--><?php // $this->display_actions( $row->ID ); ?>
					<?php
					if ( isset( $row->action_after ) ) {
						echo $row->action_after;
					}
					?>
				<?php endif; ?>
			</td>

		<?php endforeach; ?>
        <td><a class="btn btn--link btn-bordered float-right" href="#" id="open-order-details-modal-<?php echo $orderId; ?>">View</a></td>
	</tr>

<?php endforeach; ?>
</tbody>
