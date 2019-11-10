<?php

/**
 * Add vendor id to the import and export of products for admins
 *
 * Allow vendor details to be exported and imported via the product screen as admins.
 *
 * @package    WCVendors_Pro
 * @subpackage WCVendors_Pro/admin
 * @author     Jamie Madden <support@wcvendors.com>
 */

class WCVendors_Pro_Admin_Import_Export {

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
	 * Script suffix for debugging
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $suffix script suffix for including minified file versions
	 */
	private $suffix;

	/**
	 * Is the plugin in debug mode
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      bool $debug plugin is in debug mode
	 */
	private $debug;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 *
	 * @param    string $wcvendors_pro The name of this plugin.
	 * @param    string $version       The version of this plugin.
	 */
	public function __construct( $wcvendors_pro, $version, $debug ) {

		$this->wcvendors_pro   = $wcvendors_pro;
		$this->version         = $version;
		$this->debug           = $debug;
		$this->base_dir        = plugin_dir_url( __FILE__ );
		$this->plugin_base_dir = plugin_dir_path( dirname( __FILE__ ) );
	}

	/**
	 * Register the 'Custom Column' column in the importer.
	 *
	 * @param array $options
	 *
	 * @return array $options
	 */
	public function add_column_to_importer( $options ) {

		// column slug => column name
		$options['vendor_id'] = 'Vendor ID';

		return apply_filters( 'wcv_wc_csv_product_import_mapping_options', $options );
	}

	/**
	 * Add automatic mapping support for 'Custom Column'.
	 * This will automatically select the correct mapping for columns named 'Custom Column' or 'custom column'.
	 *
	 * @param array $columns
	 *
	 * @return array $columns
	 */
	public function add_column_to_mapping_screen( $columns ) {

		// potential column name => column slug
		$columns['Vendor ID'] = 'vendor_id';

		return $columns;
	}

	/**
	 * Process the data read from the CSV file.
	 * This just saves the value in meta data, but you can do anything you want here with the data.
	 *
	 * @param WC_Product $object - Product being imported or updated.
	 * @param array      $data   - CSV data read for the product.
	 *
	 * @return WC_Product $object
	 */
	function process_import( $object, $data ) {

		if ( is_a( $object, 'WC_Product' ) || is_a( $object, 'WC_Product_Variation' ) ) {

			$post = array(
				'ID'          => $object->get_id(),
				'post_author' => $data['vendor_id'],
			);

			$update = wp_update_post( $post );

		}

		return $object;
	}

	/**
	 * Add the custom column to the exporter and the exporter column menu.
	 *
	 * @param array $columns
	 *
	 * @return array $columns
	 */
	public function add_export_column( $columns ) {

		// column slug => column name
		$columns['vendor_id'] = 'Vendor ID';

		return $columns;
	}

	/**
	 * Provide the data to be exported for one item in the column.
	 *
	 * @param mixed      $value (default: '')
	 * @param WC_Product $product
	 *
	 * @return mixed $value - Should be in a format that can be output into a text file (string, numeric, etc).
	 */
	function add_export_data( $value, $product ) {
		$vendor_id = WCV_Vendors::get_vendor_from_product( $product->get_id() );

		return $vendor_id;
	}
	// Filter you want to hook into will be: 'woocommerce_product_export_product_column_{$column_slug}'.
}
