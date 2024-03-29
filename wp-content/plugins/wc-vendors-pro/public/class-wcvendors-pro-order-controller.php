<?php

/**
 * The WCVendors Pro order Controller class
 *
 * This is the order controller class for all front end order management
 *
 * @package    WCVendors_Pro
 * @subpackage WCVendors_Pro/public
 * @author     Jamie Madden <support@wcvendors.com>
 */
class WCVendors_Pro_Order_Controller {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $wcvendors_pro The ID of this plugin.
	 */
	private $wcvendors_pro;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * Is the plugin in debug mode
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      bool $debug plugin is in debug mode
	 */
	private $debug;

	/**
	 * Is the plugin base directory
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $base_dir string path for the plugin directory
	 */
	private $base_dir;

	/**
	 * The tables header rows
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      array $columns The table columns
	 */
	private $columns;

	/**
	 * The table rows
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      array $rows The table rows
	 */
	private $rows;

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $wcvendors_pro The ID of this plugin.
	 */
	private $controller_type;

	private static $billing_fields;
	private static $shipping_fields;

	private $default_start;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 *
	 * @param      string $wcvendors_pro The name of the plugin.
	 * @param      string $version       The version of this plugin.
	 */
	public function __construct( $wcvendors_pro, $version, $debug ) {

		$this->wcvendors_pro   = $wcvendors_pro;
		$this->version         = $version;
		$this->debug           = $debug;
		$this->base_dir        = plugin_dir_path( dirname( __FILE__ ) );
		$this->controller_type = 'order';

		$orders_sales_range  = get_option( 'wcvendors_orders_sales_range' );
		$this->default_start = '';

		switch ( $orders_sales_range ) {
			case 'annually':
				$this->default_start = '-1 year';
				break;
			case 'quarterly':
				$this->default_start = '-3 month';
				break;
			case 'monthly':
				$this->default_start = '-1 month';
				break;
			case 'weekly':
				$this->default_start = '-1 week';
				break;
			case 'daily':
				$this->default_start = '-1 day';
				break;
			case 'custom':
				$this->default_start = '-1 year';
				break;
			default:
				$this->default_start = '-1 month';
				break;
		}

		$this->default_start = apply_filters( 'wcv_default_order_start_date', $this->default_start );

		self::$billing_fields = apply_filters(
			'wcv_order_billing_fields',
			array(
				'first_name' => array(
					'label' => __( 'First Name', 'wcvendors-pro' ),
					'show'  => false,
				),
				'last_name'  => array(
					'label' => __( 'Last Name', 'wcvendors-pro' ),
					'show'  => false,
				),
				'company'    => array(
					'label' => __( 'Company', 'wcvendors-pro' ),
					'show'  => false,
				),
				'address_1'  => array(
					'label' => __( 'Address 1', 'wcvendors-pro' ),
					'show'  => false,
				),
				'address_2'  => array(
					'label' => __( 'Address 2', 'wcvendors-pro' ),
					'show'  => false,
				),
				'city'       => array(
					'label' => __( 'City', 'wcvendors-pro' ),
					'show'  => false,
				),
				'postcode'   => array(
					'label' => __( 'Postcode', 'wcvendors-pro' ),
					'show'  => false,
				),
				'country'    => array(
					'label'   => __( 'Country', 'wcvendors-pro' ),
					'show'    => false,
					'class'   => 'js_field-country select short',
					'type'    => 'select',
					'options' => array( '' => __( 'Select a country&hellip;', 'wcvendors-pro' ) ) + WCVendors_Pro_Form_Helper::countries(),
				),
				'state'      => array(
					'label' => __( 'State/County', 'wcvendors-pro' ),
					'class' => 'js_field-state select short',
					'show'  => false,
				),
				'email'      => array(
					'label' => __( 'Email', 'wcvendors-pro' ),
				),
				'phone'      => array(
					'label' => __( 'Phone', 'wcvendors-pro' ),
				),
			)
		);

		self::$shipping_fields = apply_filters(
			'wcv_order_shipping_fields',
			array(
				'first_name' => array(
					'label' => __( 'First Name', 'wcvendors-pro' ),
					'show'  => false,
				),
				'last_name'  => array(
					'label' => __( 'Last Name', 'wcvendors-pro' ),
					'show'  => false,
				),
				'company'    => array(
					'label' => __( 'Company', 'wcvendors-pro' ),
					'show'  => false,
				),
				'address_1'  => array(
					'label' => __( 'Address 1', 'wcvendors-pro' ),
					'show'  => false,
				),
				'address_2'  => array(
					'label' => __( 'Address 2', 'wcvendors-pro' ),
					'show'  => false,
				),
				'city'       => array(
					'label' => __( 'City', 'wcvendors-pro' ),
					'show'  => false,
				),
				'postcode'   => array(
					'label' => __( 'Postcode', 'wcvendors-pro' ),
					'show'  => false,
				),
				'country'    => array(
					'label'   => __( 'Country', 'wcvendors-pro' ),
					'show'    => false,
					'type'    => 'select',
					'class'   => 'js_field-country select short',
					'options' => array( '' => __( 'Select a country&hellip;', 'wcvendors-pro' ) ) + WCVendors_Pro_Form_Helper::countries(),
				),
				'state'      => array(
					'label' => __( 'State/County', 'wcvendors-pro' ),
					'class' => 'js_field-state select short',
					'show'  => false,
				),
			)
		);

	}

	/**
	 * Display the custom order table
	 *
	 * @since    1.0.0
	 */
	public function display() {

		// Use the internal table generator to create object list
		$order_table = new WCVendors_Pro_Table_Helper( $this->wcvendors_pro, $this->version, $this->controller_type, null, get_current_user_id() );

		$order_table->set_columns( $this->table_columns() );
		$order_table->set_rows( $this->table_rows() );

		// display the table
		$order_table->display();

	}

	/**
	 *  Process the form submission from the front end.
	 *
	 * @since    1.0.0
	 */
	public function process_submit() {

		if ( isset( $_GET['wcv_mark_shipped'] ) ) {

			$vendor_id = get_current_user_id();
			$order_id  = $_GET['wcv_mark_shipped'];

			self::mark_shipped( $vendor_id, $order_id );
		}

		if ( isset( $_GET['wcv_shipping_label'] ) ) {

			$vendor_id = get_current_user_id();
			$order_id  = $_GET['wcv_shipping_label'];

			self::shipping_label( $vendor_id, $order_id );
		}

		if ( isset( $_GET['wcv_export_orders'] ) ) {

			$vendor_id = get_current_user_id();
			$this->export_csv();
		}

		if ( isset( $_POST['wcv_order_id'] ) && isset( $_POST['wcv_add_note'] ) ) {

			if ( ! wp_verify_nonce( $_POST['wcv_add_note'], 'wcv-add-note' ) ) {
				return false;
			}

			$order_id = (int) $_POST['wcv_order_id'];
			$comment  = $_POST['wcv_comment_text'];

			if ( empty( $comment ) ) {
				wc_add_notice( __( 'You need type something in the note field', 'wcvendors-pro' ), 'error' );

				return false;
			}

			self::add_order_note( $order_id, $comment );
		}

		if ( isset( $_POST['wcv_add_tracking_number'] ) ) {

			if ( ! wp_verify_nonce( $_POST['wcv_add_tracking_number'], 'wcv-add-tracking-number' ) ) {
				return false;
			}

			self::update_shipment_tracking();
		}

		// Process the date updates for the form
		if ( isset( $_POST['wcv_order_date_update'] ) ) {

			if ( ! wp_verify_nonce( $_POST['wcv_order_date_update'], 'wcv-order-date-update' ) ) {
				return;
			}

			// Start Date
			if ( isset( $_POST['_wcv_order_start_date_input'] ) || '' === $_POST['_wcv_order_start_date_input'] ) {
				WC()->session->set( 'wcv_order_start_date', strtotime( $_POST['_wcv_order_start_date_input'] ) );
			}

			// End Date
			if ( isset( $_POST['_wcv_order_end_date_input'] ) || '' === $_POST['_wcv_order_end_date_input'] ) {
				WC()->session->set( 'wcv_order_end_date', strtotime( $_POST['_wcv_order_end_date_input'] ) );
			}
		}

	} // process_submit()

	/**
	 *  Process the delete action
	 *
	 * @since    1.0.0
	 */
	public function process_delete() {

	} // process_delete()

	/**
	 *  Update Table Headers for display
	 *
	 * @since    1.0.0
	 *
	 * @param     array $headers array passed via filter
	 */
	public function table_columns() {

		$columns = apply_filters(
			'wcv_order_table_columns',
			array(
				'ID'           => __( 'ID', 'wcvendors-pro' ),
				'order_number' => __( 'No.', 'wcvendors-pro' ),
				'products'     => __( 'Item/s', 'wcvendors-pro' ),
				'total'        => __( 'Total', 'wcvendors-pro' ),
				'status'       => __( 'Status', 'wcvendors-pro' ),
				'order_date'   => __( 'Order Date', 'wcvendors-pro' ),
			)
		);

		return $columns;

	} // table_columns()

	/**
	 *  create the table data
	 *
	 * @since    1.0.0
	 * @return   array  $new_rows   array of stdClass objects passed back to the filter
	 */
	public function table_rows() {

		$date_range = array(
			'before' => date( 'Y-m-d', $this->get_end_date() ),
			'after'  => date( 'Y-m-d', $this->get_start_date() ),
		);

		$all_orders = WCVendors_Pro_Vendor_Controller::get_orders2( get_current_user_id(), $date_range, true );

		$rows = array();

		if ( ! empty( $all_orders ) ) {

			foreach ( $all_orders as $_order ) {

				$order          = $_order->order;
				$products_html  = '';
				$needs_shipping = false;
				$needs_to_ship  = false;
				$downloadable   = false;

				if ( ! empty( $_order->order_items ) ) {

					foreach ( $_order->order_items as $item ) {

						$product_id     = $item->get_product_id();
						$_product       = $item->get_product();
						$needs_shipping = $_product->needs_shipping();

						if ( $downloadable == null ) {
							$downloadable = 0;
						}

						$products_html .= '<strong>' . $item->get_quantity() . ' x ' . $item->get_name() . '</strong><br />';
//						$products_html .= $_product->get_sku() ? sprintf( __( 'SKU: %1$s %2$s', 'wcvendors-pro' ), $_product->get_sku(), '<br />' ) : '';
//						$products_html .= wc_display_item_meta( $item, [ 'echo' => false ] );
						$products_html  = apply_filters( 'wcv_orders_order_item_meta_end', $products_html, $item->get_id(), $item, $order );

					}
				}

				$order_id    = $order->get_id();
				$shippers    = (array) get_post_meta( $order_id, 'wc_pv_shipped', true );
				$has_shipped = in_array( get_current_user_id(), $shippers ) ? __( 'Yes', 'wcvendors-pro' ) : __( 'No', 'wcvendors-pro' );
				$shipped     = ( $needs_shipping ) ? $has_shipped : __( 'NA', 'wcvendors-pro' );

				$row_actions = apply_filters(
					'wcv_orders_row_actions_' . $order->get_order_number(),
					array(
						'view_details' =>
							array(
								'label'  => __( 'View Order Details', 'wcvendors-pro' ),
								'url'    => '#',
								'custom' => array(
									'id' => 'open-order-details-modal-' . $order->get_order_number(),
								),
							),
						'print_label'  =>
							   array(
								   'label'  => __( 'Shipping Label', 'wcvendors-pro' ),
								   'url'    => '?wcv_shipping_label=' . $order->get_order_number(),
								   'target' => '_blank',
							   ),
						'add_note'     =>
							   array(
								   'label'  => __( 'Order Note', 'wcvendors-pro' ),
								   'url'    => '#',
								   'custom' => array(
									   'id' => 'open-order-note-modal-' . $order->get_order_number(),
								   ),
							   ),
						'add_tracking' =>
							   array(
								   'label'  => __( 'Tracking Number', 'wcvendors-pro' ),
								   'url'    => '#',
								   'custom' => array(
									   'id' => 'open-tracking-modal-' . $order->get_order_number(),
								   ),
							   ),

					),
					$order->get_order_number()
				);

				if ( ! $needs_shipping ) {
					unset( $row_actions['print_label'] );
					unset( $row_actions['add_tracking'] );
				}

				// If it hasn't been shipped then provide a link to mark as shipped.
				if ( __( 'No', 'wcvendors-pro' ) == $shipped ) {
					$row_actions['mark_shipped'] = array(
						'label' => __( 'Mark Shipped', 'wcvendors-pro' ),
						'url'   => '?wcv_mark_shipped=' . $order->get_order_number(),
					);
				}

				// If the order is any of the following status, remove order actions.
				if ( in_array(
					$order->get_status(),
					apply_filters(
						'wcv_order_status_action_hide',
						array(
							'refunded',
							'cancelled',
						)
					)
				) ) {
					unset( $row_actions['print_label'] );
					unset( $row_actions['add_note'] );
					unset( $row_actions['add_tracking'] );
					unset( $row_actions['mark_shipped'] );
				}

				$row_actions = apply_filters( 'wcv_order_row_actions', $row_actions, $order->get_order_number() );

				$hide_view_details    = wc_string_to_bool( get_option( 'wcvendors_hide_order_view_details', 'no' ) );
				$hide_shipping_label  = wc_string_to_bool( get_option( 'wcvendors_hide_order_shipping_label', 'no' ) );
				$hide_order_note      = wc_string_to_bool( get_option( 'wcvendors_hide_order_order_note', 'no' ) );
				$hide_tracking_number = wc_string_to_bool( get_option( 'wcvendors_hide_order_tracking_number', 'no' ) );
				$hide_mark_shipped    = wc_string_to_bool( get_option( 'wcvendors_hide_order_mark_shipped', 'no' ) );
				$order_currency       = $order->get_currency();

				if ( $hide_view_details && array_key_exists( 'view_details', $row_actions ) ) {
					unset( $row_actions['view_details'] );
				}
				if ( $hide_shipping_label && array_key_exists( 'print_label', $row_actions ) ) {
					unset( $row_actions['print_label'] );
				}
				if ( $hide_order_note && array_key_exists( 'add_note', $row_actions ) ) {
					unset( $row_actions['add_note'] );
				}
				if ( $hide_tracking_number && array_key_exists( 'add_tracking', $row_actions ) ) {
					unset( $row_actions['add_tracking'] );
				}
				if ( $hide_mark_shipped && array_key_exists( 'mark_shipped', $row_actions ) ) {
					unset( $row_actions['mark_shipped'] );
				}

				$commission_due = sprintf( get_woocommerce_price_format(), get_woocommerce_currency_symbol( $order_currency ), number_format( $_order->total_due, 2 ) );
				$shipping_due   = sprintf( get_woocommerce_price_format(), get_woocommerce_currency_symbol( $order_currency ), number_format( $_order->total_shipping, 2 ) );
				$tax_due        = sprintf( get_woocommerce_price_format(), get_woocommerce_currency_symbol( $order_currency ), number_format( $_order->total_tax, 2 ) );
				$total_text     = '<span class="wcv-tooltip" data-tip-text="' . sprintf( '%s %s %s %s %s %s', __( 'Product: ', 'wcvendors-pro' ), $commission_due, __( 'Shipping: ', 'wcvendors-pro' ), $shipping_due, __( 'Tax: ', 'wcvendors-pro' ), $tax_due ) . '">' . wc_price( $_order->commission_total ) . '</span>';

				$new_row = new stdClass();

				$can_view_emails  = wc_string_to_bool( get_option( 'wcvendors_capability_order_customer_email', 'no' ) );
				$hide_phone       = wc_string_to_bool( get_option( 'wcvendors_capability_order_customer_phone', 'no' ) );
				$customer_address = get_option( 'wcvendors_order_customer_address' );
				$customer_details = '';

				switch ( $customer_address ) {
					case 'billing':
						$customer_details = $order->get_formatted_billing_address();
						break;

					default:
						$customer_details = $order->get_formatted_shipping_address();
						break;
				}

				if ( $can_view_emails ) {
					$billing_email = $order->get_billing_email();
					if ( ! empty( $customer_details ) ) {
						$customer_details .= '<br />';
					}
					$customer_details .= $billing_email . '<br />';
				}

				if ( $hide_phone ) {
					$billing_phone     = $order->get_billing_phone();
					$customer_details .= $billing_phone;
				}

				$order_date = $order->get_date_created();

				$new_row->ID           = $order->get_order_number();
				$new_row->order_number = '#' . $order->get_order_number();
				$new_row->customer     = $customer_details;
                $new_row->product_id   = $product_id;
				$new_row->products     = $products_html;
				$new_row->total        = $total_text;
				$new_row->status       = ucfirst( wc_get_order_status_name( $order->get_status() ) );
				$new_row->order_date   = date_i18n( 'd/m/y', strtotime( $order_date ) );
				$new_row->row_actions  = $row_actions;
				$new_row->action_after = $this->order_details_template( $_order ) . $this->order_note_template( $order->get_order_number() ) . $this->tracking_number_template( $order->get_order_number(), get_current_user_id() );

				do_action( 'wcv_orders_add_new_row', $new_row );

				$rows[] = $new_row;

			}
		} // check for orders

		return apply_filters( 'wcv_orders_table_rows', $rows );

	} // table_rows()

	/**
	 *  Change the column that actions are displayed in
	 *
	 * @since    1.0.0
	 *
	 * @param     string $column column passed from filter
	 *
	 * @return   string $new_column    new column passed back to filter
	 */
	public function table_action_column( $column ) {

		$new_column = 'order_date';

		return $new_column;

	}

	/**
	 *  Add actions before and after the table
	 *
	 * @since    1.0.0
	 */
	public function table_actions() {

		$can_export_csv = wc_string_to_bool( get_option( 'wcvendors_capability_orders_export', 'no' ) );
		$add_url        = '?wcv_export_orders';

		$search = isset( $_POST['wcv-search'] ) ? $_POST['wcv-search'] : '';

		include apply_filters( 'wcvendors_pro_table_actions_path', 'partials/order/wcvendors-pro-order-table-actions.php' );

	} // table_actions()

	/**
	 *  Change the column that actions are displayed in
	 *
	 * @since    1.0.0
	 *
	 * @param     string $column column passed from filter
	 *
	 * @return   string $new_column    new column passed back to filter
	 */
	public function table_no_data_notice( $notice ) {

		$notice = apply_filters( 'wcv_orders_table_no_data_notice', __( 'No orders found.', 'wcvendors-pro' ) );

		return $notice;
	}

	/**
	 *  Get the store id of the vendor
	 *
	 * @since    1.0.0
	 *
	 * @param     array $vendor_id which vendor is being mark shipped
	 * @param     array $order_id  which order is being marked shipped
	 *
	 * @todo     clean up the code to bring into newer code standards
	 */
	public static function mark_shipped( $vendor_id, $order_id ) {

		$store_name = WCV_Vendors::get_vendor_shop_name( $vendor_id );
		$shippers   = (array) get_post_meta( $order_id, 'wc_pv_shipped', true );
		$order      = new WC_Order( $order_id );

		if ( ! in_array( $vendor_id, $shippers ) ) {

			$shippers[] = $vendor_id;

			if ( ! empty( $mails ) ) {
				WC()->mailer()->emails['WC_Email_Notify_Shipped']->trigger( $order_id, $user_id );
			}

			do_action( 'wcvendors_vendor_ship', $order_id, $vendor_id, $order );

			wc_add_notice( __( 'Order marked shipped.', 'wcvendors-pro' ), 'success' );

		}

		update_post_meta( $order_id, 'wc_pv_shipped', $shippers );
	}

	/**
	 *  Get the store id of the vendor
	 *
	 * @since    1.0.0
	 *
	 * @param     array $vendor_id which vendor is being mark shipped
	 * @param     array $order_id  which order is being marked shipped
	 *
	 * @todo     check the vendor is in the order otherwise kick out
	 */
	public static function shipping_label( $vendor_id, $order_id ) {

		$store_name = WCV_Vendors::get_vendor_shop_name( $vendor_id );
		$order      = new WC_Order( $order_id );
		$base_dir   = plugin_dir_path( dirname( __FILE__ ) );
		$countries  = WCVendors_Pro_Form_Helper::countries();

		$store_address1 = get_user_meta( $vendor_id, '_wcv_store_address1', true );
		$store_address2 = get_user_meta( $vendor_id, '_wcv_store_address2', true );
		$store_city     = get_user_meta( $vendor_id, '_wcv_store_city', true );
		$store_state    = get_user_meta( $vendor_id, '_wcv_store_state', true );
		$store_country  = $countries[ get_user_meta( $vendor_id, '_wcv_store_country', true ) ];
		$store_postcode = get_user_meta( $vendor_id, '_wcv_store_postcode', true );

		$vendor_items    = WCV_Queries::get_products_for_order( $order_id );
		$vendor_products = array();
		$order_items     = $order->get_items();

		foreach ( $order_items as $key => $value ) {
			if ( in_array( $value['variation_id'], $vendor_items ) || in_array( $value['product_id'], $vendor_items ) ) {
				$vendor_products[] = $value;
			}
		}

		// Prevent user editing the $_GET variable
		if ( empty( $vendor_products ) ) {
			return;
		}

		$products_html = '';

		foreach ( $vendor_products as $key => $item ) {
			// May need to fix for variations
			$_product       = new WC_Product( $item['product_id'] );
			$products_html .= '<strong>' . $item['qty'] . ' x ' . $item['name'] . '</strong><br />';
		}

		wc_get_template(
			'shipping-label.php',
			apply_filters(
				'wcvendors_pro_order_shipping_label',
				array(
					'order'          => $order,
					'store_name'     => $store_name,
					'store_address1' => $store_address1,
					'store_address2' => $store_address2,
					'store_city'     => $store_city,
					'store_state'    => $store_state,
					'store_country'  => $store_country,
					'store_postcode' => $store_postcode,
					'picking_list'   => $products_html,
				)
			),
			'wc-vendors/dashboard/order/',
			$base_dir . 'templates/dashboard/order/'
		);

		die();

	}  // shipping_label()

	/**
	 *  Add an order note
	 *
	 * @since    1.0.0
	 *
	 * @param     array $note order note array
	 */
	public static function add_order_note( $order_id, $comment ) {

		$order = new WC_Order( $order_id );

		if ( is_object( $order ) ) {
			add_filter( 'woocommerce_new_order_note_data', array( __CLASS__, 'filter_order_note' ), 10, 2 );
			$order->add_order_note( apply_filters( 'wcv_add_order_note', $comment, $order_id ), 1 );
			remove_filter( 'woocommerce_new_order_note_data', array( __CLASS__, 'filter_order_note' ), 10, 2 );
			wc_add_notice( __( 'The customer has been notified.', 'wcvendors-pro' ), 'success' );
		}

	}

	/**
	 *  Filter the order note
	 *
	 * @since    1.0.0
	 *
	 * @param     array $commentdata comment data
	 * @param     array $order       order this is relevant to
	 *
	 * @todo     clean up the code to bring into newer code standards
	 */
	public static function filter_order_note( $commentdata, $order ) {
		$user_id = get_current_user_id();

		$commentdata['user_id']              = $user_id;
		$commentdata['comment_author']       = WCV_Vendors::get_vendor_shop_name( $user_id );
		$commentdata['post_author']          = $user_id;
		$commentdata['comment_author_url']   = WCV_Vendors::get_vendor_shop_page( $user_id );
		$commentdata['comment_author_email'] = wp_get_current_user()->user_email;

		return $commentdata;
	}

	/**
	 *  Order Note Template
	 *
	 * @since    1.0.0
	 *
	 * @param     int $order_id order id for notes.
	 */
	public function order_note_template( $order_id ) {

		$can_add_comments = get_option( 'wcvendors_capability_order_update_notes' );

		$form = '';

		if ( $can_add_comments ) {
			ob_start();
			$notes = $this->existing_order_notes( $order_id );
			wc_get_template(
				'order_note_form.php',
				array(
					'order_id' => $order_id,
					'notes'    => $notes,
				),
				'wc-vendors/dashboard/order/',
				$this->base_dir . 'templates/dashboard/order/'
			);
			$form = ob_get_contents();
			ob_end_clean();
		}

		return $form;
	}

	/**
	 *  Order Details Template
	 *
	 * @since    1.0.0
	 *
	 * @param     int $order_id order id for notes.
	 */
	public function order_details_template( $_order ) {

		$form = '';

		// Get line items
		$order           = $_order->order;
		$line_items      = $_order->order_items;
		$billing_fields  = $order->get_formatted_billing_address();
		$shipping_fields = $order->get_formatted_shipping_address();

		$order_taxes = array();

		if ( wc_tax_enabled() ) {
			$order_taxes         = $order->get_taxes();
			$tax_classes         = WC_Tax::get_tax_classes();
			$classes_options     = array();
			$classes_options[''] = __( 'Standard', 'wcvendors-pro' );

			if ( ! empty( $tax_classes ) ) {
				foreach ( $tax_classes as $class ) {
					$classes_options[ sanitize_title( $class ) ] = $class;
				}
			}

			$show_tax_columns = sizeof( $order_taxes ) === 1;
		}

		ob_start();

		wc_get_template(
			'order_details.php',
			array(
				'order'           => $order,
				'_order'          => $_order,
				'order_currency'  => $order->get_currency(),
				'order_date'      => $order->get_date_created(),
				'order_id'        => $order->get_order_number(),
				'line_items'      => $line_items,
				'order_taxes'     => $order_taxes,
				'billing_fields'  => $billing_fields,
				'shipping_fields' => $shipping_fields,
			),
			'wc-vendors/dashboard/order/',
			$this->base_dir . 'templates/dashboard/order/'
		);

		$form = ob_get_contents();
		ob_end_clean();

		return $form;
	}

	/**
	 *  Existing Order Notes
	 *
	 * @since    1.0.0
	 *
	 * @param     int $order_id order id for notes.
	 */
	public function existing_order_notes( $order_id ) {

		$can_view_comments = wc_string_to_bool( get_option( 'wcvendors_capability_order_read_notes' ) );

		$notes = '';

		if ( ! $can_view_comments ) {
			return;
		}

		$order_notes = $this->get_vendor_order_notes( $order_id );

		if ( ! empty( $order_notes ) ) {
			ob_start();
			foreach ( $order_notes as $order_note ) {
				$time_posted = human_time_diff( strtotime( $order_note->comment_date_gmt ), current_time( 'timestamp', 1 ) );
				$note_text   = $order_note->comment_content;
				wc_get_template(
					'order_note.php',
					array(
						'time_posted' => $time_posted,
						'note_text'   => $note_text,
					),
					'wc-vendors/dashboard/order/',
					$this->base_dir . 'templates/dashboard/order/'
				);
			}
			$notes = ob_get_contents();
			ob_end_clean();
		}

		return $notes;
	}

	/**
	 *  Get the vendor notes for an order
	 *
	 * @since    1.0.0
	 *
	 * @param     int $order_id order id for notes.
	 */
	public function get_vendor_order_notes( $order_id ) {

		$notes = array();

		$args = array(
			'user_id' => get_current_user_id(),
			'post_id' => $order_id,
			'approve' => 'approve',
			'type'    => '',
		);

		remove_filter( 'comments_clauses', array( 'WC_Comments', 'exclude_order_comments' ) );

		$comments = get_comments( $args );

		foreach ( $comments as $comment ) {

			$is_customer_note = get_comment_meta( $comment->comment_ID, 'is_customer_note', true );

			if ( $is_customer_note ) {
				$notes[] = $comment;
			}
		}

		add_filter( 'comments_clauses', array( 'WC_Comments', 'exclude_order_comments' ) );

		return (array) $notes;

	} // get_vendor_order_notes()

	/**
	 *  Trigger the csv export
	 *
	 * @param     int $vendor_id which vendor is being mark shipped
	 *
	 * @since    1.0.0
	 */
	public function export_csv() {

		include_once 'class-wcvendors-pro-export-helper.php';

		$date_range = array(
			'before' => date( 'Y-m-d', $this->get_end_date() ),
			'after'  => date( 'Y-m-d', $this->get_start_date() ),
		);

		$csv_output  = new WCVendors_Pro_Export_Helper( $this->wcvendors_pro, $this->version, $this->debug );
		$csv_headers = $this->table_columns();
		$orders      = WCVendors_Pro_Vendor_Controller::get_orders2( get_current_user_id(), $date_range, true );
		$rows        = $csv_output->format_orders_export( $orders );

		// remove the ID column as its not required
		unset( $csv_headers['ID'] );
		$csv_headers  = apply_filters( 'wcv_order_export_csv_headers', $csv_headers );
		$csv_rows     = apply_filters( 'wcv_order_export_csv_rows', $rows, $orders, $date_range );
		$csv_filename = apply_filters( 'wcv_order_export_csv_filename', 'orders' );

		$csv_output->download_csv( $csv_headers, $csv_rows, $csv_filename );

	} // download_csv()

	/**
	 *  Tracking Number Template
	 *
	 * @since    1.0.0
	 *
	 * @param     int $order_id order id for notes.
	 */
	public function tracking_number_template( $order_id, $vendor_id ) {

		$form = '';

		ob_start();

		$tracking_details = $this->get_vendor_tracking_details( $order_id, $vendor_id );

		// Clean up any empty indexes
		if ( ! isset( $tracking_details['_wcv_shipping_provider'] ) ) {
			$tracking_details['_wcv_shipping_provider'] = '';
		}
		if ( ! isset( $tracking_details['_wcv_tracking_number'] ) ) {
			$tracking_details['_wcv_tracking_number'] = '';
		}
		if ( ! isset( $tracking_details['_wcv_date_shipped'] ) ) {
			$tracking_details['_wcv_date_shipped'] = '';
		}

		wc_get_template(
			'tracking_number.php',
			array(
				'order_id'         => $order_id,
				'tracking_details' => $tracking_details,
			),
			'wc-vendors/dashboard/order/',
			$this->base_dir . 'templates/dashboard/order/'
		);

		$form = ob_get_contents();
		ob_end_clean();

		return $form;
	}

	/**
	 *  Tracking Number Template
	 *
	 * @since    1.0.0
	 *
	 * @param     int $order_id order id for notes.
	 */
	public function get_vendor_tracking_details( $order_id, $vendor_id ) {

		$order_tracking_details = get_post_meta( $order_id, '_wcv_tracking_details', true );

		if ( empty( $order_tracking_details ) ) {
			return array();
		}

		if ( array_key_exists( $vendor_id, $order_tracking_details ) ) {
			return $order_tracking_details[ $vendor_id ];
		} else {
			return array();
		}

	}

	/**
	 *  Update the order shipment tracking
	 *
	 * @since    1.0.0
	 */
	public function update_shipment_tracking() {

		$order_id               = $_POST['_wcv_order_id'];
		$order_tracking_details = get_post_meta( $order_id, '_wcv_tracking_details', true );
		$vendor_id              = get_current_user_id();
		$store_name             = WCV_Vendors::get_vendor_shop_name( $vendor_id );
		$order_tracking_details = is_array( $order_tracking_details ) ? $order_tracking_details : array();

		$vendor_tracking_details = array(
			'_wcv_shipping_provider' => $_POST[ '_wcv_shipping_provider_' . $order_id ],
			'_wcv_tracking_number'   => $_POST[ '_wcv_tracking_number_' . $order_id ],
			'_wcv_date_shipped'      => $_POST[ '_wcv_date_shipped_' . $order_id ],
		);

		$order_tracking_details[ $vendor_id ] = $vendor_tracking_details;

		$tracking_base_url = '';
		$tracking_provider = '';

		// Loop through providers and get the URL to input
		foreach ( $this->shipping_providers() as $provider_countries ) {

			foreach ( $provider_countries as $provider => $url ) {

				if ( sanitize_title( $provider ) == sanitize_title( $vendor_tracking_details['_wcv_shipping_provider'] ) ) {
					$tracking_base_url = $url;
					$tracking_provider = $provider;
					break;
				}
			}

			if ( $tracking_base_url ) {
				break;
			}
		}

		$order_note  = sprintf( __( 'A %s has added a tracking number to your order. You can track this at the following url. ', 'wcvendors-pro' ), wcv_get_vendor_name( true, false ) );
		$full_link   = sprintf( $tracking_base_url, $vendor_tracking_details['_wcv_tracking_number'] );
		$order_note .= sprintf( '<a href="%s" target="_blank">%s</a>', $full_link, $full_link );

		$order_note = apply_filters( 'wcv_shipping_tracking_update_note', $order_note, $full_link, $vendor_id, $order_id, $store_name, $order_tracking_details );
		$this->add_order_note( $order_id, $order_note );

		update_post_meta( $order_id, '_wcv_tracking_details', $order_tracking_details );

		// Mark as shipped as tracking information has been added
		self::mark_shipped( $vendor_id, $order_id );

		do_action( 'wcv_update_shipment_tracking', $vendor_tracking_details );

	} // update_shipment_tracking()

	/**
	 *  Shipment tracking providers
	 *
	 * @since    1.0.0
	 * @return     array    shipping providers
	 */
	public static function shipping_providers() {

		$shipping_providers = array(
			'Australia'           => array(
				'Australia Post' => 'http://auspost.com.au/track/track.html?id=%1$s',
				'FedEx'          => 'https://www.fedex.com/apps/fedextrack/?tracknumbers=%1$s&cntry_code=au',
			),
			'Canada'              => array(
				'Canada Post' => 'http://www.canadapost.ca/cpotools/apps/track/personal/findByTrackNumber?trackingNumber=%1$s',
				'Fedex'       => 'http://www.fedex.com/Tracking?action=track&tracknumbers=%1$s',
				'UPS'         => 'http://wwwapps.ups.com/WebTracking/track?track=yes&trackNums=%1$s',
			),
			'Germany'             => array(
				'DHL Intraship (DE)' => 'http://nolp.dhl.de/nextt-online-public/set_identcodes.do?lang=de&idc=%1$s&rfn=&extendedSearch=true',
				'Hermes'             => 'https://tracking.hermesworld.com/?TrackID=%1$s',
				'Deutsche Post DHL'  => 'http://nolp.dhl.de/nextt-online-public/set_identcodes.do?lang=de&idc=%1$s',
				'UPS Germany'        => 'http://wwwapps.ups.com/WebTracking/processInputRequest?sort_by=status&tracknums_displayed=1&TypeOfInquiryNumber=T&loc=de_DE&InquiryNumber1=%1$s',
				'DPD'                => 'https://tracking.dpd.de/parcelstatus?query=%1$s&locale=en_DE',
			),
			'Ireland'             => array(
				'DPD' => 'http://www2.dpd.ie/Services/QuickTrack/tabid/222/ConsignmentID/%1$s/Default.aspx',
			),
			'Italy'               => array(
				'BRT (Bartolini)' => 'http://as777.brt.it/vas/sped_det_show.hsm?referer=sped_numspe_par.htm&Nspediz=%1$s',
				'DHL Express'     => 'http://www.dhl.it/it/express/ricerca.html?AWB=%1$s&brand=DHL',
			),
			'India'               => array(
				'DTDC' => 'http://www.dtdc.in/dtdcTrack/Tracking/consignInfo.asp?strCnno=%1$s',
			),
			'Netherlands'         => array(
				'PostNL' => 'https://mijnpakket.postnl.nl/Claim?Barcode=%1$s&Postalcode=%2$s&Foreign=False&ShowAnonymousLayover=False&CustomerServiceClaim=False',
				'DPD.NL' => 'http://track.dpdnl.nl/?parcelnumber=%1$s',
			),
			'New Zealand'         => array(
				'Courier Post' => 'http://trackandtrace.courierpost.co.nz/Search/%1$s',
				'NZ Post'      => 'http://www.nzpost.co.nz/tools/tracking?trackid=%1$s',
				'Fastways'     => 'http://www.fastway.co.nz/courier-services/track-your-parcel?l=%1$s',
				'PBT Couriers' => 'http://www.pbt.com/nick/results.cfm?ticketNo=%1$s',
			),
			'South Africa'        => array(
				'SAPO' => 'http://sms.postoffice.co.za/TrackingParcels/Parcel.aspx?id=%1$s',
			),
			'United Kingdom (UK)' => array(
				'DHL'                       => 'http://www.dhl.com/content/g0/en/express/tracking.shtml?brand=DHL&AWB=%1$s',
				'DPD'                       => 'http://www.dpd.co.uk/tracking/trackingSearch.do?search.searchType=0&search.parcelNumber=%1$s',
				'InterLink'                 => 'http://www.interlinkexpress.com/apps/tracking/?reference=%1$s&postcode=%2$s#results',
				'ParcelForce'               => 'http://www.parcelforce.com/portal/pw/track?trackNumber=%1$s',
				'Royal Mail'                => 'https://www.royalmail.com/track-your-item/?trackNumber=%1$s',
				'TNT Express (consignment)' => 'http://www.tnt.com/webtracker/tracking.do?requestType=GEN&searchType=CON&respLang=en&respCountry=GENERIC&sourceID=1&sourceCountry=ww&cons=%1$s&navigation=1&genericSiteIdent=',
				'TNT Express (reference)'   => 'http://www.tnt.com/webtracker/tracking.do?requestType=GEN&searchType=REF&respLang=en&respCountry=GENERIC&sourceID=1&sourceCountry=ww&cons=%1$s&navigation=1&genericSiteIdent=',
				'UK Mail'                   => 'https://old.ukmail.com/ConsignmentStatus/ConsignmentSearchResults.aspx?SearchType=Reference&SearchString=%1$s',
			),
			'United States (US)'  => array(
				'Fedex'         => 'http://www.fedex.com/Tracking?action=track&tracknumbers=%1$s',
				'FedEx Sameday' => 'https://www.fedexsameday.com/fdx_dotracking_ua.aspx?tracknum=%1$s',
				'OnTrac'        => 'http://www.ontrac.com/trackingdetail.asp?tracking=%1$s',
				'UPS'           => 'http://wwwapps.ups.com/WebTracking/track?track=yes&trackNums=%1$s',
				'USPS'          => 'https://tools.usps.com/go/TrackConfirmAction_input?qtc_tLabels1=%1$s',
			),
		);

		$ship_to_countries      = get_option( 'woocommerce_ship_to_countries' );
		$countries              = new WC_Countries();
		$shipping_countries     = $countries->get_shipping_countries();
		$shipping_country_names = array();

		foreach ( $shipping_countries as $country_code => $country_name ) {
			$shipping_country_names[] = $country_name;
		}

		if ( $ship_to_countries == 'specific' ) {
			foreach ( $shipping_providers as $country_name => $providers ) {
				if ( ! in_array( $country_name, $shipping_country_names ) ) {
					unset( $shipping_providers[ $country_name ] );
				}
			}
		}

		return apply_filters( 'wcv_shipping_providers_list', $shipping_providers );

	}

	/**
	 * Filter the customers shipping address for the order table and view order details
	 *
	 * @since   1.3.6
	 * @version 1.5.1
	 * @access  public
	 */
	public function filter_formatted_shipping_address( $address ) {

		$show_name             = wc_string_to_bool( get_option( 'wcvendors_capability_order_customer_name', 'no' ) );
		$show_shipping_address = wc_string_to_bool( get_option( 'wcvendors_capability_order_customer_shipping', 'no' ) );

		if ( ! $show_name ) {
			unset( $address['first_name'] );
			unset( $address['last_name'] );
		}

		if ( ! $show_shipping_address ) {
			unset( $address['company'] );
			unset( $address['address_1'] );
			unset( $address['address_2'] );
			unset( $address['city'] );
			unset( $address['state'] );
			unset( $address['postcode'] );
			unset( $address['country'] );
		}

		return $address;

	} // filter_formatted_shipping_address

	/**
	 * Filter the customers billing address for view order details
	 *
	 * @since   1.3.6
	 * @version 1.5.1
	 * @access  public
	 */
	public function filter_formatted_billing_address( $address ) {

		$show_name            = wc_string_to_bool( get_option( 'wcvendors_capability_order_customer_name', 'no' ) );
		$show_billing_address = wc_string_to_bool( get_option( 'wcvendors_capability_order_customer_billing', 'no' ) );

		if ( ! $show_name ) {
			unset( $address['first_name'] );
			unset( $address['last_name'] );
		}

		if ( ! $show_billing_address ) {
			unset( $address['company'] );
			unset( $address['address_1'] );
			unset( $address['address_2'] );
			unset( $address['city'] );
			unset( $address['state'] );
			unset( $address['postcode'] );
			unset( $address['country'] );
		}

		return $address;

	} // filter_formatted_billing_address

	/**
	 * Filter the order meta to remove hidden keys
	 *
	 * @since   1.5.0
	 * @version 1.5.1
	 */
	public function filter_order_item_get_formatted_meta_data( $formatted_meta, $order_item ) {

		$dashboard_page_ids = (array) get_option( 'wcvendors_dashboard_page_id' );

		foreach ( $dashboard_page_ids as $dashboard_page_id ) {
			if ( isset( $dashboard_page_id ) && is_page( $dashboard_page_id ) ) {
				$hide_meta = apply_filters(
					'wcvendors_hide_order_meta_data',
					array(
						'Sold By',
						get_option( 'wcvendors_label_sold_by' ),
					)
				);

				// Filter any meta not to show
				foreach ( $formatted_meta as $key => $meta ) {
					if ( in_array( $meta->key, $hide_meta ) ) {
						unset( $formatted_meta[ $key ] );
					}
				}
			}
		}

		return $formatted_meta;
	}

	/**
	 * Get order start date.
	 *
	 * @return array|string
	 */
	public function get_start_date() {
		return WC()->session->get( 'wcv_order_start_date', strtotime( apply_filters( 'wcv_order_start_date', $this->default_start ) ) );
	}

	/**
	 * Get order end date.
	 *
	 * @return array|string
	 */
	public function get_end_date() {
		return WC()->session->get( 'wcv_order_end_date', strtotime( apply_filters( 'wcv_order_end_date', 'now' ) ) );
	}

	/**
	 * Get default start date.
	 *
	 * @return string
	 */
	public function get_default_start_date() {
		return strtotime( apply_filters( 'wcv_order_start_date', $this->default_start ) );
	}
}
