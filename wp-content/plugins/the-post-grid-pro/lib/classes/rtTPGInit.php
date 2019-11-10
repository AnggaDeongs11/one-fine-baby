<?php

if ( ! class_exists( 'rtTPGInit' ) ):
	class rtTPGInit {

		private $version;

		function __construct() {
			$this->version = defined( 'WP_DEBUG' ) && WP_DEBUG  ? time() : RT_TPG_PRO_VERSION;
			add_action( 'init', array( $this, 'init' ), 1 );
			add_action( 'admin_init', array( $this, 'the_post_grid_pro_remove_all_meta_box' ) );
			add_action( 'admin_menu', array( $this, 'tgp_menu_register' ) );
			add_action( 'plugins_loaded', array( $this, 'the_post_grid_load_text_domain' ) );
			register_activation_hook( RT_THE_POST_GRID_PRO_PLUGIN_ACTIVE_FILE_NAME, array( $this, 'activate' ) );
			register_deactivation_hook( RT_THE_POST_GRID_PRO_PLUGIN_ACTIVE_FILE_NAME, array( $this, 'deactivate' ) );
			add_filter( 'plugin_action_links_' . RT_THE_POST_GRID_PRO_PLUGIN_ACTIVE_FILE_NAME, array(
				$this,
				'rt_post_grid_marketing'
			) );
			add_action( 'admin_enqueue_scripts', array( $this, 'settings_admin_enqueue_scripts' ) );
		}

		function the_post_grid_pro_remove_all_meta_box() {
			if ( is_admin() ) {
				add_filter( "get_user_option_meta-box-order_" . rtTPG()->post_type, array(
					$this,
					'remove_all_meta_boxes_tgp_sc'
				) );
			}
		}

		function remove_all_meta_boxes_tgp_sc() {
			global $wp_meta_boxes;
			$publishBox                          = $wp_meta_boxes[ rtTPG()->post_type ]['side']['core']['submitdiv'];
			$scBox                               = $wp_meta_boxes[ rtTPG()->post_type ]['normal']['high']['rttpg_meta'];
			$scBoxPreview                        = $wp_meta_boxes[ rtTPG()->post_type ]['normal']['high']['rttpg_sc_preview_meta'];
			$wp_meta_boxes[ rtTPG()->post_type ] = array(
				'side'     => array( 'core' => array( 'submitdiv' => $publishBox ) ),
				'normal'   => array( 'high' => array( 'submitdiv' => $scBox ) ),
				'advanced' => array( 'high' => array( 'postexcerpt' => $scBoxPreview ) )
			);

			return array();
		}

		function init() {

			// Create the post grid post type
			$labels = array(
				'name'               => __( 'The Post Grid Pro', 'the-post-grid-pro' ),
				'singular_name'      => __( 'The Post Grid', 'the-post-grid-pro' ),
				'add_new'            => __( 'Add New Grid', 'the-post-grid-pro' ),
				'all_items'          => __( 'All Grids', 'the-post-grid-pro' ),
				'add_new_item'       => __( 'Add New Post Grid', 'the-post-grid-pro' ),
				'edit_item'          => __( 'Edit Post Grid', 'the-post-grid-pro' ),
				'new_item'           => __( 'New Post Grid', 'the-post-grid-pro' ),
				'view_item'          => __( 'View Post Grid', 'the-post-grid-pro' ),
				'search_items'       => __( 'Search Post Grids', 'the-post-grid-pro' ),
				'not_found'          => __( 'No Post Grids found', 'the-post-grid-pro' ),
				'not_found_in_trash' => __( 'No Post Grids found in Trash', 'the-post-grid-pro' ),
			);

			register_post_type( rtTPG()->post_type, array(
				'labels'          => $labels,
				'public'          => false,
				'show_ui'         => true,
				'_builtin'        => false,
				'capability_type' => 'page',
				'hierarchical'    => true,
				'menu_icon'       => rtTPG()->assetsUrl . 'images/rt-tpg-menu.png',
				'rewrite'         => false,
				'query_var'       => rtTPG()->post_type,
				'supports'        => array(
					'title',
				),
				'show_in_menu'    => true,
				'menu_position'   => 20,
			) );

			// register scripts
			$scripts   = array();
			$styles    = array();
			$scripts[] = array(
				'handle' => 'rt-image-load-js',
				'src'    => rtTPG()->assetsUrl . "vendor/isotope/imagesloaded.pkgd.min.js",
				'deps'   => array( 'jquery' ),
				'footer' => true
			);
			$scripts[] = array(
				'handle' => 'rt-isotope-js',
				'src'    => rtTPG()->assetsUrl . "vendor/isotope/isotope.pkgd.min.js",
				'deps'   => array( 'jquery' ),
				'footer' => true
			);
			$scripts[] = array(
				'handle' => 'rt-jzoom',
				'src'    => rtTPG()->assetsUrl . "js/jzoom.min.js",
				'deps'   => array( 'jquery' ),
				'footer' => true
			);
			$scripts[] = array(
				'handle'  => 'jquery-mousewheel',
				'src'     => rtTPG()->assetsUrl . "vendor/jquery.mousewheel.min.js",
				'deps'    => array( 'jquery' ),
				'footer'  => true,
				'version' => '3.1.13'
			);
			$scripts[] = array(
				'handle'  => 'rt-scrollbar',
				'src'     => rtTPG()->assetsUrl . "vendor/scrollbar/jquery.mCustomScrollbar.min.js",
				'deps'    => array( 'jquery', 'jquery-mousewheel' ),
				'footer'  => true,
				'version' => '3.1.5'
			);

			$scripts[] = array(
				'handle' => 'rt-magnific-popup',
				'src'    => rtTPG()->assetsUrl . "vendor/Magnific-Popup/jquery.magnific-popup.min.js",
				'deps'   => array( 'jquery' ),
				'footer' => true
			);

			$scripts[] = array(
				'handle' => 'rt-owl-carousel',
				'src'    => rtTPG()->assetsUrl . "vendor/owl-carousel/owl.carousel.min.js",
				'deps'   => array( 'jquery' ),
				'footer' => true
			);
			$scripts[] = array(
				'handle' => 'rt-pagination',
				'src'    => rtTPG()->assetsUrl . "js/pagination.min.js",
				'deps'   => array( 'jquery' ),
				'footer' => true
			);
			$scripts[] = array(
				'handle'  => 'rt-actual-height-js',
				'src'     => rtTPG()->assetsUrl . "vendor/actual-height/jquery.actual.min.js",
				'deps'    => array( 'jquery' ),
				'footer'  => true,
				'version' => '1.0.19'
			);
			$scripts[] = array(
				'handle' => 'rt-tpg',
				'src'    => rtTPG()->assetsUrl . "js/rttpg.js",
				'deps'   => array( 'jquery' ),
				'footer' => true
			);
			// register acf styles
			$styles['rt-fontawsome']         = rtTPG()->assetsUrl . 'vendor/font-awesome/css/font-awesome.min.css';
			$styles['rt-scrollbar']          = rtTPG()->assetsUrl . 'vendor/scrollbar/jquery.mCustomScrollbar.min.css';
			$styles['rt-magnific-popup']     = rtTPG()->assetsUrl . 'vendor/Magnific-Popup/magnific-popup.css';
			$styles['rt-owl-carousel']       = rtTPG()->assetsUrl . 'vendor/owl-carousel/owl.carousel.min.css';
			$styles['rt-owl-carousel-theme'] = rtTPG()->assetsUrl . 'vendor/owl-carousel/owl.theme.default.min.css';
			$styles['rt-tpg']                = rtTPG()->assetsUrl . 'css/thepostgrid.css';
			$styles['rt-tpg-rtl']            = rtTPG()->assetsUrl . 'css/thepostgrid-rtl.css';

			if ( is_admin() ) {

				$scripts[] = array(
					'handle' => 'rt-select2',
					'src'    => rtTPG()->assetsUrl . "vendor/select2/select2.min.js",
					'deps'   => array( 'jquery' ),
					'footer' => false
				);

				$scripts[]                      = array(
					'handle' => 'tpg-admin-taxonomy',
					'src'    => rtTPG()->assetsUrl . "js/admin-taxonomy.js",
					'deps'   => array( 'jquery' ),
					'footer' => true
				);
				$scripts[]                      = array(
					'handle' => 'rt-tpg-admin',
					'src'    => rtTPG()->assetsUrl . "js/admin.js",
					'deps'   => array( 'jquery' ),
					'footer' => true
				);
				$scripts[]                      = array(
					'handle' => 'rt-tpg-admin-preview',
					'src'    => rtTPG()->assetsUrl . "js/admin-preview.js",
					'deps'   => array( 'jquery' ),
					'footer' => true
				);
				$styles['rt-jquery-ui']         = '//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css';
				$styles['rt-select2']           = rtTPG()->assetsUrl . 'vendor/select2/select2.min.css';
				$styles['rt-tpg-admin']         = rtTPG()->assetsUrl . 'css/admin.css';
				$styles['rt-tpg-admin-preview'] = rtTPG()->assetsUrl . 'css/admin-preview.css';
			}


			foreach ( $scripts as $script ) {
				wp_register_script( $script['handle'], $script['src'], $script['deps'], isset( $script['version'] ) ? $script['version'] : $this->version, $script['footer'] );
			} //


			foreach ( $styles as $k => $v ) {
				wp_register_style( $k, $v, false, isset( $script['version'] ) ? $script['version'] : $this->version );
			}
		}

		function tgp_menu_register() {
			add_submenu_page( 'edit.php?post_type=' . rtTPG()->post_type, __( 'Taxonomy Order', "the-post-grid-pro" ),
				__( 'Taxonomy Order', "the-post-grid-pro" ), 'administrator', 'tgp_taxonomy_order',
				array( $this, 'tpg_menu_page_taxonomy_order' ) );

			add_submenu_page( 'edit.php?post_type=' . rtTPG()->post_type, __( 'Settings' ), __( 'Settings', "the-post-grid-pro" ), 'administrator', 'rttpg_settings', array(
				$this,
				'rttpg_settings'
			) );
		}

		function rttpg_settings() {
			rtTPG()->render_view( 'settings.settings' );
		}

		function tpg_menu_page_taxonomy_order() {
			rtTPG()->render_view( 'taxonomy-order' );
		}

		public function the_post_grid_load_text_domain() {
			load_plugin_textdomain( 'the-post-grid-pro', false, RT_THE_POST_GRID_PRO_LANGUAGE_PATH );
		}

		function activate() {
			$this->insertDefaultData();
		}

		function deactivate() {

		}

		private function insertDefaultData() {
			update_option( rtTPG()->options['installed_version'], RT_TPG_PRO_VERSION );
			if ( ! get_option( rtTPG()->options['settings'] ) ) {
				update_option( rtTPG()->options['settings'], rtTPG()->defaultSettings );
			}
		}

		function rt_post_grid_marketing() {
			$links[] = '<a target="_blank" href="' . esc_url( 'http://demo.radiustheme.com/wordpress/plugins/the-post-grid/' ) . '">' . __( "Demo", 'the-post-grid-pro' ) . '</a>';
			$links[] = '<a target="_blank" href="' . esc_url( 'https://radiustheme.com/how-to-setup-configure-the-post-grid-free-version-for-wordpress/' ) . '">' . __( "Documentation", 'the-post-grid-pro' ) . '</a>';

			return $links;
		}

		function settings_admin_enqueue_scripts() {
			global $pagenow, $typenow;

			// validate page
			if ( ! in_array( $pagenow, array( 'edit.php' ) ) ) {
				return;
			}
			if ( $typenow != rtTPG()->post_type ) {
				return;
			}

			wp_enqueue_script( array(
				'jquery',
				'rt-tpg-admin',
			) );

			// styles
			wp_enqueue_style( array(
				'rt-tpg-admin'
			) );

			$nonce = wp_create_nonce( rtTPG()->nonceText() );
			wp_localize_script( 'rt-tpg-admin', 'rttpg',
				array(
					'nonceID' => rtTPG()->nonceId(),
					'nonce'   => $nonce,
					'ajaxurl' => admin_url( 'admin-ajax.php' )
				) );
		}
	}
endif;