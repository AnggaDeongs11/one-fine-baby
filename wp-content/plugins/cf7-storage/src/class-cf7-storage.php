<?php

/**
 * Main CF7 Storage class.
 */
class Cf7_Storage {

	/**
	 * Name of the uploads directory.
	 *
	 * @var string
	 */
	const UPLOADS_DIR = 'cf7-storage';

	/**
	 * Name of the custom post type for entries.
	 *
	 * @var string
	 */
	const POST_TYPE = 'cf7_entry';

	/**
	 * Name of the custom post meta used for storing paths to entry file
	 * attachments.
	 *
	 * @var string
	 */
	const ENTRY_ATTACHMENT_KEY = 'mail_attachments';

	/**
	 * Plugin interface.
	 *
	 * @var Cf7_Plugin
	 */
	protected $plugin;

	/**
	 * Settings interface.
	 *
	 * @var Cf7_Storage_Settings
	 */
	protected $settings;

	/**
	 * Pairs of query args for the current admin request.
	 *
	 * @var array
	 */
	protected $query_args = array();

	/**
	 * Post ID of the currently viewed entry.
	 *
	 * @var integer
	 */
	protected $current_entry_id;

	/**
	 * Setup the core logic.
	 *
	 * @param Cf7_Storage_Plugin $plugin Instance of the WP plugin.
	 */
	public function __construct( $plugin ) {
		$this->plugin = $plugin;

		$this->plugin_settings = new Cf7_Storage_Plugin_Settings(
			new Cf7_Storage_Options_Store( 'cf7-storage' )
		);

		$this->plugin_settings_page = new Cf7_Storage_Plugin_Settings_Page(
			'cf7_storage_settings',
			$this,
			$this->plugin_settings
		);

		$this->form_settings = new Cf7_Storage_Form_Settings(
			new Cf7_Storage_Meta_Store( 'cf7_storage_form_settings' )
		);

		$this->form_settings_page = new Cf7_Storage_Form_Settings_Page(
			$this,
			$this->form_settings
		);
	}

	/**
	 * Return the current plugin object.
	 *
	 * @return Cf7_Storage_Plugin
	 */
	public function plugin() {
		return $this->plugin;
	}

	/**
	 * Return absolute path to our custom uploads directory.
	 *
	 * @return string
	 */
	public function uploads_dir() {
		return $this->plugin->uploads_dir( self::UPLOADS_DIR );
	}

	/**
	 * Return URL to our custom uploads directory.
	 *
	 * @return string
	 */
	public function uploads_dir_url() {
		return $this->plugin->uploads_dir_url( self::UPLOADS_DIR );
	}

	/**
	 * Return the current singleton instance of the plugin.
	 *
	 * @return Cf7_Storage
	 */
	public static function instance() {
		return $this;
	}

	/**
	 * Register all WP hooks.
	 *
	 * @return void
	 */
	public function init() {
		$this->plugin_settings_page->init();
		$this->form_settings_page->init();

		$this->init_capture();

		add_action( 'init', array( $this, 'init_l10n' ) );

		// Define storage post type.
		add_action( 'init', array( $this, 'storage_init' ) );

		// Add admin view.
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );

		// Return entry ID and entry URL as special tags.
		add_filter( 'wpcf7_special_mail_tags', array( $this, 'special_tags_entry' ), 10, 3 );

		// Notify users running old versions of Contact Form 7.
		add_action( 'cf7_storage_admin_notices', array( $this, 'cf7_upgrade_notice' ) );

		add_filter( 'plugin_action_links_' . $this->plugin->basename(), array( $this, 'plugin_action_links' ) );

		// Ensure that [file-*] points to the actual uploaded URL in the body.
		add_filter( 'wpcf7_mail_components', array( $this, 'wpcf7_mail_components' ), 10, 2 );
	}

	/**
	 * Return the current version of the plugin.
	 *
	 * @return string
	 */
	public function version() {
		return $this->plugin->version();
	}

	/**
	 * URL to plugin asset.
	 *
	 * @param string $path Asset path relative to the plugin root directory.
	 */
	public function asset_url( $path ) {
		return $this->plugin->asset_url( $path );
	}

	/**
	 * Get path to the hashed filename of the uploaded file.
	 *
	 * @param  string  $path Absolute path of the uploaded plugin.
	 * @param  integer $entry_id Entry post ID.
	 *
	 * @return string
	 */
	public function get_storage_file_path( $path, $entry_id ) {
		return sprintf(
			'%s/%d-%s.%s',
			$this->uploads_dir(),
			$entry_id,
			md5( $path ),
			pathinfo( $path, PATHINFO_EXTENSION )
		);
	}

	/**
	 * Get URL to the hashed filename of the uploaded file.
	 *
	 * @param  string  $path Absolute path of the uploaded plugin.
	 * @param  integer $entry_id Post object ID.
	 *
	 * @return string
	 */
	public function get_storage_file_url( $path, $entry_id ) {
		return sprintf(
			'%s/%d-%s.%s',
			$this->uploads_dir_url(),
			$entry_id,
			md5( $path ),
			pathinfo( $path, PATHINFO_EXTENSION )
		);
	}

	/**
	 * Build a link to the entries admin page.
	 *
	 * @param  array $extra_params Additional query parameters to include.
	 *
	 * @return string
	 */
	public function admin_link( $extra_params = null ) {
		$params = array(
			'page' => 'cf7_storage',
		);

		if ( ! empty( $extra_params ) && is_array( $extra_params ) ) {
			$params = array_merge( $params, $extra_params );
		}

		return add_query_arg( $params, admin_url( 'admin.php' ) );
	}

	/**
	 * URL to form entries.
	 *
	 * @param  int $form_id Form ID.
	 *
	 * @return string
	 */
	public function link_entries( $form_id ) {
		return $this->admin_link(
			array(
				'form_id' => $form_id,
			)
		);
	}

	/**
	 * URL to a form submission in the admin.
	 *
	 * @param  integer $entry_id Entry ID.
	 *
	 * @return string
	 */
	public function link_entry( $entry_id ) {
		return $this->admin_link(
			array(
				'action' => 'view',
				'post_id' => $entry_id,
			)
		);
	}

	/**
	 * URL of the form storage settings.
	 *
	 * @param  int $form_id Form ID.
	 *
	 * @return string
	 */
	public function link_form_settings( $form_id ) {
		return $this->form_settings_page->link( $form_id );
	}

	/**
	 * Ensure [file-*] points to the actual uploaded URL in the mail body.
	 *
	 * @param  array             $components All mail components.
	 * @param  WPCF7_ContactForm $form Current contact form.
	 *
	 * @return array
	 */
	function wpcf7_mail_components( $components, $form ) {
		$submission = WPCF7_Submission::get_instance();

		if ( ! empty( $components['body'] ) && $submission ) {
			$components['body'] = $this->mail_replace_file_urls( $components['body'], $submission->uploaded_files() );
		}

		return $components;
	}

	/**
	 * Find and replace filenames with the actual URLs in mail body.
	 *
	 * @param  string $body Mail body.
	 * @param  array  $attachments List of attachments.
	 *
	 * @return string
	 */
	function mail_replace_file_urls( $body, $attachments ) {
		if ( empty( $attachments ) ) {
			return $body;
		}

		foreach ( $attachments as $attachment_path ) {
			$body = str_replace(
				basename( $attachment_path ),
				$this->get_storage_file_url( $attachment_path, $this->current_entry_id ),
				$body
			);
		}

		return $body;
	}

	/**
	 * Hook into main CF7 form submission hooks to capture the data.
	 *
	 * @return void
	 */
	function init_capture() {
		if ( ! defined( 'WPCF7_VERSION' ) ) {
			add_action( 'admin_notices', array( $this, 'require_cf7' ) );
		} elseif ( class_exists( 'WPCF7_Mail', false ) ) {
			// for CF7 >= 3.9.
			add_action( 'wpcf7_before_send_mail', array( $this, 'storage_capture' ), 100 );
		} else {
			// for CF7 < 3.9.
			add_action( 'wpcf7_mail_sent', array( $this, 'storage_capture_legacy' ) );
		}
	}

	/**
	 * Display an admin warning of CF7 plugin not found.
	 *
	 * @return void
	 */
	function require_cf7() {
		$notice_screens = array(
			'plugins',
			'dashboard',
		);

		$screen = get_current_screen();

		if ( ! in_array( $screen->id, $notice_screens ) ) {
			return;
		}

		printf(
			'<div class="notice notice-error">
				<p>%s <a href="%s" class="button">%s</a></p>
			</div>',
			esc_html__( 'The Storage for Contact Form 7 plugin requires the Contact Form 7 plugin which currently isn\'t installed and activated.', 'cf7-storage' ),
			esc_url( admin_url( 'plugin-install.php?s=contact+form+7&tab=search' ) ),
			esc_html__( 'Search and Install', 'cf7-storage' )
		);
	}

	/**
	 * Register plugin localization.
	 *
	 * @return void
	 */
	function init_l10n() {
		load_plugin_textdomain(
			'cf7-storage',
			false,
			dirname( $this->plugin->basename() ) . '/languages'
		);
	}

	/**
	 * Register out custom post type for the actual storage.
	 *
	 * @return void
	 */
	function storage_init() {
		register_post_type(
			self::POST_TYPE,
			array(
				'public' => false,
				'label' => __( 'Entries', 'cf7-storage' ),
				'supports' => false,
			)
		);
	}

	/**
	 * Process and store the form submission for older versions of CF7.
	 *
	 * @param  WPCF7_ContactForm $cf7 Contact form object.
	 *
	 * @return void
	 */
	function storage_capture_legacy( $cf7 ) {
		$mail = $cf7->compose_mail(
			$cf7->setup_mail_template( $cf7->mail, 'mail' ),
			false // Don't send.
		);

		$entry_id = wp_insert_post(
			array(
				'post_title' => $mail['sender'],
				'post_type' => self::POST_TYPE,
				'post_status' => 'publish',
				'post_parent' => $cf7->id,
				'post_content' => $mail['body'],
			)
		);

		foreach ( $mail as $mail_field => $mail_value ) {
			add_post_meta( $entry_id, 'mail_' . $mail_field, $mail_value );
		}

		// Store all the meta data.
		foreach ( $cf7->posted_data as $key => $value ) {
			add_post_meta( $entry_id, 'cf7_' . $key, $value );
		}

		// Maybe store meta data of the request.
		$extra_meta = $this->get_request_meta();

		foreach ( $extra_meta as $key => $value ) {
			add_post_meta( $entry_id, $key, $value );
		}

		// Store uploads permanently.
		$uploads_stored = $this->store_uploaded_files( $entry_id, $cf7->uploaded_files );

		do_action( 'cf7_storage_capture', $entry_id, $cf7 );
	}

	/**
	 * Capture the form data for the newer versions of CF7.
	 *
	 * @param  WPCF7_ContactForm $cf7 Contact form object.
	 *
	 * @return void
	 */
	function storage_capture( $cf7 ) {
		if ( apply_filters( 'cf7_storage_skip_capture', false, $cf7 ) ) {
			return;
		}

		$submission = WPCF7_Submission::get_instance();

		// Store this entry and get its ID.
		$entry_id = wp_insert_post(
			array(
				'post_type' => self::POST_TYPE,
				'post_status' => 'publish',
				'post_parent' => $cf7->id(),
			)
		);

		do_action( 'cf7_storage_pre_capture', $entry_id, $cf7 );

		// Make this entry ID available to this class.
		$this->set_entry_id( $entry_id );

		// Get the mail template and settings of this contact form.
		$template = $cf7->prop( 'mail' );

		// Replace all variables with form values.
		$mail = wpcf7_mail_replace_tags(
			$template,
			array(
				'html' => $template['use_html'],
				'exclude_blank' => $template['exclude_blank'],
			)
		);

		// Ensure that all files point to the uploaded file.
		$mail['body'] = $this->mail_replace_file_urls( $mail['body'], $submission->uploaded_files() );

		// Update post title and body content.
		wp_update_post(
			array(
				'ID' => $entry_id,
				'post_title' => $mail['sender'],
				'post_content' => $mail['body'],
			)
		);

		// Store all field values that were mailed.
		foreach ( $mail as $mail_field => $mail_value ) {
			add_post_meta( $entry_id, 'mail_' . $mail_field, $mail_value, true );
		}

		$form_fields = $cf7->scan_form_tags();
		$posted_data_raw = $submission->get_posted_data();
		$posted_fields = array();

		foreach ( $form_fields as $field ) {

			$field_name = $field['name'];

			if ( isset( $posted_data_raw[ $field_name ] ) ) {

				$field_value = $posted_data_raw[ $field_name ];

				// Store the field value.
				$posted_fields[ $field_name ] = $field_value;

				// Store the labels of the piped data too.
				if ( ! empty( $field['raw_values'] ) ) {

					$piped_options = array();

					// Convert pipe string pairs into value => label mapping.
					foreach ( $field['raw_values'] as $piped_option ) {
						$option_pair = explode( '|', $piped_option );

						if ( isset( $option_pair[1] ) ) {
							$piped_options[ $option_pair[1] ] = $option_pair[0];
						} else {
							$piped_options[ $option_pair[0] ] = $option_pair[0];
						}
					}

					// Add the label as an additional field.
					if ( is_string( $field_value ) && isset( $piped_options[ $field_value ] ) ) {
						$posted_fields[ $field_name . '-pipe-label' ] = $piped_options[ $field_value ];
					} elseif ( is_array( $field_value ) ) {
						// Support checkboxes, radios, etc.
						$posted_fields[ $field_name . '-pipe-label' ] = array_intersect_key( $piped_options, array_flip( $field_value ) );
					}
				}
			}
		}

		// Store field values.
		add_post_meta( $entry_id, 'form_fields', $posted_fields );

		$extra_meta = $this->get_request_meta();

		$unit_tag = $submission->get_meta( 'unit_tag' );

		// Store the post/page ID where the form was submitted.
		if ( $unit_tag && preg_match( '/^wpcf7-f(\d+)-p(\d+)-o(\d+)$/', $unit_tag, $unit_matches ) ) {
			$extra_meta['post_id'] = absint( $unit_matches[2] );
		}

		if ( is_user_logged_in() ) {
			$extra_meta['user_id'] = get_current_user_id();
		}

		foreach ( $extra_meta as $key => $value ) {
			add_post_meta( $entry_id, $key, $value );
		}

		// Store uploads permanently.
		$uploads_stored = $this->store_uploaded_files( $entry_id, $submission->uploaded_files() );

		do_action( 'cf7_storage_capture', $entry_id, $cf7 );
	}

	/**
	 * Store the meta data enabled.
	 *
	 * @return array
	 */
	public function get_request_meta() {
		// Store referer by default.
		$meta = array(
			'http_referer' => sanitize_text_field( $_SERVER['HTTP_REFERER'] ),
			'http_user_agent' => '-',
			'remote_addr' => '-',
		);

		// Maybe store browser data.
		if ( ! $this->plugin_settings->is_meta_user_agent_disabled() ) {
			$meta['http_user_agent'] = sanitize_text_field( $_SERVER['HTTP_USER_AGENT'] );
		}

		// Maybe store user IP.
		if ( ! $this->plugin_settings->is_meta_ip_disabled() ) {
			$meta['remote_addr'] = sanitize_text_field( $_SERVER['REMOTE_ADDR'] );
		}

		return $meta;
	}

	/**
	 * Upload form submission attachments to a permanant location.
	 *
	 * @param  integer $entry_id Form submission post ID.
	 * @param  array   $files List of attachment file path.
	 *
	 * @return array List of the new file path for each attachment.
	 */
	function store_uploaded_files( $entry_id, $files = array() ) {
		// Escape all backslashes so they don't get removed in DB.
		foreach ( $files as &$file ) {
			$file = str_replace( '\\', '/', $file );
		}

		// Make sure we store the information about attachments even if they are not being sent via mail.
		if ( ! empty( $files ) ) {
			$this->store_entry_attachments( $entry_id, $files );
		}

		if ( ! is_dir( $this->uploads_dir() ) ) {
			@mkdir( $this->uploads_dir() ); // phpcs:ignore Generic.PHP.NoSilencedErrors.Discouraged
		}

		$htaccess_file = sprintf( '%s/.htaccess', $this->uploads_dir() );

		// Make sure that uploads directory is protected from listing.
		if ( ! file_exists( $htaccess_file ) ) {
			file_put_contents( $htaccess_file, 'Options -Indexes' );
		}

		$uploads_stored = array();

		foreach ( $files as $name => $path ) {
			if ( ! isset( $_FILES[ $name ] ) ) {
				continue;
			}

			$destination = $this->get_storage_file_path( $path, $entry_id );

			$uploads_stored[] = $destination;

			// Copy to a permanant storage location.
			@copy( $path, $destination ); // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged
		}

		// Store information about uploads that were stored.
		add_post_meta( $entry_id, 'cf7_storage_uploads_stored', $uploads_stored );

		return $uploads_stored;
	}

	/**
	 * Store absolute paths to file attachments in entry meta.
	 *
	 * @param  integer $entry_id Entry post ID.
	 * @param  array   $files    List of file paths.
	 *
	 * @return boolean
	 */
	protected function store_entry_attachments( $entry_id, $files ) {
		return update_post_meta( $entry_id, self::ENTRY_ATTACHMENT_KEY, $files );
	}

	/**
	 * Get absolute paths to entry file attachments.
	 *
	 * Note that `mail_attachments` is also populated after compose_mail() in
	 * storage_capture() so we ensure this contains our list of attachments.
	 *
	 * @param  integer $entry_id Entry post ID.
	 *
	 * @return array
	 */
	protected function get_entry_attachments( $entry_id ) {
		$attachments = get_post_meta( $entry_id, self::ENTRY_ATTACHMENT_KEY, true );

		if ( is_array( $attachments ) ) {
			return $attachments;
		}

		return array();
	}

	/**
	 * Set the working entry ID. Probably needs to be refactored.
	 *
	 * @param integer $entry_id Post ID of the current entry.
	 */
	function set_entry_id( $entry_id ) {
		$this->current_entry_id = $entry_id;
	}

	/**
	 * Replace our custom mail content tags with data.
	 *
	 * @param  string  $replaced Tag replace value.
	 * @param  string  $tagname Tag ID.
	 * @param  boolean $html If the email is plain text or rich HTML.
	 *
	 * @return string|null
	 */
	function special_tags_entry( $replaced, $tagname, $html ) {
		switch ( $tagname ) {

			case 'storage_entry_id':
				if ( $this->current_entry_id ) {
					return $this->current_entry_id;
				}
				break;

			case 'storage_entry_url':
				if ( $this->current_entry_id ) {
					return $this->link_entry( $this->current_entry_id );
				}
				break;

		}

		return $replaced;
	}

	/**
	 * Register a subpage for Contact Form 7.
	 *
	 * @return void
	 */
	function admin_menu() {
		$cf7_subpage = add_submenu_page(
			'wpcf7',
			__( 'Contact Form Entries', 'cf7-storage' ),
			__( 'Entries', 'cf7-storage' ),
			'wpcf7_read_contact_forms',
			'cf7_storage',
			array( $this, 'admin_page' )
		);

		add_action( 'load-' . $cf7_subpage, array( $this, 'admin_actions_process' ) );
	}

	/**
	 * Process the current request.
	 *
	 * @return void
	 */
	function admin_actions_process() {
		global $wpdb;

		// Enqueue our admin style and scripts.
		wp_enqueue_style(
			'cf7s-style',
			$this->asset_url( 'assets/css/entries.css' )
		);

		wp_enqueue_script(
			'cf7s-js',
			$this->asset_url( 'assets/js/entries.js' ),
			array( 'jquery' ),
			null,
			true
		);

		if ( empty( $_REQUEST['action'] ) || 'view' === $_REQUEST['action'] ) {
			return;
		}

		// Make sure this is a valid admin request.
		check_admin_referer( 'bulk-form-entries' );

		$action = $_REQUEST['action'];

		if ( isset( $_REQUEST['export-entries'] ) ) {
			$action = 'export';
		}

		// Export ALL entries.
		if ( 'export' === $action && empty( $_REQUEST['form-entry'] ) && empty( $_REQUEST['post_id'] ) ) {
			// Parse request to select posts for export.
			$query_args = $this->get_query_args();

			// Export ALL entries.
			$query_args['posts_per_page'] = -1;

			$this->export_entries( $query_args );
		}

		if ( ! empty( $_REQUEST['s'] ) ) {
			$action = 'search';
		}

		if ( isset( $_REQUEST['delete_all'] ) ) {
			$action = 'delete';
		}

		$action_whitelist = array(
			'export',
			'trash',
			'delete',
			'untrash',
			'search',
		);

		if ( ! in_array( $action, $action_whitelist ) ) {
			return;
		}

		$sendback = esc_url_raw(
			remove_query_arg(
				array(
					'export-entries',
					'export-csv-delimiter',
					'export',
					'trashed',
					'untrashed',
					'deleted',
					'delete_all',
					'locked',
					'ids',
					'action',
					'action2',
					'post_id',
					'_wp_http_referer',
					'_wpnonce',
				)
			)
		);

		$post_ids = array();

		// Collect the post IDs we need to act on.
		if ( 'delete' == $action && ! isset( $_REQUEST['post_id'] ) ) {
			// Get all posts in trash.
			$post_ids = $wpdb->get_col(
				$wpdb->prepare(
					"SELECT ID FROM $wpdb->posts WHERE post_type = %s AND post_status = %s",
					self::POST_TYPE,
					'trash'
				)
			);
		} elseif ( isset( $_REQUEST['ids'] ) && ! empty( $_REQUEST['ids'] ) ) {
			$post_ids = explode( ',', $_REQUEST['ids'] );
		} elseif ( isset( $_REQUEST['post_id'] ) && ! empty( $_REQUEST['post_id'] ) ) {
			$post_ids = array( (int) $_REQUEST['post_id'] );
		} elseif ( isset( $_REQUEST['form-entry'] ) && ! empty( $_REQUEST['form-entry'] ) ) {
			$post_ids = array_map( 'intval', $_REQUEST['form-entry'] );
		}

		// No posts have been selected, bail out.
		if ( empty( $post_ids ) ) {
			wp_redirect( $sendback );
			exit();
		}

		switch ( $action ) {

			case 'export':
				// Parse request to select posts for export.
				$query_args = $this->get_query_args();

				// Export ALL entries.
				$query_args['posts_per_page'] = -1;
				$query_args['post__in'] = $post_ids;

				$this->export_entries( $query_args );

				break;

			case 'trash':
				foreach ( $post_ids as $post_id ) {

					if ( ! current_user_can( 'delete_post', $post_id ) ) {
						wp_die( __( 'You are not allowed to move this item to Trash.', 'cf7-storage' ) );
					}

					if ( ! wp_trash_post( $post_id ) ) {
						wp_die( __( 'Error moving an item to Trash.', 'cf7-storage' ) );
					}

					$sendback = add_query_arg(
						array(
							'trashed' => true,
						),
						$sendback
					);
				}

				break;

			case 'untrash':
				foreach ( $post_ids as $post_id ) {

					if ( ! current_user_can( 'delete_post', $post_id ) ) {
						wp_die( __( 'You are not allowed to restore this item from Trash.', 'cf7-storage' ) );
					}

					if ( ! wp_untrash_post( $post_id ) ) {
						wp_die( __( 'Error in restoring an item from Trash.', 'cf7-storage' ) );
					}
				}

				$sendback = add_query_arg(
					'untrashed',
					true,
					$sendback
				);

				break;

			case 'delete':
				foreach ( $post_ids as $post_id ) {

					if ( ! current_user_can( 'delete_post', $post_id ) ) {
						wp_die( __( 'You are not allowed to delete this entry.', 'cf7-storage' ) );
					}

					$attachments = $this->get_entry_attachments( $post_id );

					if ( ! empty( $attachments ) ) {
						foreach ( $attachments as $attachment_path ) {
							$attachment_file = $this->get_storage_file_path( $attachment_path, $post_id );

							if ( file_exists( $attachment_file ) ) {
								unlink( $attachment_file );
							}
						}
					}

					if ( ! wp_delete_post( $post_id, true ) ) {
						wp_die( __( 'Error in deleting an entry.', 'cf7-storage' ) );
					}
				}

				$sendback = add_query_arg(
					array(
						'deleted' => true,
					),
					$sendback
				);

				break;

		}

		wp_redirect( $sendback );
		exit();

	}

	/**
	 * Set query args based on the current request parameters.
	 */
	function set_query_args() {
		$this->query_args = array(
			'post_type' => self::POST_TYPE,
			'orderby' => 'date',
			'order' => 'DESC',
		);

		// Search entries.
		if ( ! empty( $_REQUEST['s'] ) ) {
			$this->query_args['s'] = $_REQUEST['s'];
		}

		// Custom order by date.
		if ( ! empty( $_REQUEST['order'] ) ) {
			if ( 'asc' == strtolower( $_REQUEST['order'] ) ) {
				$this->query_args['order'] = 'ASC';
			} elseif ( 'desc' == strtolower( $_REQUEST['order'] ) ) {
				$this->query_args['order'] = 'DESC';
			}
		}

		// Filter by contact form.
		if ( ! empty( $_REQUEST['form_id'] ) ) {
			$this->query_args['post_parent'] = absint( $_REQUEST['form_id'] );
		}

		// Filter by trash.
		if ( ! empty( $_REQUEST['post_status'] ) ) {
			$this->query_args['post_status'] = $_REQUEST['post_status'];
		}

		// Filter by month of submission.
		if ( ! empty( $_REQUEST['m'] ) ) {
			$this->query_args['m'] = absint( $_REQUEST['m'] );
		}
	}


	/**
	 * Get the current request query args.
	 *
	 * @return array
	 */
	function get_query_args() {
		if ( empty( $this->query_args ) ) {
			$this->set_query_args();
		}

		return $this->query_args;
	}

	/**
	 * Route and render the correct admin page.
	 *
	 * @return void
	 */
	function admin_page() {
		$action = 'index';

		if ( isset( $_REQUEST['action'] ) ) {
			$action = $_REQUEST['action'];
		}

		switch ( $action ) {

			case 'view':
				if ( ! isset( $_REQUEST['post_id'] ) ) {
					wp_die( __( 'Missing entry ID.', 'cf7-storage' ) );
				}

				$post_id = $_REQUEST['post_id'];

				// We are viewing this entry now.
				$this->admin_single_entry( $post_id );

				break;

			default:
				// List of all entries.
				$this->admin_entry_index();

				break;

		}
	}

	/**
	 * Render the entry table.
	 *
	 * @return void
	 */
	function admin_entry_index() {
		// Include our list view, TODO: use a reference path instead.
		require_once $this->plugin->dir() . '/src/class-cf7-storage-list-table.php';

		if ( ! class_exists( 'WP_List_Table' ) ) {
			require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
		}

		$list_table = new Cf7_Storage_List_Table( self::POST_TYPE, $this );
		$list_table->prepare_items();

		?>
		<div class="wrap cf7-storage-entries">
			<h1 class="wp-heading-inline">
				<?php esc_html_e( 'Contact Form Entries', 'cf7-storage' ); ?>

				<?php
				$settings_url = $this->plugin_settings_page->link();

				if ( $settings_url ) {
					printf(
						'<a href="%s" class="page-title-action">%s</a>',
						esc_url( $settings_url ),
						__( 'Settings', 'cf7-storage' )
					);
				}

				if ( ! empty( $_REQUEST['s'] ) ) {
					printf(
						'<span class="subtitle">%s</span>',
						esc_html(
							sprintf(
								// translators: %s: search query string.
								__( 'Search results for "%s"', 'cf7-storage' ),
								$_REQUEST['s']
							)
						)
					);
				}

				if ( ! empty( $_REQUEST['form_id'] ) ) {
					$form = WPCF7_ContactForm::get_instance( absint( $_REQUEST['form_id'] ) );

					if ( ! empty( $form ) ) {
						printf(
							'<span class="subtitle">
								%s
								<a href="%s" class="page-title-action">%s</a>
							</span>',
							sprintf(
								// translators: %s: search query string.
								esc_html__( 'Entries for "%s"', 'cf7-storage' ),
								$form->title()
							),
							esc_url( $this->link_form_settings( $form->id() ) ),
							esc_html__( 'Form Settings', 'cf7-storage' )
						);
					}
				}
				?>
			</h1>
			<hr class="wp-header-end">

			<?php do_action( 'cf7_storage_admin_notices' ); ?>

			<?php $list_table->views(); ?>

			<form method="get" action="">
				<input type="hidden" name="page" value="<?php echo esc_attr( $_REQUEST['page'] ); ?>" />
				<?php
					$list_table->search_box( __( 'Search Entries', 'cf7-storage' ), self::POST_TYPE );
					$list_table->display();
				?>
			</form>
		</div>
		<?php
	}

	/**
	 * Render the single entry view.
	 *
	 * @param  integer $post_id Entry post ID.
	 *
	 * @return void
	 */
	function admin_single_entry( $post_id ) {
		// Make sure we cast it to an integer value.
		$post_id = intval( $post_id );
		$post = get_post( $post_id );

		if ( empty( $post ) ) {
			wp_die( __( 'This contact form submission doesn\'t exist!', 'cf7-storage' ) );
		}

		if ( self::POST_TYPE !== $post->post_type ) {
			return;
		}

		// Prepare links to attachments.
		$attachments = $this->get_entry_attachments( $post->ID );

		if ( ! empty( $attachments ) ) {
			$attachment_list = array();

			foreach ( $attachments as $url ) {
				$attachment_list[] = sprintf(
					'<li><a href="%s" target="_blank">%s</a></li>',
					esc_url( $this->get_storage_file_url( $url, $post_id ) ),
					esc_html( basename( $url ) )
				);
			}

			$maybe_attachments = sprintf(
				'<ul>%s<ul>',
				implode( '', $attachment_list )
			);
		} else {
			$maybe_attachments = _x( 'None', 'No attachments found', 'cf7-storage' );
		}

		$timestamp = strtotime( $post->post_date );

		$body = apply_filters( 'the_content', $post->post_content );
		$body = wp_strip_all_tags( $body );

		$rows = array(
			'form-link' => array(
				'label' => __( 'Contact Form', 'cf7-storage' ),
				'value' => sprintf(
					'<a href="%s">%s</a>',
					esc_url( $this->link_form_settings( $post->post_parent ) ),
					esc_html( get_the_title( $post->post_parent ) )
				),
			),
			'from' => array(
				'label' => __( 'From', 'cf7-storage' ),
				'value' => esc_html( get_post_meta( $post->ID, 'mail_sender', true ) ),
			),
			'to' => array(
				'label' => __( 'To', 'cf7-storage' ),
				'value' => esc_html( get_post_meta( $post->ID, 'mail_recipient', true ) ),
			),
			'date' => array(
				'label' => __( 'Date', 'cf7-storage' ),
				'value' => esc_html(
					sprintf(
						'%s %s',
						date_i18n( get_option( 'date_format' ), $timestamp ),
						date_i18n( get_option( 'time_format' ), $timestamp )
					)
				),
			),
			'subject' => array(
				'label' => __( 'Subject', 'cf7-storage' ),
				'value' => esc_html( get_post_meta( $post->ID, 'mail_subject', true ) ),
			),
			'body' => array(
				'label' => __( 'Message', 'cf7-storage' ),
				'value' => sprintf(
					'<div class="body-content-wrap"><pre>%s</pre></div>',
					esc_html( $body )
				),
			),
			'attachments' => array(
				'label' => __( 'Attachments', 'cf7-storage' ),
				'value' => $maybe_attachments,
			),
		);

		// Allow other plugins to add more elements to our message view.
		$rows = apply_filters( 'cf7_entry_rows', $rows, $post );

		$rows_html = array();

		foreach ( $rows as $row_id => $row_elements ) {
			$rows_html[] = sprintf(
				'<tr class="cf7s-%s">
					<th>%s:</th>
					<td>%s</td>
				</tr>',
				esc_attr( $row_id ),
				esc_html( $row_elements['label'] ),
				$row_elements['value']
			);
		}

		// Get raw form fields.
		$form_fields = $this->get_entry_field_values( $post->ID );
		$fields_html = array();

		if ( ! empty( $form_fields ) ) {
			foreach ( $form_fields as $field_key => $field_value ) {
				$fields_html[] = sprintf(
					'<tr class="cf7s-form-field">
						<th>%s:</th>
						<td>%s</td>
					</tr>',
					esc_html( $field_key ),
					esc_html( $field_value )
				);
			}
		} else {
			$fields_html[] = sprintf(
				'<tr class="cf7s-form-fields-empty">
					<td>%s</td>
				</tr>',
				esc_html__( 'No field values were captured.', 'cf7-storage' )
			);
		}

		$meta_rows = array();
		$on_post_id = (int) get_post_meta( $post->ID, 'post_id', true );

		if ( ! empty( $on_post_id ) ) {
			$on_post_type_label = __( 'Post', 'cf7-storage' );
			$on_post_type_object = get_post_type_object( get_post_type( $on_post_id ) );

			if ( $on_post_type_object ) {
				$on_post_type_label = $on_post_type_object->labels->singular_name;
			}

			$meta_rows['referer'] = array(
				'label' => __( 'Referer', 'cf7-storage' ),
				'value' => sprintf(
					'%s: <a href="%s" target="_blank">%s</a>',
					esc_html( $on_post_type_label ),
					esc_url( get_permalink( $on_post_id ) ),
					esc_html( get_the_title( $on_post_id ) )
				),
			);
		} else {
			$referer = get_post_meta( $post->ID, 'http_referer', true );

			$meta_rows['referer'] = array(
				'label' => __( 'Referer', 'cf7-storage' ),
				'value' => sprintf(
					'%s &mdash; <a href="%s" target="_blank">%s</a>',
					esc_html( $referer ),
					esc_url( $referer ),
					__( 'Visit', 'cf7-storage' )
				),
			);
		}

		$meta_rows['user-agent'] = array(
			'label' => __( 'User agent', 'cf7-storage' ),
			'value' => esc_html( get_post_meta( $post->ID, 'http_user_agent', true ) ),
		);

		$remote_addr = get_post_meta( $post->ID, 'remote_addr', true );

		if ( false !== strpos( $remote_addr, '.' ) ) {
			$ip_address = sprintf(
				'<a href="http://whois.arin.net/rest/ip/%s" target="_blank">%s</a>',
				esc_attr( $remote_addr ),
				esc_html( $remote_addr )
			);
		} else {
			$ip_address = esc_html( $remote_addr );
		}

		$meta_rows['remote_addr'] = array(
			'label' => __( 'IP Address', 'cf7-storage' ),
			'value' => $ip_address,
		);

		// Allow other plugins to add more elements to meta information.
		$meta_rows = apply_filters( 'cf7_entry_rows_meta', $meta_rows, $post );

		foreach ( $meta_rows as $row_id => $row_elements ) {
			$meta_html[] = sprintf(
				'<tr class="cf7s-%s">
					<th>%s:</th>
					<td>%s</td>
				</tr>',
				esc_attr( $row_id ),
				esc_html( $row_elements['label'] ),
				$row_elements['value']
			);
		}

		printf(
			'<div class="wrap cfs7-entry-wrap">
				<h2>%s</h2>
				<table class="cf7s-entry">
					%s
				</table>
				<h3>%s</h3>
				<table id="cf7s-entry-fields" class="cf7s-entry">
					%s
				</table>
				<h3>%s <a href="%s" class="page-title-action">%s</a></h3>
				<table id="cf7s-entry-meta" class="cf7s-entry">
					%s
				</table>
			</div>',
			esc_html__( 'Form Submission', 'cf7-storage' ),
			implode( '', $rows_html ),
			esc_html__( 'Field Values', 'cf7-storage' ),
			implode( '', $fields_html ),
			esc_html__( 'Submission Details', 'cf7-storage' ),
			$this->plugin_settings_page->link(),
			esc_attr__( 'Settings', 'cf7-storage' ),
			implode( '', $meta_html )
		);
	}

	/**
	 * Get a list of the system entry fields.
	 *
	 * @return array
	 */
	public function default_entry_fields() {
		// Important: update in export_entries() if you change this!
		return array(
			'mail-date' => __( 'Date', 'cf7-storage' ),
			'mail-from' => __( 'From', 'cf7-storage' ),
			'mail-to' => __( 'To', 'cf7-storage' ),
			'mail-subject' => __( 'Subject', 'cf7-storage' ),
			'mail-body' => __( 'Body', 'cf7-storage' ),
			'mail-attachments' => __( 'Attachments', 'cf7-storage' ),
			'mail-from-name' => __( 'Form Name', 'cf7-storage' ),
			'entry-id' => __( 'Entry ID', 'cf7-storage' ),
			'entry-url' => __( 'Entry URL', 'cf7-storage' ),
			'http-referer' => __( 'Referer', 'cf7-storage' ),
			'http-user-agent' => __( 'User-agent', 'cf7-storage' ),
			'http-remote-addr' => __( 'IP Address', 'cf7-storage' ),
		);
	}

	/**
	 * Get entry field values.
	 *
	 * @param  int $form_id Form ID.
	 *
	 * @return array
	 */
	public function get_entry_field_values( $form_id ) {
		$fields_values = array();

		// Append raw field data to the end.
		$form_fields = get_post_meta( $form_id, 'form_fields', true );

		if ( ! is_array( $form_fields ) ) {
			return $fields_values;
		}

		foreach ( $form_fields as $field_name => $field_value ) {
			if ( is_array( $field_value ) ) {
				$fields_values[ $field_name ] = implode( ', ', $field_value );
			} else {
				$fields_values[ $field_name ] = $field_value;
			}
		}

		return $fields_values;
	}

	/**
	 * Export entries.
	 *
	 * @param  array $query_args Query args for selecting the posts.
	 *
	 * @return void
	 */
	function export_entries( $query_args = array() ) {
		$query = new WP_Query( $query_args );
		$entries = $query->posts;
		$fields_excluded = array();

		if ( ! empty( $query_args['post_parent'] ) ) {
			$fields_excluded = $this->form_settings->excluded_fields( absint( $query_args['post_parent'] ) );
		}

		$list = array();
		$extras_headers = array();
		$extras = array();

		$rows_default = $this->default_entry_fields();
		$rows_default_keys = array_keys( $rows_default );

		// Add column headers.
		$list[0] = $rows_default;

		$format_date = get_option( 'date_format' );
		$format_time = get_option( 'time_format' );

		foreach ( $entries as $post ) {
			$timestamp = strtotime( $post->post_date );
			$attachments = $this->get_entry_attachments( $post->ID );
			$maybe_attachments = array();

			if ( ! empty( $attachments ) ) {
				$attachment_list = array();

				foreach ( $attachments as $url ) {
					$maybe_attachments[] = $this->get_storage_file_url( $url, $post->ID );
				}
			}

			$row_values = array(
				sprintf(
					'%s %s',
					date_i18n( $format_date, $timestamp ),
					date_i18n( $format_time, $timestamp )
				),
				get_post_meta( $post->ID, 'mail_sender', true ),
				get_post_meta( $post->ID, 'mail_recipient', true ),
				get_post_meta( $post->ID, 'mail_subject', true ),
				$post->post_content,
				implode( "\n", $maybe_attachments ),
				get_the_title( $post->post_parent ),
				$post->ID,
				$this->link_entry( $post->ID ),
				get_post_meta( $post->ID, 'http_referer', true ),
				get_post_meta( $post->ID, 'http_user_agent', true ),
				get_post_meta( $post->ID, 'remote_addr', true ),
			);

			$list[ $post->ID ] = array_combine( $rows_default_keys, $row_values );

			// Append raw field data to the end.
			$form_fields = $this->get_entry_field_values( $post->ID );

			// Add all fields to in own columns.
			$extras[ $post->ID ] = array();

			// Store all field keys ever used.
			foreach ( $form_fields as $field_name => $field_value ) {
				// This must match field names excluded in settings Cf7_Storage_Form_Settings_Page.
				$field_id = 'field-' . $field_name;

				if ( ! in_array( $field_name, $extras_headers ) ) {
					$extras_headers[ $field_id ] = $field_name;
				}

				$extras[ $post->ID ][ $field_id ] = $field_value;
			}
		}

		// Append field labels to header.
		$list[0] = array_merge( $list[0], $extras_headers );

		foreach ( $list as $post_id => $row ) {
			// Skip the header row.
			if ( ! $post_id ) {
				continue;
			}

			// Add the field values in the correct columns.
			foreach ( $extras_headers as $field_id => $field_name ) {
				if ( isset( $extras[ $post_id ][ $field_id ] ) ) {
					$list[ $post_id ][ $field_id ] = $extras[ $post_id ][ $field_id ];
				} else {
					$list[ $post_id ][ $field_id ] = null;
				}
			}
		}

		// Remove excluded fields from the export.
		if ( ! empty( $fields_excluded ) ) {
			// Exclude related piped field values too.
			foreach ( $fields_excluded as $field_excluded ) {
				$fields_excluded[] = $field_excluded . '-pipe-label';
			}

			foreach ( $list as $post_id => $fields ) {
				foreach ( $fields as $field_id => $field_value ) {
					if ( in_array( $field_id, $fields_excluded, true ) ) {
						unset( $list[ $post_id ][ $field_id ] );
					}
				}
			}
		}

		// Allow plugins to customize the export columns.
		$list = apply_filters( 'cf7_storage_csv_columns', $list );

		if ( ! empty( $_GET['export-csv-delimiter'] ) ) {
			$this->set_csv_delimiter( wp_unslash( $_GET['export-csv-delimiter'] ) );
		}

		// Use the last-used delimiter value, if possible.
		$delimiter = $this->get_csv_delimiter();

		// Send download headers.
		header( 'Content-Type: text/csv; charset=utf-8' );
		header( sprintf( 'Content-Disposition: attachment; filename=cf7-entries-%s.csv', date( 'dmY-His' ) ) );
		header( 'Cache-control: private' );

		$df = fopen( 'php://output', 'w' );

		// UTF-8 BOM for Excel.
		fputs( $df, "\xEF\xBB\xBF" );

		foreach ( $list as $row ) {
			fputcsv( $df, $row, $delimiter );
		}

		fclose( $df );
		die;
	}

	/**
	 * Get a list of available delimiters and their labels.
	 *
	 * @return array
	 */
	public function get_csv_delimiters() {
		$delimiters = array(
			',' => __( 'Comma delimited', 'cf7-storage' ),
			';' => __( 'Semicolon delimited', 'cf7-storage' ),
			'tab' => __( 'Tab delimited', 'cf7-storage' ),
		);

		return apply_filters( 'cf7_storage_csv_delimiters', $delimiters );
	}

	/**
	 * Get the preferred delimiter.
	 *
	 * @return string
	 */
	public function get_csv_delimiter() {
		$delimiter = ',';
		$delimiters_available = $this->get_csv_delimiters();

		// Use the last-used delimiter value, if possible.
		$delimiter_setting = (string) get_option( 'cf7_storage_csv_delimiter_used_last', $delimiter );

		if ( ! empty( $delimiter_setting ) && isset( $delimiters_available[ $delimiter_setting ] ) ) {
			$delimiter = $delimiter_setting;
		}

		// Convert special characters into their string values.
		$delimiter = str_replace( 'tab', "\t", $delimiter );

		// Allow plugins to specify the delimiter.
		$delimiter = apply_filters( 'cf7_storage_csv_delimiter', $delimiter );

		// Ensure the delimiter is 1-char long.
		return substr( $delimiter, 0, 1 );
	}

	/**
	 * Set the preferred delimiter.
	 *
	 * @param string $delimiter Delimiter character.
	 */
	public function set_csv_delimiter( $delimiter ) {
		$delimiter = sanitize_text_field( (string) $delimiter );
		$delimiters_available = $this->get_csv_delimiters();

		if ( isset( $delimiters_available[ $delimiter ] ) ) {
			update_option( 'cf7_storage_csv_delimiter_used_last', $delimiter );
		}
	}

	/**
	 * Ensure that users are running the latest supported version of CF7.
	 *
	 * @return void
	 */
	function cf7_upgrade_notice() {
		if ( class_exists( 'WPCF7_Mail' ) ) {
			return;
		}

		printf(
			'<div class="cf7-storage-notice error">
				<p>%s</p>
			</div>',
			esc_html__( 'Storage for Contact Form 7 requires the latest version of Contact Form 7 plugin.', 'cf7-storage' )
		);
	}

	/**
	 * Add link to form entries to the plugin list.
	 *
	 * @param  array $links List of links.
	 *
	 * @return array
	 */
	function plugin_action_links( $links ) {
		$links[] = sprintf(
			'<a href="%s">%s</a>',
			esc_url( $this->admin_link() ),
			esc_html__( 'Form Entries', 'cf7-storage' )
		);

		$settings_url = $this->plugin_settings_page->link();

		if ( ! empty( $settings_url ) ) {
			$links[] = sprintf(
				'<a href="%s">%s</a>',
				esc_url( $settings_url ),
				esc_html__( 'Settings', 'cf7-storage' )
			);
		}

		return $links;
	}

}
