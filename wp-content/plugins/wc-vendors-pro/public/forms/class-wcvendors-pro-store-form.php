<?php

/**
 * The WCVendors Pro Order Form Class
 *
 * This is the order form class
 *
 * @package    WCVendors_Pro
 * @subpackage WCVendors_Pro/public/forms
 * @author     Jamie Madden <support@wcvendors.com>
 */
class WCVendors_Pro_Store_Form {

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
	 * What form type is it settings or sign up
	 *
	 * @since    1.2.0
	 * @access   public
	 * @var      bool $form_type bool true for sign up form otherwise its the settings form for vendors
	 */
	public static $form_type = 'signup';

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

	}

	/**
	 *  Init variables for use in this class
	 *
	 * @since    1.2.0
	 */
	public function init() {

	}

	/**
	 *  Output required form data
	 *
	 * @since    1.2.0
	 *
	 * @param     int $post_id post_id for this meta if any
	 */
	public static function form_data( $form_type = 'settings' ) {

		self::$form_type = $form_type;

		wp_nonce_field( 'wcv-save_store_settings', '_wcv-save_store_settings' );

	} //form_data()

	/**
	 *  Output required sign up form data
	 *
	 * @since    1.0.0
	 *
	 * @param     int $post_id post_id for this meta if any
	 */
	public static function sign_up_form_data() {

		self::form_data( 'signup' );

		// Needed for processing the signup form
		WCVendors_Pro_Form_Helper::input(
			apply_filters(
				'wcv_vendor_application_id',
				array(
					'type'  => 'hidden',
					'id'    => '_wcv_vendor_application_id',
					'value' => get_current_user_id(),
				)
			)
		);

	} //sign_up_form_data()

	/**
	 *  Output the tabs for the settings or signup form.
	 *
	 * @since    1.2.0
	 */
	public static function store_form_tabs() {

		$hide_tabs_signup   = array();
		$hide_tabs_settings = array();

		if ( 'yes' === get_option( 'wcvendors_hide_signup_tab_payment' ) ) {
			$hide_tabs_signup[] = 'payment';
		}
		if ( 'yes' === get_option( 'wcvendors_hide_signup_tab_branding' ) ) {
			$hide_tabs_signup[] = 'branding';
		}
		if ( 'yes' === get_option( 'wcvendors_hide_signup_tab_shipping' ) ) {
			$hide_tabs_signup[] = 'shipping';
		}
		if ( 'yes' === get_option( 'wcvendors_hide_signup_tab_social' ) ) {
			$hide_tabs_signup[] = 'social';
		}
		if ( 'yes' === get_option( 'wcvendors_hide_signup_tab_seo' ) ) {
			$hide_tabs_signup[] = 'seo';
		}
		if (
			wc_string_to_bool( get_option( 'wcvendors_hide_signup_tab_policies' ) )
			|| (
				wc_string_to_bool( get_option( 'wcvendors_hide_signup_store_policy_privacy' ) )
				&& wc_string_to_bool( get_option( 'wcvendors_hide_signup_store_policy_terms' ) )
				&& wc_string_to_bool( get_option( 'wcvendors_hide_signup_shipping_shipping_policy' ) )
				&& wc_string_to_bool( get_option( 'wcvendors_hide_signup_shipping_return_policy' ) )
			)
		) {
			$hide_tabs_signup[] = 'policies';
		}

		if ( 'yes' === get_option( 'wcvendors_hide_settings_tab_payment' ) ) {
			$hide_tabs_settings[] = 'payment';
		}
		if ( 'yes' === get_option( 'wcvendors_hide_settings_tab_branding' ) ) {
			$hide_tabs_settings[] = 'branding';
		}
		if ( 'yes' === get_option( 'wcvendors_hide_settings_tab_shipping' ) ) {
			$hide_tabs_settings[] = 'shipping';
		}
		if ( 'yes' === get_option( 'wcvendors_hide_settings_tab_social' ) ) {
			$hide_tabs_settings[] = 'social';
		}
		if (
			wc_string_to_bool( get_option( 'wcvendors_hide_settings_tab_policies' ) )
			|| (
				wc_string_to_bool( get_option( 'wcvendors_hide_settings_store_policy_privacy' ) )
				&& wc_string_to_bool( get_option( 'wcvendors_hide_settings_store_policy_terms' ) )
				&& wc_string_to_bool( get_option( 'wcvendors_hide_settings_shipping_shipping_policy' ) )
				&& wc_string_to_bool( get_option( 'wcvendors_hide_settings_shipping_return_policy' ) )
			)
		) {
			$hide_tabs_settings[] = 'policies';
		}

		if ( 'yes' === get_option( 'wcvendors_hide_settings_tab_seo' ) ) {
			$hide_tabs_settings[] = 'seo';
		}

		$hide_tabs = ( 'signup' == self::$form_type ) ? $hide_tabs_signup : $hide_tabs_settings;

		$shipping_disabled       = wc_string_to_bool( get_option( 'wcvendors_shipping_management_cap', 'no' ) );
		$shipping_methods        = WC()->shipping->load_shipping_methods();
		$shipping_method_enabled = ( array_key_exists( 'wcv_pro_vendor_shipping', $shipping_methods ) && $shipping_methods['wcv_pro_vendor_shipping']->enabled == 'yes' ) ? true : false;
		$css_classes             = apply_filters( 'wcv_store_tabs_class', array( 'tabs-nav' ) );

		$store_tabs = apply_filters(
			'wcv_store_tabs',
			array(
				'store'    => array(
					'label'  => __( 'Store', 'wcvendors-pro' ),
					'target' => 'store',
					'class'  => array(),
				),
				'payment'  => array(
					'label'  => __( 'Payment', 'wcvendors-pro' ),
					'target' => 'payment',
					'class'  => array(),
				),
				'branding' => array(
					'label'  => __( 'Branding', 'wcvendors-pro' ),
					'target' => 'branding',
					'class'  => array(),
				),
				'shipping' => array(
					'label'  => __( 'Shipping', 'wcvendors-pro' ),
					'target' => 'shipping',
					'class'  => array(),
				),
				'social'   => array(
					'label'  => __( 'Social', 'wcvendors-pro' ),
					'target' => 'social',
					'class'  => array(),
				),
				'policies'   => array(
					'label'  => __( 'Policies', 'wcvendors-pro' ),
					'target' => 'policies',
					'class'  => array(),
				),
				'seo'      => array(
					'label'  => __( 'SEO', 'wcvendors-pro' ),
					'target' => 'seo',
					'class'  => array(),
				),
			)
		);

		foreach ( $hide_tabs as $tabs ) {

			if ( array_key_exists( $tabs, $store_tabs ) ) {
				unset( $store_tabs[ $tabs ] );
			}
		}

		// if ( $social_count == $social_total ) { unset( $store_tabs[ 'social' ] ); }
		$css_class = implode( ' ', $css_classes );

		if ( $shipping_disabled || ! $shipping_method_enabled ) {
			unset( $store_tabs['shipping'] );
		}

		include apply_filters( 'wcvendors_pro_store_form_store_tabs_path', 'partials/wcvendors-pro-store-tabs.php' );

	} // form_tabs()

	/**
	 *  Output save button
	 *
	 * @since    1.0.0
	 *
	 * @param     int $post_id post_id for this meta if any
	 */
	public static function save_button( $button_text ) {

		WCVendors_Pro_Form_helper::submit(
			apply_filters(
				'wcv_store_save_button',
				array(
					'id'    => 'store_save_button',
					'value' => $button_text,
					'class' => 'btn btn--primary btn-long',
				)
			)
		);

	} // save_button()

	/**
	 *  Output store banner uploader
	 *
	 * @since    1.2.0
	 */
	public static function store_banner() {

		$branding = ( 'signup' == self::$form_type ) ? get_option( 'wcvendors_hide_signup_branding_store_banner' ) : get_option( 'wcvendors_hide_settings_branding_store_banner' );

		if ( 'yes' !== $branding ) {

			$value = get_user_meta( get_current_user_id(), '_wcv_store_banner_id', true );

			echo '<h6>' . __( 'Store Banner', 'wcvendors-pro' ) . '</h6>';

			if ( 'signup' == self::$form_type ) {

				echo '<p>' . sprintf( __( 'Once you become a %s you can upload your banner here.', 'wcvendors-pro' ), wcv_get_vendor_name( true, false ) ) . '</p>';

			} else {

				// Store Banner Image
				WCVendors_Pro_Form_Helper::file_uploader(
					apply_filters(
						'wcv_vendor_store_banner',
						array(
							'id'             => '_wcv_store_banner_id',
							'header_text'    => __( 'Store Banner', 'wcvendors-pro' ),
							'add_text'       => __( 'Add Store Banner', 'wcvendors-pro' ),
							'remove_text'    => __( 'Remove Store Banner', 'wcvendors-pro' ),
							'image_meta_key' => '_wcv_store_banner_id',
							'save_button'    => __( 'Add Store Banner', 'wcvendors-pro' ),
							'window_title'   => __( 'Select an Image', 'wcvendors-pro' ),
							'value'          => $value,
						)
					)
				);

			}
		}

	} // store_banner()

	/**
	 *  Output store icon uploader
	 *
	 * @since    1.2.0
	 * @todo     dimension limits
	 */
	public static function store_icon() {

		$branding = ( 'signup' == self::$form_type ) ? get_option( 'wcvendors_hide_signup_branding_store_icon' ) : get_option( 'wcvendors_hide_settings_branding_store_icon' );

		if ( 'yes' !== $branding ) {

			$value = get_user_meta( get_current_user_id(), '_wcv_store_icon_id', true );

			echo '<h6>' . __( 'Store Icon', 'wcvendors-pro' ) . '</h6>';

			if ( 'signup' == self::$form_type ) {

				echo '<p>' . sprintf( __( 'Once you become a %s you can upload your store icon here.', 'wcvendors-pro' ), wcv_get_vendor_name( true, false ) ) . '</p>';

			} else {

				// Store Icon
				WCVendors_Pro_Form_Helper::file_uploader(
					apply_filters(
						'wcv_vendor_store_icon',
						array(
							'id'             => '_wcv_store_icon_id',
							'header_text'    => __( 'Store Icon', 'wcvendors-pro' ),
							'add_text'       => __( 'Add Store Icon', 'wcvendors-pro' ),
							'remove_text'    => __( 'Remove Store Icon', 'wcvendors-pro' ),
							'image_meta_key' => '_wcv_store_icon_id',
							'save_button'    => __( 'Add Store Icon', 'wcvendors-pro' ),
							'window_title'   => __( 'Select an Image', 'wcvendors-pro' ),
							'value'          => $value,
							'size'           => 'thumbnail',
							'class'          => 'wcv-store-icon',
						)
					)
				);

			}
		}

	} // store_icon()

	/**
	 *  Output paypal address
	 *
	 * @since    1.2.0
	 */
	public static function paypal_address() {

		$payment = ( 'signup' == self::$form_type ) ? get_option( 'wcvendors_hide_signup_payment_paypal' ) : get_option( 'wcvendors_hide_settings_payment_paypal' );

		if ( 'yes' !== $payment ) {

			$value = get_user_meta( get_current_user_id(), 'pv_paypal', true );

			// Paypal address
			WCVendors_Pro_Form_Helper::input(
				apply_filters(
					'wcv_vendor_paypal_address',
					array(
						'id'          => '_wcv_paypal_address',
						'label'       => __( 'PayPal Address', 'wcvendors-pro' ),
						'placeholder' => __( 'yourpaypaladdress@goeshere.com', 'wcvendors-pro' ),
						'desc_tip'    => 'true',
						'description' => __( 'Your PayPal address is used to send you your commission.', 'wcvendors-pro' ),
						'type'        => 'email',
						'value'       => $value,
					)
				)
			);
		}

	} // paypal_address()

	/**
	 *  Bank Account Name
	 *
	 * @since 1.5.0
	 */
	public static function bank_account_name() {

		$bank_account_name = ( 'signup' == self::$form_type ) ? get_option( 'wcvendors_hide_signup_payment_bank_account_name' ) : get_option( 'wcvendors_hide_settings_payment_bank_account_name' );

		if ( 'yes' !== $bank_account_name ) {

			$value = get_user_meta( get_current_user_id(), 'wcv_bank_account_name', true );

			// Paypal address
			WCVendors_Pro_Form_Helper::input(
				apply_filters(
					'wcv_vendor_bank_account_name',
					array(
						'id'          => 'wcv_bank_account_name',
						'label'       => __( 'Bank Account Name', 'wcvendors-pro' ),
						'placeholder' => __( '', 'wcvendors-pro' ),
						'type'        => 'text',
						'value'       => $value,
					)
				)
			);
		}

	} // bank_account_name()

	/**
	 *  Bank Account Number
	 *
	 * @since 1.5.0
	 */
	public static function bank_account_number() {

		$bank_account_number = ( 'signup' == self::$form_type ) ? get_option( 'wcvendors_hide_signup_payment_bank_account_number' ) : get_option( 'wcvendors_hide_settings_payment_bank_account_number' );

		if ( 'yes' !== $bank_account_number ) {

			$value = get_user_meta( get_current_user_id(), 'wcv_bank_account_number', true );

			// Paypal address
			WCVendors_Pro_Form_Helper::input(
				apply_filters(
					'wcv_vendor_bank_account_number',
					array(
						'id'          => 'wcv_bank_account_number',
						'label'       => __( 'Bank Account Number', 'wcvendors-pro' ),
						'placeholder' => __( '', 'wcvendors-pro' ),
						'type'        => 'text',
						'value'       => $value,
					)
				)
			);
		}

	} // bank_account_number()

	/**
	 *  Bank Account Name
	 *
	 * @since 1.5.0
	 */
	public static function bank_name() {

		$bank_name = ( 'signup' == self::$form_type ) ? get_option( 'wcvendors_hide_signup_payment_bank_name' ) : get_option( 'wcvendors_hide_settings_payment_bank_name' );

		if ( 'yes' !== $bank_name ) {

			$value = get_user_meta( get_current_user_id(), 'wcv_bank_name', true );

			// Paypal address
			WCVendors_Pro_Form_Helper::input(
				apply_filters(
					'wcv_vendor_bank_name',
					array(
						'id'          => 'wcv_bank_name',
						'label'       => __( 'Bank Name', 'wcvendors-pro' ),
						'placeholder' => __( '', 'wcvendors-pro' ),
						'type'        => 'text',
						'value'       => $value,
					)
				)
			);
		}

	} // bank_name()

	/**
	 *  Bank Account Name
	 *
	 * @since 1.5.0
	 */
	public static function bank_routing_number() {

		$bank_routing_number = ( 'signup' == self::$form_type ) ? get_option( 'wcvendors_hide_signup_payment_routing_number' ) : get_option( 'wcvendors_hide_settings_payment_routing_number' );

		if ( 'yes' !== $bank_routing_number ) {

			$value = get_user_meta( get_current_user_id(), 'wcv_bank_routing_number', true );

			// Paypal address
			WCVendors_Pro_Form_Helper::input(
				apply_filters(
					'wcv_vendor_bank_routing_number',
					array(
						'id'          => 'wcv_bank_routing_number',
						'label'       => __( 'Bank Routing Number', 'wcvendors-pro' ),
						'placeholder' => __( '', 'wcvendors-pro' ),
						'type'        => 'text',
						'value'       => $value,
					)
				)
			);
		}

	} // bank_routing_number()

	/**
	 *  Bank Iban
	 *
	 * @since 1.5.0
	 */
	public static function bank_iban() {

		$bank_iban = ( 'signup' == self::$form_type ) ? get_option( 'wcvendors_hide_signup_payment_iban' ) : get_option( 'wcvendors_hide_settings_payment_iban' );

		if ( 'yes' !== $bank_iban ) {

			$value = get_user_meta( get_current_user_id(), 'wcv_bank_iban', true );

			// Paypal address
			WCVendors_Pro_Form_Helper::input(
				apply_filters(
					'wcv_vendor_bank_routing_number',
					array(
						'id'          => 'wcv_bank_iban',
						'label'       => __( 'Bank IBAN', 'wcvendors-pro' ),
						'placeholder' => __( '', 'wcvendors-pro' ),
						'type'        => 'text',
						'value'       => $value,
					)
				)
			);
		}

	} // bank_iban()

	/**
	 *  Bank Iban
	 *
	 * @since 1.5.0
	 */
	public static function bank_bic_swift() {

		$bank_bic_swift = ( 'signup' == self::$form_type ) ? get_option( 'wcvendors_hide_signup_payment_bic_swift' ) : get_option( 'wcvendors_hide_settings_payment_bic_swift' );

		if ( 'yes' !== $bank_bic_swift ) {

			$value = get_user_meta( get_current_user_id(), 'wcv_bank_bic_swift', true );

			// Paypal address
			WCVendors_Pro_Form_Helper::input(
				apply_filters(
					'wcv_vendor_bank_bic_swift',
					array(
						'id'          => 'wcv_bank_bic_swift',
						'label'       => __( 'Bank BIC/SWIFT', 'wcvendors-pro' ),
						'placeholder' => __( '', 'wcvendors-pro' ),
						'type'        => 'text',
						'value'       => $value,
					)
				)
			);
		}

	} // bank_bic_swift()

	/**
	 *  Output store name
	 *
	 * @since    1.2.0
	 */
	public static function store_name( $store_name ) {

		if ( '' == $store_name ) {
			$user_data  = get_userdata( get_current_user_id() );
			$store_name = apply_filters( 'wcv_default_store_name', ucfirst( $user_data->display_name ) . __( ' Store', 'wcvendors-pro' ), $user_data );
		}

		// Store Name
		WCVendors_Pro_Form_Helper::input(
			apply_filters(
				'wcv_vendor_store_name',
				array(
					'id'                => '_wcv_store_name',
					'label'             => __( 'Store Name <small>Required</small>', 'wcvendors-pro' ),
					'placeholder'       => __( '', 'wcvendors-pro' ),
					'desc_tip'          => 'true',
					'description'       => __( 'Your shop name is public and must be unique.', 'wcvendors-pro' ),
					'type'              => 'text',
					'value'             => $store_name,
					'custom_attributes' => array(
						'required'                   => '',
						'data-parsley-error-message' => __( 'Store Name is required' ),
					),
				)
			)
		);

	} // store_name()

	/**
	 *  Output store name
	 *
	 * @since    1.2.0
	 */
	public static function store_phone() {

		$hide_store_phone = ( 'signup' == self::$form_type ) ? wc_string_to_bool( get_option( 'wcvendors_hide_signup_store_phone' ) ) : wc_string_to_bool( get_option( 'wcvendors_hide_settings_store_phone' ) );
		$required         = ( 'signup' == self::$form_type ) ? wc_string_to_bool( get_option( 'wcvendors_required_signup_store_phone' ) ) : wc_string_to_bool( get_option( 'wcvendors_required_settings_store_phone' ) );
		$required_attr    = $required ? array( 'required' => '' ) : array();

		if ( ! $hide_store_phone ) {

			$value = get_user_meta( get_current_user_id(), '_wcv_store_phone', true );

			// Store Name
			WCVendors_Pro_Form_Helper::input(
				apply_filters(
					'wcv_vendor_store_phone',
					array(
						'id'                => '_wcv_store_phone',
						'label'             => __( 'Store Phone', 'wcvendors-pro' ),
						'placeholder'       => __( 'Your store phone number', 'wcvendors-pro' ),
						'desc_tip'          => 'true',
						'description'       => __( 'This is your store contact number', 'wcvendors-pro' ),
						'type'              => 'text',
						'value'             => $value,
						'custom_attributes' => $required_attr,
					)
				)
			);

		}

	} // store_name()

	/**
	 *  Output store info
	 *
	 * @since    1.2.0
	 */
	public static function seller_info() {

		$hide_seller_info = ( 'signup' == self::$form_type ) ? wc_string_to_bool( get_option( 'wcvendors_hide_signup_store_seller_info' ) ) : wc_string_to_bool( get_option( 'wcvendors_hide_settings_store_seller_info' ) );
		$required         = ( 'signup' == self::$form_type ) ? wc_string_to_bool( get_option( 'wcvendors_required_signup_store_seller_info' ) ) : wc_string_to_bool( get_option( 'wcvendors_required_settings_store_seller_info' ) );
		$required_attr    = $required ? array( 'required' => '' ) : array();

		if ( ! $hide_seller_info ) {
			$user_id           = get_current_user_id();
			$value             = get_user_meta( $user_id, 'pv_seller_info', true );
			$vendor_store_html = get_user_meta( $user_id, 'pv_shop_html_enabled', true );
			$store_wide_html   = wc_string_to_bool( get_option( 'wcvendors_display_shop_description_html', 'no' ) );
			$enable_media      = wc_string_to_bool( get_option( 'wcvendors_allow_editor_media', 'no' ) );

			// If html in info is allowed then display the tinyMCE otherwise just display a text box.
			if ( $vendor_store_html || $store_wide_html ) {

				if ( $required ) {
					add_filter( 'the_editor', array( __CLASS__, 'wp_editor_required' ) );
					add_filter( 'tiny_mce_before_init', array( __CLASS__, 'wp_tinymce_required' ) );
					add_filter( 'teeny_mce_before_init', array( __CLASS__, 'wp_tinymce_required' ) );
				}

				$required_class = $required ? 'wcv-required' : '';

				$settings = apply_filters(
					'wcv_vendor_seller_info_editor_settings',
					array(
						'editor_height' => 200,
						'media_buttons' => $enable_media,
						'teeny'         => true,
						'tinymce'       => true,
						'editor_class'  => $required_class,
						'tinymce'       => array(
							'setup' => 'function (editor) {
							editor.on("change", function () {
								var content = tinyMCE.activeEditor.getContent( {format : "raw"} )
									.replace( \'<p><br data-mce-bogus="1"></p>\', "" );

								if ( content != undefined && content != "" ) {
									jQuery( "#" + editor.id ).html( content );
								}
							});
						}',
						),

					)
				);

				echo '<label>' . apply_filters( 'wcv_vendor_seller_info_editor', __( 'Seller Info', 'wcvendors-pro' ) ) . '</label>';

				wp_editor( $value, 'pv_seller_info', $settings );

			} else {

				WCVendors_Pro_Form_Helper::textarea(
					apply_filters(
						'wcv_vendor_seller_info',
						array(
							'id'                => 'pv_seller_info',
							'label'             => __( 'Seller Info', 'wcvendors-pro' ),
							'value'             => $value,
							'custom_attributes' => $required_attr,
						)
					)
				);

			}
		}

	} // description()

	/**
	 *  Output store description
	 *
	 * @since    1.2.0
	 */
	public static function store_description() {

		$hide_store_description = ( 'signup' == self::$form_type ) ? wc_string_to_bool( get_option( 'wcvendors_hide_signup_store_description' ) ) : wc_string_to_bool( get_option( 'wcvendors_hide_settings_store_description' ) );
		$required               = ( 'signup' == self::$form_type ) ? wc_string_to_bool( get_option( 'wcvendors_required_signup_store_description' ) ) : wc_string_to_bool( get_option( 'wcvendors_required_settings_store_description' ) );
		$required_attr          = $required ? array( 'required' => '' ) : array();

		if ( ! $hide_store_description ) {

			$user_id           = get_current_user_id();
			$vendor_store_html = get_user_meta( $user_id, 'pv_shop_html_enabled', true );
			$store_wide_html   = wc_string_to_bool( get_option( 'wcvendors_display_shop_description_html', 'no' ) );
			$enable_media      = wc_string_to_bool( get_option( 'wcvendors_allow_editor_media', 'no' ) );
			$value             = get_user_meta( get_current_user_id(), 'pv_shop_description', true );

			// If html in info is allowed then display the tinyMCE otherwise just display a text box.
			if ( $vendor_store_html || $store_wide_html ) {

				if ( $required ) {
					add_filter( 'the_editor', array( __CLASS__, 'wp_editor_required' ) );
					add_filter( 'tiny_mce_before_init', array( __CLASS__, 'wp_tinymce_required' ) );
					add_filter( 'teeny_mce_before_init', array( __CLASS__, 'wp_tinymce_required' ) );
				}

				$required_class = $required ? 'wcv-required' : '';

				$settings = apply_filters(
					'wcv_vendor_store_description_editor_settings',
					array(
						'editor_height' => 200,
						'media_buttons' => $enable_media,
						'teeny'         => true,
						'tinymce'       => true,
						'editor_class'  => $required_class,
						'tinymce'       => array(
							'setup' => 'function (editor) {
							editor.on("change", function () {
								var content = tinyMCE.activeEditor.getContent( {format : "raw"} )
									.replace( \'<p><br data-mce-bogus="1"></p>\', "" );

								if ( content != undefined && content != "" ) {
									jQuery( "#" + editor.id ).html( content );
								}
							});
						}',
						),
					)
				);

				echo '<label>' . __( 'Store Description', 'wcvendors-pro' ) . '</label>';

				wp_editor( $value, 'pv_shop_description', $settings );

			} else {

				WCVendors_Pro_Form_Helper::textarea(
					apply_filters(
						'wcv_vendor_store_description',
						array(
							'id'                => 'pv_shop_description',
							'label'             => __( 'Store Description', 'wcvendors-pro' ),
							'value'             => $value,
							'custom_attributes' => $required_attr,
						)
					)
				);
			}
		}

	} // description()

	/**
	 * Output a formatted store address country
	 *
	 * @since      1.2.0
	 *
	 * @param      int $post_id the post id for the files being uploaded
	 */
	public static function store_address_country() {

		$hide_store_country = ( 'signup' == self::$form_type ) ? wc_string_to_bool( get_option( 'wcvendors_hide_signup_store_address' ) ) : wc_string_to_bool( get_option( 'wcvendors_hide_settings_store_address' ) );
		$required           = ( 'signup' == self::$form_type ) ? wc_string_to_bool( get_option( 'wcvendors_required_signup_store_address' ) ) : wc_string_to_bool( get_option( 'wcvendors_required_settings_store_address' ) );
		$required_attr      = $required ? array( 'required' => '' ) : array();

		if ( ! $hide_store_country ) {

			$country = get_user_meta( get_current_user_id(), '_wcv_store_country', true );

			WCVendors_Pro_Form_Helper::country_select2(
				apply_filters(
					'wcv_vendor_store_country',
					array(
						'id'                => '_wcv_store_country',
						'label'             => __( 'Store Country', 'wcvendors-pro' ),
						'type'              => 'text',
						'value'             => $country,
						'custom_attributes' => $required_attr,
					)
				)
			);
		}

	} //store_address_country()

	/**
	 * Output a formatted store address1
	 *
	 * @since      1.2.0
	 *
	 * @param      int $post_id the post id for the files being uploaded
	 */
	public static function store_address1() {

		$hide_store_address1 = ( 'signup' == self::$form_type ) ? wc_string_to_bool( get_option( 'wcvendors_hide_signup_store_address' ) ) : wc_string_to_bool( get_option( 'wcvendors_hide_settings_store_address' ) );
		$required            = ( 'signup' == self::$form_type ) ? wc_string_to_bool( get_option( 'wcvendors_required_signup_store_address' ) ) : wc_string_to_bool( get_option( 'wcvendors_required_settings_store_address' ) );

		$hide_store_address_chooser = ( 'signup' == self::$form_type ) ? wc_string_to_bool( get_option( 'wcvendors_hide_signup_store_address_chooser' ) ) : wc_string_to_bool( get_option( 'wcvendors_hide_settings_store_address_chooser' ) );

		$api_key        = get_option( 'wcvendors_pro_google_maps_api_key' );
		$map_zoom_level = get_option( 'wcvendors_pro_google_maps_zoom_level', '' );
		$key_exists     = empty( $api_key ) ? false : true;

		$required_attr = $required ? array( 'required' => '' ) : array();

		if ( ! $hide_store_address1 ) {

			$address1 = get_user_meta( get_current_user_id(), '_wcv_store_address1', true );

			WCVendors_Pro_Form_Helper::input(
				apply_filters(
					'wcv_vendor_store_address1',
					array(
						'id'                => '_wcv_store_address1',
						'label'             => __( 'Store Address', 'wcvendors-pro' ),
						'placeholder'       => __( 'Street Address', 'wcvendors-pro' ),
						'type'              => 'text',
						'value'             => $address1,
						'custom_attributes' => $required_attr,
					)
				)
			);
		}

	} //store_address1()

	/**
	 * Output the show/hide map button
	 *
	 * @return    void
	 * @since      1.5.9
	 * @version    1.5.9
	 */
	public static function show_hide_map() {

		$hide_store_address_chooser = ( 'signup' == self::$form_type ) ? wc_string_to_bool( get_option( 'wcvendors_hide_signup_store_address_chooser' ) ) : wc_string_to_bool( get_option( 'wcvendors_hide_settings_store_address_chooser' ) );
		$hide_store_address1        = ( 'signup' == self::$form_type ) ? wc_string_to_bool( get_option( 'wcvendors_hide_signup_store_address' ) ) : wc_string_to_bool( get_option( 'wcvendors_hide_settings_store_address' ) );

		$api_key        = get_option( 'wcvendors_pro_google_maps_api_key' );
		$map_zoom_level = get_option( 'wcvendors_pro_google_maps_zoom_level', '' );
		$key_exists     = empty( $api_key ) ? false : true;

		$required = ( 'signup' == self::$form_type ) ? wc_string_to_bool( get_option( 'wcvendors_required_signup_store_address_chooser' ) ) : wc_string_to_bool( get_option( 'wcvendors_required_settings_store_address_chooser' ) );
		$required_attr = $required ? array( 'required' => '' ) : array();

		if ( $key_exists && ! empty( $map_zoom_level ) && ! $hide_store_address_chooser && ! $hide_store_address1 ) {
			echo '<div class="wcv-cols-group wcv-horizontal-gutters">';
			WCVendors_Pro_Form_Helper::input(
				apply_filters(
					'wcv_vendor_store_address1',
					array(
						'id'                => '_wcv_store_search_address',
						'label'             => '',
						'placeholder'       => __( 'Search address', 'wcvendors-pro' ),
						'type'              => 'text',
						'value'             => get_user_meta( get_current_user_id(), '_wcv_store_search_address', true ),
						'custom_attributes' => $required_attr,
						'wrapper_start'     => '<div class="all-60 small-100">',
						'wrapper_end'       => '</div>',
					)
				)
			);
			WCVendors_Pro_Form_Helper::html_element(
				apply_filters(
					'wcv_show_location_picker',
					array(
						'id'            => 'use_current_position',
						'element'       => 'a',
						'content'       => __( 'Your location', 'wcvendors-pro' ),
						'wrapper_start' => '<div class="all-20 small-100 control-group">',
						'wrapper_end'   => '</div>',
						'attributes'    => array(
							'class' => 'button',
							'href'  => '#',
						),
					)
				)
			);
			WCVendors_Pro_Form_Helper::html_element(
				apply_filters(
					'wcv_show_location_picker',
					array(
						'id'            => 'show_location_picker',
						'element'       => 'a',
						'content'       => __( 'Show map', 'wcvendors-pro' ),
						'wrapper_start' => '<div class="all-20 small-100 control-group">',
						'wrapper_end'   => '</div>',
						'attributes'    => array(
							'class' => 'button',
							'href'  => '#',
						),
					)
				)
			);
			echo '</div>';
		}
	}

	/**
	 * Output map for location picker and its latitude and longitude fields
	 *
	 * @return void
	 * @ver
	 */
	public static function location_picker() {

		$hide_store_address_chooser = ( 'signup' == self::$form_type ) ? wc_string_to_bool( get_option( 'wcvendors_hide_signup_store_address_chooser' ) ) : wc_string_to_bool( get_option( 'wcvendors_hide_settings_store_address_chooser' ) );
		$hide_store_address1        = ( 'signup' == self::$form_type ) ? wc_string_to_bool( get_option( 'wcvendors_hide_signup_store_address' ) ) : wc_string_to_bool( get_option( 'wcvendors_hide_settings_store_address' ) );

		$api_key        = get_option( 'wcvendors_pro_google_maps_api_key' );
		$map_zoom_level = get_option( 'wcvendors_pro_google_maps_zoom_level', '' );
		$key_exists     = empty( $api_key ) ? false : true;

		$required = ( 'signup' == self::$form_type ) ? wc_string_to_bool( get_option( 'wcvendors_required_signup_store_address_chooser' ) ) : wc_string_to_bool( get_option( 'wcvendors_required_settings_store_address_chooser' ) );

		if ( $required ) {
			$required_attr = array( 'required' => '' );
		}

		if ( $key_exists && ! empty( $map_zoom_level ) && ! $hide_store_address_chooser && ! $hide_store_address1 ) {

			$visibility = apply_filters( 'wcvendors_pro_location_picker_default_visibility', get_option( 'wcvendors_pro_location_picker_default_visibility' ) );
			$visibility = $visibility == 'visible' ? 'block' : 'none';

			WCVendors_Pro_Form_Helper::html_element(
				apply_filters(
					'wcv_location_picker',
					array(
						'id'            => 'wcv_location_picker',
						'element'       => 'div',
						'attributes'    => array(
							'style' => 'width: 100%; height: 400px; display:' . $visibility,
						),
						'wrapper_start' => '<div class="control-group"><div class="all-100 small-100">',
						'wrapper_end'   => '</div></div>',
					)
				)
			);
		}
	} // location_picker()

	/**
	 * Output the latitude and longitude fields
	 *
	 * @return    void
	 * @since      1.5.9
	 * @version    1.5.9
	 */
	public static function coordinates() {

		$hide_store_address_chooser = ( 'signup' == self::$form_type ) ? wc_string_to_bool( get_option( 'wcvendors_hide_signup_store_address_chooser' ) ) : wc_string_to_bool( get_option( 'wcvendors_hide_settings_store_address_chooser' ) );
		$hide_store_address1        = ( 'signup' == self::$form_type ) ? wc_string_to_bool( get_option( 'wcvendors_hide_signup_store_address' ) ) : wc_string_to_bool( get_option( 'wcvendors_hide_settings_store_address' ) );

		$api_key        = get_option( 'wcvendors_pro_google_maps_api_key' );
		$map_zoom_level = get_option( 'wcvendors_pro_google_maps_zoom_level', '' );
		$key_exists     = empty( $api_key ) ? false : true;

		if ( $key_exists && ! empty( $map_zoom_level ) && ! $hide_store_address_chooser && ! $hide_store_address1 ) {
			$latitude  = get_user_meta( get_current_user_id(), 'wcv_address_latitude', true );
			$longitude = get_user_meta( get_current_user_id(), 'wcv_address_longitude', true );

			WCVendors_Pro_Form_Helper::input(
				apply_filters(
					'wcv_address_latitude',
					array(
						'id'            => 'wcv_address_latitude',
						'placeholder'   => __( 'Latitude', 'wcvendors-pro' ),
						'label'         => __( 'Latitude', 'wcvendors-pro' ),
						'type'          => 'text',
						'value'         => $latitude,
						'desc'          => __( 'This value will be set automatically after choosing your address.', 'wcvendors-pro' ),
						'attributes'    => array( 'readonly' => 'readonly' ),
						'wrapper_start' => '<div class="wcv-cols-group wcv-horizontal-gutters"><div class="all-50 small-100">',
						'wrapper_end'   => '</div>',
					)
				)
			);

			WCVendors_Pro_Form_Helper::input(
				apply_filters(
					'wcv_address_longitude',
					array(
						'id'            => 'wcv_address_longitude',
						'placeholder'   => __( 'Longitude', 'wcvendors-pro' ),
						'label'         => __( 'Longitude', 'wcvendors-pro' ),
						'type'          => 'text',
						'value'         => $longitude,
						'desc'          => __( 'This value will be set automatically after choosing your address.', 'wcvendors-pro' ),
						'attributes'    => array( 'readonly' => 'readonly' ),
						'wrapper_start' => '<div class="all-50 small-100">',
						'wrapper_end'   => '</div></div>',
					)
				)
			);
		}
	}

	/**
	 * Output a formatted store address2
	 *
	 * @since      1.2.0
	 *
	 * @param      int $post_id the post id for the files being uploaded
	 */
	public static function store_address2() {

		$hide_store_address2 = ( 'signup' == self::$form_type ) ? wc_string_to_bool( get_option( 'wcvendors_hide_signup_store_address' ) ) : wc_string_to_bool( get_option( 'wcvendors_hide_settings_store_address' ) );

		if ( ! $hide_store_address2 ) {

			$address2 = get_user_meta( get_current_user_id(), '_wcv_store_address2', true );

			WCVendors_Pro_Form_Helper::input(
				apply_filters(
					'wcv_vendor_store_address2',
					array(
						'id'          => '_wcv_store_address2',
						'placeholder' => __( 'Apartment, unit, suite etc. ', 'wcvendors-pro' ),
						'type'        => 'text',
						'label'		  => '',
						'value'       => $address2,
					)
				)
			);
		}

	} //store_address2()

	/**
	 * Output a formatted store address city
	 *
	 * @since      1.2.0
	 *
	 * @param      int $post_id the post id for the files being uploaded
	 */
	public static function store_address_city() {

		$hide_store_city = ( 'signup' == self::$form_type ) ? wc_string_to_bool( get_option( 'wcvendors_hide_signup_store_address' ) ) : wc_string_to_bool( get_option( 'wcvendors_hide_settings_store_address' ) );

		if ( ! $hide_store_city ) {

			$city = get_user_meta( get_current_user_id(), '_wcv_store_city', true );

			WCVendors_Pro_Form_Helper::input(
				apply_filters(
					'wcv_vendor_store_city',
					array(
						'id'          => '_wcv_store_city',
						'label'       => __( 'City / Town', 'wcvendors-pro' ),
						'placeholder' => __( 'City / Town', 'wcvendors-pro' ),
						'type'        => 'text',
						'value'       => $city,
					)
				)
			);
		}

	} //store_address_city()

	/**
	 * Output a formatted store address state
	 *
	 * @since      1.2.0
	 *
	 * @param      int $post_id the post id for the files being uploaded
	 */
	public static function store_address_state() {

		$hide_store_state = ( 'signup' == self::$form_type ) ? wc_string_to_bool( get_option( 'wcvendors_hide_signup_store_address' ) ) : wc_string_to_bool( get_option( 'wcvendors_hide_settings_store_address' ) );

		if ( ! $hide_store_state ) {

			$state = get_user_meta( get_current_user_id(), '_wcv_store_state', true );

			WCVendors_Pro_Form_Helper::input(
				apply_filters(
					'wcv_vendor_store_state',
					array(
						'id'            => '_wcv_store_state',
						'label'         => __( 'State / County', 'wcvendors-pro' ),
						'placeholder'   => __( 'State / County', 'wcvendors-pro' ),
						'value'         => $state,
						'wrapper_start' => '<div class="wcv-cols-group wcv-horizontal-gutters"><div class="all-50 small-100">',
						'wrapper_end'   => '</div>',
					)
				)
			);

		}

	} //store_address_state()

	/**
	 * Output a formatted store address postcode
	 *
	 * @since      1.2.0
	 *
	 * @param      int $post_id the post id for the files being uploaded
	 */
	public static function store_address_postcode() {

		$hide_store_postcode = ( 'signup' == self::$form_type ) ? wc_string_to_bool( get_option( 'wcvendors_hide_signup_store_address' ) ) : wc_string_to_bool( get_option( 'wcvendors_hide_settings_store_address' ) );

		if ( ! $hide_store_postcode ) {

			$postcode = get_user_meta( get_current_user_id(), '_wcv_store_postcode', true );

			WCVendors_Pro_Form_Helper::input(
				apply_filters(
					'wcv_vendor_store_postcode',
					array(
						'id'            => '_wcv_store_postcode',
						'label'         => __( 'Postcode / Zip', 'wcvendors-pro' ),
						'placeholder'   => __( 'Postcode / Zip', 'wcvendors-pro' ),
						'value'         => $postcode,
						'wrapper_start' => '<div class="all-50 small-100">',
						'wrapper_end'   => '</div></div>',
					)
				)
			);

		}

	} //store_address_state()

	/**
	 *  Output company url field
	 *
	 * @since    1.2.0
	 */
	public static function company_url() {

		$hide_store_company_url = ( 'signup' == self::$form_type ) ? wc_string_to_bool( get_option( 'wcvendors_hide_signup_store_company_url' ) ) : wc_string_to_bool( get_option( 'wcvendors_hide_settings_store_company_url' ) );

		if ( ! $hide_store_company_url ) {

			$value = get_user_meta( get_current_user_id(), '_wcv_company_url', true );

			// Company URL
			WCVendors_Pro_Form_Helper::input(
				apply_filters(
					'wcv_vendor_company_url',
					array(
						'id'          => '_wcv_company_url',
						'label'       => __( 'Store Website / Blog URL', 'wcvendors-pro' ),
						'placeholder' => __( 'https://yourcompany-blogurl.com/here', 'wcvendors-pro' ),
						'type'        => 'url',
						'value'       => $value,
					)
				)
			);
		}

	} // company_url()

	/**
	 *  Output twitter username field
	 *
	 * @since    1.2.0
	 */
	public static function twitter_username() {

		$social = ( 'signup' == self::$form_type ) ? get_option( 'wcvendors_hide_signup_social_twitter' ) : get_option( 'wcvendors_hide_settings_social_twitter' );

		if ( 'yes' !== $social ) {

			$value = get_user_meta( get_current_user_id(), '_wcv_twitter_username', true );

			// Twitter Username
			WCVendors_Pro_Form_Helper::input(
				apply_filters(
					'wcv_vendor_twitter_username',
					array(
						'id'          => '_wcv_twitter_username',
						'label'       => __( 'Twitter Username', 'wcvendors-pro' ),
						'placeholder' => __( 'YourTwitterUserHere', 'wcvendors-pro' ),
						'desc_tip'    => 'true',
						'description' => __( 'Your <a href="https://twitter.com/">Twitter</a> username without the url.', 'wcvendors-pro' ),
						'type'        => 'text',
						'value'       => $value,
					)
				)
			);

		}

	} // twitter_username()

	/**
	 *  Output instagram username field
	 *
	 * @since    1.2.0
	 */
	public static function instagram_username() {

		$social = ( 'signup' == self::$form_type ) ? get_option( 'wcvendors_hide_signup_social_instagram' ) : get_option( 'wcvendors_hide_settings_social_instagram' );

		if ( 'yes' !== $social ) {

			$value = get_user_meta( get_current_user_id(), '_wcv_instagram_username', true );

			// Instagram Username
			WCVendors_Pro_Form_Helper::input(
				apply_filters(
					'wcv_vendor_instagram_username',
					array(
						'id'          => '_wcv_instagram_username',
						'label'       => __( 'Instagram Username', 'wcvendors-pro' ),
						'placeholder' => __( 'YourInstagramUsername', 'wcvendors-pro' ),
						'desc_tip'    => 'true',
						'description' => __( 'Your <a href="https://instagram.com/">Instagram</a> username without the url.', 'wcvendors-pro' ),
						'type'        => 'text',
						'value'       => $value,
					)
				)
			);

		}

	} // instagram_username()

	/**
	 *  Output facebook url field
	 *
	 * @since    1.2.0
	 */
	public static function facebook_url() {

		$social = ( 'signup' == self::$form_type ) ? get_option( 'wcvendors_hide_signup_social_facebook' ) : get_option( 'wcvendors_hide_settings_social_facebook' );

		if ( 'yes' !== $social ) {

			$value = get_user_meta( get_current_user_id(), '_wcv_facebook_url', true );

			// Facebook URL
			WCVendors_Pro_Form_Helper::input(
				apply_filters(
					'wcv_vendor_facebook_url',
					array(
						'id'          => '_wcv_facebook_url',
						'label'       => __( 'Facebook URL', 'wcvendors-pro' ),
						'placeholder' => __( 'http://yourfacebookurl/here', 'wcvendors-pro' ),
						'desc_tip'    => 'true',
						'description' => __( 'Your <a href="https://facebook.com/">Facebook</a> url.', 'wcvendors-pro' ),
						'type'        => 'text',
						'value'       => $value,
					)
				)
			);

		}

	} // facebook_url()

	/**
	 *  Output LinkedIn url field
	 *
	 * @since    1.2.0
	 */
	public static function linkedin_url() {

		$social = ( 'signup' == self::$form_type ) ? get_option( 'wcvendors_hide_signup_social_linkedin' ) : get_option( 'wcvendors_hide_settings_social_linkedin' );

		if ( 'yes' !== $social ) {

			$value = get_user_meta( get_current_user_id(), '_wcv_linkedin_url', true );

			// LInkedIn URL
			WCVendors_Pro_Form_Helper::input(
				apply_filters(
					'wcv_vendor_linkedin_url',
					array(
						'id'          => '_wcv_linkedin_url',
						'label'       => __( 'LinkedIn URL', 'wcvendors-pro' ),
						'placeholder' => __( 'http://linkedinurl.com/here', 'wcvendors-pro' ),
						'desc_tip'    => 'true',
						'description' => __( 'Your <a href="https://linkedin.com/">LinkedIn</a> url.', 'wcvendors-pro' ),
						'type'        => 'url',
						'value'       => $value,
					)
				)
			);

		}

	} // linkedin_url()

	/**
	 *  Output youtube url field
	 *
	 * @since    1.2.0
	 */
	public static function youtube_url() {

		$social = ( 'signup' == self::$form_type ) ? get_option( 'wcvendors_hide_signup_social_youtube' ) : get_option( 'wcvendors_hide_settings_social_youtube' );

		if ( 'yes' !== $social ) {

			$value = get_user_meta( get_current_user_id(), '_wcv_youtube_url', true );

			// Youtube URL
			WCVendors_Pro_Form_Helper::input(
				apply_filters(
					'wcv_vendor_youtube_url',
					array(
						'id'          => '_wcv_youtube_url',
						'label'       => __( 'YouTube URL', 'wcvendors-pro' ),
						'placeholder' => __( 'http://youtube.com/here', 'wcvendors-pro' ),
						'desc_tip'    => 'true',
						'description' => __( 'Your <a href="https://youtube.com/">Youtube</a> url.', 'wcvendors-pro' ),
						'type'        => 'url',
						'value'       => $value,
					)
				)
			);

		}

	} // youtube_url()

	/**
	 *  Output youtube url field
	 *
	 * @since    1.2.0
	 */
	public static function googleplus_url() {

		$social = ( 'signup' == self::$form_type ) ? get_option( 'wcvendors_hide_signup_social_google_plus' ) : get_option( 'wcvendors_hide_settings_social_google_plus' );

		if ( 'yes' !== $social ) {

			$value = get_user_meta( get_current_user_id(), '_wcv_googleplus_url', true );

			// Google+ URL
			WCVendors_Pro_Form_Helper::input(
				apply_filters(
					'wcv_vendor_googleplus_url',
					array(
						'id'          => '_wcv_googleplus_url',
						'label'       => __( 'Google+ URL', 'wcvendors-pro' ),
						'placeholder' => __( 'https://plus.google.com/yourname', 'wcvendors-pro' ),
						'desc_tip'    => 'true',
						'description' => __( 'Your <a href="https://plus.google.com">Google+</a> url.', 'wcvendors-pro' ),
						'type'        => 'url',
						'value'       => $value,
					)
				)
			);

		}

	} // googleplus_url()

	/**
	 *  Output pintrest url field
	 *
	 * @since    1.2.0
	 */
	public static function pinterest_url() {

		$social = ( 'signup' == self::$form_type ) ? get_option( 'wcvendors_hide_signup_social_pinterest' ) : get_option( 'wcvendors_hide_settings_social_pinterest' );

		if ( 'yes' !== $social ) {

			$value = get_user_meta( get_current_user_id(), '_wcv_pinterest_url', true );

			// Pinterest URL
			WCVendors_Pro_Form_Helper::input(
				apply_filters(
					'wcv_vendor_pinterest_url',
					array(
						'id'          => '_wcv_pinterest_url',
						'label'       => __( 'Pinterest URL', 'wcvendors-pro' ),
						'placeholder' => __( 'https://www.pinterest.com/username/', 'wcvendors-pro' ),
						'desc_tip'    => 'true',
						'description' => __( 'Your <a href="https://www.pinterest.com/">Pinterest</a> url.', 'wcvendors-pro' ),
						'type'        => 'url',
						'value'       => $value,
					)
				)
			);

		}

	} // pinterest_url()

	/**
	 *  Output snapchat username field
	 *
	 * @since    1.2.0
	 */
	public static function snapchat_username() {

		$social = ( 'signup' == self::$form_type ) ? get_option( 'wcvendors_hide_signup_social_snapchat' ) : get_option( 'wcvendors_hide_settings_social_snapchat' );

		if ( 'yes' !== $social ) {

			$value = get_user_meta( get_current_user_id(), '_wcv_snapchat_username', true );

			// Instagram Username
			WCVendors_Pro_Form_Helper::input(
				apply_filters(
					'wcv_vendor_snapchat_username',
					array(
						'id'          => '_wcv_snapchat_username',
						'label'       => __( 'Snapchat Username', 'wcvendors-pro' ),
						'placeholder' => __( 'snapchatUsername', 'wcvendors-pro' ),
						'desc_tip'    => 'true',
						'description' => __( 'Your snapchat username.', 'wcvendors-pro' ),
						'type'        => 'text',
						'value'       => $value,
					)
				)
			);

		}

	} // snapchat_username()


	/**
	 *
	 *    Shipping Information
	 */


	/**
	 *  Output default national shipping fee field
	 *
	 * @since    1.2.0
	 */
	public static function shipping_fee_national( $shipping_details ) {

		$value = ( is_array( $shipping_details ) && array_key_exists( 'national', $shipping_details ) ) ? $shipping_details['national'] : '';

		// Shipping Fee
		WCVendors_Pro_Form_Helper::input(
			apply_filters(
				'wcv_shipping_national_fee',
				array(
					'id'          => '_wcv_shipping_fee_national',
					'label'       => __( 'Default National Shipping Fee', 'wcvendors-pro' ),
					'placeholder' => __( '0', 'wcvendors-pro' ),
					'desc_tip'    => 'number',
					'description' => __( 'The default shipping fee within your country, this can be overridden on a per product basis.', 'wcvendors-pro' ),
					'data_type'   => 'price',
					'class'       => 'wcv-disable-national-input',
					'value'       => $value,
				)
			)
		);

	} // shipping_fee_national()

	/**
	 *  Output default national shipping qty override field
	 *
	 * @since    1.2.0
	 */
	public static function shipping_fee_national_qty( $shipping_details ) {

		$qty_value = ( is_array( $shipping_details ) && array_key_exists( 'national_qty_override', $shipping_details ) ) ? $shipping_details['national_qty_override'] : 0;

		// QTY Override
		WCVendors_Pro_Form_Helper::input(
			apply_filters(
				'wcv_shipping_national_qty',
				array(
					'id'    => '_wcv_shipping_fee_national_qty',
					'label' => __( 'Charge once per product for national shipping, even if more than one is purchased.', 'wcvendors-pro' ),
					'type'  => 'checkbox',
					'class' => 'wcv-disable-national-input',
					'value' => $qty_value,
				)
			)
		);

	} // shipping_fee_national_qty()

	/**
	 *  Output default national shipping qty override field
	 *
	 * @since    1.0.0
	 */
	public static function shipping_fee_national_free( $shipping_details ) {

		$free = ( is_array( $shipping_details ) && array_key_exists( 'national_free', $shipping_details ) ) ? $shipping_details['national_free'] : 0;

		// QTY Override
		WCVendors_Pro_Form_Helper::input(
			apply_filters(
				'wcv_shipping_national_free',
				array(
					'id'    => '_wcv_shipping_fee_national_free',
					'label' => __( 'Free national shipping.', 'wcvendors-pro' ),
					'type'  => 'checkbox',
					'class' => 'wcv-disable-national-input',
					'value' => $free,
				)
			)
		);

	} // shipping_fee_national_qty()

	/**
	 *  Output default national shipping qty override field
	 *
	 * @since    1.0.0
	 */
	public static function shipping_fee_national_disable( $shipping_details ) {

		$disable = ( is_array( $shipping_details ) && array_key_exists( 'national_disable', $shipping_details ) ) ? $shipping_details['national_disable'] : 0;

		// QTY Override
		WCVendors_Pro_Form_Helper::input(
			apply_filters(
				'wcv_shipping_national_disable',
				array(
					'id'    => '_wcv_shipping_fee_national_disable',
					'label' => __( 'Disable national shipping.', 'wcvendors-pro' ),
					'type'  => 'checkbox',
					'value' => $disable,
				)
			)
		);

	} // shipping_fee_national_qty()

	/**
	 *  Output default international shipping fee field
	 *
	 * @since    1.0.0
	 */
	public static function shipping_fee_international( $shipping_details ) {

		$value = ( is_array( $shipping_details ) && array_key_exists( 'international', $shipping_details ) ) ? $shipping_details['international'] : '';

		// Shipping Fee
		WCVendors_Pro_Form_Helper::input(
			apply_filters(
				'wcv_shipping_international_fee',
				array(
					'id'          => '_wcv_shipping_fee_international',
					'label'       => __( 'Default International Shipping Fee', 'wcvendors-pro' ),
					'placeholder' => __( '0', 'wcvendors-pro' ),
					'desc_tip'    => 'true',
					'description' => __( 'The default shipping fee outside your country, this can be overridden on a per product basis. ', 'wcvendors-pro' ),
					'data_type'   => 'price',
					'class'       => 'wcv-disable-international-input',
					'value'       => $value,
				)
			)
		);

	} // shipping_fee_international()

	/**
	 *  Output default international shipping fee field
	 *
	 * @since    1.0.0
	 */
	public static function shipping_fee_international_qty( $shipping_details ) {

		$qty_value = ( is_array( $shipping_details ) && array_key_exists( 'international_qty_override', $shipping_details ) ) ? $shipping_details['international_qty_override'] : 0;

		// QTY Override
		WCVendors_Pro_Form_Helper::input(
			apply_filters(
				'wcv_shipping_international_qty',
				array(
					'id'    => '_wcv_shipping_fee_international_qty',
					'label' => __( 'Charge once per product for international shipping, even if more than one is purchased.', 'wcvendors-pro' ),
					'type'  => 'checkbox',
					'class' => 'wcv-disable-international-input',
					'value' => $qty_value,
				)
			)
		);

	} // shipping_fee_international_qty()

	/**
	 *  Output default international shipping free field
	 *
	 * @since    1.0.0
	 */
	public static function shipping_fee_international_free( $shipping_details ) {

		$free = ( is_array( $shipping_details ) && array_key_exists( 'international_free', $shipping_details ) ) ? $shipping_details['international_free'] : 0;

		// QTY Override
		WCVendors_Pro_Form_Helper::input(
			apply_filters(
				'wcv_shipping_international_free',
				array(
					'id'    => '_wcv_shipping_fee_international_free',
					'label' => __( 'Free international shipping.', 'wcvendors-pro' ),
					'type'  => 'checkbox',
					'class' => 'wcv-disable-international-input',
					'value' => $free,
				)
			)
		);

	} // shipping_fee_international_free()

	public static function vendor_shipping_type() {

		$shipping_settings    	= get_option( 'woocommerce_wcv_pro_vendor_shipping_settings' );
		$store_shipping_type   	= get_user_meta( get_current_user_id(), '_wcv_shipping_type', true );
		$vendor_select  		= wc_string_to_bool( get_option( 'wcvendors_vendor_select_shipping', 'no' ) );
		$shipping_type      	= ( $store_shipping_type != '' ) ? $store_shipping_type : $shipping_settings['shipping_system'];

		if ( ! $vendor_select ) {
			return;
		}

		WCVendors_Pro_Form_Helper::select(
			apply_filters(
				'wcv_vendor_shipping_type',
				array(
					'id'                => '_wcv_shipping_type',
					'type'              => 'select',
					'label'             => __('Shipping Type', 'wcvendors-pro'),
					'class'             => 'wcv-shipping-type',
					'options'           => array_merge( array( '' => '' ), WCVendors_Pro_Shipping_Controller::shipping_types() ),
					'value'             => $shipping_type,
				)
			)
		);
	}

	/**
	 *  Output default international shipping free field
	 *
	 * @since    1.0.0
	 */
	public static function shipping_fee_international_disable( $shipping_details ) {

		$disable = ( is_array( $shipping_details ) && array_key_exists( 'international_disable', $shipping_details ) ) ? $shipping_details['international_disable'] : 0;

		// QTY Override
		WCVendors_Pro_Form_Helper::input(
			apply_filters(
				'wcv_shipping_international_disable',
				array(
					'id'    => '_wcv_shipping_fee_international_disable',
					'label' => __( 'Disable international shipping.', 'wcvendors-pro' ),
					'type'  => 'checkbox',
					'value' => $disable,
				)
			)
		);

	} // shipping_fee_international_free()

	/**
	 *  Output the shipping rate depending on the admin settings
	 *
	 * @since    1.0.0
	 */
	public static function shipping_rates() {

		$shipping_settings     = get_option( 'woocommerce_wcv_pro_vendor_shipping_settings' );
		$shipping_details      = get_user_meta( get_current_user_id(), '_wcv_shipping', true );
		$national_disable      = wc_string_to_bool( $shipping_settings['national_disable'] );
		$international_disable = wc_string_to_bool( $shipping_settings['international_disable'] );

		echo '<div id="shipping-flat-rates" class="wcv-cols-group wcv-horizontal-gutters">';

		if ( ! $national_disable ) {
			if ( $international_disable ) {
				echo '<div class="all-100 small-100">';
			} else {
				echo '<div class="all-50 small-100">';
			}
			self::shipping_fee_national( $shipping_details );
			self::shipping_fee_national_free( $shipping_details );
			self::shipping_fee_national_qty( $shipping_details );
			self::shipping_fee_national_disable( $shipping_details );
			echo '</div>';

		}

		if ( ! $international_disable ) {
			if ( $national_disable ) {
				echo '<div class="all-100 small-100">';
			} else {
				echo '<div class="all-50 small-100">';
			}
			self::shipping_fee_international( $shipping_details );
			self::shipping_fee_international_qty( $shipping_details );
			self::shipping_fee_international_disable( $shipping_details );
			self::shipping_fee_international_free( $shipping_details );
			echo '</div>';

		}

		echo '</div>';

		self::shipping_rate_table();

		// Backwards compatability
		// This has been moved into the store-settings template for 1.3.7 and above.
		if ( version_compare( WCV_PRO_VERSION, '1.3.7', '<' ) ) {

			self::product_handling_fee( $shipping_details );
			self::shipping_policy( $shipping_details );
			self::return_policy( $shipping_details );
			self::shipping_from( $shipping_details );
			self::shipping_address( $shipping_details );

		}

	} // shipping_rates()

	/**
	 *  Output default product handling fee field
	 *
	 * @since    1.0.0
	 */
	public static function product_handling_fee( $shipping_details ) {

		$shipping = ( 'signup' == self::$form_type ) ? get_option( 'wcvendors_hide_signup_shipping_handling_fee' ) : get_option( 'wcvendors_hide_settings_shipping_handling_fee' );

		if ( 'yes' !== $shipping ) {

			$value = ( is_array( $shipping_details ) && array_key_exists( 'product_handling_fee', $shipping_details ) ) ? $shipping_details['product_handling_fee'] : '';

			// Product handling Fee
			WCVendors_Pro_Form_Helper::input(
				apply_filters(
					'wcv_shipping_product_fee',
					array(
						'id'          => '_wcv_shipping_product_handling_fee',
						'label'       => __( 'Product handling fee', 'wcvendors-pro' ),
						'placeholder' => __( 'Leave empty to disable', 'wcvendors-pro' ),
						'desc_tip'    => 'true',
						'description' => __( 'The product handling fee, this can be overridden on a per product basis. Amount (5.00) or Percentage (5%).', 'wcvendors-pro' ),
						'type'        => 'text',
						'value'       => $value,
					)
				)
			);

		}

	} // product_handling_fee()

	/**
	 *  Output the max order spend for shipping
	 *
	 * @since    1.0.0
	 */
	public static function order_min_charge( $shipping_details ) {

		$value = ( is_array( $shipping_details ) && array_key_exists( 'min_charge', $shipping_details ) ) ? $shipping_details['min_charge'] : '';

		// Order min Spend
		WCVendors_Pro_Form_Helper::input(
			apply_filters(
				'wcv_shipping_min_charge',
				array(
					'id'          => '_wcv_shipping_min_charge',
					'label'       => __( 'Minimum shipping charged per order', 'wcvendors-pro' ),
					'placeholder' => __( '0', 'wcvendors-pro' ),
					'desc_tip'    => 'true',
					'description' => __( 'The minimum shipping fee charged for an order.', 'wcvendors-pro' ),
					'data_type'   => 'price',
					'value'       => $value,
				)
			)
		);
	} // min_charge()

	/**
	 *  Output the max order spend for shipping
	 *
	 * @since    1.0.0
	 */
	public static function order_max_charge( $shipping_details ) {

		$value = ( is_array( $shipping_details ) && array_key_exists( 'max_charge', $shipping_details ) ) ? $shipping_details['max_charge'] : '';

		// Order min spend
		WCVendors_Pro_Form_Helper::input(
			apply_filters(
				'wcv_shipping_max_charge',
				array(
					'id'          => '_wcv_shipping_max_charge',
					'label'       => __( 'Maximum shipping charged per order', 'wcvendors-pro' ),
					'placeholder' => __( '0', 'wcvendors-pro' ),
					'desc_tip'    => 'true',
					'description' => __( 'The maximum shipping fee charged for an order.', 'wcvendors-pro' ),
					'data_type'   => 'price',
					'value'       => $value,
				)
			)
		);
	} // order_max_spend()


	/**
	 *  Output the min and max order spend for shipping
	 *
	 * @since    1.0.0
	 */
	public static function order_shipping_charge( $shipping_details ) {
		$max = ( 'signup' == self::$form_type ) ? get_option( 'wcvendors_hide_signup_shipping_max_charge' ) : get_option( 'wcvendors_hide_settings_shipping_max_charge' );
		$min = ( 'signup' == self::$form_type ) ? get_option( 'wcvendors_hide_signup_shipping_min_charge' ) : get_option( 'wcvendors_hide_settings_shipping_min_charge' );
		$max = ! wc_string_to_bool($max);
		$min = ! wc_string_to_bool($min);

		if ( ! $max && ! $min ) {
			return;
		}

		if ( ! $min ) {
			WCVendors_Pro_Store_Form::order_max_charge( $shipping_details );
			return;
		}

		if( ! $max ) {
			WCVendors_Pro_Store_Form::order_min_charge( $shipping_details );
			return;
		}
		?>

		<div class="wcv-cols-group wcv-horizontal-gutters">
			<div class="all-50 small-100">
				<?php WCVendors_Pro_Store_Form::order_min_charge( $shipping_details ); ?>
			</div>
			<div class="all-50 small-100">
				<?php WCVendors_Pro_Store_Form::order_max_charge( $shipping_details ); ?>
			</div>
		</div>
		<?php
	}

	/**
	 *  Output the free shipping for order spend for shipping
	 *
	 * @since    1.0.0
	 */
	public static function free_shipping_order( $shipping_details ) {

		$shipping = ( 'signup' == self::$form_type ) ? get_option( 'wcvendors_hide_signup_shipping_free_shipping_order' ) : get_option( 'wcvendors_hide_settings_shipping_free_shipping_order' );

		if ( 'yes' !== $shipping ) {

			$value = ( is_array( $shipping_details ) && array_key_exists( 'free_shipping_order', $shipping_details ) ) ? $shipping_details['free_shipping_order'] : '';

			// Order min Spend
			WCVendors_Pro_Form_Helper::input(
				apply_filters(
					'wcv_shipping_free_shipping_order',
					array(
						'id'          => '_wcv_shipping_free_shipping_order',
						'label'       => __( 'Free shipping order', 'wcvendors-pro' ),
						'placeholder' => __( '0', 'wcvendors-pro' ),
						'desc_tip'    => 'true',
						'description' => __( 'Free shipping for order spends over this amount. This will override the max shipping charge above.', 'wcvendors-pro' ),
						'data_type'   => 'price',
						'value'       => $value,
					)
				)
			);

		}

	} // free_shipping_order()

	/**
	 *  Output the free shipping for product
	 *
	 * @since    1.0.0
	 */
	public static function free_shipping_product( $shipping_details ) {

		$shipping = ( 'signup' == self::$form_type ) ? get_option( 'wcvendors_hide_signup_shipping_free_shipping_product' ) : get_option( 'wcvendors_hide_settings_shipping_free_shipping_product' );

		if ( 'yes' !== $shipping ) {
			$value = ( is_array( $shipping_details ) && array_key_exists( 'free_shipping_product', $shipping_details ) ) ? $shipping_details['free_shipping_product'] : '';

			// Order min Spend
			WCVendors_Pro_Form_Helper::input(
				apply_filters(
					'wcv_shipping_free_shipping_product',
					array(
						'id'          => '_wcv_shipping_free_shipping_product',
						'label'       => __( 'Free shipping product', 'wcvendors-pro' ),
						'placeholder' => __( '0', 'wcvendors-pro' ),
						'desc_tip'    => 'true',
						'description' => __( 'Free shipping if the spend per product is over this amount. This will override the max shipping charge above.', 'wcvendors-pro' ),
						'data_type'   => 'price',
						'value'       => $value,
					)
				)
			);

		}

	} // free_shipping_product()

	/**
	 *  Output the max product spend for shipping
	 *
	 * @since    1.0.0
	 */
	public static function product_max_charge( $shipping_details ) {

		$shipping = ( 'signup' == self::$form_type ) ? get_option( 'wcvendors_hide_signup_shipping_max_charge_product' ) : get_option( 'wcvendors_hide_settings_shipping_max_charge_product' );

		if ( 'yes' !== $shipping ) {

			$value = ( is_array( $shipping_details ) && array_key_exists( 'max_charge_product', $shipping_details ) ) ? $shipping_details['max_charge_product'] : '';

			// Order min spend
			WCVendors_Pro_Form_Helper::input(
				apply_filters(
					'wcv_shipping_max_charge_product',
					array(
						'id'          => '_wcv_shipping_max_charge_product',
						'label'       => __( 'Maximum product charge', 'wcvendors-pro' ),
						'placeholder' => __( '0', 'wcvendors-pro' ),
						'desc_tip'    => 'true',
						'description' => __( 'The maximum shipping charged per product no matter the quantity.', 'wcvendors-pro' ),
						'data_type'   => 'price',
						'value'       => $value,
					)
				)
			);

		}

	} // product_max_charge()

	/**
	 *  Output shipping policy field
	 *
	 * @since    1.0.0
	 */
	public static function shipping_policy( $shipping_details ) {

		$hide = ( 'signup' === self::$form_type )
			? get_option( 'wcvendors_hide_signup_shipping_shipping_policy' )
			: get_option( 'wcvendors_hide_settings_shipping_shipping_policy' );

		if ( wc_string_to_bool( $hide ) ) {
			return;
		}

		echo '<div class="control-group">';

		$required      = wc_string_to_bool( get_option( 'wcvendors_required_settings_shipping_return_policy' ) );
		if ( 'signup' === self::$form_type ) {
			$required      = wc_string_to_bool( get_option( 'wcvendors_required_signup_shipping_return_policy' ) );
		}
		$required_attr = $required ? array( 'required' => '' ) : array();

		$enable_media        = wc_string_to_bool( get_option( 'wcvendors_allow_editor_media', 'no' ) );
		$store_policies_html = wc_string_to_bool( get_option( 'wcvendors_allow_settings_policy_html', 'no' ) );
		if ( 'signup' === self::$form_type ) {
			$store_policies_html = wc_string_to_bool( get_option( 'wcvendors_allow_signup_policy_html', 'no' ) );
		}

		$value = ( is_array( $shipping_details ) && array_key_exists( 'shipping_policy', $shipping_details ) ) ? $shipping_details['shipping_policy'] : '';
		$label = __( 'Shipping Policy', 'wcvendors-pro' );

		// If html in info is allowed then display the tinyMCE otherwise just display a text box.
		if ( $store_policies_html ) {

			if ( $required ) {
				add_filter( 'the_editor', array( __CLASS__, 'wp_editor_required' ) );
				add_filter( 'tiny_mce_before_init', array( __CLASS__, 'wp_tinymce_required' ) );
				add_filter( 'teeny_mce_before_init', array( __CLASS__, 'wp_tinymce_required' ) );
			}

			$required_class = $required ? 'wcv-required' : '';

			$settings = apply_filters(
				'wcv_vendor_store_policy_editor_settings',
				array(
					'editor_height' => 200,
					'media_buttons' => $enable_media,
					'teeny'         => true,
					'tinymce'       => true,
					'editor_class'  => $required_class,
					'tinymce'       => array(
						'setup' => 'function (editor) {
							editor.on("change", function () {
								var content = tinyMCE.activeEditor.getContent( {format : "raw"} )
									.replace( \'<p><br data-mce-bogus="1"></p>\', "" );

								if ( content != undefined && content != "" ) {
									jQuery( "#" + editor.id ).html( content );
								}
							});
						}',
					),
				)
			);

			echo '<label>' . $label . '</label>';

			wp_editor( $value, '_wcv_shipping_policy', $settings );

		} else {

			WCVendors_Pro_Form_Helper::textarea(
				apply_filters(
					'wcv_vendor_shipping_policy',
					array(
						'id'                => '_wcv_shipping_policy',
						'label'             => $label,
						'value'             => $value,
						'custom_attributes' => $required_attr,
					)
				)
			);
		}

		echo '</div>';

	} // shipping_policy()

	/**
	 *  Output shipping policy field
	 *
	 * @since    1.0.0
	 */
	public static function return_policy( $shipping_details ) {

		$hide = ( 'signup' === self::$form_type )
			? get_option( 'wcvendors_hide_signup_shipping_return_policy' )
			: get_option( 'wcvendors_hide_settings_shipping_return_policy' );

		if ( wc_string_to_bool( $hide ) ) {
			return;
		}

		echo '<div class="control-group">';

		$required      = wc_string_to_bool( get_option( 'wcvendors_required_settings_shipping_return_policy' ) );
		if ( 'signup' === self::$form_type ) {
			$required      = wc_string_to_bool( get_option( 'wcvendors_required_signup_shipping_return_policy' ) );
		}
		$required_attr = $required ? array( 'required' => '' ) : array();

		$enable_media        = wc_string_to_bool( get_option( 'wcvendors_allow_editor_media', 'no' ) );
		$store_policies_html = wc_string_to_bool( get_option( 'wcvendors_allow_settings_policy_html', 'no' ) );
		if ( 'signup' === self::$form_type ) {
			$store_policies_html = wc_string_to_bool( get_option( 'wcvendors_allow_signup_policy_html', 'no' ) );
		}

		$value = ( is_array( $shipping_details ) && array_key_exists( 'return_policy', $shipping_details ) ) ? $shipping_details['return_policy'] : '';
		$label = __( 'Return Policy', 'wcvendors-pro' );

		// If html in info is allowed then display the tinyMCE otherwise just display a text box.
		if ( $store_policies_html ) {

			if ( $required ) {
				add_filter( 'the_editor', array( __CLASS__, 'wp_editor_required' ) );
				add_filter( 'tiny_mce_before_init', array( __CLASS__, 'wp_tinymce_required' ) );
				add_filter( 'teeny_mce_before_init', array( __CLASS__, 'wp_tinymce_required' ) );
			}

			$required_class = $required ? 'wcv-required' : '';

			$settings = apply_filters(
				'wcv_vendor_store_policy_editor_settings',
				array(
					'editor_height' => 200,
					'media_buttons' => $enable_media,
					'teeny'         => true,
					'tinymce'       => true,
					'editor_class'  => $required_class,
					'tinymce'       => array(
						'setup' => 'function (editor) {
							editor.on("change", function () {
								var content = tinyMCE.activeEditor.getContent( {format : "raw"} )
									.replace( \'<p><br data-mce-bogus="1"></p>\', "" );

								if ( content != undefined && content != "" ) {
									jQuery( "#" + editor.id ).html( content );
								}
							});
						}',
					),
				)
			);

			echo '<label>' . $label . '</label>';

			wp_editor( $value, '_wcv_shipping_return_policy', $settings );

		} else {

			WCVendors_Pro_Form_Helper::textarea(
				apply_filters(
					'wcv_shipping_return_policy',
					array(
						'id'                => '_wcv_shipping_return_policy',
						'label'             => $label,
						'value'             => $value,
						'custom_attributes' => $required_attr,
					)
				)
			);
		}

		echo '</div>';

	} // return_policy()

	/**
	 *  Output shipping type
	 *
	 * @since    1.0.0
	 *
	 * @param     int $post_id post_id for this meta if any
	 */
	public static function shipping_from( $shipping_details ) {

		$value = ( is_array( $shipping_details ) && array_key_exists( 'shipping_from', $shipping_details ) ) ? $shipping_details['shipping_from'] : '';

		// shipping from
		WCVendors_Pro_Form_Helper::select(
			apply_filters(
				'wcv_vendor_shipping_from',
				array(
					'id'            => '_wcv_shipping_from',
					'class'         => 'select2',
					'label'         => __( 'Shipping from', 'wcvendors-pro' ),
					'desc_tip'      => 'true',
					'description'   => __( 'Where products will be shipped from.', 'wcvendors-pro' ),
					'wrapper_start' => '<div class="all-100">',
					'wrapper_end'   => '</div>',
					'value'         => $value,
					'options'       => array(
						'store_address' => __( 'Store Address', 'wcvendors-pro' ),
						'other'         => __( 'Other', 'wcvendors-pro' ),
					),
				)
			)
		);

	} // shipping_type()

	/**
	 * Output a formatted store address
	 *
	 * @since      1.0.0
	 *
	 * @param      array $shipping_details the shipping details meta
	 */
	public static function shipping_address( $shipping_details ) {

		$value = ( is_array( $shipping_details ) && array_key_exists( 'shipping_address', $shipping_details ) ) ? $shipping_details['shipping_address'] : '';

		$address1 = ( is_array( $value ) && array_key_exists( 'address1', $value ) ) ? $value['address1'] : '';
		$address2 = ( is_array( $value ) && array_key_exists( 'address2', $value ) ) ? $value['address2'] : '';
		$city     = ( is_array( $value ) && array_key_exists( 'city', $value ) ) ? $value['city'] : '';
		$state    = ( is_array( $value ) && array_key_exists( 'state', $value ) ) ? $value['state'] : '';
		$country  = ( is_array( $value ) && array_key_exists( 'country', $value ) ) ? $value['country'] : '';
		$postcode = ( is_array( $value ) && array_key_exists( 'postcode', $value ) ) ? $value['postcode'] : '';

		include apply_filters( 'wcvendors_pro_store_form_shipping_address_path', 'wcvendors-pro-shipping-address.php' );

	}

	/**
	 *  Output shipping rate table
	 *
	 * @since    1.0.0
	 */
	public static function shipping_rate_table() {

		$helper_text = apply_filters( 'wcv_shipping_rate_table_msg', __( 'Countries must use the international standard for two letter country codes. eg. AU for Australia.', 'wcvendors-pro' ) );

		$shipping_rates = get_user_meta( get_current_user_id(), '_wcv_shipping_rates', true );

		include_once apply_filters( 'wcvendors_pro_store_form_shipping_rate_table_path', 'partials/wcvendors-pro-shipping-table.php' );

	} // download_files()

	/**
	 *  Output vacation mode
	 *
	 * @since    1.3.0
	 */
	public static function vacation_mode() {

		$hide_vacation_mode = ( 'signup' == self::$form_type ) ? get_option( 'wcvendors_hide_signup_store_vacation_mode', 'no' ) : get_option( 'wcvendors_hide_settings_store_vacation_mode', 'no' );

		if ( ! wc_string_to_bool( $hide_vacation_mode ) ) {

			$vacation_mode = get_user_meta( get_current_user_id(), '_wcv_vacation_mode', true );
			$vacation_msg  = get_user_meta( get_current_user_id(), '_wcv_vacation_mode_msg', true );

			$disable_cart = get_user_meta( get_current_user_id(), '_wcv_vacation_disable_cart', true );

			// Vacation Mode
			WCVendors_Pro_Form_Helper::input(
				apply_filters(
					'wcv_vacation_mode',
					array(
						'id'            => '_wcv_vacation_mode',
						'label'         => __( 'Enable Vacation Mode', 'wcvendors-pro' ),
						'type'          => 'checkbox',
						'class'         => 'wcv-vacaction-mode',
						'wrapper_start' => '<div class="wcv-cols-group wcv-horizontal-gutters"><div class="all-100">',
						'wrapper_end'   => '</div>',
						'value'         => $vacation_mode,
					)
				)
			);

			WCVendors_Pro_Form_Helper::textarea(
				apply_filters(
					'wcv_vacation_mode_msg',
					array(
						'id'            => '_wcv_vacation_mode_msg',
						'label'         => __( 'Vacation Message', 'wcvendors-pro' ),
						'class'         => 'wcv-vacaction-mode-msg',
						'wrapper_start' => '<div class="all-100 wcv-vacation-mode-msg-wrapper">',
						'wrapper_end'   => '</div>',
						'value'         => $vacation_msg,
					)
				)
			);

			WCVendors_Pro_Form_Helper::input(
				apply_filters(
					'wcv_vacation_disable_cart',
					array(
						'id'            => '_wcv_vacation_disable_cart',
						'label'         => __( 'Disable add to cart for my products', 'wcvendors-pro' ),
						'type'          => 'checkbox',
						'class'         => 'wcv-vacation-disable-cart',
						'wrapper_start' => '<div class="all-100 wcv-vacation-mode-msg-wrapper">',
						'wrapper_end'   => '</div></div>',
						'value'         => $disable_cart,
					)
				)
			);
		}
	} // vaction_mode()

	/**
	 *  Show product total sales
	 *
	 * @since    1.5.8
	 * @version  1.5.8
	 * @return   void
	 */
	public static function product_total_sales() {

		if ( ! wc_string_to_bool( get_option( 'wcvendors_hide_settings_store_product_total_sales', 'no' ) ) ) {

			$show_product_total_sales  = get_user_meta( get_current_user_id(), '_wcv_show_product_total_sales', true );
			$product_total_sales_label = WCVendors_Pro_Vendor_Controller::get_total_sales_label( get_current_user_id(), 'product' );
			$store_total_sales_label   = WCVendors_Pro_Vendor_Controller::get_total_sales_label( get_current_user_id(), 'store' );
			// Show product sold count
			WCVendors_Pro_Form_Helper::input(
				apply_filters(
					'wcv_show_product_total_sales',
					array(
						'id'            => '_wcv_show_product_total_sales',
						'label'         => __( 'Show Product total sales', 'wcvendors-pro' ),
						'type'          => 'checkbox',
						'class'         => 'wcv-product-total-sales',
						'wrapper_start' => '<div class="wcv-cols-group wcv-horizontal-gutters"><div class="all-100">',
						'wrapper_end'   => '</div></div>',
						'value'         => $show_product_total_sales,
					)
				)
			);

			// Product sold label
			WCVendors_Pro_Form_Helper::input(
				apply_filters(
					'wcv_product_total_sales_label',
					array(
						'id'            => '_wcv_product_total_sales_label',
						'label'         => __( 'Product Total Sales Label', 'wcvendors-pro' ),
						'type'          => 'text',
						'wrapper_start' => '<div class="wcv-cols-group wcv-horizontal-gutters"><div class="all-100">',
						'wrapper_end'   => '</div></div>',
						'value'         => $product_total_sales_label,
					)
				)
			);

			// Store total sales label
			WCVendors_Pro_Form_Helper::input(
				apply_filters(
					'wcv_store_total_sales_label',
					array(
						'id'            => '_wcv_store_total_sales_label',
						'label'         => __( 'Store Total Sales Label', 'wcvendors-pro' ),
						'type'          => 'text',
						'wrapper_start' => '<div class="wcv-cols-group wcv-horizontal-gutters"><div class="all-100">',
						'wrapper_end'   => '</div></div>',
						'value'         => $store_total_sales_label,
					)
				)
			);
		}
	}

	/**
	 * Output enable store notice field
	 *
	 * @return    void
	 * @since      1.5.9
	 * @version    1.5.9
	 */
	public static function enable_store_notice() {
		if ( ! wc_string_to_bool( get_option( 'wcvendors_hide_settings_store_enable_notice', 'no' ) ) ) {
			$enable_store_notice = get_user_meta( get_current_user_id(), '_wcv_vendor_enable_store_notice', true );
			// Store total sales label
			WCVendors_Pro_Form_Helper::input(
				apply_filters(
					'wcv_vendor_enable_store_notice',
					array(
						'id'            => '_wcv_vendor_enable_store_notice',
						'label'         => __( 'Enable Store Notice', 'wcvendors-pro' ),
						'type'          => 'checkbox',
						'wrapper_start' => '<div class="wcv-cols-group wcv-horizontal-gutters"><div class="all-100">',
						'wrapper_end'   => '</div></div>',
						'value'         => $enable_store_notice,
					)
				)
			);
		}
	}

	/**
	 * Output vendor store notice editor
	 *
	 * @return    void
	 * @since      1.5.9
	 * @version    1.5.9
	 */
	public static function vendor_store_notice() {

		if ( ! wc_string_to_bool( get_option( 'wcvendors_hide_settings_store_vendor_store_notice', 'no' ) ) ) {

			$content = get_user_meta( get_current_user_id(), '_wcv_vendor_store_notice', true );

			wp_editor(
				wp_kses_post( $content ),
				'_wcv_vendor_store_notice',
				array(
					'wpautop'       => true,
					'media_buttons' => true,
					'textarea_name' => '_wcv_vendor_store_notice',
					'textarea_rows' => apply_filters( 'wcv_store_notice_edit_rows', 4 ),
					'tinymce'       => array(
						'setup' => 'function (editor) {
							jQuery( "#" + editor.id ).attr( "name", editor.id );

							editor.on("change", function () {
								var content = tinyMCE.activeEditor.getContent( {format : "raw"} )
									.replace( \'<p><br data-mce-bogus="1"></p>\', "" );

								if ( content != undefined && content != "" ) {
									jQuery( "#" + editor.id ).html( content );
								}
							});
						}',
					),
				)
			);
		}
	}

	/**
	 * Output Vendor terms on the signup page
	 *
	 * @since 1.3.2
	 */
	public static function vendor_terms() {

		$terms_page = get_option( 'wcvendors_vendor_terms_page_id' );

		if ( ( $terms_page ) && ( ! isset( $_GET['terms'] ) ) ) {

			// Vendor Terms checkbox
			WCVendors_Pro_Form_Helper::input(
				apply_filters(
					'wcv_vendor_terms_args',
					array(
						'id'                => '_wcv_agree_to_terms',
						'label'             => sprintf( __( 'I have read and accepted the <a href="%s" target="_blank">terms and conditions</a>', 'wcvendors-pro' ), get_permalink( $terms_page ) ),
						'type'              => 'checkbox',
						'class'             => '',
						'wrapper_start'     => '<div class="wcv-cols-group wcv-horizontal-gutters"><div class="all-100">',
						'wrapper_end'       => '</div>',
						'value'             => 1,
						'custom_attributes' => array(
							'required'                   => '',
							'data-parsley-error-message' => sprintf( __( 'You must agree to the terms and conditions to apply to be a %s.', 'wcvendors-pro' ), wcv_get_vendor_name( true, false ) ),
						),
					)
				)
			);

		}

	} // vendor_terms()


	/**
	 *
	 *    Store SEO
	 */

	/**
	 * Store SEO
	 *
	 * @since 1.5.0
	 */
	public static function seo_title() {
		$seo_option = ( 'signup' == self::$form_type ) ? get_option( 'wcvendors_hide_signup_seo_title' ) : get_option( 'wcvendors_hide_settings_seo_title' );

		if ( 'yes' !== $seo_option ) {

			$value = get_user_meta( get_current_user_id(), 'wcv_seo_title', true );

			WCVendors_Pro_Form_Helper::input(
				apply_filters(
					'wcv_vendor_seo_title',
					array(
						'id'          => 'wcv_seo_title',
						'label'       => __( 'SEO Title', 'wcvendors-pro' ),
						'placeholder' => __( '', 'wcvendors-pro' ),
						'desc_tip'    => 'true',
						'description' => __( 'The SEO title shown in search engines', 'wcvendors-pro' ),
						'type'        => 'text',
						'value'       => $value,
					)
				)
			);
		}

	} // seo_title()

	/**
	 * Store SEO
	 *
	 * @since 1.5.0
	 */
	public static function seo_meta_description() {

		$seo_option = ( 'signup' == self::$form_type ) ? get_option( 'wcvendors_hide_signup_seo_meta_description' ) : get_option( 'wcvendors_hide_settings_seo_meta_description' );

		if ( 'yes' !== $seo_option ) {

			$value = get_user_meta( get_current_user_id(), 'wcv_seo_meta_description', true );

			WCVendors_Pro_Form_Helper::textarea(
				apply_filters(
					'wcv_vendor_seo_meta_description',
					array(
						'id'          => 'wcv_seo_meta_description',
						'label'       => __( 'Meta description ', 'wcvendors-pro' ),
						'desc_tip'    => 'true',
						'description' => __( 'This should be a maximum of 300 characters', 'wcvendors-pro' ),
						'value'       => $value,
					)
				)
			);
		}

	} // seo_meta_description()

	/**
	 * Store SEO
	 *
	 * @since 1.5.0
	 */
	public static function seo_meta_keywords() {
		$seo_option = ( 'signup' == self::$form_type ) ? get_option( 'wcvendors_hide_signup_seo_meta_keywords' ) : get_option( 'wcvendors_hide_settings_seo_meta_keywords' );

		if ( 'yes' !== $seo_option ) {

			$value = get_user_meta( get_current_user_id(), 'wcv_seo_meta_keywords', true );

			WCVendors_Pro_Form_Helper::input(
				apply_filters(
					'wcv_vendor_seo_meta_keywords',
					array(
						'id'          => 'wcv_seo_meta_keywords',
						'label'       => __( 'Meta keywords ', 'wcvendors-pro' ),
						'placeholder' => __( '', 'wcvendors-pro' ),
						'desc_tip'    => 'true',
						'description' => __( 'A comma separated list of keywords', 'wcvendors-pro' ),
						'type'        => 'text',
						'value'       => $value,
					)
				)
			);
		}

	} // seo_meta_keywords()

	/**
	 * Store SEO Facebook Title
	 *
	 * @since 1.5.0
	 */
	public static function seo_fb_title() {
		$seo_option = ( 'signup' == self::$form_type ) ? get_option( 'wcvendors_hide_signup_seo_fb_title' ) : get_option( 'wcvendors_hide_settings_seo_fb_title' );

		if ( 'yes' !== $seo_option ) {

			$value = get_user_meta( get_current_user_id(), 'wcv_seo_fb_title', true );

			WCVendors_Pro_Form_Helper::input(
				apply_filters(
					'wcv_vendor_seo_fb_title',
					array(
						'id'          => 'wcv_seo_fb_title',
						'label'       => __( 'Facebook title', 'wcvendors-pro' ),
						'placeholder' => __( '', 'wcvendors-pro' ),
						'desc_tip'    => 'true',
						'description' => __( 'Facebook title of the page you are sharing.', 'wcvendors-pro' ),
						'type'        => 'text',
						'value'       => $value,
					)
				)
			);
		}

	} // seo_fb_title()

	/**
	 * Store SEO Facebook Description
	 *
	 * @since 1.5.0
	 */
	public static function seo_fb_description() {
		$seo_option = ( 'signup' == self::$form_type ) ? get_option( 'wcvendors_hide_signup_seo_fb_description' ) : get_option( 'wcvendors_hide_settings_seo_fb_description' );

		if ( 'yes' !== $seo_option ) {

			$value = get_user_meta( get_current_user_id(), 'wcv_seo_fb_description', true );

			WCVendors_Pro_Form_Helper::textarea(
				apply_filters(
					'wcv_vendor_seo_fb_description',
					array(
						'id'    => 'wcv_seo_fb_description',
						'label' => __( 'Facebook description ', 'wcvendors-pro' ),
						'value' => $value,
					)
				)
			);
		}

	} // seo_fb_description()

	/**
	 * SEO Facebook Image
	 *
	 * @since 1.5.0
	 */
	public static function seo_fb_image() {

		$seo_option = ( 'signup' == self::$form_type ) ? get_option( 'wcvendors_hide_signup_seo_fb_image' ) : get_option( 'wcvendors_hide_settings_seo_fb_image' );

		if ( 'yes' !== $seo_option ) {

			$value = get_user_meta( get_current_user_id(), 'wcv_seo_fb_image_id', true );

			WCVendors_Pro_Form_Helper::file_uploader(
				apply_filters(
					'wcv_vendor_seo_fb_image',
					array(
						'id'             => 'wcv_seo_fb_image_id',
						'header_text'    => __( 'Facebook Image', 'wcvendors-pro' ),
						'add_text'       => __( 'Add Facebook image', 'wcvendors-pro' ),
						'remove_text'    => __( 'Remove facebook image', 'wcvendors-pro' ),
						'image_meta_key' => 'wcv_seo_fb_image_id',
						'save_button'    => __( 'Add Facebook image', 'wcvendors-pro' ),
						'window_title'   => __( 'Select an Image', 'wcvendors-pro' ),
						'value'          => $value,
					)
				)
			);

		}

	} // seo_fb_image()

	/**
	 * Store SEO Facebook Title
	 *
	 * @since 1.5.0
	 */
	public static function seo_twitter_title() {
		$seo_option = ( 'signup' == self::$form_type ) ? get_option( 'wcvendors_hide_signup_seo_twitter_title' ) : get_option( 'wcvendors_hide_settings_seo_twitter_title' );

		if ( 'yes' !== $seo_option ) {

			$value = get_user_meta( get_current_user_id(), 'wcv_seo_twitter_title', true );

			WCVendors_Pro_Form_Helper::input(
				apply_filters(
					'wcv_vendor_seo_twitter_title',
					array(
						'id'          => 'wcv_seo_twitter_title',
						'label'       => __( 'Twitter title', 'wcvendors-pro' ),
						'placeholder' => __( '', 'wcvendors-pro' ),
						'desc_tip'    => 'true',
						'description' => __( 'Twitter title of the page you are sharing.', 'wcvendors-pro' ),
						'type'        => 'text',
						'value'       => $value,
					)
				)
			);
		}

	} // seo_twitter_title()

	/**
	 * Store SEO Twitter Description
	 *
	 * @since 1.5.0
	 */
	public static function seo_twitter_description() {
		$seo_option = ( 'signup' == self::$form_type ) ? get_option( 'wcvendors_hide_signup_seo_twitter_description' ) : get_option( 'wcvendors_hide_settings_seo_twitter_description' );

		if ( 'yes' !== $seo_option ) {

			$value = get_user_meta( get_current_user_id(), 'wcv_seo_twitter_description', true );

			WCVendors_Pro_Form_Helper::textarea(
				apply_filters(
					'wcv_vendor_seo_twitter_description',
					array(
						'id'    => 'wcv_seo_twitter_description',
						'label' => __( 'Twitter description this is a maximum of 200 characters', 'wcvendors-pro' ),
						'value' => $value,
					)
				)
			);
		}

	} // seo_twitter_description()

	/**
	 * SEO Twitter Image
	 *
	 * @since 1.5.0
	 */
	public static function seo_twitter_image() {

		$seo_option = ( 'signup' == self::$form_type ) ? get_option( 'wcvendors_hide_signup_seo_twitter_image' ) : get_option( 'wcvendors_hide_settings_seo_twitter_image' );

		if ( 'yes' !== $seo_option ) {

			$value = get_user_meta( get_current_user_id(), 'wcv_seo_twitter_image_id', true );

			WCVendors_Pro_Form_Helper::file_uploader(
				apply_filters(
					'wcv_vendor_seo_twitter_image',
					array(
						'id'             => 'wcv_seo_twitter_image_id',
						'header_text'    => __( 'Twitter Image', 'wcvendors-pro' ),
						'add_text'       => __( 'Add Twitter image', 'wcvendors-pro' ),
						'remove_text'    => __( 'Remove Twitter image', 'wcvendors-pro' ),
						'image_meta_key' => 'wcv_seo_twitter_image_id',
						'save_button'    => __( 'Add Twitter image', 'wcvendors-pro' ),
						'window_title'   => __( 'Select an Image', 'wcvendors-pro' ),
						'value'          => $value,
					)
				)
			);

		}

	} // seo_twitter_image()

	/**
	 * Show store opening hours form
	 *
	 * @return void
	 * @since   1.5.8
	 * @version 1.5.8
	 */
	public static function store_opening_hours_form() {

		$hide = ( 'signup' == self::$form_type ) ? get_option( 'wcvendors_hide_signup_opening_hours', 'no' ) : get_option( 'wcvendors_hide_settings_opening_hours', 'no' );

		if ( ! wc_string_to_bool( $hide ) ) {
			$required = ( 'signup' == self::$form_type ) ? get_option( 'wcvendors_required_signup_store_opening_hours', 'no' ) : get_option( 'wcvendors_required_settings_opening_hours', 'no' );
			$hours    = get_user_meta( get_current_user_id(), 'wcv_store_opening_hours', true );
			$hours    = apply_filters( 'wcv_store_opening_hours_' . get_current_user_id(), $hours );

			if ( empty( $hours ) ) {
				$hours = wcv_get_default_opening_hours();
			}

			$enable_opening_hours = get_user_meta( get_current_user_id(), '_wcv_enable_opening_hours', true );

			// Opening Hours
			WCVendors_Pro_Form_Helper::input(
				apply_filters(
					'wcv_enable_opening_hours',
					array(
						'id'                => '_wcv_enable_opening_hours',
						'label'             => __( 'Enable opening hours', 'wcvendors-pro' ),
						'type'              => 'checkbox',
						'class'             => 'wcv-enable-opening-hours',
						'wrapper_start'     => '<div class="wcv-cols-group wcv-horizontal-gutters"><div class="all-100">',
						'wrapper_end'       => '</div></div>',
						'value'             => $enable_opening_hours,
						'custom_attributes' => wc_string_to_bool( $required ) ? array(
							'required'                   => '',
							'data-parsley-error-message' => __( 'Opening hours is required' ),
						) : array(),
					)
				)
			);

			include_once apply_filters( 'wcv_partial_path_opening_hours', 'partials/store-opening-hours.php' );
		}
	}

	/**
	 *  Output store policy
	 *
	 * @since    1.6.1
	 *
	 * @param string $type  Policy type string
	 * @param string $label Field value
	 */
	public static function store_policy( $type, $label ) {

		$hide = ( 'signup' == self::$form_type )
			? get_option( 'wcvendors_hide_signup_store_policy_' . $type )
			: get_option( 'wcvendors_hide_settings_store_policy_' . $type );

		if ( wc_string_to_bool( $hide ) ) {
			return;
		}

		echo '<div class="control-group">';

		$required      = ( 'signup' == self::$form_type )
			? get_option( 'wcvendors_required_signup_store_policy_' . $type )
			: get_option( 'wcvendors_required_settings_store_policy_' . $type );
		$required      = wc_string_to_bool( $required );
		$required_attr = $required ? array( 'required' => '' ) : array();

		$store_policies_html = ( 'signup' == self::$form_type )
			? wc_string_to_bool( get_option( 'wcvendors_allow_signup_policy_html', 'no' ) )
			: wc_string_to_bool( get_option( 'wcvendors_allow_settings_policy_html', 'no' ) );
		$enable_media        = wc_string_to_bool( get_option( 'wcvendors_allow_editor_media', 'no' ) );
		$value               = get_user_meta( get_current_user_id(), 'wcv_policy_' . $type, true );

		// If html in info is allowed then display the tinyMCE otherwise just display a text box.
		if ( $store_policies_html ) {

			if ( $required ) {
				add_filter( 'the_editor', array( __CLASS__, 'wp_editor_required' ) );
				add_filter( 'tiny_mce_before_init', array( __CLASS__, 'wp_tinymce_required' ) );
				add_filter( 'teeny_mce_before_init', array( __CLASS__, 'wp_tinymce_required' ) );
			}

			$required_class = $required ? 'wcv-required' : '';

			$settings = apply_filters(
				'wcv_vendor_store_policy_editor_settings',
				array(
					'editor_height' => 200,
					'media_buttons' => $enable_media,
					'teeny'         => true,
					'tinymce'       => true,
					'editor_class'  => $required_class,
					'tinymce'       => array(
						'setup' => 'function (editor) {
							editor.on("change", function () {
								var content = tinyMCE.activeEditor.getContent( {format : "raw"} )
									.replace( \'<p><br data-mce-bogus="1"></p>\', "" );

								if ( content != undefined && content != "" ) {
									jQuery( "#" + editor.id ).html( content );
								}
							});
						}',
					),
				)
			);

			echo '<label>' . $label . '</label>';

			wp_editor( $value, 'wcv_policy_' . $type, $settings );

		} else {

			WCVendors_Pro_Form_Helper::textarea(
				apply_filters(
					'wcv_policy_' . $type,
					array(
						'id'                => 'wcv_policy_' . $type,
						'label'             => $label,
						'value'             => $value,
						'custom_attributes' => $required_attr,
					)
				)
			);
		}

		echo '</div>';

	}

	/**
	 *  Hook into the wp_editor and add a required field
	 */
	public static function wp_editor_required( $markup ) {
		if ( stripos( $markup, 'wcv-required' ) !== false ) {
			$markup = str_replace( '<textarea', '<textarea required data-parsley-error-message="' . apply_filters( 'wcv_required_editor_message', __( 'This is required', 'wcvendors-pro' ) ) . '"', $markup );
		}

		return $markup;
	}

	/**
	 * Modify the tinymce editor settings
	 *
	 * @param array $settings the current editor's settings
	 *
	 * @return array $settings the current editor's settings
	 * @since 1.5.5
	 */
	public static function wp_tinymce_required( $settings ) {
		$settings['body_class'] .= ' wcv-required ';

		return $settings;
	}

	/**
	 * Output enable local pickup
	 *
	 * @return    void
	 * @since      1.6.3
	 */
	public static function enable_local_pickup() {

		if ( ! wc_string_to_bool( get_option( 'wcvendors_hide_settings_store_enable_local_pickup', 'no' ) ) ) {

			$local_pickup_enabled = get_user_meta( get_current_user_id(), '_wcv_local_pickup_enabled', true );

			echo apply_filters( 'wcv_store_local_pickup_heading', '<hr /><h3>' . __( 'Local Delivery', 'wcvendors-pro' ) . '</h3>' );

			// Enable local delivery
			WCVendors_Pro_Form_Helper::input(
				apply_filters(
					'wcv_vendor_enable_local_pickup',
					array(
						'id'            => '_wcv_local_pickup_enabled',
						'label'         => __( 'Enable local pickup', 'wcvendors-pro' ),
						'type'          => 'checkbox',
						'wrapper_start' => '<div class="wcv-cols-group wcv-horizontal-gutters"><div class="all-100">',
						'wrapper_end'   => '</div></div>',
						'value'         => $local_pickup_enabled,
					)
				)
			);
		}
	}



}
