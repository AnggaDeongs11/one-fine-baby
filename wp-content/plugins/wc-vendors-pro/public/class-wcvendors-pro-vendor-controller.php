<?php

/**
 * The WCVendors Pro Vendor Controller class
 *
 * This is the vendor controller class for all vendor related work
 *
 * @package    WCVendors_Pro
 * @subpackage WCVendors_Pro/public
 * @author     Jamie Madden <support@wcvendors.com>
 */
class WCVendors_Pro_Vendor_Controller {

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
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 *
	 * @param      string $wcvendors_pro The name of the plugin.
	 * @param      string $version       The version of this plugin.
	 */
	public function __construct( $wcvendors_pro, $version, $debug ) {

		$this->wcvendors_pro = $wcvendors_pro;
		$this->version       = $version;
		$this->debug         = $debug;
		$this->base_dir      = plugin_dir_path( dirname( __FILE__ ) );
		$this->base_url      = plugin_dir_url( __FILE__ );
		$this->suffix        = $this->debug ? '' : '.min';
	}

	/**
	 *  Get the store id of the vendor - DEPRECIATED
	 *
	 * @since      1.2.0
	 *
	 * @param     int $vendor_id vendor id for store id
	 *
	 * @deprecated 1.2.0
	 */
	public static function get_vendor_store_id( $vendor_id ) {

		$args = array(
			'author'      => $vendor_id,
			'orderby'     => 'post_date',
			'post_type'   => 'vendor_store',
			'post_status' => array( 'publish', 'draft' ),
		);

		$stores = get_posts( $args );

		if ( ! empty( $stores ) ) {
			// We have a store and we need to return it.
			$store = reset( $stores );

			return $store->ID;
		} else {
			return null;
		}

	} //get_vendor_store_id()

	/**
	 *  Get the login_name of the vendor
	 *
	 * @since    1.0.0
	 *
	 * @param     int    $vendor_id vendor id for store id
	 * @param     string $meta_key  user meta key
	 */
	public static function get_vendor_detail( $vendor_id, $meta_key ) {

		$vendor = get_userdata( $vendor_id );

		return $vendor->{$meta_key};

	}

	/**
	 *  Get all orders for a vendor
	 *
	 * @since    1.0.0
	 *
	 * @param     int   $vendor_id  vendor id for store id
	 * @param     array $date_range date range to search for
	 *
	 * @todo     Deprecate this function and update orders controller
	 */
	public static function get_orders( $vendor_id, $date_range = null ) {

		$start_date = strtotime( date( 'Ymd', strtotime( date( 'Ym', current_time( 'timestamp' ) ) . '01' ) ) );
		$end_date   = strtotime( date( 'Ymd', current_time( 'timestamp' ) ) );

		global $wpdb;

		$sql = "
			SELECT id, DISTINCT( order_id ), product_id, vendor_id, total_shipping, total_due, qty, tax, status, time
			FROM {$wpdb->prefix}pv_commission as order_items
			WHERE   vendor_id = {$vendor_id}";

		$sql .= " AND     status != 'reversed'";

		if ( $date_range != null ) {

			$sql .= "
			AND     time >= '" . $date_range['after'] . "'
			AND     time <= '" . $date_range['before'] . "'
			";
		}

		$sql .= '
			ORDER BY time DESC;
		';

		$orders = $wpdb->get_results( $sql );

		$total_orders = array();

		if ( $orders ) {

			foreach ( $orders as $order ) {

				$_order                    = new WC_Order( $order->order_id );
				$wcv_order                 = new stdClass();
				$wcv_order->order_id       = $order->order_id;
				$wcv_order->order          = $_order;
				$wcv_order->total_due      = $order->total_due;
				$wcv_order->total          = 0;
				$wcv_order->tax            = 0;
				$wcv_order->order_items    = array();
				$wcv_order->total_shipping = $order->total_shipping;
				$wcv_order->status         = $order->status;
				$wcv_order->recorded_time  = $order->time;

				$order_items = $_order->get_items();

				foreach ( $order_items as $key => $order_item ) {

					if ( $order_item['product_id'] == $order->product_id || $order_item['variation_id'] == $order->product_id ) {

						$wcv_order->order_items[] = $order_item;
						$wcv_order->total        += $order_item['line_total'];
						$wcv_order->tax          += $order_item['line_tax'];
					}
				}

				$total_orders[] = $wcv_order;

			}
		}

		return $total_orders;

	} // get_orders()

	/**
	 *  Get all orders for a vendor
	 *
	 * @since    1.0.0
	 *
	 * @param     int   $vendor_id  vendor id for store id
	 * @param     array $date_range date range to search for
	 *
	 * @return     array        $wcv_orders an array of order objects with required information for the vendor
	 */
	public static function get_orders2( $vendor_id, $date_range = null, $reports = true ) {

		global $wpdb;

		$sql = "
			SELECT id, order_id, product_id, vendor_id, total_due, total_shipping, qty, tax, status, time
			FROM {$wpdb->prefix}pv_commission as order_items
			WHERE vendor_id = {$vendor_id} ";

		if ( $reports ) {
			$sql .=
				" AND status != 'reversed'";
		}

		if ( $date_range != null ) {

			$sql .= "
			AND     time >= '" . $date_range['after'] . " 00:00:00'
			AND     time <= '" . $date_range['before'] . " 23:59:59'
			";
		}

		$sql .= '
			ORDER BY time DESC;
		';

		$sql = apply_filters( 'wcv_get_orders_all_sql', $sql );

		// Get all orders for the vendor id supplied except for reversed commission
		$all_orders = $wpdb->get_results( $sql );

		$sql = "
			SELECT DISTINCT( order_id )
			FROM {$wpdb->prefix}pv_commission as unqiue_orders
			WHERE   vendor_id = {$vendor_id} ";

		if ( $reports ) {
			$sql .=
				" AND     status != 'reversed'";
		}

		if ( $date_range != null ) {

			$sql .= "
			AND     time >= '" . $date_range['after'] . " 00:00:00'
			AND     time <= '" . $date_range['before'] . " 23:59:59'
			";
		}

		$sql .= '
			ORDER BY time DESC;
		';

		$sql = apply_filters( 'wcv_get_orders_unqiue_sql', $sql );

		$unique_orders = $wpdb->get_results( $sql );

		$total_orders = array();

		if ( $unique_orders ) {

			foreach ( $unique_orders as $order ) {

				// Make sure the order exists and it isn't in the trash if by some reason the commission status hasn't been reversed.
				if ( get_post_status( $order->order_id ) && 'trash' !== get_post_status( $order->order_id ) ) {

					$_order                         = new WC_Order( $order->order_id );
					$wcv_order                      = new stdClass();
					$wcv_order->order_id            = $order->order_id;
					$wcv_order->order               = $_order;
					$order_items                    = $_order->get_items();
					$wcv_order->product_commissions = array();
					$wcv_order->total               = 0;
					$wcv_order->commission_total    = 0;
					$wcv_order->product_total       = 0;
					$wcv_order->total_due           = 0;
					$wcv_order->qty                 = 0;
					$wcv_order->total_tax           = 0;
					$wcv_order->total_shipping      = 0;

					$vendor_products = array_filter(
						$all_orders,
						function ( $single_order ) use ( &$order ) {
							return $single_order->order_id == $order->order_id;
						}
					);

					$wcv_order->vendor_products = $vendor_products;

					foreach ( $vendor_products as $key => $vendor_product ) {

						$wcv_order->total_due      += $vendor_product->total_due;
						$wcv_order->total_tax      += $vendor_product->tax;
						$wcv_order->qty            += $vendor_product->qty;
						$wcv_order->total_shipping += $vendor_product->total_shipping;
						$wcv_order->status          = $vendor_product->status;
						$wcv_order->recorded_time   = date( 'Y-m-d', strtotime( $vendor_product->time ) );

						// Do not process order items if they do not exist
						if ( is_array( $order_items ) || is_object( $order_items ) ) {

							// // Ensure that only the vendor products are in the order
							foreach ( $order_items as $key => $order_item ) {

								// fall back to the parent id if the variation has been deleted
								if ( ! get_post_status( $vendor_product->product_id ) ) {
									$vendor_product->product_id = get_metadata( 'order_item', $order_item->get_id(), '_product_id', true );
								}

								if ( $order_item['product_id'] == $vendor_product->product_id || $order_item['variation_id'] == $vendor_product->product_id ) {
									$item_id = ( $order_item['variation_id'] ) ? $order_item['variation_id'] : $order_item ['product_id'];
									$wcv_order->product_commissions[ $order_item['product_id'] ] = $vendor_product->total_due;
									$wcv_order->order_items[ $item_id ]                          = $order_item;
									$wcv_order->product_total                                   += $order_item['line_total'];
								}
							}
						} else {
							$wcv_order->product_commissions = array();
							$wcv_order->order_items         = array();
							$wcv_order->product_total       = 0;
						}
					}

					$wcv_order->total            = $wcv_order->product_total + $wcv_order->total_shipping + $wcv_order->total_tax;
					$wcv_order->commission_total = $wcv_order->total_due + $wcv_order->total_shipping + $wcv_order->total_tax;

					$total_orders[] = $wcv_order;
				}
			}
		}

		return $total_orders;

	} // get_orders2()

	/**
	 *  Get the min and max dates for a vendors orders
	 *
	 * @since    1.2.3
	 *
	 * @param     int $vendor_id vendor id
	 *
	 * @return     object    $dates  the min and max dates
	 * @todo     make this actually function as its supposed to.
	 */
	public static function get_order_dates( $vendor_id, $range_type ) {

		global $wpdb;

		// Get the first and last order date for the vendor
		$sql   = "SELECT min(time) as start_date, max(time) as end_date FROM {$wpdb->prefix}pv_commission WHERE vendor_id = $vendor_id";
		$dates = $wpdb->get_row( $sql );

		// Get the start of the week option from Settings > General
		// Convert the start day to the date interval format required by PHP
		$start_of_week = get_option( 'start_of_week' );
		$start_day     = ( 0 == (int) $start_of_week ) ? 6 : (int) $start_of_week - 1;

		$start    = new DateTime( $dates->start_date );
		$end      = new DateTime( $dates->end_date );
		$interval = new DateInterval( 'P1D' );

		$date_range = new DatePeriod( $start, $interval, $end );

		$weekNumber  = 1;
		$monthNumber = 0;
		$weeks       = array();

		foreach ( $date_range as $date ) {

			$weeks[ $weekNumber ][] = $date->format( 'Y-m-d' );

			// Weekly
			if ( $date->format( 'w' ) == $start_day ) {
				$weekNumber ++;
			}
		}

		$ranges = array_map(
			function ( $week ) {
					return array(
						'start_week' => array_shift( $week ),
						'end_week'   => array_pop( $week ),
					);
			},
			$weeks
		);

		return $dates;

	} //get_order_dates

	/**
	 *  Get the vendors products by id only
	 *
	 * @since    1.0.0
	 *
	 * @param     int $vendor_id vendor id for store id
	 *
	 * @return     array        $product_ids  All the vendors product ids, no matter their post status
	 */
	public static function get_products_by_id( $vendor_id, $args = array() ) {

		$args = wp_parse_args(
			$args,
			array(
				'numberposts' => - 1,
				'post_type'   => 'product',
				'author'      => $vendor_id,
				'post_status' => 'any',
			)
		);

		$args = apply_filters( 'wcv_get_vendor_products_by_id_args', $args );

		$products    = get_posts( $args );
		$product_ids = wp_list_pluck( $products, 'ID' );

		return $product_ids;

	} //get_products_by_id()

	/**
	 *  Get the vendors products
	 *
	 * @since    1.0.0
	 *
	 * @param     int    $vendor_id vendor id for store id
	 * @param     string $meta_key  user meta key
	 *
	 * @return     array        $products  All vendors products in array of product objects
	 */
	public static function get_products( $vendor_id ) {

		$all_product_ids = self::get_products_by_id( $vendor_id );

		$products = array();

		foreach ( $all_product_ids as $product_id ) {

			$products[] = new WC_Product( $product_id );

		}

		return $products;

	} //get_products()

	/**
	 *  Get the vendor id from the object id parsed
	 *
	 * @since    1.0.0
	 *
	 * @param     int $object_id search for the object id
	 *
	 * @return   int        $vendor_id  author of the product
	 */
	public static function get_vendor_from_object( $object_id ) {
		// Make sure we are returning an author for products or product variations only or shop coupon
		if ( 'product' === get_post_type( $object_id ) || 'product_variation' === get_post_type( $object_id ) || 'shop_coupon' === get_post_type( $object_id ) ) {
			$object = get_post( $object_id );
			$author = $object ? $object->post_author : 1;
		} else {
			$author = - 1;
		}

		return $author;
	} //get_vendor_from_object()

	/**
	 *  Save the pending vendor
	 *
	 * @since    1.0.0
	 *
	 * @param     int $vendor_id the new vendor id
	 */
	public static function save_pending_vendor( $vendor_id ) {

		// Stop admins from registering as vendor
		if ( user_can( get_current_user_id(), 'manage_options' ) ) {
			wc_add_notice( sprintf( __( 'The %1$s Dashboard is only visible to %2$s. Due to WordPress capabilities and its limitations, Administrators can not view it. You should create a test %3$s user account, with the role %4$s, and use that account to view and experience the %5$s Dashboard. ', 'wcvendors-pro' ), wcv_get_vendor_name(), wcv_get_vendor_name( false, false ), wcv_get_vendor_name( true, false ), wcv_get_vendor_name(), wcv_get_vendor_name() ), 'error' );
			wp_safe_redirect( get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ) );
			exit;
		}

		$manual = wc_string_to_bool( get_option( 'wcvendors_vendor_approve_registration', 'no' ) );
		$role   = apply_filters( 'wcvendors_pending_role', ( $manual ? 'pending_vendor' : 'vendor' ) );

		$wp_user_object = new WP_User( $vendor_id );
		$wp_user_object->add_role( $role );

		do_action( 'wcv_save_pending_vendor', $vendor_id );

	} //save_pending_vendor()

	/**
	 *  Is the user a pending vendor
	 *
	 * @since    1.0.0
	 *
	 * @param     int $vendor_id the user id to test
	 */
	public static function is_pending_vendor( $user_id ) {

		$user = get_userdata( $user_id );

		if ( is_object( $user ) ) {
			$is_pending_vendor = is_array( $user->roles ) ? in_array( 'pending_vendor', $user->roles ) : false;
		} else {
			$is_pending_vendor = false;
		}

		return apply_filters( 'wcv_is_pending_vendor', $is_pending_vendor, $user_id );

	} //is_pending_vendor()

	/**
	 *  Get the vendors store url
	 *
	 * @since    1.0.0
	 *
	 * @param     int $vendor_id the user id to test
	 */
	public static function get_vendor_store_url( $vendor_id ) {

		$vendor_store_url = WCV_Vendors::get_vendor_shop_page( $vendor_id );

		return apply_filters( 'wcv_vendor_store_url', $vendor_store_url, $vendor_id );

	} //get_vendor_store_url()

	/**
	 *  Redirect the applicant to the pro dashboard
	 *
	 * @since    1.0.0
	 *
	 * @param     int $user_id the user id returned from the registration
	 *
	 * @todo     Fix how the loading happens so that we don't have to change the role of the vendor before firing.
	 */
	public function apply_vendor_redirect( $user_id ) {

		// If apply for vendor is selected, redirect to the pro dash board
		if ( isset( $_POST['apply_for_vendor'] ) ) {
			add_filter( 'woocommerce_registration_redirect', array( $this, 'redirect_to_pro_dashboard' ), 11 );
		}

	} //apply_vendor_redirect()

	/**
	 *  Output the pro dashboard
	 *
	 * @since    1.0.0
	 *
	 * @param     int $user_id the user id returned from the registration
	 */
	public function redirect_to_pro_dashboard( $redirect ) {

		$terms = isset( $_POST['agree_to_terms'] ) ? $_POST['agree_to_terms'] : '';

		$dashboard_url = WCVendors_Pro_Dashboard::get_dashboard_page_url() . '?terms=' . $terms;

		return apply_filters( 'wcv_vendor_signup_redirect', $dashboard_url );

	} // redirect_to_pro_dashboard()

	/**
	 *  Process the store settings submission from the front end, this applies to vendor dashboard and vendor application.
	 *
	 * @since    1.2.0
	 */
	public function process_submit() {

		if ( ! isset( $_POST['_wcv-save_store_settings'] ) || ! wp_verify_nonce( $_POST['_wcv-save_store_settings'], 'wcv-save_store_settings' ) || ! is_user_logged_in() ) {
			return;
		}

		$vendor_status = '';
		$notice_text   = '';
		$vendor_id     = get_current_user_id();

		$this->allow_markup = get_option( 'wcvendors_allow_form_markup' );

		$settings_store = ( isset( $_POST['_wcv_vendor_application_id'] ) ) ? (array) get_option( 'wcvendors_hide_signup_store' ) : (array) get_option( 'wcvendors_hide_settings_store' );

		// Check if the Shop name is unique
		$users = get_users(
			array(
				'meta_key'   => 'pv_shop_slug',
				'meta_value' => sanitize_title( $_POST['_wcv_store_name'] ),
			)
		);

		if ( ! empty( $users ) && $users[0]->ID != $vendor_id ) {
			wc_add_notice( __( 'That store name is already taken. Your store name must be unique. <br /> Settings have not been saved.', 'wcvendors-pro' ), 'error' );

			return;
		}

		wc_add_notice( __( 'Store Settings Saved', 'wcvendors-pro' ), 'success' );

		// Maybe server side validation
		$paypal_address       = ( isset( $_POST['_wcv_paypal_address'] ) ) ? sanitize_email( $_POST['_wcv_paypal_address'] ) : '';
		$store_name           = ( isset( $_POST['_wcv_store_name'] ) ) ? sanitize_text_field( trim( $_POST['_wcv_store_name'] ) ) : '';
		$store_phone          = ( isset( $_POST['_wcv_store_phone'] ) ) ? sanitize_text_field( trim( $_POST['_wcv_store_phone'] ) ) : '';
		$seller_info          = ( isset( $_POST['pv_seller_info'] ) ) ? trim( $_POST['pv_seller_info'] ) : '';
		$store_description    = ( isset( $_POST['pv_shop_description'] ) ) ? trim( $_POST['pv_shop_description'] ) : '';
		$store_banner_id      = ( isset( $_POST['_wcv_store_banner_id'] ) ) ? sanitize_text_field( $_POST['_wcv_store_banner_id'] ) : '';
		$store_icon_id        = ( isset( $_POST['_wcv_store_icon_id'] ) ) ? sanitize_text_field( $_POST['_wcv_store_icon_id'] ) : '';
		$twitter_username     = ( isset( $_POST['_wcv_twitter_username'] ) ) ? sanitize_text_field( $_POST['_wcv_twitter_username'] ) : '';
		$instagram_username   = ( isset( $_POST['_wcv_instagram_username'] ) ) ? sanitize_text_field( $_POST['_wcv_instagram_username'] ) : '';
		$facebook_url         = ( isset( $_POST['_wcv_facebook_url'] ) ) ? sanitize_text_field( $_POST['_wcv_facebook_url'] ) : '';
		$linkedin_url         = ( isset( $_POST['_wcv_linkedin_url'] ) ) ? sanitize_text_field( $_POST['_wcv_linkedin_url'] ) : '';
		$youtube_url          = ( isset( $_POST['_wcv_youtube_url'] ) ) ? sanitize_text_field( $_POST['_wcv_youtube_url'] ) : '';
		$pinterest_url        = ( isset( $_POST['_wcv_pinterest_url'] ) ) ? sanitize_text_field( $_POST['_wcv_pinterest_url'] ) : '';
		$googleplus_url       = ( isset( $_POST['_wcv_googleplus_url'] ) ) ? sanitize_text_field( $_POST['_wcv_googleplus_url'] ) : '';
		$snapchat_username    = ( isset( $_POST['_wcv_snapchat_username'] ) ) ? sanitize_text_field( $_POST['_wcv_snapchat_username'] ) : '';
		$address1             = ( isset( $_POST['_wcv_store_address1'] ) ) ? sanitize_text_field( $_POST['_wcv_store_address1'] ) : '';
		$latitude             = ( isset( $_POST['wcv_address_latitude'] ) ) ? sanitize_text_field( $_POST['wcv_address_latitude'] ) : '';
		$longitude            = ( isset( $_POST['wcv_address_longitude'] ) ) ? sanitize_text_field( $_POST['wcv_address_longitude'] ) : '';
		$address2             = ( isset( $_POST['_wcv_store_address2'] ) ) ? sanitize_text_field( $_POST['_wcv_store_address2'] ) : '';
		$city                 = ( isset( $_POST['_wcv_store_city'] ) ) ? sanitize_text_field( $_POST['_wcv_store_city'] ) : '';
		$state                = ( isset( $_POST['_wcv_store_state'] ) ) ? sanitize_text_field( $_POST['_wcv_store_state'] ) : '';
		$country              = ( isset( $_POST['_wcv_store_country'] ) ) ? sanitize_text_field( $_POST['_wcv_store_country'] ) : '';
		$postcode             = ( isset( $_POST['_wcv_store_postcode'] ) ) ? sanitize_text_field( $_POST['_wcv_store_postcode'] ) : '';
		$company_url          = ( isset( $_POST['_wcv_company_url'] ) ) ? sanitize_text_field( $_POST['_wcv_company_url'] ) : '';
		$vacation_mode        = ( isset( $_POST['_wcv_vacation_mode'] ) ) ? sanitize_text_field( $_POST['_wcv_vacation_mode'] ) : '';
		$disable_cart         = ( isset( $_POST['_wcv_vacation_disable_cart'] ) ) ? sanitize_text_field( $_POST['_wcv_vacation_disable_cart'] ) : '';
		$vacation_msg         = ( isset( $_POST['_wcv_vacation_mode_msg'] ) ) ? wp_kses_post( $_POST['_wcv_vacation_mode_msg'] ) : '';
		$show_total_sales     = ( isset( $_POST['_wcv_show_product_total_sales'] ) ) ? wp_kses_post( $_POST['_wcv_show_product_total_sales'] ) : '';
		$product_sales_label  = ( isset( $_POST['_wcv_product_total_sales_label'] ) ) ? wp_kses_post( $_POST['_wcv_product_total_sales_label'] ) : '';
		$store_sales_label    = ( isset( $_POST['_wcv_store_total_sales_label'] ) ) ? wp_kses_post( $_POST['_wcv_store_total_sales_label'] ) : '';
		$enable_store_notice  = ( isset( $_POST['_wcv_vendor_enable_store_notice'] ) ) ? sanitize_text_field( $_POST['_wcv_vendor_enable_store_notice'] ) : '';
		$vendor_store_notice  = ( isset( $_POST['_wcv_vendor_store_notice'] ) ) ? wp_kses_post( $_POST['_wcv_vendor_store_notice'] ) : '';
		$enable_opening_hours = ( isset( $_POST['_wcv_enable_opening_hours'] ) ) ? 'yes' : '';
		$enable_local_pickup  = ( isset( $_POST['_wcv_local_pickup_enabled'] ) ) ? 'yes' : '';

		$shipping_type                      = ( isset( $_POST['_wcv_shipping_type'] ) ) ? sanitize_text_field( $_POST['_wcv_shipping_type'] ): '';
		$shipping_fee_national              = ( isset( $_POST['_wcv_shipping_fee_national'] ) ) ? wc_format_decimal( $_POST['_wcv_shipping_fee_national'] ) : '';
		$shipping_fee_international         = ( isset( $_POST['_wcv_shipping_fee_international'] ) ) ? wc_format_decimal( $_POST['_wcv_shipping_fee_international'] ) : '';
		$shipping_fee_national_qty          = ( isset( $_POST['_wcv_shipping_fee_national_qty'] ) ) ? 'yes' : '';
		$shipping_fee_international_qty     = ( isset( $_POST['_wcv_shipping_fee_international_qty'] ) ) ? 'yes' : '';
		$shipping_fee_national_free         = ( isset( $_POST['_wcv_shipping_fee_national_free'] ) ) ? 'yes' : '';
		$shipping_fee_international_free    = ( isset( $_POST['_wcv_shipping_fee_international_free'] ) ) ? 'yes' : '';
		$shipping_fee_national_disable      = ( isset( $_POST['_wcv_shipping_fee_national_disable'] ) ) ? 'yes' : '';
		$shipping_fee_international_disable = ( isset( $_POST['_wcv_shipping_fee_international_disable'] ) ) ? 'yes' : '';
		$product_handling_fee               = ( isset( $_POST['_wcv_shipping_product_handling_fee'] ) ) ? sanitize_text_field( $_POST['_wcv_shipping_product_handling_fee'] ) : '';
		$max_charge                         = ( isset( $_POST['_wcv_shipping_max_charge'] ) ) ? wc_format_decimal( $_POST['_wcv_shipping_max_charge'] ) : '';
		$min_charge                         = ( isset( $_POST['_wcv_shipping_min_charge'] ) ) ? wc_format_decimal( $_POST['_wcv_shipping_min_charge'] ) : '';
		$free_shipping_order                = ( isset( $_POST['_wcv_shipping_free_shipping_order'] ) ) ? wc_format_decimal( $_POST['_wcv_shipping_free_shipping_order'] ) : '';
		$free_shipping_product              = ( isset( $_POST['_wcv_shipping_free_shipping_product'] ) ) ? wc_format_decimal( $_POST['_wcv_shipping_free_shipping_product'] ) : '';
		$max_charge_product                 = ( isset( $_POST['_wcv_shipping_max_charge_product'] ) ) ? wc_format_decimal( $_POST['_wcv_shipping_max_charge_product'] ) : '';
		$shipping_policy                    = ( isset( $_POST['_wcv_shipping_policy'] ) ) ? wp_kses_post( $_POST['_wcv_shipping_policy'] ) : '';
		$return_policy                      = ( isset( $_POST['_wcv_shipping_return_policy'] ) ) ? wp_kses_post( $_POST['_wcv_shipping_return_policy'] ) : '';
		$shipping_from                      = ( isset( $_POST['_wcv_shipping_from'] ) ) ? sanitize_text_field( $_POST['_wcv_shipping_from'] ) : '';
		$shipping_address1                  = ( isset( $_POST['_wcv_shipping_address1'] ) ) ? sanitize_text_field( $_POST['_wcv_shipping_address1'] ) : '';
		$shipping_address2                  = ( isset( $_POST['_wcv_shipping_address2'] ) ) ? sanitize_text_field( $_POST['_wcv_shipping_address2'] ) : '';
		$shipping_city                      = ( isset( $_POST['_wcv_shipping_city'] ) ) ? sanitize_text_field( $_POST['_wcv_shipping_city'] ) : '';
		$shipping_state                     = ( isset( $_POST['_wcv_shipping_state'] ) ) ? sanitize_text_field( $_POST['_wcv_shipping_state'] ) : '';
		$shipping_country                   = ( isset( $_POST['_wcv_shipping_country'] ) ) ? sanitize_text_field( $_POST['_wcv_shipping_country'] ) : '';
		$shipping_postcode                  = ( isset( $_POST['_wcv_shipping_postcode'] ) ) ? sanitize_text_field( $_POST['_wcv_shipping_postcode'] ) : '';

		// Bank fields
		$wcv_bank_account_name   = ( isset( $_POST['wcv_bank_account_name'] ) ) ? sanitize_text_field( $_POST['wcv_bank_account_name'] ) : '';
		$wcv_bank_account_number = ( isset( $_POST['wcv_bank_account_number'] ) ) ? sanitize_text_field( $_POST['wcv_bank_account_number'] ) : '';
		$wcv_bank_name           = ( isset( $_POST['wcv_bank_name'] ) ) ? sanitize_text_field( $_POST['wcv_bank_name'] ) : '';
		$wcv_bank_routing_number = ( isset( $_POST['wcv_bank_routing_number'] ) ) ? sanitize_text_field( $_POST['wcv_bank_routing_number'] ) : '';
		$wcv_bank_iban           = ( isset( $_POST['wcv_bank_iban'] ) ) ? sanitize_text_field( $_POST['wcv_bank_iban'] ) : '';
		$wcv_bank_bic_swift      = ( isset( $_POST['wcv_bank_bic_swift'] ) ) ? sanitize_text_field( $_POST['wcv_bank_bic_swift'] ) : '';
        // Custom
        $wcv_bank_bsb = ( isset( $_POST['wcv_bank_bsb'] ) ) ? sanitize_text_field( $_POST['wcv_bank_bsb'] ) : '';

	 //Integration Jomel

	 $integration_option   = ( isset( $_POST['integration_option'] ) ) ? sanitize_text_field( $_POST['integration_option'] ) : '';
	 $shopifyurl   = ( isset( $_POST['shopifyurl'] ) ) ? sanitize_text_field( $_POST['shopifyurl'] ) : '';
	 $api_key   = ( isset( $_POST['api_key'] ) ) ? sanitize_text_field( $_POST['api_key'] ) : '';
	 $shopify_password   = ( isset( $_POST['shopify_password'] ) ) ? sanitize_text_field( $_POST['shopify_password'] ) : '';
	 $api_path  = ( isset( $_POST['api_path'] ) ) ? sanitize_text_field( $_POST['api_path'] ) : '';
	 $access_token   = ( isset( $_POST['access_token'] ) ) ? sanitize_text_field( $_POST['access_token'] ) : '';
	 $client_id   = ( isset( $_POST['client_id'] ) ) ? sanitize_text_field( $_POST['client_id'] ) : '';
	 $merchant_id   = ( isset( $_POST['merchant_id'] ) ) ? sanitize_text_field( $_POST['merchant_id'] ) : '';


		// SEO fields
		$wcv_seo_title               = ( isset( $_POST['wcv_seo_title'] ) ) ? sanitize_text_field( $_POST['wcv_seo_title'] ) : '';
		$wcv_seo_meta_description    = ( isset( $_POST['wcv_seo_meta_description'] ) ) ? sanitize_text_field( $_POST['wcv_seo_meta_description'] ) : '';
		$wcv_seo_meta_keywords       = ( isset( $_POST['wcv_seo_meta_keywords'] ) ) ? sanitize_text_field( $_POST['wcv_seo_meta_keywords'] ) : '';
		$wcv_seo_fb_title            = ( isset( $_POST['wcv_seo_fb_title'] ) ) ? sanitize_text_field( $_POST['wcv_seo_fb_title'] ) : '';
		$wcv_seo_fb_description      = ( isset( $_POST['wcv_seo_fb_description'] ) ) ? sanitize_text_field( $_POST['wcv_seo_fb_description'] ) : '';
		$wcv_seo_fb_image_id         = ( isset( $_POST['wcv_seo_fb_image_id'] ) ) ? sanitize_text_field( $_POST['wcv_seo_fb_image_id'] ) : '';
		$wcv_seo_twitter_title       = ( isset( $_POST['wcv_seo_twitter_title'] ) ) ? sanitize_text_field( $_POST['wcv_seo_twitter_title'] ) : '';
		$wcv_seo_twitter_description = ( isset( $_POST['wcv_seo_twitter_description'] ) ) ? sanitize_text_field( $_POST['wcv_seo_twitter_description'] ) : '';
		$wcv_seo_twitter_image_id    = ( isset( $_POST['wcv_seo_twitter_image_id'] ) ) ? sanitize_text_field( $_POST['wcv_seo_twitter_image_id'] ) : '';

		// Policies
		$privacy_policy   = ( isset( $_POST['wcv_policy_privacy'] ) ) ? wp_kses_post( $_POST['wcv_policy_privacy'] ) : '';
		$terms_conditions = ( isset( $_POST['wcv_policy_terms'] ) ) ? wp_kses_post( $_POST['wcv_policy_terms'] ) : '';

		// Contact
        $title = ( isset( $_POST['_wcv_owner_title'] ) ) ? sanitize_text_field( $_POST['_wcv_owner_title'] ) : '';
        $first_name = ( isset( $_POST['firstname'] ) ) ? sanitize_text_field( $_POST['firstname'] ) : '';
        $last_name = ( isset( $_POST['lastname'] ) ) ? sanitize_text_field( $_POST['lastname'] ) : '';
        $reg_num = ( isset( $_POST['business_reg_num'] ) ) ? sanitize_text_field( $_POST['business_reg_num'] ) : '';
        $abn = ( isset( $_POST['business_abn'] ) ) ? sanitize_text_field( $_POST['business_abn'] ) : '';
        $tin = ( isset( $_POST['business_tin'] ) ) ? sanitize_text_field( $_POST['business_tin'] ) : '';
        $return_date = ( isset( $_POST['return_date'] ) ) ? sanitize_text_field( $_POST['return_date'] ) : '';

		// Opening Hours
		if ( wc_string_to_bool( $enable_opening_hours ) ) {

			update_user_meta( $vendor_id, '_wcv_enable_opening_hours', $enable_opening_hours );

			if ( isset( $_POST['days'] ) && is_array( $_POST['days'] ) ) {
				$opening_hours = array();

				for ( $i = 0; $i < count( $_POST['days'] ); $i ++ ) {
					$status          = ! empty( $_POST['status'][ $i ] ) ? $_POST['status'][ $i ] : 0;
					$opening_hours[] = array(
						'day'    => esc_attr( $_POST['days'][ $i ] ),
						'open'   => esc_attr( $_POST['open'][ $i ] ),
						'close'  => esc_attr( $_POST['close'][ $i ] ),
						'status' => esc_attr( $status ),
					);
				}

				update_user_meta( $vendor_id, 'wcv_store_opening_hours', $opening_hours );
			} else {
				delete_user_meta( $vendor_id, 'wcv_store_opening_hours' );
			}
		} else {
			delete_user_meta( $vendor_id, '_wcv_enable_opening_hours' );
			delete_user_meta( $vendor_id, 'wcv_store_opening_hours' );
		}

		// Save free user meta
		update_user_meta( $vendor_id, 'pv_paypal', $paypal_address );
		update_user_meta( $vendor_id, 'pv_shop_name', $store_name );
		update_user_meta( $vendor_id, 'pv_shop_slug', sanitize_title( $store_name ) );

		// Bank details
		update_user_meta( $vendor_id, 'wcv_bank_account_name', $wcv_bank_account_name );
		update_user_meta( $vendor_id, 'wcv_bank_account_number', $wcv_bank_account_number );
		update_user_meta( $vendor_id, 'wcv_bank_name', $wcv_bank_name );
		update_user_meta( $vendor_id, 'wcv_bank_routing_number', $wcv_bank_routing_number );
		update_user_meta( $vendor_id, 'wcv_bank_iban', $wcv_bank_iban );
		update_user_meta( $vendor_id, 'wcv_bank_bic_swift', $wcv_bank_bic_swift );
		update_user_meta( $vendor_id, 'wcv_bank_bsb', $wcv_bank_bsb );

		// Integration Jomel

		update_user_meta( $vendor_id, 'integration_option', $integration_option );
		update_user_meta( $vendor_id, 'shopifyurl', $shopifyurl );
		update_user_meta( $vendor_id, 'api_key', $api_key );
		update_user_meta( $vendor_id, 'shopify_password', $shopify_password );
		update_user_meta( $vendor_id, 'api_path', $api_path );
		update_user_meta( $vendor_id, 'access_token', $access_token );
		update_user_meta( $vendor_id, 'client_id', $client_id );
		update_user_meta( $vendor_id, 'merchant_id', $merchant_id );
		// SEO
		update_user_meta( $vendor_id, 'wcv_seo_title', $wcv_seo_title );
		update_user_meta( $vendor_id, 'wcv_seo_meta_description', $wcv_seo_meta_description );
		update_user_meta( $vendor_id, 'wcv_seo_meta_keywords', $wcv_seo_meta_keywords );
		update_user_meta( $vendor_id, 'wcv_seo_fb_title', $wcv_seo_fb_title );
		update_user_meta( $vendor_id, 'wcv_seo_fb_description', $wcv_seo_fb_description );
		update_user_meta( $vendor_id, 'wcv_seo_fb_image_id', $wcv_seo_fb_image_id );
		update_user_meta( $vendor_id, 'wcv_seo_twitter_title', $wcv_seo_twitter_title );
		update_user_meta( $vendor_id, 'wcv_seo_twitter_description', $wcv_seo_twitter_description );
		update_user_meta( $vendor_id, 'wcv_seo_twitter_image_id', $wcv_seo_twitter_image_id );

		// Contact
		update_user_meta( $vendor_id, 'wcv_owner_title', $title );
		update_user_meta( $vendor_id, 'firstname', $first_name );
		update_user_meta( $vendor_id, 'last_name', $last_name );
		update_user_meta( $vendor_id, 'business_reg_num', $reg_num );
		update_user_meta( $vendor_id, 'business_abn', $abn );
		update_user_meta( $vendor_id, 'business_tin', $tin );
		update_user_meta( $vendor_id, 'return_date', $return_date );

		// Store description
		if ( isset( $store_description ) && '' !== $store_description ) {
			update_user_meta( $vendor_id, 'pv_shop_description', $this->allow_markup ? $store_description : wp_strip_all_tags( $store_description ) );
		} else {
			delete_user_meta( $vendor_id, 'pv_shop_description' );
		}

		// Seller info
		if ( isset( $seller_info ) && '' !== $seller_info ) {
			update_user_meta( $vendor_id, 'pv_seller_info', $this->allow_markup ? $seller_info : wp_strip_all_tags( $seller_info ) );
		} else {
			delete_user_meta( $vendor_id, 'pv_seller_info' );
		}

		// Store Banner
		if ( isset( $store_banner_id ) && '' !== $store_banner_id ) {
			update_user_meta( $vendor_id, '_wcv_store_banner_id', (int) $store_banner_id );
		} else {
			delete_user_meta( $vendor_id, '_wcv_store_banner_id' );
		}

		// Store Icon
		if ( isset( $store_icon_id ) && '' !== $store_icon_id ) {
			update_user_meta( $vendor_id, '_wcv_store_icon_id', $store_icon_id );
		} else {
			delete_user_meta( $vendor_id, '_wcv_store_icon_id' );
		}

		// Company URL
		if ( isset( $company_url ) && '' !== $company_url ) {
			update_user_meta( $vendor_id, '_wcv_company_url', $company_url );
		} else {
			delete_user_meta( $vendor_id, '_wcv_company_url' );
		}

		// Store Address1
		if ( isset( $address1 ) && '' !== $address1 ) {
			update_user_meta( $vendor_id, '_wcv_store_address1', $address1 );
		} else {
			delete_user_meta( $vendor_id, '_wcv_store_address1' );
		}

		// Store Address Latitude
		if ( isset( $latitude ) && '' != $latitude ) {
			update_user_meta( $vendor_id, 'wcv_address_latitude', $latitude );
		} else {
			delete_user_meta( $vendor_id, 'wcv_address_latitude' );
		}

		// Store Address Longitude
		if ( isset( $longitude ) && '' != $longitude ) {
			update_user_meta( $vendor_id, 'wcv_address_longitude', $longitude );
		} else {
			delete_user_meta( $vendor_id, 'wcv_address_longitude' );
		}

		// Store Address2
		if ( isset( $address2 ) && '' !== $address2 ) {
			update_user_meta( $vendor_id, '_wcv_store_address2', $address2 );
		} else {
			delete_user_meta( $vendor_id, '_wcv_store_address2' );
		}
		// Store City
		if ( isset( $city ) && '' !== $city ) {
			update_user_meta( $vendor_id, '_wcv_store_city', $city );
		} else {
			delete_user_meta( $vendor_id, '_wcv_store_city' );
		}
		// Store State
		if ( isset( $state ) && '' !== $state ) {
			update_user_meta( $vendor_id, '_wcv_store_state', $state );
		} else {
			delete_user_meta( $vendor_id, '_wcv_store_state' );
		}
		// Store Country
		if ( isset( $country ) && '' !== $country ) {
			update_user_meta( $vendor_id, '_wcv_store_country', $country );
		} else {
			delete_user_meta( $vendor_id, '_wcv_store_country' );
		}
		// Store post code
		if ( isset( $postcode ) && '' !== $postcode ) {
			update_user_meta( $vendor_id, '_wcv_store_postcode', $postcode );
		} else {
			delete_user_meta( $vendor_id, '_wcv_store_postcode' );
		}
		// Store Phone
		if ( isset( $store_phone ) && '' !== $store_phone ) {
			update_user_meta( $vendor_id, '_wcv_store_phone', $store_phone );
		} else {
			delete_user_meta( $vendor_id, '_wcv_store_phone' );
		}

		// Vacation Message
		if ( isset( $vacation_mode ) ) {
			update_user_meta( $vendor_id, '_wcv_vacation_mode', $vacation_mode );
			update_user_meta( $vendor_id, '_wcv_vacation_disable_cart', $disable_cart );
			update_user_meta( $vendor_id, '_wcv_vacation_mode_msg', $vacation_msg );
		} else {
			delete_user_meta( $vendor_id, '_wcv_vacation_mode' );
			delete_user_meta( $vendor_id, '_wcv_vacation_disable_cart' );
			delete_user_meta( $vendor_id, '_wcv_vacation_mode_msg' );
		}

		// Vendor store notice
		if ( isset( $enable_store_notice ) && '' != $enable_store_notice ) {
			update_user_meta( $vendor_id, '_wcv_vendor_enable_store_notice', $enable_store_notice );
		} else {
			delete_user_meta( $vendor_id, '_wcv_vendor_enable_store_notice' );
		}

		if ( isset( $vendor_store_notice ) && '' != $vendor_store_notice ) {
			update_user_meta( $vendor_id, '_wcv_vendor_store_notice', $vendor_store_notice );
		} else {
			delete_user_meta( $vendor_id, '_wcv_vendor_store_notice' );
		}


		// Vendor store notice
		if ( isset( $enable_local_pickup ) && '' != $enable_local_pickup ) {
			update_user_meta( $vendor_id, '_wcv_local_pickup_enabled', $enable_local_pickup );
		} else {
			delete_user_meta( $vendor_id, '_wcv_local_pickup_enabled' );
		}

		// Twitter Username
		if ( isset( $twitter_username ) && '' !== $twitter_username ) {
			update_user_meta( $vendor_id, '_wcv_twitter_username', $twitter_username );
		} else {
			delete_user_meta( $vendor_id, '_wcv_twitter_username' );
		}

		// Instagram Username
		if ( isset( $instagram_username ) && '' !== $instagram_username ) {
			update_user_meta( $vendor_id, '_wcv_instagram_username', $instagram_username );
		} else {
			delete_user_meta( $vendor_id, '_wcv_instagram_username' );
		}

		// Facebook URL
		if ( isset( $facebook_url ) && '' !== $facebook_url ) {
			update_user_meta( $vendor_id, '_wcv_facebook_url', $facebook_url );
		} else {
			delete_user_meta( $vendor_id, '_wcv_facebook_url' );
		}

		// LinkedIn URL
		if ( isset( $linkedin_url ) && '' !== $linkedin_url ) {
			update_user_meta( $vendor_id, '_wcv_linkedin_url', $linkedin_url );
		} else {
			delete_user_meta( $vendor_id, '_wcv_linkedin_url' );
		}

		// YouTube URL
		if ( isset( $youtube_url ) && '' !== $youtube_url ) {
			update_user_meta( $vendor_id, '_wcv_youtube_url', $youtube_url );
		} else {
			delete_user_meta( $vendor_id, '_wcv_youtube_url' );
		}

		// Pinterest URL
		if ( isset( $pinterest_url ) && '' !== $pinterest_url ) {
			update_user_meta( $vendor_id, '_wcv_pinterest_url', $pinterest_url );
		} else {
			delete_user_meta( $vendor_id, '_wcv_pinterest_url' );
		}

		// Google+ URL
		if ( isset( $googleplus_url ) && '' !== $googleplus_url ) {
			update_user_meta( $vendor_id, '_wcv_googleplus_url', $googleplus_url );
		} else {
			delete_user_meta( $vendor_id, '_wcv_googleplus_url' );
		}

		// Snapchat Username
		if ( isset( $snapchat_username ) && '' !== $snapchat_username ) {
			update_user_meta( $vendor_id, '_wcv_snapchat_username', $snapchat_username );
		} else {
			delete_user_meta( $vendor_id, '_wcv_snapchat_username' );
		}

		// Polices
		if ( isset( $privacy_policy ) ) {
			update_user_meta( $vendor_id, 'wcv_policy_privacy', $privacy_policy );
		} else {
			delete_user_meta( $vendor_id, 'wcv_policy_privacy' );
		}

		if ( isset( $terms_conditions ) ) {
			update_user_meta( $vendor_id, 'wcv_policy_terms', $terms_conditions );
		} else {
			delete_user_meta( $vendor_id, 'wcv_policy_terms' );
		}

		// Shipping
		if ( isset( $shipping_type ) && '' !== $shipping_type ) {
			update_user_meta( $vendor_id, '_wcv_shipping_type', $shipping_type );
		} else {
			delete_user_meta( $vendor_id, '_wcv_shipping_type' );
		}

		$wcvendors_shipping = array(
			'national'                   => $shipping_fee_national,
			'national_qty_override'      => $shipping_fee_national_qty,
			'national_free'              => $shipping_fee_national_free,
			'national_disable'           => $shipping_fee_national_disable,
			'international'              => $shipping_fee_international,
			'international_qty_override' => $shipping_fee_international_qty,
			'international_free'         => $shipping_fee_international_free,
			'international_disable'      => $shipping_fee_international_disable,
			'product_handling_fee'       => $product_handling_fee,
			'max_charge'                 => $max_charge,
			'min_charge'                 => $min_charge,
			'free_shipping_order'        => $free_shipping_order,
			'free_shipping_product'      => $free_shipping_product,
			'max_charge_product'         => $max_charge_product,
			'shipping_policy'            => $shipping_policy,
			'return_policy'              => $return_policy,
			'shipping_from'              => $shipping_from,
			'shipping_address'           => '',
		);

		$shipping_address = array(
			'address1' => $shipping_address1,
			'address2' => $shipping_address2,
			'city'     => $shipping_city,
			'state'    => $shipping_state,
			'country'  => $shipping_country,
			'postcode' => $shipping_postcode,
		);

		$wcvendors_shipping['shipping_address'] = $shipping_address;

		update_user_meta( $vendor_id, '_wcv_shipping', $wcvendors_shipping );

		// shipping rates
		$shipping_rates = array();

		if ( isset( $_POST['_wcv_shipping_fees'] ) ) {
			$shipping_countries = isset( $_POST['_wcv_shipping_countries'] ) ? $_POST['_wcv_shipping_countries'] : array();
			$shipping_states    = isset( $_POST['_wcv_shipping_states'] ) ? $_POST['_wcv_shipping_states'] : array();
			$shipping_postcodes = isset( $_POST['_wcv_shipping_postcodes'] ) ? $_POST['_wcv_shipping_postcodes'] : array();
			$shipping_fees      = isset( $_POST['_wcv_shipping_fees'] ) ? $_POST['_wcv_shipping_fees'] : array();
			$shipping_fee_count = sizeof( $shipping_fees );

			for ( $i = 0; $i < $shipping_fee_count; $i ++ ) {

				if ( $shipping_fees[ $i ] != '' ) {
					$country              = wc_clean( $shipping_countries[ $i ] );
					$state                = wc_clean( $shipping_states[ $i ] );
					$postcode             = wc_clean( $shipping_postcodes[ $i ] );
					$qty_override         = isset( $_POST[ '_wcv_shipping_overrides_' . $i ] ) ? 'yes' : 'no';
					$fee                  = wc_format_localized_price( $shipping_fees[ $i ] );
					$shipping_rates[ $i ] = array(
						'country'      => $country,
						'state'        => $state,
						'postcode'     => $postcode,
						'fee'          => $fee,
						'qty_override' => $qty_override,
					);
				}
			}
			update_user_meta( $vendor_id, '_wcv_shipping_rates', $shipping_rates );
		} else {
			delete_user_meta( $vendor_id, '_wcv_shipping_rates' );
		}

		// To be used to allow hidden custom meta keys
		$wcv_hidden_custom_metas = array_intersect_key( $_POST, array_flip( preg_grep( '/^_wcv_custom_settings_/', array_keys( $_POST ) ) ) );

		if ( ! empty( $wcv_hidden_custom_metas ) ) {

			foreach ( $wcv_hidden_custom_metas as $key => $value ) {
				update_user_meta( $vendor_id, $key, $value );
			}
		}

		// To be used to allow custom meta keys
		$wcv_custom_metas = array_intersect_key( $_POST, array_flip( preg_grep( '/^wcv_custom_settings_/', array_keys( $_POST ) ) ) );

		if ( ! empty( $wcv_custom_metas ) ) {

			foreach ( $wcv_custom_metas as $key => $value ) {
				update_user_meta( $vendor_id, $key, $value );
			}
		}

		// save the pending vendor
		// TODO: If the vendor is denied then need to scrub database of meta's above
		if ( isset( $_POST['_wcv_vendor_application_id'] ) ) {

			$manual = wc_string_to_bool( get_option( 'wcvendors_vendor_approve_registration', 'no' ) );

			self::save_pending_vendor( $vendor_id );
			wc_clear_notices();

			do_action( 'wcv_pro_store_settings_saved', $vendor_id );

			if ( $manual ) {
				$vendor_pending_notice = get_option( 'wcvendors_vendor_pending_notice' );
				wc_add_notice( $vendor_pending_notice, 'success' );
				wp_safe_redirect( apply_filters( 'wcv_register_pending_vendor_url', get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ) ) );
				exit;
			} else {
				$approved_vendor_notice = get_option( 'wcvendors_vendor_approved_notice' );
				wc_add_notice( $approved_vendor_notice, 'success' );
				$dashboard_page_ids = get_option( 'wcvendors_dashboard_page_id' );
				$dashboard_page_id  = reset( $dashboard_page_ids );
				wp_safe_redirect( apply_filters( 'wcv_register_vendor_url', get_permalink( $dashboard_page_id ) ) );
				exit;
			}
		}

		do_action( 'wcv_pro_store_settings_saved', $vendor_id );

	} // process_submit()

	/**
	 *  Hook into the single product page to display the ships from
	 *
	 * @since    1.0.0
	 *
	 * @param     int $product_id the product to hook into
	 */
	public function product_ships_from( $product_id ) {

		global $post, $product;

		$shipping_disabled = wc_string_to_bool( get_option( 'wcvendors_shipping_management_cap', 'no' ) );
		$post              = get_post( $product->get_id() );

		if ( $product->needs_shipping() && ! $shipping_disabled && WCV_Vendors::is_vendor( $post->post_author ) ) {

			$vendor_id = WCV_Vendors::get_vendor_from_product( $product_id );
			$is_vendor = WCV_Vendors::is_vendor( $vendor_id );

			$store_rates = (array) get_user_meta( $vendor_id, '_wcv_shipping', true );

			$store_country = ( $store_rates && array_key_exists( 'shipping_from', $store_rates ) && $store_rates['shipping_from'] == 'other' ) ? strtolower( $store_rates['shipping_address']['country'] ) : strtolower( get_user_meta( $vendor_id, '_wcv_store_country', true ) );
			$countries     = WCVendors_Pro_Form_Helper::countries();

			if ( ! $store_country ) {
				$store_country = WC()->countries->get_base_country();
			}

			$ships_from = apply_filters(
				'wcv_product_ships_from',
				array(
					'store_country' => $countries[ strtoupper( $store_country ) ],
					'wrapper_start' => '<span class="wcvendors_ships_from"><br />',
					'wrapper_end'   => '</span><br />',
					'title'         => __( 'Ships From: ', 'wcvendors-pro' ),
				)
			);

			include apply_filters( 'wcvendors_pro_vendor_product_ships_from_path', 'partials/product/wcvendors-pro-ships-from.php' );
		}

	} // product_ships_from()

	/**
	 *  Hook into the single product page to vendor tools
	 *
	 * @since    1.0.0
	 *
	 * @param     int $product_id the product to hook into
	 */
	public function enable_vendor_tools( $product_id ) {

		global $post, $product;

		if ( get_current_user_id() == $post->post_author && WCV_Vendors::is_vendor( get_current_user_id() ) ) {

			$can_edit       = wc_string_to_bool( get_option( 'wcvendors_capability_products_edit', 'no' ) );
			$disable_delete = wc_string_to_bool( get_option( 'wcvendors_capability_product_delete', 'no' ) );
			$can_duplicate  = wc_string_to_bool( get_option( 'wcvendors_capability_product_duplicate', 'no' ) );
			$tools_label    = apply_filters( 'wcv_product_tools_label', __( 'Tools: ', 'wcvendors-pro' ) );
			$product_id     = $product->get_id();

			$actions = apply_filters(
				'wcv_product_single_actions',
				array(
					'edit'      =>
					apply_filters(
						'wcv_product_single_actions_edit',
						array(
							'label' => __( 'Edit', 'wcvendors-pro' ),
							'class' => '',
							'url'   => WCVendors_Pro_Product_Controller::get_product_edit_link( $product_id ),
						)
					),
					'duplicate' =>
					apply_filters(
						'wcv_product_single_actions_duplicate',
						array(
							'label' => __( 'Duplicate', 'wcvendors-pro' ),
							'class' => '',
							'url'   => WCVendors_Pro_Dashboard::get_dashboard_page_url( 'product/duplicate/' . $product_id ),
						)
					),
					'delete'    =>
					apply_filters(
						'wcv_product_single_actions_delete',
						array(
							'label'  => __( 'Delete', 'wcvendors-pro' ),
							'class'  => 'confirm_delete',
							'custom' => array( 'data-confirm_text' => __( 'Delete product?', 'wcvendors-pro' ) ),
							'url'    => WCVendors_Pro_Dashboard::get_dashboard_page_url( 'product/delete/' . $product_id ),
						)
					),
				)
			);

			// Abide by dashboard permissions
			if ( ! $can_edit ) {
				unset( $actions['edit'] );
			}
			if ( ! $disable_delete ) {
				unset( $actions['delete'] );
			}
			if ( ! $can_duplicate ) {
				unset( $actions['duplicate'] );
			}

			if ( ! empty( $actions ) ) {
				include apply_filters( 'wcvendors_pro_vendor_single_product_tools_path', 'partials/product/wcvendors-pro-single-product-tools.php' );
			}
		}

	} // edit_product_link()

	/**
	 * Output the Pro header on single product page
	 *
	 * @since    1.0.0
	 * @return html
	 */
	public function store_single_header() {

		global $product;

		$post = get_post( $product->get_id() );

		if ( WCV_Vendors::is_vendor_product_page( $post->post_author ) ) {

			$vendor_id   = $post->post_author;
			$vendor_meta = array_map(
				function ( $a ) {
						return $a[0];
				},
				get_user_meta( $vendor_id )
			);

			do_action( 'wcv_before_main_header', $vendor_id );

			wc_get_template(
				wcv_get_store_header_template(),
				array(
					'vendor_id'   => $vendor_id,
					'vendor_meta' => $vendor_meta,
					'product'     => $product,
					'post'        => $post,
				),
				'wc-vendors/store/',
				$this->base_dir . 'templates/store/'
			);

			do_action( 'wcv_after_main_header', $vendor_id );

		}

	}

	/**
	 * Remove the free headers and related headers
	 *
	 * @since    1.2.0
	 */
	public function remove_free_headers() {

		remove_action( 'woocommerce_before_main_content', array( 'WCV_Vendor_Shop', 'vendor_main_header' ), 20 );
		remove_action( 'woocommerce_before_single_product', array( 'WCV_Vendor_Shop', 'vendor_mini_header' ) );
		remove_action( 'woocommerce_before_main_content', array( 'WCV_Vendor_Shop', 'shop_description' ), 30 );

	} //remove_free_headers()

	/**
	 * Add the new pro store header on the main page
	 *
	 * @since    1.2.0
	 */
	public function store_main_content_header() {

		if ( WCV_Vendors::is_vendor_page() ) {

			$vendor_shop = urldecode( get_query_var( 'vendor_shop' ) );
			$vendor_id   = WCV_Vendors::get_vendor_id( $vendor_shop );
			$vendor_meta = array_map(
				function ( $a ) {
						return $a[0];
				},
				get_user_meta( $vendor_id )
			);

			do_action( 'wcv_before_main_header', $vendor_id );

			wc_get_template(
				wcv_get_store_header_template(),
				array(
					'vendor_id'   => $vendor_id,
					'vendor_meta' => $vendor_meta,
				),
				'wc-vendors/store/',
				$this->base_dir . 'templates/store/'
			);

			do_action( 'wcv_after_main_header', $vendor_id );

		}

	} // store_main_content_header()

	/**
	 * Add link to pro dashboard on my account page
	 *
	 * @since    1.2.3
	 */
	public function pro_dashboard_link_myaccount() {

		$user               = get_user_by( 'id', get_current_user_id() );
		$dashboard_page_ids = (array) get_option( 'wcvendors_dashboard_page_id' );
		$dashboard_page_id  = reset( $dashboard_page_ids );
		$dashboard_url      = apply_filters( 'wcv_my_account_dashboard_url', get_permalink( $dashboard_page_id ) );
		$my_account_msg     = apply_filters( 'wcv_my_account_msg', sprintf( __( '<p>To add or edit products, view sales and orders for your %1$s account, or to configure your store, visit your <a href="%2$s">%3$s Dashboard</a>.</p>', 'wcvendors-pro' ), wcv_get_vendor_name( true, false ), $dashboard_url, wcv_get_vendor_name() ) );

		if ( ! WCV_Vendors::is_vendor( $user->ID ) ) {
			return;
		}

		echo sprintf( $my_account_msg, $dashboard_url );

	} // pro_dashboard_link_myaccount ()

	/**
	 *    vendors_with_products - Get vendors with products pubilc or private
	 *
	 * @param array $query
	 */
	public function vendors_with_products( $query ) {

		global $wpdb;

		if ( isset( $query->query_vars['query_id'] ) && 'vendors_with_products' == $query->query_vars['query_id'] ) {
			$query->query_from  = $query->query_from . ' LEFT OUTER JOIN (
	                SELECT post_author, COUNT(*) as post_count
	                FROM ' . $wpdb->prefix . 'posts
	                WHERE post_type = "product" AND (post_status = "publish" OR post_status = "private")
	                GROUP BY post_author
	            ) p ON (' . $wpdb->prefix . 'users.ID = p.post_author)';
			$query->query_where = $query->query_where . ' AND post_count  > 0 ';
		}
	}

	/**
	 * Add a pro vendor list short code
	 *
	 * @since    1.2.3
	 * @version  1.5.6
	 */
	public function vendors_list( $atts ) {

		$html = '';

		extract(
			shortcode_atts(
				array(
					'orderby'       => 'registered',
					'order'         => 'ASC',
					'per_page'      => '12',
					'show_products' => 'yes',
				),
				$atts
			)
		);

		$paged  = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
		$offset = ( $paged - 1 ) * $per_page;

		$vendor_search_args = array();
		$vendor_search_term = isset( $_GET['vendor_search_term'] ) != '' ? esc_attr( $_GET['vendor_search_term'] ) : '';

		if ( ! empty( $vendor_search_term ) ) {

			$vendor_search_args = apply_filters(
				'wcv_vendor_search_args',
				array(
					'order'      => 'ASC',
					'fields'     => 'all',
					'orderby'    => 'display_name',
					'search'     => '*' . $vendor_search_term . '*',
					'meta_query' => array(
						'relation' => 'OR',
						array(
							'meta_key'   => 'pv_shop_name',
							'meta_value' => '*' . $vendor_search_term . '*',
							'compare'    => 'LIKE',
						),
						array(
							'meta_key'   => 'pv_shop_slug',
							'meta_value' => '*' . $vendor_search_term . '*',
							'compare'    => 'LIKE',
						),
					),
				)
			);
		}

		// Hook into the user query to modify the query to return users that have at least one product
		if ( $show_products == 'yes' ) {
			add_action( 'pre_user_query', array( $this, 'vendors_with_products' ) );
		}

		// Get all vendors
		$vendor_total_args = apply_filters(
			'wcv_vendor_total_args',
			array(
				'role'         => 'vendor',
				'meta_key'     => 'pv_shop_slug',
				'meta_value'   => '',
				'meta_compare' => '>',
				'orderby'      => $orderby,
				'order'        => $order,
			)
		);

		if ( ! empty( $vendor_search_args ) ) {
			$vendor_total_args = apply_filters( 'wcv_vendor_total_search_args', array_merge( $vendor_total_args, $vendor_search_args ) );
			unset( $vendor_total_args['meta_key'] );
			unset( $vendor_total_args['meta_value'] );
			unset( $vendor_total_args['meta_compare'] );
		}

		if ( $show_products == 'yes' ) {
			$vendor_total_args['query_id'] = 'vendors_with_products';
		}

		$vendor_query = new WP_User_Query( $vendor_total_args );
		$all_vendors  = $vendor_query->get_results();

		// Get the paged vendors
		$vendor_paged_args = apply_filters(
			'wcv_vendor_paged_args',
			array(
				'role'         => 'vendor',
				'meta_key'     => 'pv_shop_slug',
				'meta_value'   => '',
				'meta_compare' => '>',
				'orderby'      => $orderby,
				'order'        => $order,
				'offset'       => $offset,
				'number'       => $per_page,
			)
		);

		if ( ! empty( $vendor_search_args ) ) {
			$vendor_paged_args = apply_filters( 'wcv_vendor_paged_search_args', array_merge( $vendor_paged_args, $vendor_search_args ) );
			unset( $vendor_paged_args['meta_key'] );
			unset( $vendor_paged_args['meta_value'] );
			unset( $vendor_paged_args['meta_compare'] );
		}

		if ( $show_products == 'yes' ) {
			$vendor_paged_args['query_id'] = 'vendors_with_products';
		}

		$vendor_paged_query = new WP_User_Query( $vendor_paged_args );
		$paged_vendors      = $vendor_paged_query->get_results();

		// Pagination calcs
		$total_vendors       = count( $all_vendors );
		$total_vendors_paged = count( $paged_vendors );
		$total_pages         = ceil( $total_vendors / $per_page );

		ob_start();

		do_action( 'wcv_before_vendorslist' );

		// Loop through all vendors and output a simple link to their vendor pages
		foreach ( $paged_vendors as $vendor ) {

			$vendor_meta = array_map(
				function ( $a ) {
						return $a[0];
				},
				get_user_meta( $vendor->ID )
			);

			wc_get_template(
				'pro-vendor-list.php',
				array(
					'shop_link'   => WCV_Vendors::get_vendor_shop_page( $vendor->ID ),
					'shop_name'   => $vendor->pv_shop_name,
					'vendor_id'   => $vendor->ID,
					'vendor_meta' => $vendor_meta,
				),
				'wc-vendors/front/',
				$this->base_dir . 'templates/front/'
			);

		} // End foreach

		$html .= ob_get_clean();

		if ( $total_vendors > $total_vendors_paged ) {

			$html .= apply_filters( 'wcv_pagination_before', '<nav class="woocommerce-pagination">' );

			$current_page = max( 1, get_query_var( 'paged' ) );

			$html .= paginate_links(
				apply_filters(
					'wcv_pagination_args',
					array(
						'base'      => get_pagenum_link() . '%_%',
						'format'    => 'page/%#%/',
						'current'   => $current_page,
						'total'     => $total_pages,
						'prev_next' => false,
						'type'      => 'list',
					),
					$current_page,
					$total_pages
				)
			);

			$html .= apply_filters( 'wcv_pagination_after', '</nav>' );
		}

		do_action( 'wcv_after_vendorslist' );

		return $html;

	} //vendors_list()

	/**
	 * Add vacation mode message
	 *
	 * @since    1.2.3
	 */
	public function vacation_mode() {

		if ( is_product() ) {

			global $post;

			if ( is_object( $post ) && WCV_Vendors::is_vendor_product_page( $post->post_author ) ) {
				$vendor_id = $post->post_author;
			}
		} else {
			$vendor_shop = urldecode( get_query_var( 'vendor_shop' ) );
			$vendor_id   = WCV_Vendors::get_vendor_id( $vendor_shop );
		}

		if ( isset( $vendor_id ) ) {

			$vacation_mode = get_user_meta( $vendor_id, '_wcv_vacation_mode', true );
			$vacation_msg  = ( $vacation_mode ) ? get_user_meta( $vendor_id, '_wcv_vacation_mode_msg', true ) : '';

			wc_get_template(
				'store-vacation-message.php',
				array(
					'vendor_id'     => $vendor_id,
					'vacation_mode' => $vacation_mode,
					'vacation_msg'  => $vacation_msg,
				),
				'wc-vendors/store/',
				$this->base_dir . 'templates/store/'
			);
		}

	} // vacation_mode()

	/**
	 * Load the store styles only on the vendors list shortcode page
	 *
	 * @since    1.3.1
	 */
	public function wcvendors_list_scripts() {

		global $post;

		if ( is_a( $post, 'WP_Post' ) && has_shortcode( $post->post_content, 'wcv_pro_vendorslist' ) ) {
			wp_enqueue_style( 'wcv-pro-store-style', apply_filters( 'wcv_pro_store_style', $this->base_url . 'assets/css/store' . $this->suffix . '.css' ), false, $this->version );
		}

	} // wcvendors_list_scripts()

	/**
	 * Get vendor categories
	 *
	 * @since  1.4.4.
	 * @access public
	 * @todo   cache results
	 */
	public static function get_categories( $vendor_id ) {

		$vendor_categories = array();

		$vendor_products = get_posts(
			array(
				'author'      => $vendor_id,
				'post_type'   => 'product',
				'numberposts' => - 1,
			)
		);

		foreach ( $vendor_products as $vendor_product ) {

			if ( $terms = get_the_terms( $vendor_product->ID, 'product_cat' ) ) {

				foreach ( $terms as $category ) {

					if ( ! empty( $vendor_categories ) && isset( $vendor_categories[ $category->term_id ] ) ) {

						$vendor_categories[ $category->term_id ]['count'] = $vendor_categories[ $category->term_id ]['count'] + 1;

					} else {

						$vendor_categories[ $category->term_id ]['count'] = 1;
					}

					$vendor_categories[ $category->term_id ]['term'] = $category;
				}
			}
		}

		return $vendor_categories;

	} // get_categories()

	/**
	 * Hook into the pre_get_posts query and set the post author to the current vendor.
	 *
	 * @access public
	 * @since  1.4.4
	 */
	public function vendor_store_search_where( $query ) {

		global $wp_the_query;

		// escape if not vendor store search query
		if ( empty( $wp_the_query->query_vars['wc_query'] ) || empty( $wp_the_query->query_vars['s'] ) || ! isset( $_GET['wcv_vendor_id'] ) ) {
			return;
		}

		if ( array_key_exists( 'post_type', $query->query ) && $query->query['post_type'] === 'product' ) {
			$query->set( 'author', $_GET['wcv_vendor_id'] );
		}

	} // vendor_store_search_where()

	/**
	 * Filter the main loop based on the vendor_category
	 *
	 * @since  1.4.4
	 * @access public
	 */
	public function vendor_store_category_filter( $query ) {

		global $wp_query;

		if ( is_admin() ) {
			return;
		}

		$vendor_category = isset( $_GET['vendor_category'] ) ? $_GET['vendor_category'] : '';

		if ( empty( $wp_query->query_vars['wc_query'] ) || empty( $vendor_category ) ) {
			return;
		}

		if ( $query->is_main_query() ) {

			$vendor_category_query = apply_filters(
				'wcv_vendor_store_category_query',
				array(
					'taxonomy' => 'product_cat',
					'field'    => 'slug',
					'terms'    => $vendor_category,
				)
			);

			$query->set( 'tax_query', array( $vendor_category_query ) );

		}

	} // vendor_store_category_filter()

	/**
	 *
	 *
	 *
	 */
	public function json_unique_store_name() {

		ob_start();

		check_ajax_referer( 'wcv-unique-store-name', 'security' );

		$store_name = (string) wc_clean( stripslashes( $_POST['store_name'] ) );
		$vendor_id  = get_current_user_id();

		if ( empty( $store_name ) ) {
			die();
		}

		// Check if the Shop name is unique
		$users = get_users(
			array(
				'meta_key'   => 'pv_shop_slug',
				'meta_value' => sanitize_title( $store_name ),
			)
		);

		if ( ! empty( $users ) && $users[0]->ID != $vendor_id ) {
			wp_send_json( array( 'error' => __( 'Your store name must be unique', 'wcvendors-pro' ) ) );
		} else {
			wp_send_json( array( 'store_name' => $store_name ) );
		}

	} //json_unique_storename()

	/**
	 * Redirect vendor logins to the specified page
	 *
	 * @since 1.5.0
	 */
	public function vendor_login_redirect( $redirect_to, $user ) {

		$vendor_redirect    = get_option( 'wcvendors_vendor_login_redirect' );
		$dashboard_page_ids = (array) get_option( 'wcvendors_dashboard_page_id' );
		$dashboard_page_id  = reset( $dashboard_page_ids );

		if ( WCV_Vendors::is_vendor( $user->ID ) && 'dashboard' === $vendor_redirect ) {
			$redirect_to = apply_filters( 'wcv_vendor_login_redirect_url', get_permalink( $dashboard_page_id ), $dashboard_page_id, $dashboard_page_ids );
		}

		return $redirect_to;

	} // vendor_login_redirect()

	/**
	 * Store SEO on the store main page
	 *
	 * @since 1.5.0
	 */
	public function storefront_seo() {

		if ( WCV_Vendors::is_vendor_page() ) {

			$vendor_shop = urldecode( get_query_var( 'vendor_shop' ) );
			$vendor_id   = WCV_Vendors::get_vendor_id( $vendor_shop );

			$wcv_seo_title               = get_user_meta( $vendor_id, 'wcv_seo_title', true );
			$wcv_seo_meta_description    = get_user_meta( $vendor_id, 'wcv_seo_meta_description', true );
			$wcv_seo_meta_keywords       = get_user_meta( $vendor_id, 'wcv_seo_meta_keywords', true );
			$wcv_seo_fb_title            = get_user_meta( $vendor_id, 'wcv_seo_fb_title', true );
			$wcv_seo_fb_description      = get_user_meta( $vendor_id, 'wcv_seo_fb_description', true );
			$wcv_seo_fb_image_id         = get_user_meta( $vendor_id, 'wcv_seo_fb_image_id', true );
			$wcv_seo_twitter_title       = get_user_meta( $vendor_id, 'wcv_seo_twitter_title', true );
			$wcv_seo_twitter_description = get_user_meta( $vendor_id, 'wcv_seo_twitter_description', true );
			$wcv_seo_twitter_image_id    = get_user_meta( $vendor_id, 'wcv_seo_twitter_image_id', true );
			$twitter_username            = get_user_meta( $vendor_id, '_wcv_twitter_username', true );

			// Meta Description
			if ( is_string( $wcv_seo_meta_description ) && $wcv_seo_meta_description !== '' ) {
				echo '<meta name="description" content="', esc_attr( wp_strip_all_tags( stripslashes( $wcv_seo_meta_description ) ) ), '"/>', "\n";
			}

			// Meta Keywords
			if ( is_string( $wcv_seo_meta_keywords ) && $wcv_seo_meta_keywords !== '' ) {
				echo '<meta name="keywords" content="', esc_attr( wp_strip_all_tags( stripslashes( $wcv_seo_meta_keywords ) ) ), '"/>', "\n";
			}

			// Facebook OpenGraph
			// FB url
			if ( is_string( $wcv_seo_fb_title ) && $wcv_seo_fb_title !== '' ) {
				echo '<meta property="og:url" content="', get_permalink(), '"/>', "\n";
			}
			// FB Title
			if ( is_string( $wcv_seo_fb_title ) && $wcv_seo_fb_title !== '' ) {
				echo '<meta property="og:title" content="', esc_attr( wp_strip_all_tags( stripslashes( $wcv_seo_fb_title ) ) ), '"/>', "\n";
			}

			// FB Description
			if ( is_string( $wcv_seo_fb_description ) && $wcv_seo_fb_description !== '' ) {
				echo '<meta property="og:description" content="', esc_attr( wp_strip_all_tags( stripslashes( $wcv_seo_fb_description ) ) ), '"/>', "\n";
			}

			// FB Image
			$fb_image_src = wp_get_attachment_image_src( $wcv_seo_fb_image_id, 'full' );
			if ( is_array( $fb_image_src ) ) {
				echo '<meta property="og:image" content="', $fb_image_src[0], '"/>', "\n";
			}

			// Twitter Card
			// Twitter url
			if ( is_string( $wcv_seo_twitter_title ) && $wcv_seo_twitter_title !== '' ) {
				echo '<meta property="twitter:card" content="summary" />', "\n";
				echo '<meta property="twitter:site" content="', esc_attr( wp_strip_all_tags( stripslashes( $twitter_username ) ) ), '"/>', "\n";
			}
			// Twitter Title
			if ( is_string( $wcv_seo_twitter_title ) && $wcv_seo_twitter_title !== '' ) {
				echo '<meta property="twitter:title" content="', esc_attr( wp_strip_all_tags( stripslashes( $wcv_seo_twitter_title ) ) ), '"/>', "\n";
			}

			// Twitter Description
			if ( is_string( $wcv_seo_twitter_description ) && $wcv_seo_twitter_description !== '' ) {
				echo '<meta property="twitter:description" content="', esc_attr( wp_strip_all_tags( stripslashes( $wcv_seo_twitter_description ) ) ), '"/>', "\n";
			}

			// Twitter Image
			$twitter_image_src = wp_get_attachment_image_src( $wcv_seo_twitter_image_id, 'full' );
			if ( is_array( $twitter_image_src ) ) {
				echo '<meta property="twitter:image" content="', $twitter_image_src[0], '"/>', "\n";
			}
		}
	}

	/**
	 * Override the become a vendor link to pro dashboard
	 *
	 * @since 1.5.4
	 */
	public function become_a_vendor_override( $url, $endpoint, $value, $permalink ) {

		if ( $endpoint == 'become-a-vendor' ) {
			$url = WCVendors_Pro_Dashboard::get_dashboard_page_url();
		}

		return $url;
	}

	/**
	 * Make product not purchasable if vendor is on vacation and has disabled cart
	 *
	 * @param $is_purchasable
	 * @param $product
	 *
	 * @return boolean true|false Whether to product is purchasable or not
	 */
	public function is_product_purchasable( $is_purchasable, $product ) {

		$product_id = $product->get_id();
		$vendor_id  = WCV_Vendors::get_vendor_from_product( $product_id );

		if ( self::is_on_vacation( $vendor_id ) && self::is_cart_disabled( $vendor_id ) ) {
			$is_purchasable = false;
		}

		return $is_purchasable;
	}

	/**
	 * Add vacation notice on vendor dashboard
	 *
	 * @return void
	 * @since 1.5.8
	 */
	public function vacation_mode_notice() {
		if ( WCVendors_Pro_vendor_Controller::is_on_vacation() ) {

			$settings_page = get_permalink( get_option( 'wcvendors_dashboard_page_id' ) ) . 'settings/';
			$notice        = sprintf( __( 'You currently have vacation mode turned on. You can disable it in the <a href="%s">settings page</a> when ready to do so. ', 'wcvendors-pro' ), $settings_page );
			$cart_disabled = self::is_cart_disabled( get_current_user_id() );

			if ( $cart_disabled ) {
				$notice .= __( '<br/><strong>Reminder :</strong> You have disabled your cart. No purchases can be made from your store while this is active.', 'wcvendors-pro' );

			}

			wc_get_template(
				'dashboard-notice.php',
				array(
					'vendor_dashboard_notice' => $notice,
					'notice_type'             => 'info',
				),
				'wc-vendors/dashboard/',
				$this->base_dir . '/templates/dashboard/'
			);
		}
	}

	/**
	 * Check and display vendor store notice
	 *
	 * @return void
	 * @since
	 * @version
	 */
	public function show_vendor_store_notice() {

		global $post;

		if ( ! $post ) return;

		if ( ! WCV_Vendors::is_vendor_page() && ! WCV_Vendors::is_vendor_product_page( $post->post_author ) ) {
			return;
		}

		$vendor_id = wcv_get_vendor_id();

		if ( ! isset( $vendor_id ) ) {
			return;
		}

		if ( ! wc_string_to_bool( get_user_meta( $vendor_id, '_wcv_vendor_enable_store_notice', true ) ) ) {
			return;
		}

		$vendor_store_notice = get_user_meta( $vendor_id, '_wcv_vendor_store_notice', true );

		wc_get_template(
			'vendor-store-notice.php',
			array( 'vendor_store_notice' => $vendor_store_notice ),
			'wc-vendors/store/',
			$this->base_dir . '/templates/store/'
		);

	}

	/**
	 * Check if vendor has reached upload limits and display a notice on the dashboard
	 *
	 * @return  void
	 * @since   1.6.0
	 * @version 1.6.0
	 */
	public function show_vendor_upload_limits_notice() {

		global $post;

		$current_page_id  = get_the_ID();

		if ( ! wcv_is_dashboard_page( $current_page_id ) || ! is_user_logged_in() ) {
			return;
		}

		$vendor_id = wcv_get_vendor_id();

		if ( ! isset( $vendor_id ) ) {
			return;
		}

		$upload_limits_notice = '';

		$limits        = WCVendors_Pro_Upload_Limits::get_instance( $vendor_id );
		$files_limited = $limits->files_limit_reached();
		$disk_limited  = $limits->disk_limit_reached();

		$upload_limits_notice .= $limits->files_limit_reached() ? __('You have reached the total disk usage allowed in your account. ', 'wcvendors-pro' ) : '';
		$upload_limits_notice .= $limits->disk_limit_reached()  ? __('You have reached the total number of files you are allowed to upload.', 'wcvendors-pro'): '';

		$upload_limits_notice = apply_filters( 'wcv_vendor_uploads_limit_notice', $upload_limits_notice, $files_limited, $disk_limited );

		wc_get_template(
			'upload-limits-notice.php',
			array( 'upload_limits_notice' => $upload_limits_notice ),
			'wc-vendors/store/',
			$this->base_dir . '/templates/store/'
		);

	}

	/**
	 * Check if a vendor is on vacation or not
	 *
	 * @param integer $vendor_id The id of the vendor
	 *
	 * @return boolean Whether the vendor is on vacation or not
	 * @since   1.5.8
	 * @version 1.5.8
	 */
	public static function is_on_vacation( $vendor_id = 0 ) {
		if ( ! $vendor_id && is_user_logged_in() && current_user_can( 'vendor' ) ) {
			$vendor_id = get_current_user_id();
		}

		if ( ! $vendor_id && WCV_Vendors::is_vendor_page() ) {
			$vendor_shop = urldecode( get_query_var( 'vendor_shop' ) );
			$vendor_id   = WCV_Vendors::get_vendor_id( $vendor_shop );
		}

		if ( ! $vendor_id ) {
			return false;
		}

		return wc_string_to_bool( get_user_meta( $vendor_id, '_wcv_vacation_mode', true ) );
	}

	/**
	 * Check if add to cart is disabled for vendor
	 *
	 * @param integer $vendor_id The id of the vendor
	 *
	 * @return boolean $cart_disabled Whether the cart is disabled or not
	 */
	public static function is_cart_disabled( $vendor_id = 0 ) {

		if ( ! $vendor_id && is_user_logged_in() && current_user_can( 'vendor' ) ) {
			$vendor_id = get_current_user_id();
		}

		if ( ! $vendor_id && WCV_Vendors::is_vendor_page() ) {
			$vendor_shop = urldecode( get_query_var( 'vendor_shop' ) );
			$vendor_id   = WCV_Vendors::get_vendor_id( $vendor_shop );
		}

		if ( ! $vendor_id ) {
			return false;
		}

		return wc_string_to_bool( get_user_meta( $vendor_id, '_wcv_vacation_disable_cart', true ) );
	}

	/**
	 * Get the total number of sales for the vendor
	 *
	 * @param int $vendor_id The id of the vendor
	 *
	 * @return int the number of sales for the vendor
	 * @since   1.5.8
	 * @version 1.5.8
	 */
	public static function get_vendor_sales_count( $vendor_id ) {

		$product_ids = self::get_vendor_sold_products( $vendor_id );

		$total_sales = 0;

		if ( $product_ids ) {

			foreach ( $product_ids as $product_id ) {
				$product = wc_get_product( $product_id );
				if ( is_a( $product, 'WC_Product' ) ) {
					$total_sales += $product->get_total_sales();
				}
			}
		}

		return $total_sales;
	}

	/**
	 * Get the total sales count for a product
	 *
	 * @return    void
	 * @since    1.5.8
	 * @version  1.5.9
	 */
	public function product_total_sales_summary() {

		global $product, $post;

		$admin_show   = wc_string_to_bool( get_option( 'wcvendors_show_product_total_sales' ) );
		$totals_label = $this->get_total_sales_label( $post->post_author, 'product' );

		if ( ! $admin_show ) {
			return;
		}

		$total_sales = $this->get_product_total_sales( $product );
		$totals_html = $this->generate_totals_text( $total_sales, $totals_label, 'left' );

		if ( is_numeric( $total_sales ) ) {
			echo apply_filters( 'wcv_product_total_sales_html', sprintf( __( '<p class="product-totals-html">%s</p>', 'wcvendors-pro' ), $totals_html ), $product );
		}
	}

	/**
	 * Filter the main loop based on the product_ids
	 *
	 * @since  1.5.8
	 * @access public
	 */
	public function vendor_store_products_filter( $query ) {

		global $wp_query;

		if ( is_admin() ) {
			return;
		}

		$product_ids = isset( $_GET['product_ids'] ) ? $_GET['product_ids'] : '';

		if ( empty( $wp_query->query_vars['wc_query'] ) || empty( $product_ids ) ) {
			return;
		}

		if ( $query->is_main_query() ) {

			$query->set( 'post__in', explode( ',', urldecode( $product_ids ) ) );
			$query->set( 'post_type', 'product' );
		}
	} // vendor_store_product_filter()

	/**
	 * Output the product total sales count shortcode
	 *
	 * Shortcode usage [wcv_pro_vendor_totalsales product_id="" position="left|right|none"]
	 *
	 * @return    string Product sales html output
	 * @since    1.5.8
	 * @version  1.5.9
	 */
	public function product_total_sales_shortcode( $atts = null ) {

		$atts = shortcode_atts(
			array(
				'product_id' => 0,
				'position'   => 'left',
			),
			$atts,
			'wcv_pro_product_totalsales'
		);

		extract( $atts );

		$product = wc_get_product( $product_id );

		if ( ! is_a( $product, 'WC_Product' ) ) {
			return;
		}

		$post = get_post( $product->get_id() );

		$admin_show = wc_string_to_bool( get_option( 'wcvendors_show_product_total_sales' ) );

		if ( ! $admin_show ) {
			return;
		}

		$label       = $this->get_total_sales_label( $post->post_author, 'product' );
		$total_sales = $this->get_product_total_sales( $product );
		$totals_html = $this->generate_totals_text( $total_sales, $label, $position );

		return apply_filters( 'wcv_product_total_sales_html', sprintf( __( '<span class="product-totals-html">%s</span>', 'wcvendors-pro' ), $totals_html ) );
	}

	/**
	 * Vendor total sales shortcode
	 *
	 * Shortcode usage [wcv_pro_vendor_totalsales vendor_id="" position="left|right|none"]
	 *
	 * @param    array $atts The shortcode attributes
	 *
	 * @return    string    The total sales html output
	 * @since    1.5.8
	 * @version  1.5.9
	 */
	public function vendor_total_sales_shortcode( $atts ) {

		$atts = shortcode_atts(
			array(
				'vendor_id' => 0,
				'position'  => 'left',
			),
			$atts,
			'wcv_pro_vendor_totalsales'
		);
		extract( $atts );

		$admin_show = wc_string_to_bool( get_option( 'wcvendors_show_store_total_sales' ) );
		if ( ! $admin_show ) {
			return;
		}

		$label       = $this->get_total_sales_label( $vendor_id, 'store' );
		$sales_count = $this->get_vendor_sales_count( $vendor_id );
		$totals_html = $this->generate_totals_text( $sales_count, $label, $position );

		return apply_filters( 'wcv_vendor_total_sales_shortcode_html', sprintf( __( '<span class="vendor-total-sales">%s</span>', 'wcvendors-pro' ), $totals_html ) );
	}

	/**
	 * Get product total sales count
	 *
	 * @param    int|WC_Product $product
	 *
	 * @return    int Number of sales for this product
	 * @since    1.5.8
	 * @version  1.5.9
	 */
	public function get_product_total_sales( $product ) {
		if ( is_numeric( $product ) ) {
			$product = get_product( $product );
		}

		if ( ! is_a( $product, 'WC_Product' ) ) {
			return 0;
		}

		return $product->get_total_sales();
	}

	/**
	 * Get the vendor's total sales label text
	 *
	 * @param    int $vendor_id
	 *
	 * @return    string The label for the vendor total sales output
	 * @since    1.5.8
	 * @version  1.5.9
	 */
	public static function get_total_sales_label( $vendor_id, $for = 'store' ) {

		$global_label = get_option( "wcvendors_{$for}_total_sales_label" );

		return apply_filters( 'wcv_' . $for . '_total_sales_label', $global_label );
	}

	/**
	 * Get the ids of products sold at least once
	 *
	 * @param int $vendor_id The id of the vendor
	 *
	 * @since   1.5.8
	 * @version 1.6.2
	 *
	 * @return array
	 */
	public static function get_vendor_sold_products( $vendor_id ) {

		$posts_products = get_posts(
			apply_filters(
				'wcv_vendor_sold_products_arguments',
				array(
					'author'         => $vendor_id,
					'post_type'      => 'product',
					'status'         => 'publish',
					'posts_per_page' => - 1,
					'fields'         => 'ids'
				)
			)
		);

		$product_ids = array();

		if ( $posts_products ) {
			foreach ( $posts_products as $post_id ) {
				$product = wc_get_product( $post_id );

				if ( is_a( $product, 'WC_Product' ) && $product->get_total_sales() > 0 ) {
					$product_ids[] = $product->get_id();
				}
			}
		}

		return $product_ids;
	}

	/**
	 * Generate the total sales string based on label and position
	 *
	 * @param    int    $sales_count
	 * @param    string $label
	 * @param    string $position
	 *
	 * @return    string
	 * @since    1.5.8
	 * @version  1.5.8
	 */
	public function generate_totals_text( $sales_count, $label, $position ) {

		if ( 'left' == $position ) {
			$totals_html = $label . ' ' . $sales_count;
		} elseif ( 'none' == $position ) {
			$totals_html = $sales_count;
		} else {
			$totals_html = $sales_count . ' ' . $label;
		}

		return $totals_html;
	}
}
