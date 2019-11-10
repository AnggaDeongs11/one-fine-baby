<?php

/**
 * Order Table Main Actions
 *
 * This file is used to add the table actions before and after a table
 *
 * @link       http://www.wcvendors.com
 * @since      1.3.7
 *
 * @package    WCVendors_Pro
 * @subpackage WCVendors_Pro/public/partials/product
 */
?>

<div class="wcv_dashboard_table_header wcv_actions wcv-cols-group horizontal-gutters wcv-order-header">
	<div class="all-100 form-group">
		<form method="post" action="" class="wcv-form wcv-form-exclude">
            <div class="form-row">
			<?php
			// Start Date
			WCVendors_Pro_Form_Helper::input(
				apply_filters(
					'wcv_order_start_date_input',
					array(
						'id'                => '_wcv_order_start_date_input',
						'label'             => __( 'Start Date', 'wcvendors-pro' ),
						'class'             => 'wcv-datepicker no_limit form-control',
						'value'             => date( 'Y-m-d', $this->get_start_date() ),
						'placeholder'       => 'YYYY-MM-DD',
						'wrapper_start'     => '<div class="col-4">',
						'wrapper_end'       => '</div>',
						'custom_attributes' => array(
							'data-default' => date( 'Y-m-d', $this->get_default_start_date() ),
							'maxlenth'     => '10',
							'pattern'      => '[0-9]{4}-(0[1-9]|1[012])-(0[1-9]|1[0-9]|2[0-9]|3[01])',
						),
					)
				)
			);

			// End Date
			WCVendors_Pro_Form_Helper::input(
				apply_filters(
					'wcv_order_end_date_input',
					array(
						'id'                => '_wcv_order_end_date_input',
						'label'             => __( 'End Date', 'wcvendors-pro' ),
						'class'             => 'wcv-datepicker no_limit form-control',
						'value'             => date( 'Y-m-d', $this->get_end_date() ),
						'placeholder'       => 'YYYY-MM-DD',
						'wrapper_start'     => '<div class="col-4">',
						'wrapper_end'       => '</div>',
						'custom_attributes' => array(
							'data-default' => date( 'Y-m-d', strtotime( apply_filters( 'wcv_order_end_date', 'now' ) ) ),
							'maxlenth'     => '10',
							'pattern'      => '[0-9]{4}-(0[1-9]|1[012])-(0[1-9]|1[0-9]|2[0-9]|3[01])',
						),
					)
				)
			);

			// Update Button
			WCVendors_Pro_Form_helper::submit(
				apply_filters(
					'wcv_order_update_button',
					array(
						'id'            => 'update_button',
						'value'         => __( 'Search', 'wcvendors-pro' ),
						'class'         => 'btn btn--secondary btn-longer',
						'wrapper_start' => '<div class="col-4 d-flex justify-content-center align-items-end"><div class="control-group" style="margin-bottom: 10px">',
						'wrapper_end'   => '</div></div>',
					)
				)
			);

			wp_nonce_field( 'wcv-order-date-update', 'wcv_order_date_update' );
			?>
            </div>
		</form>
	</div>

</div>
