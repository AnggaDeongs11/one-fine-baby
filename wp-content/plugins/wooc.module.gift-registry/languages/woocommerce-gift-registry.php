<?php
/**
 * Plugin Name: WooCommerce Gift Registry Test
* Plugin URI: http://store.magenest.com/woocommerce-plugins/woocommerce-gift-registry.html
* Description:Add gift registry function to website
* Author: Magenest
* Author URI: http://magenest.com
* Version: 2.4
* Text Domain: woocommerce-gift-registry
* Domain Path: /languages/
*
* Copyright: (c) 2011-2015 Hungnam. (info@hungnamecommerce.com)
*
*
* @package   woocommerce-gift-registry
* @author    Hungnam
* @category  Gift registry
* @copyright Copyright (c) 2014, Hungnam, Inc.
*/
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


if (! defined ('GIFTREGISTRY_TEXT_DOMAIN'))
	define ( 'GIFTREGISTRY_TEXT_DOMAIN', 'giftregistry' );

// Plugin Folder Path
if (! defined ('GIFTREGISTRY_PATH'))
	define ('GIFTREGISTRY_PATH', plugin_dir_path ( __FILE__ ) );

// Plugin Folder URL
if (! defined ('GIFTREGISTRY_URL'))
	define ('GIFTREGISTRY_URL', plugins_url ( 'woocommerce-gift-registry', 'woocommerce-gift-registry.php' ) );

// Plugin Root File
if (! defined ('GIFTREGISTRY_FILE'))
	define ('GIFTREGISTRY_FILE', plugin_basename ( __FILE__ ) );

class Magenest_Giftregistry {
		private static $giftregistry_instance;
		
		/** plugin version number */
		const VERSION = '2.4';
		
		/** plugin text domain */
		const TEXT_DOMAIN = 'giftregistry';
		
		public function __construct() {
			global $wpdb;
		
			register_activation_hook ( GIFTREGISTRY_FILE, array ($this,'install' ) );
			add_action ( 'init', array ($this,'load_text_domain' ), 1 );
				
			//add_action( 'init', array($this,'add_label_taxonomies'), 5 );
			add_action('wp_enqueue_scripts', array($this,'load_custom_scripts'));
			//add_action('wp_print_scripts', array($this,'add_media_script'));
	        $this->include_for_frontend();
	        add_action( 'init', array('Magenest_Giftregistry_Shortcode','init'), 5 );
	        add_action( 'init', array('Magenest_Giftregistry_Form_Handler','init'), 5 );
	       add_action('init',array($this,'register_session'));
	         
	        if (is_admin ()) {
	        	add_action ( 'admin_enqueue_scripts', array ($this,'load_admin_scripts' ), 99 );
	        	require_once plugin_dir_path ( __FILE__ ). 'admin/magenest-giftregistry-setting.php';
	        	
	        	add_action ( 'admin_menu', array ( $this, 'admin_menu' ), 5 );
	        }
	        //update information after a guest buy gift registry
	        add_action('woocommerce_checkout_order_processed', array('Magenest_Giftregistry_Model','after_buy_gift'), 5 );
            add_action('woocommerce_after_add_to_cart_button', array($this,'add_giftregistry'), 10, 1);
            // add_action('woocommerce_after_my_account', array($this,'add_giftregistry_after_myaccount'), 10, 1);
            add_action( 'init', array( $this, 'gr_add_my_account_endpoint' ) );
			add_filter( 'woocommerce_account_menu_items', array( $this, 'giftregistry_account_menu_items' ), 10, 1 );
			add_action( 'woocommerce_account_my-gift-registry_endpoint', array(
			$this,'add_giftregistry_after_myaccount') );
			add_action('wp_head', array($this,'add_code_sharesocail'),1);
        }
		
		/**
		 * Load the Text Domain for i18n
		 *
		 * @return void
		 * @access public
		 */
		function add_code_sharesocail(){
			$title = urlencode(get_option('giftregistry_share_title'));
	     	$imageurl = urlencode( get_option( 'giftregistry_share_image_url' ) );
         	$replace = array('{giftregistry_url}'=>'') ;
         	$content = strtr (  get_option( 'giftregistry_share_text' ), $replace );
?><meta property='og:image' content='<?= isset($imageurl)?$imageurl:''?>'/>
<meta property='og:title' content='<?= isset($title)?$title:'Gift Registry'?>' ?>
<meta property='og:description' content='<?= isset($content)?$content:'Gift Registry'?>'/>
<?php
		}
		// function add_to_giftregistry(){
  //          include_once GIFTREGISTRY_PATH . 'frontend/magenest-form-handler.php';
  //          $adds = new Magenest_Giftregistry_Form_Handler();
  //          $add = $adds->add_to_giftregistry_action();
  //      }
//        function giftregistry_item_init(){
//            add_action('admin_post_add_registry', 'add_to_giftregistry', 10, 1);
//        }
		function add_giftregistry(){
			$user_id = get_current_user_id();
			$permission = get_option('giftregistry_enable_permission',true);
			if($permission == 'yes' || $user_id > 0){
				include_once GIFTREGISTRY_PATH . 'frontend/magenest-giftregistry-myaccount.php';
				$adds = new Magenest_Giftregistry_MyAccount();
				$add = $adds->show_gift_registry_link();
			}
        }
        // function add_giftregistry_after_myaccount(){
        //     include_once GIFTREGISTRY_PATH . 'frontend/magenest-giftregistry-myaccount.php';
        //     $adds = new Magenest_Giftregistry_MyAccount();
        //     $add = $adds->show_my_registry();
        // }
		function load_text_domain() {
			load_plugin_textdomain ( GIFTREGISTRY_TEXT_DOMAIN, false, 'woocommerce-gift-registry/languages/' );
		}
		
		public function register_session(){
			if( !session_id() )
				session_start();
		}
		public function load_admin_scripts() {
			global $woocommerce;
			
			if (is_object($woocommerce))
				wp_enqueue_style ( 'woocommerce_admin_styles', $woocommerce->plugin_url () . '/assets/css/admin.css' );
			wp_enqueue_style('giftregistryadmin', GIFTREGISTRY_URL. '/assets/magenestgiftregistry.css');
		}
		public function load_custom_scripts($hook) {
				
			wp_enqueue_style('magenestgiftregistry' , GIFTREGISTRY_URL .'/assets/magenestgiftregistry.css');
			wp_enqueue_script('magenestgiftregistryjs' , GIFTREGISTRY_URL .'/assets/magenestgiftregistry.js');
		
		}
		public function include_for_frontend() {
			
			include_once GIFTREGISTRY_PATH .'model/magenest-giftregistry-model.php';
			include_once GIFTREGISTRY_PATH .'frontend/magenest-giftregistry-shortcode.php';
			include_once GIFTREGISTRY_PATH . 'frontend/magenest-giftregistry-frontend.php';
			include_once GIFTREGISTRY_PATH . 'frontend/magenest-giftregistry-myaccount.php';
			include_once GIFTREGISTRY_PATH . 'frontend/magnest-form-handler.php';
		}
	public function install() {

		global $wpdb;
		// get current version to check for upgrade
		$installed_version = get_option( 'magenest_giftregistry_version' );
		// install
		if ( ! $installed_version ) {

			// install default settings, terms, etc
            /*
			if (!function_exists('dbDelta')) {
				include_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			}*/

			$prefix = $wpdb->prefix;

            include_once(ABSPATH . 'wp-admin/includes/upgrade.php');

			$query = "CREATE TABLE IF NOT EXISTS `{$prefix}magenest_giftregistry_wishlist` (
			`id` int(11) unsigned NOT NULL auto_increment,
			`user_id` varchar (255)  NOT NULL,
			`sharing_code` varchar (255)  NOT NULL,
			`shared` tinyint  NOT NULL,
			`created_at` timestamp NULL,
			`update_at` timestamp NULL,

			`status` VARCHAR(50) NOT NULL,
			
			`title` VARCHAR(250)  NULL,
			`registrant_firstname` VARCHAR(250)  NULL,
			`registrant_lastname` VARCHAR(250)  NULL,
			`registrant_email` VARCHAR(250)  NULL,
			`registrant_image` INT(11) NULL,
			`registrant_desscription` TEXT NULL,
			
			`coregistrant_firstname` VARCHAR(250)  NULL,
			`coregistrant_lastname` VARCHAR(250)  NULL,
			`coregistrant_email` VARCHAR(250)  NULL,
			`coregistrant_image` INT(11) NULL,
			`coregistrant_desscription` TEXT NULL,
			
			`event_date_time` DATETIME  NULL,
			`event_location` VARCHAR(250)  NULL,
			`message` text null,
			`background_image` varchar (255)   NULL,
			`registrant_description` text null,
			`coregistrant_description` text null,
			`image` varchar (255)   NULL,
			PRIMARY KEY (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

			/**
			 *  $product_id, $quantity = 1, $variation_id = '', $variation = '', $cart_item_data = array()
			 */
            include_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			dbDelta( $query );

			$query = "CREATE TABLE IF NOT EXISTS `{$prefix}magenest_giftregistry_item` (
			`id` int(11) unsigned NOT NULL auto_increment,
			`wishlist_id` int(11)NOT NULL,
			`product` varchar(255) NULL,
			`product_id` int(11) NOT NULL,
			`quantity` int(11) NOT NULL,
			`received_qty` int(11)  NULL,
			`received_order` TEXT NULL,
			`variation_id` int(11) NULL,
			`variation` varchar(255) NULL,
			`cart_item_data` text NULL,
			`description` varchar (255)  NOT NULL,
			`info_request` text,
			`add_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
			PRIMARY KEY (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 ;";
            include_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			dbDelta( $query );

			$query = "CREATE TABLE IF NOT EXISTS `{$prefix}magenest_giftregistry_event` (
			`id` int(11) unsigned NOT NULL auto_increment,
			`name` varchar (255) NULL,
			`image` varchar (255) NULL,
			`created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
			PRIMARY KEY (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 ;";
            include_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			dbDelta( $query );

			//back

			$this->create_pages();
			//update_option( 'magenest_giftregistry_version', self::VERSION );

		}
		// upgrade if installed version lower than plugin version
		if ( -1 === version_compare( $installed_version, self::VERSION ) )
		$this->upgrade( $installed_version );
	}
	
	public function upgrade($installed_version) {
		global $wpdb;
		$prefix = $wpdb->prefix;
        $giftregistryTbl = $prefix.'magenest_giftregistry_item';
        $wishlistTbl = $prefix.'magenest_giftregistry_wishlist';
		
				//image
				
			$query1 = 	"ALTER TABLE  `{$prefix}magenest_giftregistry_wishlist` ADD  `registrant_image` int(11)  NULL DEFAULT 0;";
			$query2 = 	"ALTER TABLE  `{$prefix}magenest_giftregistry_wishlist` ADD  `coregistrant_image` int(11)  NULL DEFAULT 0;";
			$query3 = 	"ALTER TABLE  `{$prefix}magenest_giftregistry_wishlist` ADD  `image` varchar(255)  NULL ";
			$query4 = 	"ALTER TABLE  `{$prefix}magenest_giftregistry_wishlist` ADD  `background_image` varchar(255)  NULL";
			
			$query5 = 	"ALTER TABLE  `{$prefix}magenest_giftregistry_wishlist` ADD  `registrant_description` text  NULL";
			$query6 = 	"ALTER TABLE  `{$prefix}magenest_giftregistry_wishlist` ADD  `coregistrant_description` text  NULL";
            $query7 = "ALTER TABLE  $giftregistryTbl ADD `priority` int(11) NULL";
            $query8 = "ALTER TABLE  $wishlistTbl ADD `role` int(11) NULL";
            $query9 = "ALTER TABLE  $wishlistTbl ADD `password` varchar(255) NULL";

			if (!function_exists('dbDelta'))
				include_once  (ABSPATH . 'wp-admin/includes/upgrade.php');
				
			$giftregistry = $wpdb->get_row("SELECT * FROM `{$prefix}magenest_giftregistry_wishlist`");
			//Add column if not present.
			if(!isset($giftregistry->registrant_image)){
				$wpdb->query($query1);
			}
			
			//co image
			if(!isset($giftregistry->coregistrant_image)){
				$wpdb->query($query2);
			}
			
			//album
			if(!isset($giftregistry->image)){
				$wpdb->query($query3);
			}
			//album
			if(!isset($giftregistry->background_image)){
				$wpdb->query($query4);
			}
			
			//des
			if(!isset($giftregistry->registrant_description)){
				$wpdb->query($query5);
			}
			//co-desc
			if(!isset($giftregistry->coregistrant_description)){
				$wpdb->query($query6);
			}
			$wpdb->query($query7);
            $wpdb->query($query8);
            $wpdb->query($query9);
	}
	/**
	 * create gift registry pages for plugin
	 */
	public function create_pages() {
		if (!function_exists('wc_create_page'))  {
		   include_once dirname ( __DIR__ ) . '/woocommerce/includes/admin/wc-admin-functions.php';
		}
		$pages =  array (
				'giftregistry' => array (
						'name' => _x ( 'giftregistry', 'Page slug', 'woocommerce' ),
						'title' => _x ( 'Gift Registry', 'Page title', 'woocommerce' ),
						'content' => '[magenest_giftregistry]'
				)
		) ;
	
		foreach ( $pages as $key => $page ) {
			wc_create_page ( esc_sql ( $page ['name'] ), 'follow_up_email' . $key . '_page_id', $page ['title'], $page ['content'], ! empty ( $page ['parent'] ) ? wc_get_page_id ( $page ['parent'] ) : '' );
		}
	}
	
	/**
	 * add menu items
	 */
	public function admin_menu() {
		global $menu;
		include_once GIFTREGISTRY_PATH .'admin/magenest-giftregistry-admin.php';
		
		$admin = new Magenest_Giftregistry_Admin();
		add_menu_page(__('Gift registry', GIFTREGISTRY_TEXT_DOMAIN), __('Gift registry', GIFTREGISTRY_TEXT_DOMAIN), 'manage_woocommerce','gift_registry', array($admin,'giftregistry_manage' ));
	
	}
	public static function getInstance() {
		if (! self::$giftregistry_instance) {
			self::$giftregistry_instance = new Magenest_Giftregistry();
		}
	
		return self::$giftregistry_instance;
	}

	public function gr_add_my_account_endpoint()
	{
		add_rewrite_endpoint( 'my-gift-registry', EP_PAGES );
	}
	
	function giftregistry_account_menu_items( $items )
	{
		$items['my-gift-registry'] = __( 'My Gift Registry', GIFTREGISTRY_TEXT_DOMAIN );

		return $items;
	}

	function add_giftregistry_after_myaccount()
	{
		include_once GIFTREGISTRY_PATH . 'frontend/magenest-giftregistry-myaccount.php';
		$adds = new Magenest_Giftregistry_MyAccount();
		$add  = $adds->show_my_registry();
	}
	
}

$magenest_giftregistry_loaded = Magenest_Giftregistry::getInstance ();

$GLOBAl['giftregistryresult'] = array();
?>