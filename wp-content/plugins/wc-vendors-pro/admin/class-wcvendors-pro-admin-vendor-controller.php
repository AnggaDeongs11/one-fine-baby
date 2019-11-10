<?php

/**
 * The admin side vendor controller functions
 *
 * This controller looks after all admin vendor features for pro.
 *
 * @package    WCVendors_Pro
 * @subpackage WCVendors_Pro/admin
 * @author     Jamie Madden <support@wcvendors.com>
 */

class WCVendors_Pro_Admin_Vendor_Controller {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.2.0
	 * @access   private
	 * @var      string $wcvendors_pro The ID of this plugin.
	 */
	private $wcvendors_pro;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.2.0
	 * @access   private
	 * @var      string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * Is the plugin in debug mode
	 *
	 * @since    1.2.0
	 * @access   private
	 * @var      bool $debug plugin is in debug mode
	 */
	private $debug;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.2.0
	 *
	 * @param    string $wcvendors_pro The name of this plugin.
	 * @param    string $version       The version of this plugin.
	 * @param    bool   $debug         Plugin in debug mode
	 */
	public function __construct( $wcvendors_pro, $version, $debug ) {

		$this->wcvendors_pro = $wcvendors_pro;
		$this->version       = $version;
		$this->debug         = $debug;
		$this->base_dir      = plugin_dir_url( __FILE__ );

	}

	/**
	 * Get all Custom pro user fields
	 *
	 * @since    1.2.0
	 */
	public function get_pro_user_meta_fields( $user ) {

		$vendor_shipping = get_user_meta( $user->ID, '_wcv_shipping', true );

		return $fields = apply_filters(
			'wcv_custom_user_fields',
			array(
				'store_general' => array(
					'title'  => __( 'Store General' ),
					'fields' => array(
						'_wcv_verified_vendor'            => array(
							'label'       => sprintf( __( 'Verified %s', 'wcvendors-pro' ), wcv_get_vendor_name( true, true ) ),
							'description' => sprintf( __( ' Check to publish that this %s is verified by the store admin..', 'wcvendors-pro' ), wcv_get_vendor_name( true, false ) ),
							'type'        => 'checkbox',
						),
						'_wcv_trusted_vendor'             => array(
							'label'       => sprintf( __( 'Trusted %s', 'wcvendors-pro' ), wcv_get_vendor_name() ),
							'description' => sprintf( __( ' Check to allow this %s to publish products immediately regardless of global publishing rules.', 'wcvendors-pro' ), wcv_get_vendor_name( true, false ) ),
							'type'        => 'checkbox',
						),
						'_wcv_untrusted_vendor'           => array(
							'label'       => sprintf( __( 'Untrusted %s', 'wcvendors-pro' ), wcv_get_vendor_name() ),
							'description' => sprintf( __( ' Check to require all products submitted to be reviewed, regardless of global publishing rules. This overrides the trusted %s option.', 'wcvendors-pro' ), wcv_get_vendor_name( true, false ) ),
							'type'        => 'checkbox',
						),
						'_wcv_lock_new_products_vendor'   => array(
							'label'       => __( 'Lock New Products', 'wcvendors-pro' ),
							'description' => sprintf( __( 'Lock %s from creating any new products.', 'wcvendors-pro' ), wcv_get_vendor_name( true, false ) ),
							'type'        => 'checkbox',
						),
						'_wcv_lock_new_products_vendor_msg' => array(
							'label'       => __( 'Lock New Products Message', 'wcvendors-pro' ),
							'value'       => __( 'Your access to create new products has been disabled.', 'wcvendors-pro' ),
							'description' => '',
							'type'        => 'textarea',
						),
						'_wcv_lock_edit_products_vendor'  => array(
							'label'       => __( 'Lock edit products', 'wcvendors-pro' ),
							'description' => sprintf( __( 'Lock %s from editing any existing products', 'wcvendors-pro' ), wcv_get_vendor_name( true, false ) ),
							'type'        => 'checkbox',
						),
						'_wcv_lock_edit_products_vendor_msg' => array(
							'label'       => __( 'Lock Edit Products Message', 'wcvendors-pro' ),
							'value'       => __( 'Your access to edit products has been disabled.', 'wcvendors-pro' ),
							'type'        => 'textarea',
							'description' => '',
						),
						'_wcv_company_url'                => array(
							'label'       => __( 'Store Website / Blog URL', 'wcvendors-pro' ),
							'description' => '',
						),
						'_wcv_show_product_total_sales'   => array(
							'label'       => __( 'Product Total Sales', 'wcvendors-pro' ),
							'description' => __( 'Show product total sales', 'wcvendors-pro' ),
							'type'        => 'checkbox',
						),
						'_wcv_vacation_mode'              => array(
							'label'       => __( 'Vacation Mode', 'wcvendors-pro' ),
							'description' => __( 'Enable vacation mode', 'wcvendors-pro' ),
							'type'        => 'checkbox',
						),
						'_wcv_vacation_disable_cart'      => array(
							'label'       => __( 'Disable Cart', 'wcvendors-pro' ),
							'description' => __( 'Disable cart when vendor is on vacation', 'wcvendors-pro' ),
							'type'        => 'checkbox',
						),
						'_wcv_vacation_mode_msg'          => array(
							'label'       => __( 'Vacation Message', 'wcvendors-pro' ),
							'description' => '',
							'type'        => 'textarea',
						),
						'_wcv_vendor_enable_store_notice' => array(
							'label'       => __( 'Enable Store Notice', 'wcvendors-pro' ),
							'description' => '',
							'type'        => 'checkbox',
						),
						'_wcv_vendor_store_notice'        => array(
							'label'       => __( 'Store Notice', 'wcvendors-pro' ),
							'description' => '',
							'type'        => 'wp_editor',
						),
						'_wcv_vendor_disk_usage_limit'    => array(
							'label'       => __( 'Disk Usage Limit', 'wcvendors-pro' ),
							'description' => __( 'Limit the total disk usage this user can use. 0 = Unlimited.', 'wcvendors-pro' ),
							'type'        => 'text',
						),
						'_wcv_vendor_file_count_limit'    => array(
							'label'       => __( 'File Count Limit', 'wcvendors-pro' ),
							'description' => __( 'Limit the number of files that can be uploaded by this user use. 0 = Unlimited.', 'wcvendors-pro' ),
							'type'        => 'text',
						),
						'_wcv_vendor_upload_limits_include_thumbnails'    => array(
							'label'       => __( 'Limits Include Thumbnails', 'wcvendors-pro' ),
							'description' => __( 'Check if you want media thumbnails to contribute to limits.', 'wcvendors-pro' ),
							'type'        => 'checkbox',
						),
					),
				),
				'store_address' => array(
					'title'  => __( 'Store Address' ),
					'fields' => array(
						'_wcv_store_address1' => array(
							'label'       => __( 'Address 1', 'wcvendors-pro' ),
							'description' => '',
						),
						'_wcv_store_address2' => array(
							'label'       => __( 'Address 2', 'wcvendors-pro' ),
							'description' => '',
						),
						'_wcv_store_city'     => array(
							'label'       => __( 'City', 'wcvendors-pro' ),
							'description' => '',
						),
						'_wcv_store_postcode' => array(
							'label'       => __( 'Postcode', 'wcvendors-pro' ),
							'description' => '',
						),
						'_wcv_store_country'  => array(
							'label'       => __( 'Country', 'wcvendors-pro' ),
							'description' => '',
							'class'       => 'js_field-country',
							'type'        => 'select',
							'options'     => array( '' => __( 'Select a country&hellip;', 'wcvendors-pro' ) ) + WC()->countries->get_allowed_countries(),
						),
						'_wcv_store_state'    => array(
							'label'       => __( 'State/County', 'wcvendors-pro' ),
							'description' => __( 'State/County or state code', 'wcvendors-pro' ),
							'class'       => 'js_field-state',
						),
						'_wcv_store_phone'    => array(
							'label'       => __( 'Telephone', 'wcvendors-pro' ),
							'description' => '',
						),
					),
				),
				'store_social'  => array(
					'title'  => __( 'Store Social' ),
					'fields' => array(
						'_wcv_twitter_username'   => array(
							'label'       => __( 'Twitter', 'wcvendors-pro' ),
							'description' => __( '<a href="https://twitter.com/">Twitter</a> username without the url.', 'wcvendors-pro' ),
						),
						'_wcv_instagram_username' => array(
							'label'       => __( 'Instagram', 'wcvendors-pro' ),
							'description' => __( '<a href="https://instagram.com/">Instagram</a> username without the url.', 'wcvendors-pro' ),
						),
						'_wcv_facebook_url'       => array(
							'label'       => __( 'Facebook', 'wcvendors-pro' ),
							'description' => __( '<a href="https://facebook.com/">Facebook</a> url.', 'wcvendors-pro' ),
						),
						'_wcv_linkedin_url'       => array(
							'label'       => __( 'LinkedIn', 'wcvendors-pro' ),
							'description' => __( '<a href="https://linkedin.com/">LinkedIn</a> url.', 'wcvendors-pro' ),
						),
						'_wcv_youtube_url'        => array(
							'label'       => __( 'YouTube', 'wcvendors-pro' ),
							'description' => __( '<a href="https://youtube.com/">Youtube</a> url.', 'wcvendors-pro' ),
						),
						'_wcv_googleplus_url'     => array(
							'label'       => __( 'Google+', 'wcvendors-pro' ),
							'description' => __( '<a href="https://plus.google.com">Google+</a> url.', 'wcvendors-pro' ),
						),
						'_wcv_pinterest_url'      => array(
							'label'       => __( 'Pinterest', 'wcvendors-pro' ),
							'description' => __( '<a href=https://www.pinterest.com/">Pinterest</a> url.', 'wcvendors-pro' ),
						),
						'_wcv_snapchat_username'  => array(
							'label'       => __( 'Snapchat', 'wcvendors-pro' ),
							'description' => __( 'Snapchat username.', 'wcvendors-pro' ),
						),

					),
				),
				'store_polices' => array(
					'title'  => __( 'Policies', 'wcvendors-pro' ),
					'fields' => array(
						'wcv_policy_privacy' => array(
							'label' => __('Privacy policy', 'wcvendors-pro'),
							'type' => 'textarea',
						),
						'wcv_policy_terms' => array(
							'label' => __('Terms and conditions', 'wcvendors-pro'),
							'type' => 'textarea',
						),
						'_wcv_vendor_shipping_policy'      => array(
							'label'       => __( 'Shipping policy', 'wcvendors-pro' ),
							'description' => '',
							'value'       => isset( $vendor_shipping['shipping_policy'] ) ? $vendor_shipping['shipping_policy'] : '',
							'field_type'  => 'shipping',
							'type'        => 'textarea',
						),
						'_wcv_vendor_return_policy'        => array(
							'label'       => __( 'Return policy', 'wcvendors-pro' ),
							'description' => '',
							'value'       => isset( $vendor_shipping['return_policy'] ) ? $vendor_shipping['return_policy'] : '',
							'field_type'  => 'shipping',
							'type'        => 'textarea',
						),
					),
				),
				'store_seo'     => array(
					'title'  => __( 'Store SEO' ),
					'fields' => array(
						'wcv_seo_title'               => array(
							'label'       => __( 'SEO Title', 'wcvendors-pro' ),
							'description' => __( 'The SEO title shown in search engines', 'wcvendors-pro' ),
						),
						'wcv_seo_meta_description'    => array(
							'label'       => __( 'Meta Description', 'wcvendors-pro' ),
							'description' => __( 'This should be a maximum of 300 characters', 'wcvendors-pro' ),
						),
						'wcv_seo_meta_keywords'       => array(
							'label'       => __( 'Meta Keywords', 'wcvendors-pro' ),
							'description' => __( 'A comma separated list of keywords', 'wcvendors-pro' ),
						),
						'wcv_seo_fb_title'            => array(
							'label'       => __( 'Facebook Title', 'wcvendors-pro' ),
							'description' => __( 'Facebook title of the page you are sharing.', 'wcvendors-pro' ),
						),
						'wcv_seo_fb_description'      => array(
							'label'       => __( 'Facebook Description', 'wcvendors-pro' ),
							'description' => __( 'Description for facebook', 'wcvendors-pro' ),
						),
						'wcv_seo_fb_image_id'         => array(
							'label'       => __( 'Facebook Image', 'wcvendors-pro' ),
							'description' => __( 'Facebook Image', 'wcvendors-pro' ),
							'type'        => 'image',
						),
						'wcv_seo_twitter_title'       => array(
							'label'       => __( 'Twitter title', 'wcvendors-pro' ),
							'description' => __( 'Title for the twitter card', 'wcvendors-pro' ),
						),
						'wcv_seo_twitter_description' => array(
							'label'       => __( 'Twitter Description', 'wcvendors-pro' ),
							'description' => __( 'Description for the twitter card', 'wcvendors-pro' ),
						),
						'wcv_seo_twitter_image_id'    => array(
							'label'       => __( 'Twitter Image', 'wcvendors-pro' ),
							'description' => __( 'Twitter Image to display', 'wcvendors-pro' ),
							'type'        => 'image',
						),

					),
				),
			)
		);

	} // get_pro_user_meta_fields()

	/**
	 * Show the Pro vendor store fields
	 *
	 * @since    1.2.0
	 *
	 * @param WP_User $user
	 */
	public function add_pro_vendor_meta_fields( $user ) {

		if ( ! current_user_can( 'manage_woocommerce' ) ) {
			return;
		}

		if ( ! WCV_Vendors::is_vendor( $user->ID ) && ! WCV_Vendors::is_pending( $user->ID ) ) {
			return;
		}

		$fields = $this->get_pro_user_meta_fields( $user );

		include apply_filters( 'wcv_partial_path_pro_user_meta', 'partials/vendor/wcvendors-pro-user-meta.php' );
		include apply_filters( 'wcv_partial_path_pro_user_meta_branding', 'partials/vendor/wcvendors-pro-user-meta-branding.php' );

	}

	/**
	 * Save the pro vendor store fields
	 *
	 * @since    1.2.0
	 *
	 * @param WP_User $user
	 */
	public function save_pro_vendor_meta_fields( $vendor_id ) {

		if ( ! current_user_can( 'manage_woocommerce' ) ) {
			return;
		}

		$user = get_user_by( 'id', $vendor_id );

		if ( ! WCV_Vendors::is_pending( $vendor_id ) && ! WCV_Vendors::is_vendor( $vendor_id ) ) {
			return;
		}

		$save_fields = $this->get_pro_user_meta_fields( $user );

		foreach ( $save_fields as $fieldset ) {

			foreach ( $fieldset['fields'] as $key => $field ) {

				if ( isset( $_POST[ $key ] ) ) {

					// Set the correct value for a check box
					if ( array_key_exists( 'type', $field ) && 'checkbox' == $field['type'] ) {
						$value = 'yes';
					} else {
						$value = $_POST[ $key ];
					}

					update_user_meta( $vendor_id, $key, wc_clean( $value ) );

				} else {
					delete_user_meta( $vendor_id, $key );
				}
			}
		}

		// Banner
		if ( isset( $_POST['_wcv_store_banner_id'] ) ) {
			update_user_meta( $vendor_id, '_wcv_store_banner_id', wc_clean( $_POST['_wcv_store_banner_id'] ) );
		} else {
			delete_user_meta( $vendor_id, '_wcv_store_banner_id' );
		}

		// Icon
		if ( isset( $_POST['_wcv_store_icon_id'] ) ) {
			update_user_meta( $vendor_id, '_wcv_store_icon_id', wc_clean( $_POST['_wcv_store_icon_id'] ) );
		} else {
			delete_user_meta( $vendor_id, '_wcv_store_icon_id' );
		}

	} // save_pro_vendor_meta_fields()

	/**
	 * Save vendor opening hours
	 *
	 * @param   int $vendor_id
	 *
	 * @return  void
	 * @since   1.5.9
	 * @version 1.5.9
	 */
	public function save_pro_vendor_opening_hours( $vendor_id ) {

		if ( isset( $_POST['days'] ) && is_array( $_POST['days'] ) ) {
			$opening_hours = array();

			for ( $i = 0; $i < count( $_POST['days'] ); $i ++ ) {
				$opening_hours[] = array(
					'day'    => esc_attr( $_POST['days'][ $i ] ),
					'open'   => esc_attr( $_POST['open'][ $i ] ),
					'close'  => esc_attr( $_POST['close'][ $i ] ),
					'status' => esc_attr( $_POST['status'][ $i ] ),
				);
			}

			update_user_meta( $vendor_id, 'wcv_store_opening_hours', $opening_hours );
		}

	} // save_pro_vendor_opening_hours()

	/**
	 * Output a vendor drop down to restrict the product type by
	 *
	 * @since    1.3.0
	 */
	public function restrict_manage_posts() {

		global $typenow, $wp_query;

		if ( ! current_user_can( 'manage_woocommerce' ) ) {
			return;
		}

		if ( 'product' == $typenow ) {
			$users = get_users(
				array(
					'role'   => 'vendor',
					'fields' => array(
						'ID',
						'user_login',
					),
				)
			);
			include apply_filters( 'wcvendors_pro_restrict_manage_posts_path', 'partials/vendor/wcvendors-pro-vendor-dropdown.php' );
			echo $output;
		}

	} //restrict_manage_posts()

	/**
	 * Add opening hours fields on user edit screen
	 *
	 * @param   WP_User $user
	 *
	 * @return  void
	 * @since   1.5.9
	 * @version 1.5.9
	 */
	public function add_opening_hours( $user ) {
		$hours = get_user_meta( $user->ID, 'wcv_store_opening_hours', true );

		$hours = apply_filters( 'wcv_store_opening_hours_' . $user->ID, $hours );

		if ( empty( $hours ) ) {
			$hours = wcv_get_default_opening_hours();
		}

		include_once plugin_dir_path( WCV_PRO_PLUGIN_FILE ) . '/public/forms/partials/store-opening-hours.php';
	}

	/**
	 * Filter wp query for the product post type
	 *
	 * @since    1.3.0
	 */
	public function vendor_filter_query( $query ) {

		global $typenow, $wp_query;

		if ( 'product' == $typenow ) {

			if ( isset( $_GET['vendor_id'] ) ) {

				$query->query_vars['author'] = $_GET['vendor_id'];

			}
		}

	} // vendor_filter_query()

}
