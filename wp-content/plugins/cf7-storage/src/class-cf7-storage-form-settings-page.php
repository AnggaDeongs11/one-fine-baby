<?php

/**
 * Register our custom form settings.
 */
class Cf7_Storage_Form_Settings_Page {

	/**
	 * Our custom panel ID.
	 *
	 * @var string
	 */
	const PANEL_ID = 'cf7-storage';

	/**
	 * Instance of the current plugin.
	 *
	 * @var Cf7_Storage
	 */
	protected $plugin;

	/**
	 * Settings object used for storing form settings.
	 *
	 * @var Cf7_Storage_Meta_Store
	 */
	protected $settings;

	/**
	 * Full plugin instance.
	 *
	 * TODO No need to keep this around, refactor later.
	 *
	 * @var Cf7_Storage
	 */
	protected $storage;

	/**
	 * Create the form settings instance.
	 *
	 * @param Cf7_Storage            $plugin   Instance of the current plugin.
	 * @param Cf7_Storage_Meta_Store $settings Settings data store instance.
	 */
	public function __construct( $plugin, $settings ) {
		$this->plugin = $plugin;
		$this->settings = $settings;
	}

	/**
	 * Register with WordPress.
	 *
	 * @return void
	 */
	public function init() {
		add_filter( 'wpcf7_editor_panels', array( $this, 'register_panel' ) );
		add_action( 'wpcf7_save_contact_form', array( $this, 'save_settings' ) );
	}

	/**
	 * Register our config panel with CF7.
	 *
	 * @param  array $panels List of available panels.
	 *
	 * @return array
	 */
	function register_panel( $panels ) {
		$form = WPCF7_ContactForm::get_current();
		$post_id = $form->id();

		if ( empty( $post_id ) || ! current_user_can( 'wpcf7_edit_contact_form', $post_id ) ) {
			return $panels;
		}

		$panels[ self::PANEL_ID ] = array(
			'title' => __( 'Storage', 'cf7-extras' ),
			'callback' => array( $this, 'wpcf7_panel_output' ),
		);

		return $panels;
	}

	/**
	 * URL of the form settings.
	 *
	 * @param  int $form_id Form ID.
	 *
	 * @return string
	 */
	public function link( $form_id ) {
		return add_query_arg(
			array(
				'page' => 'wpcf7',
				'post' => absint( $form_id ),
			),
			admin_url( 'admin.php' )
		) . '#cf7-storage';
	}

	/**
	 * Render the panel output.
	 *
	 * @param  Object $cf7 Contac Form 7 object.
	 *
	 * @return void
	 */
	public function wpcf7_panel_output( $cf7 ) {
		$post_id = $cf7->id();
		$excluded_fields = $this->settings->excluded_fields( $post_id );
		$mail_tags = $cf7->scan_form_tags();
		$field_ids = $this->plugin->default_entry_fields(); // Include our custom fields from the main plugin.
		$field_inputs = array();

		wp_enqueue_script(
			'cf7s-form-settings',
			$this->plugin->asset_url( 'assets/js/form-settings.js' ),
			array( 'jquery' ),
			$this->plugin->version(),
			true
		);

		// Include all fields from the form content.
		foreach ( $mail_tags as $mail_tag ) {
			if ( ! empty( $mail_tag['name'] ) ) {
				$field_ids[ 'field-' . $mail_tag['name'] ] = $mail_tag['name'];
			}
		}

		foreach ( $field_ids as $field_id => $field_label ) {
			$field_excluded = in_array( $field_id, $excluded_fields );

			$field_inputs[] = sprintf(
				'<li>
					<label>
						<input type="checkbox" name="%s[exclude][]" value="%s" %s />
						%s
					</label>
				</li>',
				esc_attr( self::PANEL_ID ),
				esc_attr( $field_id ),
				checked( $field_excluded, true, false ),
				esc_html( $field_label )
			);
		}

		?>
		<table class="form-table">
			<tbody>
				<tr>
					<th scope="row">
						<?php esc_html_e( 'Entries', 'cf7-storage' ); ?>
					</th>
					<td>
						<a href="<?php echo esc_url( $this->plugin->link_entries( $post_id ) ); ?>" class="button">
							<?php esc_html_e( 'View form entries', 'cf7-storage' ); ?>
						</a>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<?php esc_html_e( 'Export', 'cf7-storage' ); ?>
					</th>
					<td>
						<p><?php esc_html_e( 'Select the form fields to exclude from the CSV export of this form:', 'cf7-storage' ); ?></p>
						<ul>
							<?php echo implode( '', $field_inputs ); ?>
						</ul>
					</td>
				</tr>
			</tbody>
		</table>
		<?php
	}

	/**
	 * Save our form settings.
	 *
	 * @param  Object $cf7 Contact Form 7 object.
	 *
	 * @return boolean If the settings were saved.
	 */
	public function save_settings( $cf7 ) {
		$settings_input = filter_input( INPUT_POST, self::PANEL_ID, FILTER_DEFAULT, FILTER_REQUIRE_ARRAY );

		return $this->settings->set( $cf7->id(), $settings_input );
	}

}
