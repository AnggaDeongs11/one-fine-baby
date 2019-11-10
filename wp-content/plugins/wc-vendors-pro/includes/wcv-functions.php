<?php
/**
 * Active plugins
 */
if ( ! function_exists( 'get_active_plugins' ) ) {
	function get_active_plugins() {
		$active_plugins = (array) get_option( 'active_plugins', array() );
		if ( is_multisite() ) {
			$active_plugins = array_merge( $active_plugins, get_site_option( 'active_sitewide_plugins', array() ) );
		}

		return $active_plugins;
	}
}

/**
 * WooCommerce Detection
 */
if ( ! function_exists( 'is_woocommerce_active' ) ) {
	function is_woocommerce_active() {
		$active_plugins = get_active_plugins();

		return in_array( 'woocommerce/woocommerce.php', $active_plugins ) || array_key_exists( 'woocommerce/woocommerce.php', $active_plugins );
	}
}

/**
 * WC Vendors Detection
 */
if ( ! function_exists( 'is_wcvendors_active' ) ) {
	function is_wcvendors_active() {
		$active_plugins = get_active_plugins();

		return in_array( 'wc-vendors/class-wc-vendors.php', $active_plugins ) || array_key_exists( 'wc-vendors/class-wc-vendors.php', $active_plugins );
	}
}

/**
 *    WC Vendors 2.0.0
 */
if ( ! function_exists( 'is_wcvendors_2_0_0' ) ) {
	function is_wcvendors_2_0_0() {
		if ( class_exists( 'WC_Vendors' ) ) {
			return version_compare( WCV_VERSION, '2.0.0', '<' );
		}
	}
}


/**
 * WooCommerce Required Notice
 */
if ( ! function_exists( 'woocommerce_required_notice' ) ) {
	function woocommerce_required_notice() {
		echo '<div class="error"><p><strong>' . __( 'WooCommerce not found. WC Vendors Pro requires a minimum of WooCommerce v3.3.0.', 'wcvendors-pro' ) . '</strong></p></div>';
	}
}

/**
 * WCVendors Required Notice
 */
if ( ! function_exists( 'wcvendors_required_notice' ) ) {
	function wcvendors_required_notice() {
		echo '<div class="error"><p><strong>' . __( 'WC Vendors Marketplace not found. WC Vendors Pro requires a minimum of WC Vendors Marketplace v2.0.0', 'wcvendors-pro' ) . '</strong></p></div>';
	}
}


/**
 * WCVendors 2.0.0 Required Notice
 */
if ( ! function_exists( 'wcvendors_2_required_notice' ) ) {
	function wcvendors_2_required_notice() {
		echo '<div class="error"><p>' . __( '<b>WC Vendors Pro requires a minimum of WC Vendors Marketplace v2.0.0', 'wcvendors-pro' ) . '</p></div>';
	}
}


/*
*	Check to see if the current page is a pro dashboard page
*/
if ( ! function_exists( 'wcv_is_dashboard_page' ) ) {
	function wcv_is_dashboard_page( $current_page = 0 ) {

		if ( ! $current_page ) {
			$current_page = get_the_ID();
		}
		if ( ! $current_page ) {
			return false;
		}

		$dashboard_page_ids = (array) get_option( 'wcvendors_dashboard_page_id', array() );
		if ( empty( $dashboard_page_ids ) ) {
			return false;
		}

		return in_array( $current_page, $dashboard_page_ids );
	}
}


if ( ! function_exists( 'wcv_percentage_to_price' ) ) {
	/**
	 * Converts a percentage to a formatted price
	 *
	 * @param string $percentage The percentage value
	 * @param float  $price      The actual price
	 *
	 * @since 1.5.6
	 */
	function wcv_percentage_to_price( $percentage, $price ) {

		if ( stripos( $percentage, '%' ) > 0 ) {
			$percentage_to_price = wc_price( (float) $price * ( (float) $percentage / 100 ) );
		} else {
			$percentage_to_price = wc_price( $percentage );
		}

		return $percentage_to_price;
	}
}

if ( ! function_exists( 'wcv_get_vendor_id' ) ) {
	/**
	 * Get the vendor id
	 *
	 * @return    int $vendor_id
	 * @since      1.5.9
	 * @version    1.6.4
	 */
	function wcv_get_vendor_id() {
		global $post;

		$vendor_id = 0;

		if ( WCV_Vendors::is_vendor_page() ) {
			$vendor_shop = urldecode( get_query_var( 'vendor_shop' ) );
			$vendor_id   = WCV_Vendors::get_vendor_id( $vendor_shop );
		} elseif ( is_singular( 'product' ) && WCV_Vendors::is_vendor_product_page( $post->post_author ) ) {
			$vendor_id = $post->post_author;
		} else {
			if ( isset( $_GET['wcv_vendor_id'] ) ) {
				$vendor_id = $_GET['wcv_vendor_id'];
			}
		}

		return $vendor_id;
	}
}

if ( ! function_exists( 'get_time_interval_options' ) ) {
	/**
	 * Make an array of 30 minute time intervals
	 *
	 * @return  array
	 * @since   1.5.9
	 * @version 1.6.3
	 */
	function get_time_interval_options() {

		$time_format = apply_filters( 'wcv_opening_hours_time_format', wc_time_format() );

		$result = array();

		for ( $hours = 0; $hours < 24; $hours ++ ) {

			for ( $mins = 0; $mins < 60; $mins += apply_filters( 'wcv_opening_hours_interval_length', 30 ) ) {

				$time     = str_pad( $hours, 2, '0', STR_PAD_LEFT ) . ':'
				            . str_pad( $mins, 2, '0', STR_PAD_LEFT );
				$result[] = array( $time => date( $time_format, strtotime( $time ) ) );
			}
		}

		return apply_filters( 'wcv_get_time_interval_options', $result );
	}
}

if ( ! function_exists( 'wcv_options_html' ) ) {
	/**
	 * Generate options for a select input given an array of options
	 *
	 * @param    array $options
	 *
	 * @return  string
	 * @since   1.5.9
	 * @version 1.5.9
	 */
	function wcv_options_html( $options ) {
		$options_html = '';
		foreach ( $options as $option ) {
			if ( is_array( $option ) ) {
				foreach ( $option as $key => $value ) {
					$options_html .= '<option value="' . $key . '">' . $value . '</option>' . "\n";
				}
			} else {
				$options_html .= '<option value="' . $option . '">' . $option . '</option>' . "\n";
			}
		}

		return $options_html;
	}
}

if ( ! function_exists( 'wcv_get_default_opening_hours' ) ) {
	/**
	 * Get default opening hours
	 *
	 * @return  array
	 * @since   1.5.9
	 * @version 1.5.9
	 */
	function wcv_get_default_opening_hours() {
		return apply_filters(
			'wcv_default_opeing_hours',
			array(
				array(
					'status' => 1,
					'day'    => __( 'weekdays', 'wcvendors-pro' ),
					'open'   => __( 'open', 'wcvendors-pro' ),
					'close'  => __( 'open', 'wcvendors-pro' ),
				),
			)
		);
	}
}

if ( ! function_exists( 'wcv_strip_html' ) ) {
	/**
	 * Strip html tags and remove extra spaces from the resulting string
	 *
	 * @param    string $string
	 *
	 * @return    string
	 * @since      1.5.9
	 * @version    1.5.9
	 */
	function wcv_strip_html( $string ) {

		$string = strip_tags( $string );
		$string = preg_replace( '/\s+/', ' ', $string );

		return trim( $string );
	}
}


if ( ! function_exists( 'wcv_is_vendor_dashboard' ) ){
	/**
	 * Check if this is the vendor dashboard page
	 */
	function wcv_is_vendor_dashboard(){

		$free_page_id   		= get_option( 'wcvendors_vendor_dashboard_page_id' );
		$dashboard_page_ids   	= (array) get_option( 'wcvendors_dashboard_page_id', array() );

		if ( ! $free_page_id || ! $dashboard_page_ids ) {
	        return false;
	    }

	    if ( $free_page_id == get_the_ID() || in_array( get_the_ID(), $dashboard_page_ids ) ) {
	        return true;
	    }

    	return false;

	}
}

/**
 * Get header type option then return the corresponding template file name.
 *
 * @return string
 */
function wcv_get_store_header_template() {
	$type = get_option( 'wcvendors_vendor_store_header_type', 'pro' );

	if ( 'pro-modern' === $type ) {
		return 'store-header-modern.php';
	}

	return 'store-header.php';
}

/**
 * Format store url
 *
 * @param $vendor_id
 *
 * @since 1.6.3
 *
 * @return string
 */
function wcv_format_store_url( $vendor_id ) {
	$store_url = get_user_meta( $vendor_id, '_wcv_company_url', true );
	if ( ! $store_url ) {
		return '';
	}

	return apply_filters(
		'wcv_format_store_url',
		sprintf( '<a href="%1$s">%1$s</a>', $store_url ),
		$vendor_id
	);
}

/**
 * Format store address.
 *
 * @param $vendor_id
 *
 * @since 1.6.2
 *
 * @return string
 */
function wcv_format_store_address( $vendor_id ) {
	$store_address_args = apply_filters(
		'wcv_format_store_address_args',
		array(
			'address1' => get_user_meta( $vendor_id, '_wcv_store_address1', true ),
			'city'     => get_user_meta( $vendor_id, '_wcv_store_city', true ),
			'state'    => get_user_meta( $vendor_id, '_wcv_store_state', true ),
			'postcode' => get_user_meta( $vendor_id, '_wcv_store_postcode', true ),
			'country'  => WC()->countries->countries[ get_user_meta( $vendor_id, '_wcv_store_country', true ) ],
		),
		$vendor_id
	);

	$store_address_args = array_filter( $store_address_args );

	return apply_filters( 'wcv_format_store_address_output', implode( ', ', $store_address_args ), $vendor_id );
}

/**
 * Format store social icons
 *
 * @param $vendor_id
 *
 * @since 1.6.2
 * @version 1.6.3
 *
 * @return false|string
 */
function wcv_format_store_social_icons( $vendor_id ) {

// Get store details including social, adddresses and phone number
	$twitter_username   = get_user_meta( $vendor_id, '_wcv_twitter_username', true );
	$instagram_username = get_user_meta( $vendor_id, '_wcv_instagram_username', true );
	$facebook_url       = get_user_meta( $vendor_id, '_wcv_facebook_url', true );
	$linkedin_url       = get_user_meta( $vendor_id, '_wcv_linkedin_url', true );
	$youtube_url        = get_user_meta( $vendor_id, '_wcv_youtube_url', true );
	$googleplus_url     = get_user_meta( $vendor_id, '_wcv_googleplus_url', true );
	$pinterest_url      = get_user_meta( $vendor_id, '_wcv_pinterest_url', true );
	$snapchat_username  = get_user_meta( $vendor_id, '_wcv_snapchat_username', true );

	$social_icons = empty( $twitter_username ) && empty( $instagram_username ) && empty( $facebook_url ) && empty( $linkedin_url ) && empty( $youtube_url ) && empty( $googleplus_url ) && empty( $pinterst_url ) && empty( $snapchat_username ) ? false : true;
	if ( ! $social_icons ) {
		return false;
	}
	ob_start();
	?>
	<ul class="social-icons">
		<?php if ( $facebook_url != '' ) { ?>
			<li>
			<a href="<?php echo $facebook_url; ?>" target="_blank">
				<svg class="wcv-icon wcv-icon-sm">
					<use xlink:href="<?php echo WCV_PRO_PUBLIC_ASSETS_URL; ?>svg/wcv-icons.svg#wcv-icon-facebook-square"></use>
				</svg>
			</a>
			</li><?php } ?>

		<?php if ( $instagram_username != '' ) { ?>
			<li>
			<a href="//instagram.com/<?php echo $instagram_username; ?>" target="_blank">
				<svg class="wcv-icon wcv-icon-sm">
					<use xlink:href="<?php echo WCV_PRO_PUBLIC_ASSETS_URL; ?>svg/wcv-icons.svg#wcv-icon-instagram"></use>
				</svg>
			</a>
			</li><?php } ?>

		<?php if ( $twitter_username != '' ) { ?>
			<li>
			<a href="//twitter.com/<?php echo $twitter_username; ?>" target="_blank">
				<svg class="wcv-icon wcv-icon-sm">
					<use xlink:href="<?php echo WCV_PRO_PUBLIC_ASSETS_URL; ?>svg/wcv-icons.svg#wcv-icon-twitter-square"></use>
				</svg>
			</a>
			</li><?php } ?>

		<?php if ( $googleplus_url != '' ) { ?>
			<li>
			<a href="<?php echo $googleplus_url; ?>" target="_blank">
				<svg class="wcv-icon wcv-icon-sm">
					<use xlink:href="<?php echo WCV_PRO_PUBLIC_ASSETS_URL; ?>svg/wcv-icons.svg#wcv-icon-google-plus"></use>
				</svg>
			</a>
			</li><?php } ?>

		<?php if ( $pinterest_url != '' ) { ?>
			<li>
			<a href="<?php echo $pinterest_url; ?>" target="_blank">
				<svg class="wcv-icon wcv-icon-sm">
					<use xlink:href="<?php echo WCV_PRO_PUBLIC_ASSETS_URL; ?>svg/wcv-icons.svg#wcv-icon-pinterest-square"></use>
				</svg>
			</a>
			</li><?php } ?>

		<?php if ( $youtube_url != '' ) { ?>
			<li>
			<a href="<?php echo $youtube_url; ?>" target="_blank">
				<svg class="wcv-icon wcv-icon-sm">
					<use xlink:href="<?php echo WCV_PRO_PUBLIC_ASSETS_URL; ?>svg/wcv-icons.svg#wcv-icon-youtube-square"></use>
				</svg>
			</a>
			</li><?php } ?>

		<?php if ( $linkedin_url != '' ) { ?>
			<li>
			<a href="<?php echo $linkedin_url; ?>" target="_blank">
				<svg class="wcv-icon wcv-icon-sm">
					<use xlink:href="<?php echo WCV_PRO_PUBLIC_ASSETS_URL; ?>svg/wcv-icons.svg#wcv-icon-linkedin"></use>
				</svg>
			</a>
			</li><?php } ?>

		<?php if ( $snapchat_username != '' ) { ?>
			<li>
			<a href="//www.snapchat.com/add/<?php echo $snapchat_username; ?>" target="_blank">
				<svg class="wcv-icon wcv-icon-sm">
					<use xlink:href="<?php echo WCV_PRO_PUBLIC_ASSETS_URL; ?>svg/wcv-icons.svg#wcv-icon-snapchat"></use>
				</svg>
			</a>
			</li><?php } ?>
	</ul>
	<?php
	return ob_get_clean();
}

/*
 * The defined product form templates available to set as default
 *
 * @since  1.6.2
 *
 * @return array $product_template defaults
 */
function wcv_get_product_templates() {

	return apply_filters(
		'wcv_product_form_templates',
		array(
			'standard' => __( 'Standard', 'wcvendors-pro' ),
			'simple'   => __( 'Simple Product', 'wcvendors-pro' ),
			'download' => __( 'Downloadable/Virtual Product', 'wcvendors-pro' ),
		)
	);

}

/**
 * Get shipping for a product
 *
 * @param $product - WC_Product object
 * @return array -
 *	shipping_system
 *	shipping_flat_rates
 *	shipping_table_rates
 *	store_country
 *	countries
 *	product
 *	store_rates
 *	shipping_policy
 *	return_policy
 *	free_shipping_order
 *	max_charge
 *	min_charge
 *	free_shipping_product
 *	max_charge_product
 *	product_handling_fee
 *
 *
 */
function wcv_get_product_shipping( $product ){

	$product_shipping 	  = array();
	$product_id           = $product->get_id();
	$settings             = get_option( 'woocommerce_wcv_pro_vendor_shipping_settings' );
	$vendor_id            = WCV_Vendors::get_vendor_from_product( $product_id );
	$store_rates          = get_user_meta( $vendor_id, '_wcv_shipping', true );
	$store_country        = ( $store_rates && $store_rates['shipping_from'] == 'other' ) ? strtolower( $store_rates['shipping_address']['country'] ) : strtolower( get_user_meta( $vendor_id, '_wcv_store_country', true ) );
	$store_state          = ( $store_rates && $store_rates['shipping_from'] == 'other' ) ? strtolower( $store_rates['shipping_address']['state'] ) : strtolower( get_user_meta( $vendor_id, '_wcv_store_state', true ) );
	$product_rates        = get_post_meta( $product_id, '_wcv_shipping_details', true );
	$countries            = WCVendors_Pro_Form_Helper::countries();
	$shipping_flat_rates  = array();
	$shipping_table_rates = array();
	$store_shipping_type  = get_user_meta( $vendor_id, '_wcv_shipping_type', true );
	$shipping_system      = ( ! empty( $store_shipping_type ) ) ? $store_shipping_type : $settings['shipping_system'];
	$store_check          = true;

	if ( ! $store_country ) {
		$store_country = WC()->countries->get_base_country();
	}

	// Product rates is empty so set to null
	if ( is_array( $product_rates ) && ! array_filter( $product_rates ) ) {
		$product_rates = null;
	}

	// Store rates is empty so set to null
	if ( is_array( $store_rates ) && ( array_key_exists( 'national', $store_rates ) && strlen( trim( $store_rates['national'] ) ) === 0 ) && ( array_key_exists( 'international', $store_rates ) && strlen( trim( $store_rates['international'] ) ) === 0 ) && ( array_key_exists( 'national_free', $store_rates ) && strlen( trim( $store_rates['national_free'] ) ) === 0 ) && ( array_key_exists( 'national_free', $store_rates ) && strlen( trim( $store_rates['international_free'] ) ) === 0 ) ) {
		$store_check = false;
	}

	// Get default country for admin.
	if ( ! WCV_Vendors::is_vendor( $vendor_id ) ) {
		$store_country = WC()->countries->get_base_country();
	}

	if ( $shipping_system == 'flat' ) {

		if ( is_array( $product_rates ) && ! empty( $product_rates['national'] ) || ! empty( $product_rates['international'] ) || ! empty( $product_rates['national_free'] ) || ! empty( $product_rates['international_free'] ) ) {

			$shipping_flat_rates = $product_rates;

		} elseif ( is_array( $store_rates ) && ! empty( $store_rates['national'] ) || ! empty( $store_rates['international'] ) || ! empty( $store_rates['national_free'] ) || ! empty( $store_rates['international_free'] ) ) {

			$shipping_flat_rates = $store_rates;

		} elseif ( $settings['national_cost'] >= 0 && $settings['international_cost'] >= 0 ) {

			$shipping_flat_rates['national']              = $settings['national_cost'];
			$shipping_flat_rates['international']         = $settings['international_cost'];
			$shipping_flat_rates['product_fee']           = $settings['product_fee'];
			$shipping_flat_rates['national_disable']      = $settings['national_disable'];
			$shipping_flat_rates['national_free']         = $settings['national_free'];
			$shipping_flat_rates['international_disable'] = $settings['international_disable'];
			$shipping_flat_rates['international_free']    = $settings['international_free'];

		}
	} else {

		$product_shipping_table = get_post_meta( $product_id, '_wcv_shipping_rates', true );
		$store_shipping_table   = get_user_meta( $vendor_id, '_wcv_shipping_rates', true );
		$global_shipping_table  = $settings['country_rate'];

		// Check to see if the product has any rates set.
		if ( is_array( $product_shipping_table ) && ! empty( $product_shipping_table ) ) {
			$shipping_table_rates = $product_shipping_table;
		} elseif ( is_array( $store_shipping_table ) && ! empty( $store_shipping_table ) ) {
			$shipping_table_rates = $store_shipping_table;
		} else {

			$shipping_table_rates = $global_shipping_table;
		}
	}

	$shipping_policy = ( empty( $store_rates['shipping_policy'] ) ) ? $settings['shipping_policy'] : $store_rates['shipping_policy'];
	$return_policy   = ( empty( $store_rates['return_policy'] ) ) ? $settings['return_policy'] : $store_rates['return_policy'];

	// Order level shipping
	$min_charge          = ! empty( $store_rates['min_charge'] ) ? $store_rates['min_charge'] : 0;
	$free_shipping_order = ! empty( $store_rates['free_shipping_order'] ) ? wc_price( $store_rates['free_shipping_order'] ) : '';
	$max_charge          = ! empty( $store_rates['max_charge'] ) ? wc_price( $store_rates['max_charge'] ) : '';
	$min_tax             = WCV_Shipping::calculate_shipping_tax( $min_charge, '', $product->get_shipping_class() );
	$min_charge          = ! empty( $min_charge ) ? wc_price( $min_charge + $min_tax ) : '';

	// Product Level shipping
	// Free Shipping per product
	if ( ! empty( $product_rates['free_shipping_product'] ) ) {

		$free_shipping_product = wc_price( $product_rates['free_shipping_product'] );

	} elseif ( empty( $product_rates['free_shipping_product'] ) && ! empty( $store_rates['free_shipping_product'] ) ) {

		$free_shipping_product = wc_price( $store_rates['free_shipping_product'] );

	} else {
		$free_shipping_product = '';
	}

	// Maximum shipping charged per product
	if ( ! empty( $product_rates['max_charge_product'] ) ) {

		$max_charge_product = wc_price( $product_rates['max_charge_product'] );

	} elseif ( empty( $product_rates['max_charge_product'] ) && ! empty( $store_rates['max_charge_product'] ) ) {

		$max_charge_product = wc_price( $store_rates['max_charge_product'] );

	} else {
		$max_charge_product = '';
	}

	// Product handling fee
	if ( ! empty( $product_rates['handling_fee'] ) ) {

		$product_handling_fee = wcv_percentage_to_price( $product_rates['handling_fee'], $product->get_price() );

	} elseif ( empty( $product_rates['handling_fee'] ) && ! empty( $store_rates['handling_fee'] ) ) {
		$product_handling_fee = wcv_percentage_to_price( $store_rates['handling_fee'], $product->get_price() );
	} else {
		$product_handling_fee = '';
	}


	$product_shipping = array(
			'shipping_system'       => $shipping_system,
			'shipping_flat_rates'   => $shipping_flat_rates,
			'shipping_table_rates'  => $shipping_table_rates,
			'store_country'         => $store_country,
			'countries'             => $countries,
			'product'               => $product,
			'store_rates'           => $store_rates,
			'shipping_policy'       => $shipping_policy,
			'return_policy'         => $return_policy,
			'free_shipping_order'   => $free_shipping_order,
			'max_charge'            => $max_charge,
			'min_charge'            => $min_charge,
			'free_shipping_product' => $free_shipping_product,
			'max_charge_product'    => $max_charge_product,
			'product_handling_fee'  => $product_handling_fee,

		);

	return apply_filters( 'wcv_get_product_shipping_rates', $product_shipping );

}
