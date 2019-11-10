<?php
/**
 * Plugin Name: WooCommerce Gift Registry
 * Plugin URI: http://store.magenest.com/woocommerce-plugins/woocommerce-gift-registry.html
 * Description:Add gift registry function to website
 * Author: Magenest
 * Author URI: http://magenest.com
 * Version: 2.6
 * Text Domain: giftregistry
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
if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly


if (!defined('GIFTREGISTRY_TEXT_DOMAIN')) {
    define('GIFTREGISTRY_TEXT_DOMAIN', 'giftregistry');
}

// Plugin Folder Path
if (!defined('GIFTREGISTRY_PATH')) {
    define('GIFTREGISTRY_PATH', plugin_dir_path(__FILE__));
}

// Plugin Folder URL
if (!defined('GIFTREGISTRY_URL')) {
    define('GIFTREGISTRY_URL', plugin_dir_url(__FILE__));
}

// Plugin Root File
if (!defined('GIFTREGISTRY_FILE')) {
    define('GIFTREGISTRY_FILE', plugin_basename(__FILE__));
}

class Magenest_Giftregistry
{
    /** plugin version number */
    const VERSION = '2.6';
    /** plugin text domain */
    const TEXT_DOMAIN = 'giftregistry';
    private static $giftregistry_instance;

    public function __construct()
    {
        global $wpdb;

        register_activation_hook(GIFTREGISTRY_FILE, array($this, 'install'));
        add_action('init', array($this, 'load_text_domain'), 1);

        //add_action( 'init', array($this,'add_label_taxonomies'), 5 );
        add_action('wp_enqueue_scripts', array($this, 'load_custom_scripts'));
        //add_action('wp_print_scripts', array($this,'add_media_script'));
        $this->include_for_frontend();
        add_action('init', array('Magenest_Giftregistry_Shortcode', 'init'), 5);
        add_action('init', array('Magenest_Giftregistry_Form_Handler', 'init'), 5);
        add_action('init', array($this, 'register_session'));
        add_action('init', array($this, 'load_icon_my_gift_registry'));
        add_action('init', array($this, 'remove_session_access_gift_registry'));

        /* Add background image metabox in GR edit page*/
        add_action('add_meta_boxes', array($this, 'background_image_metabox'));
        add_action('save_post', array($this, 'save_background_image_metabox'), 10, 2);

        if (is_admin()) {
            add_action('admin_enqueue_scripts', array($this, 'load_admin_scripts'), 99);
            require_once plugin_dir_path(__FILE__) . 'admin/magenest-giftregistry-setting.php';

            add_action('admin_menu', array($this, 'admin_menu'), 5);
        }
        //update information after a guest buy gift registry
        add_action('woocommerce_checkout_order_processed', array('Magenest_Giftregistry_Model', 'after_buy_gift'), 5);
        add_action('woocommerce_thankyou', array('Magenest_Giftregistry_Model', 'send_email_confirm_buy_giftregistry'), 9);
        add_action('woocommerce_after_add_to_cart_form', array($this, 'add_giftregistry'), 10, 1);
        add_action('init', array($this, 'gr_add_my_account_endpoint'));
        add_filter('woocommerce_account_menu_items', array($this, 'giftregistry_account_menu_items'), 10, 1);
//        add_action('woocommerce_account_content', array($this, 'add_giftregistry_after_myaccount'));
        add_action('woocommerce_account_my-gift-registry_endpoint', array($this, 'add_giftregistry_after_myaccount'));
        add_action('wp_head', array($this, 'add_code_sharesocail'), 1);
        add_action('woocommerce_after_shop_loop_item', array($this, 'add_giftregistry_button_in_shoppage'), 11);
    }

    public function include_for_frontend()
    {

        include_once GIFTREGISTRY_PATH . 'model/magenest-giftregistry-model.php';
        include_once GIFTREGISTRY_PATH . 'frontend/magenest-giftregistry-shortcode.php';
        include_once GIFTREGISTRY_PATH . 'frontend/magenest-giftregistry-frontend.php';
        include_once GIFTREGISTRY_PATH . 'frontend/magenest-giftregistry-myaccount.php';
        include_once GIFTREGISTRY_PATH . 'frontend/magnest-form-handler.php';
    }

    public static function getInstance()
    {
        if (!self::$giftregistry_instance) {
            self::$giftregistry_instance = new Magenest_Giftregistry();
        }

        return self::$giftregistry_instance;
    }

    /**
     * Load the Text Domain for i18n
     *
     * @return void
     * @access public
     */
    function add_code_sharesocail()
    {
        if (get_the_ID() == get_option('follow_up_emailgiftregistry_page_id')) {
            $imageurl = get_option('giftregistry_share_image_url');
            $replace = array('{giftregistry_url}' => '');
            $content = strtr(get_option('giftregistry_share_text'), $replace);
            ?>
            <meta name="twitter:title" content="Gift Registry">
            <meta name="twitter:description" content="<?=isset($content) ? $content : 'Gift Registry' ?>">
            <meta name="twitter:image" content='<?= isset($imageurl) ? $imageurl : "" ?>'>
            <meta name="twitter:card" content="summary_large_image">

            <meta property='og:image:width' content='1200' >
            <meta property='og:image:height' content='630' >
            <meta property='og:image' content='<?= isset($imageurl) ? $imageurl : '' ?>'/>
            <meta property='og:title' content='Gift Registry' ?>
            <meta property='og:description' content='<?=isset($content) ? $content : 'Gift Registry' ?>'/>

            <html itemscope itemtype="http://schema.org/Article">
            <meta itemprop="title" content="Gift Registry">
            <meta itemprop="description" content="<?=isset($content) ? $content : 'Gift Registry' ?>">
            <meta itemprop="image" content="<?= isset($imageurl) ? $imageurl : '' ?>">
            <?php
        }
    }

    function add_giftregistry()
    {
        $user_id = get_current_user_id();
        $permission = get_option('giftregistry_enable_permission', true);
        if ($permission == 'yes' || $user_id > 0) {
            include_once GIFTREGISTRY_PATH . 'frontend/magenest-giftregistry-myaccount.php';
            $adds = new Magenest_Giftregistry_MyAccount();
            $adds->show_gift_registry_link();
        }
    }

    function giftregistry_account_menu_items($items)
    {
        $logout = array_pop($items);
//        $items['my-gift-registry'] = __('My Gift Registry', GIFTREGISTRY_TEXT_DOMAIN);
//        array_push($items,$logout);
        $items['my-gift-registry'] = __('My Gift Registry', GIFTREGISTRY_TEXT_DOMAIN);
        $items['customer-logout'] = __('Logout', 'woocommerce');

        return $items;
    }

    function gr_add_my_account_endpoint()
    {
        add_rewrite_endpoint('my-gift-registry', EP_PAGES);
    }

    function add_giftregistry_after_myaccount()
    {
        include_once GIFTREGISTRY_PATH . 'frontend/magenest-giftregistry-myaccount.php';
        $adds = new Magenest_Giftregistry_MyAccount();
        $adds->show_overview_statistics();
        $adds->show_my_registry();
    }

    function load_text_domain()
    {
        load_plugin_textdomain(GIFTREGISTRY_TEXT_DOMAIN, false, basename(dirname(__FILE__)) . '/languages');
    }

    public function register_session()
    {
        if (!session_id()) {
            session_start();
        }
    }

    public function load_admin_scripts()
    {
        global $woocommerce;

        if (is_object($woocommerce)) {
            wp_enqueue_style('woocommerce_admin_styles', $woocommerce->plugin_url() . '/assets/css/admin.css');
        }
        wp_enqueue_style('giftregistryadmin', GIFTREGISTRY_URL . '/assets/magenestgiftregistry.css');

        wp_register_script('GR-setting', GIFTREGISTRY_URL . '/assets/js/html-giftregistry-setting.js');
        wp_register_script('giftregistry-page', GIFTREGISTRY_URL . '/assets/js/giftregistry_page_backend.js');
        wp_register_script('my-giftregistry', GIFTREGISTRY_URL . '/assets/js/my-giftregistry.js');
        wp_register_script('add-giftregistry', GIFTREGISTRY_URL . '/assets/js/add-giftregistry.js');
    }

    public function load_custom_scripts($hook)
    {
        if (is_user_logged_in()) {
            $is_user_logged_in = 1;
        } else {
            $is_user_logged_in = 0;
        }
        if (isset($is_user_logged_in)) {
            $send_ajax_giftregistry = array('ajax_url' => admin_url('admin-ajax.php'), 'is_user_logged_in' => $is_user_logged_in,
                'myaccount_url' => wc_get_page_permalink('myaccount'));
        } else {
            $send_ajax_giftregistry = array('ajax_url' => admin_url('admin-ajax.php'), 'myaccount_url' => wc_get_page_permalink('myaccount'));
        }
        wp_register_script('send_ajax_giftregistry', GIFTREGISTRY_URL . '/assets/js/ajax_giftregistry.js', array('jquery'), null, true);
        wp_register_script('ajax-button-shop-page', GIFTREGISTRY_URL . '/assets/js/ajax-button-shop-page.js', array('jquery'), null, true);
        if(get_option('giftregistry_enable_permission') == 'yes' ||is_user_logged_in()){
            wp_localize_script('send_ajax_giftregistry', 'send_ajax_giftregistry', $send_ajax_giftregistry);
            wp_localize_script('ajax-button-shop-page', 'send_ajax_giftregistry', $send_ajax_giftregistry);
        }
        wp_register_script('bootstrap', GIFTREGISTRY_URL . '/assets/js/bootstrap.min.js');
        wp_register_script('jquery', GIFTREGISTRY_URL . '/assets/js/jquery.min.js');
        wp_enqueue_script('jquery');
        wp_enqueue_script('magenestgiftregistryjs', GIFTREGISTRY_URL . '/assets/magenestgiftregistry.js');
        wp_enqueue_script('my-giftregistry', GIFTREGISTRY_URL . '/assets/js/my-giftregistry.js');
        wp_register_script('add-giftregistry', GIFTREGISTRY_URL . '/assets/js/add-giftregistry.js');
        wp_register_script('giftregistry-share', GIFTREGISTRY_URL . '/assets/js/giftregistry-share.js');
        wp_register_script('table_giftregistry', GIFTREGISTRY_URL . '/assets/js/table_giftregistry.js');
        wp_localize_script('table_giftregistry', 'send_ajax_giftregistry', $send_ajax_giftregistry);
        wp_register_script('magenest-giftregistry-myaccount', GIFTREGISTRY_URL . '/assets/js/magenest-giftregistry-myaccount.js');

        wp_register_style('my-bootstrap', GIFTREGISTRY_URL . '/assets/css/my-bootstrap.css');
        wp_enqueue_style('magenestgiftregistry', GIFTREGISTRY_URL . '/assets/magenestgiftregistry.css');
        wp_register_style('my-style', GIFTREGISTRY_URL . '/assets/css/my-style.css');
        wp_register_style('checkpass_giftregistry', GIFTREGISTRY_URL . '/assets/css/checkpass_giftregistry.css');
        wp_register_style('search_result', GIFTREGISTRY_URL . '/assets/css/search_result.css');
        wp_register_style('button-GR-in-shop-page', GIFTREGISTRY_URL . '/assets/css/button-GR-in-shop-page.css');
        wp_register_style('button-in-simple-product', GIFTREGISTRY_URL . '/assets/css/button-in-simple-product.css');
        wp_register_style('button-in-variable-product', GIFTREGISTRY_URL . '/assets/css/button-in-variable-product.css');
        wp_register_style('header_giftregistry_public_view', GIFTREGISTRY_URL . '/assets/css/header_giftregistry_public_view.css');
        wp_register_style('icon-loading', GIFTREGISTRY_URL . '/assets/css/icon-loading.css');
    }

    public function install()
    {
        //create Gift Registry page
        $this->create_pages();

        global $wpdb;
        // get current version to check for upgrade
        $installed_version = get_option('magenest_giftregistry_version');
        // install
        if (!$installed_version) {

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

			`enable_coregistrant` VARCHAR(16) NULL,
			`coregistrant_firstname` VARCHAR(250) NULL,
			`coregistrant_lastname` VARCHAR(250) NULL,
			`coregistrant_email` VARCHAR(250) NULL,
			`coregistrant_image` INT(11) NULL,
			`coregistrant_desscription` TEXT NULL,

			`event_date_time` DATETIME  NULL,
			`event_location` VARCHAR(250)  NULL,
			`message` text null,
			`background_image` varchar (255)   NULL,
			`registrant_description` text null,
			`role` VARCHAR(250)  NULL,
			`coregistrant_description` text null,
			`image` varchar (255)   NULL,
			`shipping_first_name` varchar(255) NULL,
			`shipping_last_name` varchar(255) NULL,
			`shipping_company` varchar(255) NULL,
			`shipping_country` varchar(255) NULL,
			`shipping_address` varchar(255) NULL,
			`shipping_postcode` varchar(255) NULL,
			`shipping_city` varchar(255) NULL,
			PRIMARY KEY (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

            /**
             *  $product_id, $quantity = 1, $variation_id = '', $variation = '', $cart_item_data = array()
             */
            include_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($query);

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
            dbDelta($query);

            $query = "CREATE TABLE IF NOT EXISTS `{$prefix}magenest_giftregistry_event` (
			`id` int(11) unsigned NOT NULL auto_increment,
			`name` varchar (255) NULL,
			`image` varchar (255) NULL,
			`created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
			PRIMARY KEY (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 ;";
            include_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($query);

            //back

//            $this->create_pages();
            //update_option( 'magenest_giftregistry_version', self::VERSION );

        }
        // upgrade if installed version lower than plugin version
        if (version_compare($installed_version, self::VERSION, '<=')) {
            $this->upgrade($installed_version);
        }
    }

    /**
     * create gift registry pages for plugin
     */
    public function create_pages()
    {
        if (!function_exists('wc_create_page')) {
            include_once dirname(__DIR__) . '/woocommerce/includes/admin/wc-admin-functions.php';
        }
        $pages = array(
            'giftregistry' => array(
                'name' => _x('giftregistry', 'Page slug', 'woocommerce'),
                'title' => _x('Gift Registry', 'Page title', 'woocommerce'),
                'content' => ' [magenest_view_giftregistry]
                               [magenest_search_giftregistry]
                               [magenest_header_giftregistry_public_view]
                               [magenest_table_giftregistry_public_view]'
            )
        );
        foreach ($pages as $key => $page) {
            wc_create_page(esc_sql($page ['name']), 'follow_up_email' . $key . '_page_id', $page ['title'], $page ['content'], !empty ($page ['parent']) ? wc_get_page_id($page ['parent']) : '');
        }
    }

    public function upgrade($installed_version)
    {
        global $wpdb;
        $prefix = $wpdb->prefix;
        $giftregistryTbl = $prefix . 'magenest_giftregistry_item';
        $wishlistTbl = $prefix . 'magenest_giftregistry_wishlist';

        //image

        $query1 = "ALTER TABLE  `{$prefix}magenest_giftregistry_wishlist` ADD  `registrant_image` int(11)  NULL DEFAULT 0;";
        $query2 = "ALTER TABLE  `{$prefix}magenest_giftregistry_wishlist` ADD  `coregistrant_image` int(11)  NULL DEFAULT 0;";
        $query3 = "ALTER TABLE  `{$prefix}magenest_giftregistry_wishlist` ADD  `image` varchar(255)  NULL ";
        $query4 = "ALTER TABLE  `{$prefix}magenest_giftregistry_wishlist` ADD  `background_image` varchar(255)  NULL";

        $query5 = "ALTER TABLE  `{$prefix}magenest_giftregistry_wishlist` ADD  `registrant_description` text  NULL";
        $query6 = "ALTER TABLE  `{$prefix}magenest_giftregistry_wishlist` ADD  `coregistrant_description` text  NULL";
        $query7 = "ALTER TABLE  $giftregistryTbl ADD `priority` int(11) NULL";
        $query8 = "ALTER TABLE  $wishlistTbl ADD `role` int(11) NULL";
        $query9 = "ALTER TABLE  $wishlistTbl ADD `enable_coregistrant` int(11) NULL";
        $query10 = "ALTER TABLE  $wishlistTbl ADD `password` varchar(255) NULL";

        $query11 = "ALTER TABLE  `{$prefix}magenest_giftregistry_wishlist` ADD  `shipping_first_name` varchar(255)  NULL";
        $query12 = "ALTER TABLE  `{$prefix}magenest_giftregistry_wishlist` ADD  `shipping_last_name` varchar(255)  NULL";
        $query13 = "ALTER TABLE  `{$prefix}magenest_giftregistry_wishlist` ADD  `shipping_company` varchar(255)  NULL";
        $query14 = "ALTER TABLE  `{$prefix}magenest_giftregistry_wishlist` ADD  `shipping_country` varchar(255)  NULL";
        $query15 = "ALTER TABLE  `{$prefix}magenest_giftregistry_wishlist` ADD  `shipping_address` varchar(255)  NULL";
        $query16 = "ALTER TABLE  `{$prefix}magenest_giftregistry_wishlist` ADD  `shipping_postcode` varchar(255)  NULL";
        $query17 = "ALTER TABLE  `{$prefix}magenest_giftregistry_wishlist` ADD  `shipping_city` varchar(255)  NULL";

        $query18 = "ALTER TABLE  `{$prefix}magenest_giftregistry_wishlist` ADD  `option_quantity` int(11) NULL";

        if (!function_exists('dbDelta')) {
            include_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        }

        $giftregistry = $wpdb->get_row("SELECT * FROM `{$prefix}magenest_giftregistry_wishlist`");
        //Add column if not present.
        if (!$wpdb->get_row("SHOW COLUMNS FROM `{$prefix}magenest_giftregistry_wishlist` LIKE 'registrant_image'")) {
            $wpdb->query($query1);
        }

        //co image
        if (!$wpdb->get_row("SHOW COLUMNS FROM `{$prefix}magenest_giftregistry_wishlist` LIKE 'coregistrant_image'")) {
            $wpdb->query($query2);
        }

        //album
        if (!$wpdb->get_row("SHOW COLUMNS FROM `{$prefix}magenest_giftregistry_wishlist` LIKE 'image'")) {
            $wpdb->query($query3);
        }
        //album
        if (!$wpdb->get_row("SHOW COLUMNS FROM `{$prefix}magenest_giftregistry_wishlist` LIKE 'background_image'")) {
            $wpdb->query($query4);
        }

        //des
        if (!$wpdb->get_row("SHOW COLUMNS FROM `{$prefix}magenest_giftregistry_wishlist` LIKE 'registrant_description'")) {
            $wpdb->query($query5);
        }
        //co-desc
        if (!$wpdb->get_row("SHOW COLUMNS FROM `{$prefix}magenest_giftregistry_wishlist` LIKE 'coregistrant_description'")) {
            $wpdb->query($query6);
        }

        if (!$wpdb->get_row("SHOW COLUMNS FROM `$giftregistryTbl` LIKE 'priority'")) {
            $wpdb->query($query7);
        }

        if (!$wpdb->get_row("SHOW COLUMNS FROM `$wishlistTbl` LIKE 'role'")) {
            $wpdb->query($query8);
        }

        if (!$wpdb->get_row("SHOW COLUMNS FROM `$wishlistTbl` LIKE 'enable_coregistrant'")) {
            $wpdb->query($query9);
        }

        if (!$wpdb->get_row("SHOW COLUMNS FROM `$wishlistTbl` LIKE 'password'")) {
            $wpdb->query($query10);
        }


        if (!$wpdb->get_row("SHOW COLUMNS FROM `$wishlistTbl` LIKE 'shipping_first_name'")) {
            $wpdb->query($query11);
        }

        if (!$wpdb->get_row("SHOW COLUMNS FROM `$wishlistTbl` LIKE 'shipping_last_name'")) {
            $wpdb->query($query12);
        }

        if (!$wpdb->get_row("SHOW COLUMNS FROM `$wishlistTbl` LIKE 'shipping_company'")) {
            $wpdb->query($query13);
        }

        if (!$wpdb->get_row("SHOW COLUMNS FROM `$wishlistTbl` LIKE 'shipping_country'")) {
            $wpdb->query($query14);
        }

        if (!$wpdb->get_row("SHOW COLUMNS FROM `$wishlistTbl` LIKE 'shipping_address'")) {
            $wpdb->query($query15);
        }


        if (!$wpdb->get_row("SHOW COLUMNS FROM `$wishlistTbl` LIKE 'shipping_postcode'")) {
            $wpdb->query($query16);

        }

        if (!$wpdb->get_row("SHOW COLUMNS FROM `$wishlistTbl` LIKE 'shipping_city'")) {
            $wpdb->query($query17);
        }

        if (!$wpdb->get_row("SHOW COLUMNS FROM `{$prefix}magenest_giftregistry_wishlist` LIKE 'option_quantity'")) {
            $wpdb->query($query18);
        }

        // update shortcode
        $my_post = array(
            'ID' => get_option('follow_up_emailgiftregistry_page_id'),
            'post_content' => '[magenest_view_giftregistry]
                               [magenest_search_giftregistry]
                               [magenest_header_giftregistry_public_view]
                               [magenest_table_giftregistry_public_view]',
        );

        wp_update_post($my_post);
        update_option('magenest_giftregistry_version', self::VERSION);
    }

    /**
     * add menu items
     */
    public function admin_menu()
    {
        global $menu;
        include_once GIFTREGISTRY_PATH . 'admin/magenest-giftregistry-admin.php';

        $admin = new Magenest_Giftregistry_Admin();
        add_menu_page(__('Gift registry', GIFTREGISTRY_TEXT_DOMAIN), __('Gift registry', GIFTREGISTRY_TEXT_DOMAIN), 'manage_woocommerce', 'gift_registry', array(
            $admin,
            'giftregistry_manage'
        ));

    }

    public function load_icon_my_gift_registry()
    {
        wp_register_style('icon-my-gift-registry', GIFTREGISTRY_URL . '/assets/css/icon-my-gift-registry.css');
        wp_enqueue_style('icon-my-gift-registry');
    }

    public function add_giftregistry_button_in_shoppage()
    {
        if (get_option('giftregistry_enable_button','no') == "yes") {
            include_once GIFTREGISTRY_PATH . 'frontend/magenest-giftregistry-add-button-in-shop.php';
            $shop_button = new Magenest_Add_Button();
            $shop_button->add_giftregistry_button();
        }
    }

    public function background_image_metabox()
    {
        if (get_the_ID() == get_option('follow_up_emailgiftregistry_page_id')) {
            add_meta_box('background_image', 'Search Form Background Image', array($this, 'background_image_show'),
                'page', 'side');
        }
    }

    public function background_image_show()
    {
        wp_enqueue_script('giftregistry-page');
        ?>
        <input type="text" id="background-image" style="display: none" name="background-image"
               value="<?= !empty(get_post_meta(get_the_ID(), 'background-image', true)) ?
                   get_post_meta(get_the_ID(), 'background-image', true) : "" ?>">
        <span id="section-background-image">
            <?php if (!empty(get_post_meta(get_the_ID(), 'background-image', true))) {
                ?>
                <a id="set-background-image">
                    <img id="show-background-image" src="<?php echo get_post_meta(get_the_ID(),
                        'background-image', true); ?>" width="100%">
                    <p class="display-if-remove-image"
                       style="display: none"><?= __('Set background image', GIFTREGISTRY_TEXT_DOMAIN); ?></p>
                </a>
                <p class="hide-if-remove-image"
                   style="font-style: italic"><?= __('Click the image to edit or update', GIFTREGISTRY_TEXT_DOMAIN) ?></p>
                <a class="hide-if-remove-image"
                   id="remove-background-image"><?= __('Remove background image', GIFTREGISTRY_TEXT_DOMAIN); ?></a>
                <?php
            } else {
                ?>
                <a id="set-background-image"><p><?= __('Set background image', GIFTREGISTRY_TEXT_DOMAIN); ?></p></a>
                <p class="hide-if-remove-image"
                   style="font-style: italic;display: none"><?= __('Click the image to edit or update', GIFTREGISTRY_TEXT_DOMAIN) ?></p>
                <a class="hide-if-remove-image" id="remove-background-image"
                   style="display: none;"><?= __('Remove background image', GIFTREGISTRY_TEXT_DOMAIN); ?></a>
                <?php
            } ?>
        </span>
        <?php
    }

    public function save_background_image_metabox()
    {
        // Store data in post meta table if present in post data
        if (isset($_POST['background-image']))
            update_post_meta(get_the_ID(), 'background-image',
                $_POST['background-image']);
    }

    public function remove_session_access_gift_registry(){
        if (isset($_REQUEST['giftregistry_pw'])) {
            $wishlist_id = isset($_REQUEST['wishlist_id']) ? $_REQUEST['wishlist_id'] : 0;
            global $wpdb;
            $prefix = $wpdb->prefix;
            $wishlistTbl = $prefix . 'magenest_giftregistry_wishlist';
            $query = $wpdb->prepare("SELECT * FROM $wishlistTbl WHERE `id` = %d", $wishlist_id);
            //$query = 'SELECT * FROM '.$wishlistTbl.' WHERE id='.$wishlist_id;
            $row = $wpdb->get_row($query, ARRAY_A);
            $pass = $row['password'];
            $password = $_REQUEST['password'];


            if ($password == $pass) {
                if(!isset($_COOKIE[get_current_user_id().'open'.$wishlist_id])){
                    setcookie(get_current_user_id().'open'.$wishlist_id, 1, time() + 10*60);
                }
                $link = get_permalink(get_option('follow_up_emailgiftregistry_page_id')) . '?giftregistry_id=' . $wishlist_id;
                echo '<script type="text/javascript">window.location.href = "' . $link . '";</script>';
                exit;
            } else {
                $link = get_permalink(get_option('follow_up_emailgiftregistry_page_id')). '?wishlist_id='. $wishlist_id . '&checkpass_giftregistry=0';
                echo '<script type="text/javascript">window.location.href = "' . $link . '";</script>';
                exit;

            }
        }
//        if(!is_page(get_option('follow_up_emailgiftregistry_page_id'))){
//            $post_metas = get_post_meta(get_option('follow_up_emailgiftregistry_page_id'));
//            if(!empty($post_metas)){
//                foreach ($post_metas as $key => $post_meta){
//                    if(preg_match('/^'.get_current_user_id().'open/', $key)){
//                        delete_post_meta(get_option('follow_up_emailgiftregistry_page_id'), $key);
//                    }
//                }
//            }
//        }
    }
}

$magenest_giftregistry_loaded = Magenest_Giftregistry::getInstance();
$GLOBAl['giftregistryresult'] = array();
?>