<?php
if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

class Magenest_Giftregistry_Shortcode {
    public static function init() {
        // Define shortcodes
        $shortcodes = array(

            'magenest_giftregistry' => __CLASS__ . '::show_gift_registry_page',
//            'magenest_gift_registry_full_mode' => __CLASS__ . '::show_gift_registry_page_full_mode',
//            'magenest_gift_registry_mini_mode' => __CLASS__ . '::show_gift_registry_page_mini_mode',
//            'magenest_public_giftregistry' => __CLASS__ . '::testAddEmailQueuefororder',
            'magenest_header_giftregistry_public_view' => __CLASS__ . '::show_header_giftregistry_public_view',
            'magenest_table_giftregistry_public_view' => __CLASS__ . '::show_table_giftregistry_public_view',
            'magenest_search_giftregistry' => __CLASS__ . '::show_search_giftregistry',
            'magenest_view_giftregistry' => __CLASS__ . '::show_view_giftregistry',
            'magenest_share_giftregistry' => __CLASS__ . '::show_share_giftregistry'
        );

        foreach ($shortcodes as $shortcode => $function) {
            add_shortcode(apply_filters("{$shortcode}", $shortcode), $function);
        };
    }

    /**
     * @return string
     */
    public static function show_gift_registry_page() {

        $template_path = GIFTREGISTRY_PATH . 'template/';
        $default_path = GIFTREGISTRY_PATH . 'template/';
        if (!isset ($_REQUEST ['giftregistry_id'])) {
            ob_start();
            wc_get_template('giftregistry-index.php', array('order' => 'r', 'order_id' => '2'), $template_path, $default_path);
            return ob_get_clean();
        } else {
            ob_start();
            wc_get_template('public-view-giftregistry.php', array('id' => $_REQUEST ['giftregistry_id']), $template_path, $default_path);
            return ob_get_clean();
        }
    }


    public static function show_gift_registry_page_mini_mode() {
        $template_path = GIFTREGISTRY_PATH . 'template/';
        $default_path = GIFTREGISTRY_PATH . 'template/';
        if (!isset ($_REQUEST ['giftregistry_id'])) {

            ob_start();
            wc_get_template('giftregistry-index.php', array('order' => 'r', 'order_id' => '2'), $template_path, $default_path);
            return ob_get_clean();
        } else {

            ob_start();
            wc_get_template('public-view-giftregistry-mini-mode.php', array('id' => $_REQUEST ['giftregistry_id']), $template_path, $default_path);

            return ob_get_clean();
        }

    }


    public static function show_gift_registry_page_full_mode() {
        $template_path = GIFTREGISTRY_PATH . 'template/';
        $default_path = GIFTREGISTRY_PATH . 'template/';
        if (!isset ($_REQUEST ['giftregistry_id'])) {

            ob_start();
            wc_get_template('giftregistry-index.php', array('order' => 'r', 'order_id' => '2'), $template_path, $default_path);
            return ob_get_clean();
        } else {

            ob_start();
            wc_get_template('public-view-giftregistry-full-mode.php', array('id' => $_REQUEST ['giftregistry_id']), $template_path, $default_path);
            return ob_get_clean();
        }
    }

    public static function show_header_giftregistry_public_view(){
        $template_path = GIFTREGISTRY_PATH . 'template/';
        $default_path = GIFTREGISTRY_PATH . 'template/';
        if (isset ($_REQUEST ['giftregistry_id'])) {
            ob_start();
            $GLOBALS['giftregistry_id'] = $_REQUEST['giftregistry_id'];
            wc_get_template('header_giftregistry_public_view.php', array('id' => $_REQUEST ['giftregistry_id']), $template_path, $default_path);
            return ob_get_clean();
        }
    }

    public static function show_table_giftregistry_public_view(){
        $template_path = GIFTREGISTRY_PATH . 'template/';
        $default_path = GIFTREGISTRY_PATH . 'template/';
        if(isset($_REQUEST ['giftregistry_id'])){
            ob_start();
            wc_get_template('table_giftregistry_public_view.php', array('id' => $_REQUEST ['giftregistry_id']), $template_path, $default_path);
            return ob_get_clean();
        }
    }

    public static function show_search_giftregistry(){
        $template_path = GIFTREGISTRY_PATH . 'template/';
        $default_path = GIFTREGISTRY_PATH . 'template/';
        ob_start();
        wc_get_template('search_giftregistry.php', array(), $template_path, $default_path);
        return ob_get_clean();
    }

    public static function show_view_giftregistry(){
        $template_path = GIFTREGISTRY_PATH . 'template/';
        $default_path = GIFTREGISTRY_PATH . 'template/';
        ob_start();
        wc_get_template('view_giftregistry.php', array(), $template_path, $default_path);
        return ob_get_clean();
    }

    public static function show_share_giftregistry(){
        $giftregistry_page_url = get_permalink(get_option('follow_up_emailgiftregistry_page_id'));
        $wid = Magenest_Giftregistry_Model::get_wishlist_id();
        $http_schema = 'http://';
        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']) {
            $http_schema = 'https://';
        }
        $request_link = $http_schema . $_SERVER ["HTTP_HOST"] . $_SERVER ["REQUEST_URI"];

        if (strpos($request_link, '?') > 0) {
            $giftregistry_page_url = $giftregistry_page_url . '&giftregistry_id=' . $wid;
        } else {
            $giftregistry_page_url = $giftregistry_page_url . '?giftregistry_id=' . $wid;
        }
        $template_path = GIFTREGISTRY_PATH . 'template/account/';
        $default_path = GIFTREGISTRY_PATH . 'template/account/';
        ob_start();
        wc_get_template('giftregistry-share.php', array('url' => $giftregistry_page_url), $template_path, $default_path);
        return ob_get_clean();
    }

}