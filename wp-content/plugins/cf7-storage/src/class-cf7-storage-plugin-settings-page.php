<?php

/**
 * Register plugin settings with CF7 integrations.
 */
class Cf7_Storage_Plugin_Settings_Page {

	/**
	 * Slug of the default setting section.
	 *
	 * @var string
	 */
	const SECTION_DEFAULT = 'general';

	/**
	 * Settings ID.
	 *
	 * @var string
	 */
	protected $id;

	/**
	 * Settings page ID.
	 *
	 * @var string
	 */
	protected $page;

	/**
	 * Store all fields per section.
	 *
	 * @var array
	 */
	protected $sections = array();

	/**
	 * Instance of a WP plugin interface.
	 *
	 * @var Cf7_Storage_Plugin
	 */
	protected $plugin;

	/**
	 * Instance of settings interface.
	 *
	 * @var Cf7_Storage_Settings
	 */
	protected $settings;

	/**
	 * Go!
	 *
	 * @param string               $id Setting page slug.
	 * @param Cf7_Storage          $plugin Our plugin instance.
	 * @param Cf7_Storage_Settings $settings WP settings interface.
	 */
	public function __construct( $id, $plugin, $settings ) {
		$this->id = $id;
		$this->plugin = $plugin;
		$this->settings = $settings;
	}

	/**
	 * Settings page ID.
	 *
	 * @return string
	 */
	public function id() {
		return $this->id;
	}

	/**
	 * Get the URL of the admin page.
	 *
	 * @param array $extra_params Pairs of query parameters to add.
	 *
	 * @return string
	 */
	public function link( $extra_params = array() ) {
		$params = array(
			'page' => $this->id(),
		);

		if ( ! empty( $extra_params ) && is_array( $extra_params ) ) {
			$params = array_merge( $params, $extra_params );
		}

		return add_query_arg( $params, admin_url( 'admin.php' ) );
	}

	/**
	 * Build a nested field input name such as `field[part-one][part-two]``.
	 *
	 * @param  array $parts Input path components.
	 *
	 * @return string
	 */
	public function field_name( $parts ) {
		foreach ( $parts as &$part ) {
			$part = sprintf( '[%s]', $part );
		}

		return sprintf(
			'%s%s',
			$this->settings->id(),
			implode( '', $parts )
		);
	}

	/**
	 * Build a nested field ID part seperated with slashes.
	 *
	 * @param  array $parts Path components.
	 *
	 * @return string
	 */
	public function field_id( $parts ) {
		return sprintf(
			'%s/%s',
			$this->settings->id(),
			implode( '/', $parts )
		);
	}

	/**
	 * Init all WP hooks.
	 *
	 * @return void
	 */
	public function init() {
		add_action( 'admin_menu', array( $this, 'admin_menu' ), 15 );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
	}

	/**
	 * Register the menu page and fields.
	 *
	 * @return void
	 */
	function admin_menu() {
		$this->page = add_submenu_page(
			'wpcf7',
			__( 'Storage Settings', 'cf7-storage' ),
			__( 'Storage Settings', 'cf7-storage' ),
			'wpcf7_edit_contact_forms',
			$this->id(),
			array( $this, 'admin_page' )
		);

		add_action( 'load-' . $this->page, array( $this, 'process' ) );

		register_setting(
			$this->settings->id(),
			$this->settings->id(),
			array(
				'sanitize_callback' => array( $this, 'save' ),
			)
		);

		$this->register_section(
			self::SECTION_DEFAULT,
			array(
				'title' => __( 'General Settings', 'cf7-storage' ),
				'label' => __( 'General', 'cf7-storage' ),
			)
		);

		$this->register_field(
			self::SECTION_DEFAULT,
			'meta-disable',
			array(
				'label' => __( 'Meta Data Storage', 'cf7-storage' ),
				'callback' => array( $this, 'render_meta_disable' ),
			)
		);

		$this->register_field(
			self::SECTION_DEFAULT,
			'form-settings',
			array(
				'label' => __( 'Form Settings', 'cf7-storage' ),
				'callback' => array( $this, 'render_form_settings' ),
			)
		);
	}

	/**
	 * Enqueue settings scripts and styles.
	 *
	 * @param  string $page Current admin page ID.
	 *
	 * @return void
	 */
	public function enqueue_scripts( $page ) {
		if ( $page === $this->page ) {
			wp_enqueue_style(
				'cf7s-settings',
				$this->plugin->asset_url( 'assets/css/settings.css' ),
				null,
				$this->plugin->version()
			);
		}
	}

	/**
	 * Register a new section tab for settings.
	 *
	 * @param  string $section Section ID.
	 * @param  array  $args Section arguments.
	 *
	 * @return void
	 */
	public function register_section( $section, $args ) {
		$args = wp_parse_args(
			$args,
			array(
				'title' => '',
				'label' => '',
			)
		);

		if ( ! isset( $this->sections[ $section ] ) ) {
			$this->sections[ $section ] = array(
				'label' => $args['label'],
				'title' => $args['title'],
				'fields' => array(),
			);
		}

		add_settings_section(
			$this->field_id( array( $section ) ),
			$args['title'],
			null,
			$this->field_id( array( $this->page, $section ) )
		);
	}

	/**
	 * Register a new field in a section.
	 *
	 * @param  string $section Field section ID.
	 * @param  string $field Field ID.
	 * @param  array  $args Field settings.
	 *
	 * @return void
	 */
	public function register_field( $section, $field, $args ) {
		$args = wp_parse_args(
			$args,
			array(
				'label' => '',
				'callback' => null,
			)
		);

		add_settings_field(
			$this->field_id( array( $field ) ),
			$args['label'],
			$args['callback'],
			$this->field_id( array( $this->page, $section ) ),
			$this->field_id( array( $section ) )
		);
	}

	/**
	 * Process settings page request.
	 *
	 * @return void
	 */
	public function process() {
		// Trigger actions based on the request.
	}

	/**
	 * Santize the settings before saving them.
	 *
	 * @param  array $input Setting data.
	 *
	 * @return array
	 */
	public function save( $input = null ) {
		return $this->settings->sanitize( $input );
	}

	/**
	 * Get the current section.
	 *
	 * @return string
	 */
	public function section() {
		if ( isset( $_REQUEST['section'] ) ) {
			return sanitize_key( $_REQUEST['section'] );
		}

		return self::SECTION_DEFAULT;
	}

	/**
	 * Render the settings page.
	 *
	 * @return void
	 */
	public function admin_page() {
		$entries_link = $this->plugin->admin_link();
		$tab = $this->section();
		$current_user = wp_get_current_user();

		?>
		<div class="wrap">
			<h1 class="wp-heading-inline">
				<?php esc_html_e( 'Contact Form Storage Settings', 'cf7-storage' ); ?>
			</h1>

			<?php
			if ( ! empty( $entries_link ) ) {
				printf(
					'<a href="%s" class="page-title-action">%s</a>',
					esc_url( $entries_link ),
					esc_html__( 'View Entries', 'cf7-storage' )
				);
			}
			?>

			<hr class="wp-header-end">

			<div class="cf7-storage-settings">
				<div class="cf7-storage-settings-main">
					<?php $this->tabs(); // WPCS: XSS ok, escaped during assamble. ?>

					<form method="post" action="<?php echo esc_url( admin_url( 'options.php' ) ); ?>">
						<?php
						settings_fields( $this->settings->id() );
						do_settings_sections( $this->field_id( array( $this->page, $tab ) ) );
						submit_button();
						?>
					</form>
				</div>

				<div class="cf7-storage-settings-sidebar">
					<div class="cf7-storage-support">
						<h3><?php esc_html_e( 'Support', 'cf7-storage' ); ?></h3>
						<p>
							<?php
							printf(
								// translators: %s: URL to the support section on CodeCanyon.
								__( 'Use the <a href="%s">support section on CodeCanyon</a>.', 'cf7-storage' ),
								'https://preseto.com/go/cf7-storage-support'
							);
							?>
						</p>
					</div>

					<div class="cf7-storage-subscribe">
						<h3><?php esc_html_e( 'Plugin Updates', 'cf7-storage' ); ?></h3>
						<p>
							<?php
								printf(
									// translators: %s: URL of the plugin.
									__( 'Install and configure the <a href="%s">Envato Market plugin</a> to receive free automatic updates.' ),
									'https://preseto.com/go/envato-market-plugin'
								);
							?>
						</p>
						<form action="https://preseto.com/go/storage-subscribe-post" method="get" target="_blank">
							<p>
								<?php esc_html_e( 'Subscribe to plugin update notifications:', 'cf7-storage' ); ?>
							</p>
							<input type="email" value="<?php echo esc_attr( $current_user->user_email ); ?>" name="EMAIL" class="input-email" placeholder="Enter your email" required>
							<input type="submit" value="<?php esc_attr_e( 'Subscribe', 'cf7-storage' ); ?>" name="subscribe" class="button">
						</form>
					</div>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Render section tab navigaion.
	 *
	 * @return void
	 */
	public function tabs() {
		$links = array();
		$section = $this->section();

		foreach ( $this->sections as $tab_id => $tab ) {
			$classes = array();

			if ( $tab_id === $section ) {
				$classes[] = 'nav-tab-active';
			}

			$links[] = sprintf(
				'<a href="%s" class="nav-tab %s">%s</a>',
				esc_url( $this->section_link( $tab_id ) ),
				esc_attr( implode( ' ', $classes ) ),
				esc_html( $tab['label'] )
			);
		}

		?>
		<nav class="nav-tab-wrapper">
			<?php echo implode( '', $links ); // WPCS: XSS ok, escaped during assemble. ?>
		</nav>
		<?php
	}

	/**
	 * Generate a link to a section of settings.
	 *
	 * @param  string $section Section or tab ID.
	 *
	 * @return string
	 */
	public function section_link( $section ) {
		$params = array();

		if ( self::SECTION_DEFAULT !== $section ) {
			$params = array(
				'section' => $section,
			);
		}

		return $this->link( $params );
	}

	/**
	 * Render the meta data settings.
	 *
	 * @param  array $args Field settings from the registration.
	 *
	 * @return void
	 */
	public function render_meta_disable( $args ) {
		$toggles = array(
			'user-agent' => array(
				'label' => __( 'Do not store user browser agent', 'cf7-storage' ),
			),
			'user-ip' => array(
				'label' => __( 'Do not store user IP address', 'cf7-storage' ),
			),
		);

		$setting_list = array();
		$settings = $this->settings->get( array( 'meta-disable' ) );

		foreach ( $toggles as $key => $field ) {
			$field_value = false;

			if ( ! empty( $settings[ $key ] ) ) {
				$field_value = true;
			}

			$field_name = $this->field_name(
				array(
					'meta-disable',
					$key,
				)
			);

			$setting_list[] = sprintf(
				'<li>
					<label>
						<input type="checkbox" name="%s" value="1" %s />
						%s
					</label>
				</li>',
				esc_attr( $field_name ),
				checked( true, $field_value, false ),
				esc_html( $field['label'] )
			);
		}

		?>
		<p>
			<?php esc_html_e( 'Limit the meta data stored with each form submission.', 'cf7-storage' ); ?>
		</p>
		<ul>
			<?php echo implode( '', $setting_list ); // WPCS: XSS ok, escaped during assembling. ?>
		</ul>
		<p>
			<?php esc_html_e( 'Note that deleting a form submission will also remove all associated file attachments and meta data.', 'cf7-storage' ); ?>
		</p>
		<?php
	}

	/**
	 * Add links to edit each form settings.
	 *
	 * @param  array $args Field paramaters.
	 *
	 * @return void
	 */
	public function render_form_settings( $args = array() ) {
		$contact_forms = WPCF7_ContactForm::find();
		$form_links = array();

		foreach ( $contact_forms as $form ) {
			$form_links[] = sprintf(
				'<li><a href="%s">%s</a></li>',
				esc_url( $this->plugin->link_form_settings( $form->id() ) ),
				esc_html( $form->title() )
			);
		}

		?>
		<p>
			<?php esc_html_e( 'Configure form settings to specify which fields to include in the CSV export.', 'cf7-storage' ); ?>
		</p>
		<ul>
			<?php echo implode( '', $form_links ); // WPCS: XSS ok, escaped during assamble. ?>
		</ul>
		<?php
	}

}
