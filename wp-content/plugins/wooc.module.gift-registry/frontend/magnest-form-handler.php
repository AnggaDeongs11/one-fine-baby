<?php
session_start();
if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

require_once WP_PLUGIN_DIR . '/woocommerce/includes/abstracts/abstract-wc-settings-api.php';
require_once WP_PLUGIN_DIR . '/woocommerce/includes/emails/class-wc-email.php';

class Magenest_Giftregistry_Form_Handler
{

    public static function init()
    {
        add_action('init', array(__CLASS__, 'update_giftregistry_item_action')); //my-account/my-gift-registry/
        add_action('wp_ajax_add_giftregifttry_to_list', array(__CLASS__, 'add_to_giftregistry_action'));
        add_action('wp_ajax_nopriv_add_giftregifttry_to_list', array(__CLASS__, 'add_to_giftregistry_action'));
        add_action('wp_ajax_add_giftregifttry_from_shop_page', array(__CLASS__, 'add_giftregifttry_from_shop_page_action'));
        add_action('wp_ajax_remove_from_giftregifttry', array(__CLASS__, 'remove_from_giftregifttry_action'));
        add_action('wp_ajax_remove_giftregifttry_form_product_detail', array(__CLASS__, 'remove_giftregifttry_form_product_detail_action'));
        add_action('wp_ajax_set_priority_giftregifttry', array(__CLASS__, 'set_priority_giftregifttry_action'));
        add_action('wp_ajax_filter_giftregistry', array(__CLASS__, 'filter_giftregistry'));
        add_action('wp_ajax_nopriv_filter_giftregistry', array(__CLASS__, 'filter_giftregistry'));
//		add_action( 'init', array( __CLASS__, 'add_to_giftregistry_action' ) ); //product/....
        add_action('init', array(__CLASS__, 'create_giftregistry_action'));//my-account/my-gift-registry/
//		add_action( 'init', array( __CLASS__, 'end_buy_giftregistry' ) );
        add_action('init', array(__CLASS__, 'giftregistry_share_email'));//my-account/my-gift-registry/
        add_action('init', array(__CLASS__, 'searchgiftregistry'));//giftregistry
        add_action('init', array(__CLASS__, 'login_request'));
    }

    public static function searchgiftregistry()
    {
        global $giftregistryresult;
        $collection = array();
        if (isset($_REQUEST['searchgiftregistry'])) {

            $request = $_REQUEST;
            global $wpdb;
            $prefix = $wpdb->prefix;
            $rTb = "{$prefix}magenest_giftregistry_wishlist";
            $name = '';
            $email = '';
            if (isset($_REQUEST['grname'])) {
                $name = $_REQUEST['grname'];
            }

            if (isset($_REQUEST['email'])) {
                $email = $_REQUEST['email'];
            }
            if ($name || $email) {
                if ($name) {
                    $_SESSION ['registrynamesearch'] = $name;
                    $query = "select * from {$rTb} where registrant_firstname like \"%{$name}%\" or registrant_lastname like \"%{$name}%\" or coregistrant_firstname like \"%{$name}%\" or coregistrant_lastname like \"%{$name}%\" 
					or concat(registrant_firstname,' ',registrant_lastname) like \"%{$name}%\" 
                    or concat(coregistrant_firstname,' ',coregistrant_lastname) like \"%{$name}%\" ";

                    if ($email) {
                        $_SESSION ['registryemailsearch'] = $email;

                        $query .= "or registrant_email like \"%{$email}%\" or coregistrant_email like \"%{$email}%\"";
                    }
                } else {
                    // $query = "select * from {$rTb} ";

                    if ($email) {
                        $_SESSION ['registryemailsearch'] = $email;

                        $query = "select * from {$rTb} where registrant_email like \"%{$email}%\" or coregistrant_email like \"%{$email}%\"";
                    }
                }


                $collection = $wpdb->get_results($query, ARRAY_A);
                $giftregistryresult = $collection;

            }
        }
        $giftregistryresult = $collection;
        $_SESSION['registryresult'] = $collection;
    }

    public static function giftregistry_share_email()
    {
        if (isset ($_REQUEST ['giftregistry-share-email']) && isset($_REQUEST['recipient']) && isset($_REQUEST['email_subject']) && isset($_REQUEST['message_share'])) {

            $wishlist_id = Magenest_Giftregistry_Model::get_wishlist_id();
            if (!$wishlist_id) {
                return;
            }

            $recipients = array();
            $receivers = array();
            if ($_REQUEST['recipient']) {
                $recipients = explode(';', $_REQUEST['recipient']);
            }
            if (!empty($recipients)) {
                foreach ($recipients as $email) {
                    if (is_email($email)) {
                        $receivers[] = $email;
                    }

                }
            }

            if (empty($receivers)) {
                return;
            }
            //shared part
            $rp = get_permalink(get_option('follow_up_emailgiftregistry_page_id'));
            $http_schema = 'http://';
            if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']) {
                $http_schema = 'https://';
            }

            $request_link = $http_schema . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];

            if (strpos($request_link, '?') > 0) {
                $giftregistry_url = $rp . '&giftregistry_id=' . $wishlist_id;

            } else {
                $giftregistry_url = $rp . '?giftregistry_id=' . $wishlist_id;
            }
            $headers = array();
            $wc_email = new WC_Email();
            $headers [] = "Content-Type: text/html";
            add_filter('wp_mail_from_name', array($wc_email, 'get_from_name'));
            $headers [] = 'From: ' . get_option('woocommerce_email_from_name') . '<' . get_option('woocommerce_email_from_address') . '>';

            $subject = $_REQUEST['email_subject'];
            $content = $_REQUEST['message_share'];
            $replaces = array(
                '{giftregistry_url}' => $giftregistry_url

            );
            //$replace = array('{giftregistry_url}'=>$not_encode_url ) ;

            $content = strtr($content, $replaces);

            add_filter('wp_mail_content_type', array(
                'Magenest_Giftregistry_Form_Handler',
                'set_html_content_type'
            ));

            foreach ($receivers as $to) {
                wp_mail($to, $subject, $content, $headers);
            }

            remove_filter('wp_mail_content_type', array(
                'Magenest_Giftregistry_Form_Handler',
                'set_html_content_type'
            ));

            wc_add_notice(__('Your share email has been sent!', GIFTREGISTRY_TEXT_DOMAIN), 'success');
            wp_safe_redirect($_SERVER['HTTP_REFERER']);
            exit;
        }
    }

    /**
     * set html content type for email
     */
    public static function set_html_content_type()
    {
        return 'text/html';
    }

    public static function end_buy_giftregistry()
    {
        if (isset($_REQUEST['end_buy_giftregistry']) && isset($_SESSION['buy_for_giftregistry_id'])) {
//		    unset($_SESSION['buy_for_giftregistry_id']);
            session_unset();
        }

    }

    /**
     *
     */
    public static function create_giftregistry_action()
    {
        global $wpdb;
        $prefix = $wpdb->prefix;
        $rTb = "{$prefix}magenest_giftregistry_wishlist";

        $is_edit = false;

        if (isset($_REQUEST['create_giftregistry'])) {
            require_once(ABSPATH . 'wp-admin/includes/image.php');
            require_once(ABSPATH . 'wp-admin/includes/file.php');
            require_once(ABSPATH . 'wp-admin/includes/media.php');

            if (isset($_REQUEST['giftregistry_id'])) {
                $id = $_REQUEST['giftregistry_id'];

                if (is_numeric($id) && $id > 0) {
                    $is_edit = true;
                }
            }

            if (is_user_logged_in()) {

                $data = array();
                $wishlist = Magenest_Giftregistry_Model::get_wishlist($id);
                if(!empty($wishlist)){
                    $user_id = $wishlist->user_id;
                }
                else{
                    $user_id = get_current_user_id();
                }
                $data['user_id'] = $user_id;

                $allowedImageTypes = array(
                    "image/pjpeg",
                    "image/jpeg",
                    "image/jpg",
                    "image/png",
                    "image/x-png",
                    "image/gif"
                );
                // $data['status'] = $_REQUEST['status'];
                //image of bride
                if (!empty($_FILES ['registrant_image'])) {

                    if (in_array($_FILES ['registrant_image'] ['type'], $allowedImageTypes)) {
                        $registrant_image_id = media_handle_upload('registrant_image', '');
                        $data ['registrant_image'] = $registrant_image_id;
                    }
                }

                /* background images of the wedding */
                if (!empty($_FILES['background_image'])) {
                    if (in_array($_FILES['background_image']['type'], $allowedImageTypes)) {
                        $coregistrant_image_id = media_handle_upload('background_image', '');
                        $data['background_image'] = $coregistrant_image_id;
                    }
                }

                /* the co registrant image */

                if (!empty($_FILES['coregistrant_image'])) {
                    if (in_array($_FILES['coregistrant_image']['type'], $allowedImageTypes)) {
                        $coregistrant_image_id = media_handle_upload('coregistrant_image', '');
                        $data['coregistrant_image'] = $coregistrant_image_id;
                    }
                }

                $title = (isset($_REQUEST['title'])) ? $_REQUEST['title'] : '';
                $data['title'] = self::regu($title);

                //self::regu($message);
                $registrant_firstname = (isset($_REQUEST['registrant_firstname'])) ? $_REQUEST['registrant_firstname'] : '';
                $data['registrant_firstname'] = self::regu($registrant_firstname);

                $registrant_lastname = (isset($_REQUEST['registrant_lastname'])) ? $_REQUEST['registrant_lastname'] : '';
                $data['registrant_lastname'] = self::regu($registrant_lastname);

                $registrant_email = (isset($_REQUEST['registrant_email'])) ? $_REQUEST['registrant_email'] : '';
                if(filter_var(self::regu($registrant_email), FILTER_VALIDATE_EMAIL) !== false){
                    $data['registrant_email'] = self::regu($registrant_email);
                }else{
                    if(!is_admin()){
                        wc_add_notice('Invalid email address','error');
                    }else{
                        add_action( 'admin_notices', array(__CLASS__,'invalid_email_notice' ));
                    }
                    return;
                }

                $registrant_description = (isset($_REQUEST['registrant_description'])) ? $_REQUEST['registrant_description'] : '';
                $data['registrant_description'] = self::regu($registrant_description);

                $coregistrant = isset($_REQUEST['co_r']) ? $_REQUEST['co_r'] : '';
                $data['enable_coregistrant'] = self::regu($coregistrant);

                $option_quantity = isset($_REQUEST['option_quantity']) ? $_REQUEST['option_quantity'] : '';
                $data['option_quantity'] = self::regu($option_quantity);

                if($coregistrant == '1'){
                    $coregistrant_firstname = (isset($_REQUEST['coregistrant_firstname'])) ? $_REQUEST['coregistrant_firstname'] : '';
                    $data['coregistrant_firstname'] = self::regu($coregistrant_firstname);

                    $coregistrant_lastname = (isset($_REQUEST['coregistrant_lastname'])) ? $_REQUEST['coregistrant_lastname'] : '';
                    $data['coregistrant_lastname'] = self::regu($coregistrant_lastname);

                    $coregistrant_email = (isset($_REQUEST['coregistrant_email'])) ? $_REQUEST['coregistrant_email'] : '';
                    if(filter_var(self::regu($coregistrant_email), FILTER_VALIDATE_EMAIL) !== false){
                        $data['coregistrant_email'] = self::regu($coregistrant_email);
                    }else{
                        if(!is_admin()){
                            wc_add_notice('Invalid email address','error');
                        }else{
                            add_action( 'admin_notices', array(__CLASS__,'invalid_email_notice' ));
                        }
                        return;
                    }

                    $coregistrant_description = (isset($_REQUEST['coregistrant_description'])) ? $_REQUEST['coregistrant_description'] : '';
                    $data['coregistrant_description'] = self::regu($coregistrant_description);

                } else{
                    $data['coregistrant_firstname'] = null;
                    $data['coregistrant_lastname'] = null;
                    $data['coregistrant_email'] = null;
                    $data['coregistrant_description'] = null;
                }

                $data['event_date_time'] = (isset($_REQUEST['event_date_time'])) ? $_REQUEST['event_date_time'] : '';

                if ($data['event_date_time']) {
                    $data['event_date_time'] = date('Y-m-d H:i:s', strtotime($data['event_date_time']));
                }

                $event_location = (isset($_REQUEST['event_location'])) ? $_REQUEST['event_location'] : '';
                $data['event_location'] = self::regu($event_location);
                //stripslashes str_replace("\n","<br />", $string)

                $message = (isset($_REQUEST['message'])) ? $_REQUEST['message'] : '';
                //$message = str_replace("\t", "", $message);
                $data['message'] = self::regu($message);

                $role = isset($_REQUEST['role']) ? $_REQUEST['role'] : '';
                $data['role'] = self::regu($role);

                $pass = isset($_REQUEST['password']) ? $_REQUEST['password'] : '';
                $data['password'] = self::regu($pass);

                $shipping_first_name = isset($_REQUEST['shipping_first_name']) ? $_REQUEST['shipping_first_name'] : '';
                $data['shipping_first_name'] = self::regu($shipping_first_name);

                $shipping_last_name = isset($_REQUEST['shipping_last_name']) ? $_REQUEST['shipping_last_name'] : '';
                $data['shipping_last_name'] = self::regu($shipping_last_name);

                $shipping_company = isset($_REQUEST['shipping_company']) ? $_REQUEST['shipping_company'] : '';
                $data['shipping_company'] = self::regu($shipping_company);

                $shipping_country = isset($_REQUEST['shipping_country']) ? $_REQUEST['shipping_country'] : '';
                $data['shipping_country'] = self::regu($shipping_country);

                $shipping_address = isset($_REQUEST['shipping_address']) ? $_REQUEST['shipping_address'] : '';
                $data['shipping_address'] = self::regu($shipping_address);

                $shipping_postcode = isset($_REQUEST['shipping_postcode']) ? $_REQUEST['shipping_postcode'] : '';
                $data['shipping_postcode'] = self::regu($shipping_postcode);

                $shipping_city = isset($_REQUEST['shipping_city']) ? $_REQUEST['shipping_city'] : '';
                $data['shipping_city'] = self::regu($shipping_city);

                if (self::is_valid_to_create_giftregistry() && !$is_edit) {

                    $wpdb->insert($rTb, $data);
                } elseif ($is_edit) {
                    $wpdb->update($rTb, $data, array('id' => $id));
                }
                if(!is_admin()){
                    wc_add_notice(__('The information of Gift Registry is saved.', 'GIFTREGISTRY_TEXT_DOMAIN'));
                    wp_safe_redirect(wc_get_page_permalink('myaccount') . '/my-gift-registry');
                }else{
                    header("Refresh:0");
                }
                exit;
            }
        }
    }

    public static function invalid_email_notice() {
        ?>
        <div class="error notice">
            <p><?=__( 'Invalid email address', GIFTREGISTRY_TEXT_DOMAIN ); ?></p>
        </div>
        <?php
    }

    public static function regu($str)
    {
        $message = str_replace("\t", "", $str);
        $message = stripslashes($message);
        $message = htmlentities($message);

        return $message;
    }

    /**
     *This version 1.1 only allow one customer create a gift registry
     */
    public static function is_valid_to_create_giftregistry()
    {

        $wishlist_id = Magenest_Giftregistry_Model::get_wishlist_id();

        if (is_numeric($wishlist_id) && $wishlist_id > 0) {
            return false;
        }

        return true;
    }

//	public static function add_to_giftregistry_action()
//	{
//
//		if ( isset ( $_REQUEST ['add-registry'] ) && $_REQUEST ['add-registry'] == 1 && ! isset( $_REQUEST['buy_for_giftregistry_id'] ) ) {
//			global $wpdb;
//			$item_tbl   = $wpdb->prefix . 'magenest_giftregistry_item';
//			$productTbl = $wpdb->prefix . 'posts';
//
//			$r_id = self::get_giftregistry_id();
//
//			if ( $r_id ) {
//				///////////check the shipping address
//				$customer_id = get_current_user_id();
//				$addr_1      = get_user_meta( $customer_id, 'shipping_address_1', true );
//				$addr_2      = get_user_meta( $customer_id, 'shipping_address_2', true );
//				if ( ! $addr_1 && ( get_option( 'giftregistry_shipping_restrict', 'yes' ) == 'yes' ) ) {
//					wc_add_notice( __( 'You have to fulfill shipping address before adding item to gift registry', GIFTREGISTRY_TEXT_DOMAIN ), 'notice' );
//
//					return;
//				}
//				//product_variation
//				$product_id = $_REQUEST ['add-to-giftregistry'];
//				//				$product = get_product($product_id);
//				//				if( $product->is_type( 'simple' ) ){
//				//					echo "aaaaa";
//				//				} elseif( $product->is_type( 'variable' ) ){
//				//					// Product has variations
//				//					echo "bbbbbbbbb";
//				//				}
//				//$sql = 'SELECT * FROM '.$productTbl.' WHERE post_parent='.$product_id.' AND post_type="product_variation"';
//				$sql = $wpdb->prepare( "SELECT * FROM $productTbl WHERE `post_parent` = %d AND `post_type` = %s", $product_id, 'product_variation' );
//
//				$results      = $wpdb->get_results( $sql, ARRAY_A );
//				$post_type    = get_post_type( $product_id );
//				$variation_id = isset( $_REQUEST['variation_id'] ) ? $_REQUEST['variation_id'] : 0;
//				if ( $variation_id == 0 && $results ) {
//					wc_add_notice( __( 'Please select some product options before add gift registry', GIFTREGISTRY_TEXT_DOMAIN ), 'notice' );
//
//					return;
//				}
//				///////////////////////////////////
//				$data = array();
//				//$data ['product'] = $_REQUEST ['product'];
//				$data ['product_id'] = $_REQUEST ['add-to-giftregistry'];
//				$data ['quantity']   = isset( $_REQUEST ['quantity'] ) ? $_REQUEST ['quantity'] : 1;
//
//				if ( isset ( $_REQUEST ['variation_id'] ) ) {
//					$data ['variation_id'] = $_REQUEST ['variation_id'];
//				}
//
//				if ( isset ( $_REQUEST ['variation'] ) ) {
//					$data ['variation'] = $_REQUEST ['variation'];
//				}
//
//				$info                  = serialize( $_REQUEST );
//				$data ['info_request'] = $info;
//				$data ['wishlist_id']  = $r_id;
//				$data ['received_qty'] = 0;
//				$data ['priority']     = 2;
//				// variation
//
//				if ( $data ['product_id'] > 0 ) {
//					$wpdb->insert( $item_tbl, $data );
//					wc_add_notice( __( 'Item is added to your gift registry', GIFTREGISTRY_TEXT_DOMAIN ), 'success' );
//				} else {
//					wc_add_notice( __( 'You have to select item', GIFTREGISTRY_TEXT_DOMAIN ), 'notice' );
//
//				}
//			} else {
//				wc_add_notice( __( 'You have to enter gift registry information before adding item to gift registry', GIFTREGISTRY_TEXT_DOMAIN ), 'notice' );
//
//			}
//		} else {
//			//wc_add_notice ( __ ( 'You have to enter gift registry information before adding item to gift registry', GIFTREGISTRY_TEXT_DOMAIN ), 'notice' );
//
//		}
//	}

    public static function add_to_giftregistry_action()
    {
        if ($_REQUEST ['action'] == 'add_giftregifttry_to_list') {

            if (is_user_logged_in()) {

                global $wpdb;
                $item_tbl = $wpdb->prefix . 'magenest_giftregistry_item';
                $productTbl = $wpdb->prefix . 'posts';
                $wishlistTbl = $wpdb->prefix . 'magenest_giftregistry_wishlist';
                $wid = Magenest_Giftregistry_Model::get_wishlist_id();
                $wishlist = Magenest_Giftregistry_Model::get_wishlist($wid);

                $r_id = self::get_giftregistry_id();
                if (empty($wishlist->shipping_first_name) || empty($wishlist->shipping_last_name) || empty($wishlist->shipping_country)
                    || empty($wishlist->shipping_address) || empty($wishlist->shipping_postcode) || empty($wishlist->shipping_city)) {
                    $addr = true;
                }

                if ($r_id) {
///////////check the shipping address
                    if (isset($addr) && $addr && (get_option('giftregistry_shipping_restrict', 'yes') == 'yes')) {
                        wc_add_notice(__('You have to fulfill shipping address before adding item to gift registry', GIFTREGISTRY_TEXT_DOMAIN), 'notice');
                        return;
                    }
                    //product_variation
                    $product_id = $_REQUEST ['data_giftregistry']['add-to-giftregistry'];
                    $sql = $wpdb->prepare("SELECT * FROM $productTbl WHERE `post_parent` = %d AND `post_type` = %s", $product_id, 'product_variation');

                    $results = $wpdb->get_results($sql, ARRAY_A);
                    $post_type = get_post_type($product_id);
                    $variation_id = isset($_REQUEST['data_giftregistry']['giftregistry_variation_id']) ? $_REQUEST['data_giftregistry']['giftregistry_variation_id'] : 0;
                    if ($variation_id == 0 && $results) {
                        wc_add_notice(__('Please select some product options before add gift registry', GIFTREGISTRY_TEXT_DOMAIN), 'notice');
                        return;
                    }
                    ///////////////////////////////////
                    $data = array();
                    //$data ['product'] = $_REQUEST ['product'];
                    $data ['product_id'] = $_REQUEST['data_giftregistry']['add-to-giftregistry'];
                    $data ['quantity'] = isset($_REQUEST['data_giftregistry']['quantity']) ? $_REQUEST['data_giftregistry']['quantity'] : 1;

                    if (isset ($_REQUEST['data_giftregistry']['giftregistry_variation_id']) && $_REQUEST['data_giftregistry']['giftregistry_variation_id'] != 0) {
                        $data['product_id'] = $data ['variation_id'] = $_REQUEST['data_giftregistry']['giftregistry_variation_id'];
                        $data['variation'] = $_REQUEST['data_giftregistry']['add-to-giftregistry'];
                    }
//                    if (isset ($_REQUEST['data_giftregistry']['variation'])) $data ['variation'] = $_REQUEST['data_giftregistry']['variation'];


                    $info = serialize($_REQUEST['data_giftregistry']);
                    $data ['info_request'] = $info;
                    $data ['wishlist_id'] = $r_id;
                    $data ['received_qty'] = 0;
                    $data ['priority'] = 3;
                    // variation

                    if ($data ['product_id'] > 0) {
                        $wpdb->insert($item_tbl, $data);
                        wc_add_notice(__('Item is added to your gift registry', GIFTREGISTRY_TEXT_DOMAIN), 'success');
                    } else {
                        wc_add_notice(__('You have to select item', GIFTREGISTRY_TEXT_DOMAIN), 'notice');
                    }
                } else {
                    wc_add_notice(__('You have to enter gift registry information before adding item to gift registry', GIFTREGISTRY_TEXT_DOMAIN), 'notice');
                }
            } else {
                wc_add_notice(__('You have to enter gift registry information before adding item to gift registry', GIFTREGISTRY_TEXT_DOMAIN), 'notice');
            }
//        echo json_encode($out);
//        wp_die();
        }
    }

    public static function get_giftregistry_id()
    {
        global $wpdb;
        $prefix = $wpdb->prefix;
        $rTb = "{$prefix}magenest_giftregistry_wishlist";

        $user_id = get_current_user_id();
        $sql = $wpdb->prepare("SELECT * FROM $rTb WHERE `user_id` = %s", $user_id);
        $row = $wpdb->get_row($sql, ARRAY_A);

        if ($row) {
            return $row['id'];
        }

    }

    public static function add_giftregifttry_from_shop_page_action()
    {
        if ($_REQUEST ['action'] == 'add_giftregifttry_from_shop_page') {

            global $wpdb;
            $item_tbl = $wpdb->prefix . 'magenest_giftregistry_item';

            $r_id = self::get_giftregistry_id();

            if ($r_id) {
                $data = array();
                $data ['product_id'] = $_REQUEST['data_giftregistry']['add-to-giftregistry'];
                $data ['quantity'] = isset($_REQUEST['data_giftregistry']['quantity']) ? $_REQUEST['data_giftregistry']['quantity'] : 1;

                $info = serialize($_REQUEST['data_giftregistry']);
                $data ['info_request'] = $info;
                $data ['wishlist_id'] = $r_id;
                $data ['received_qty'] = 0;
                $data ['priority'] = 2;

                if ($data ['product_id'] > 0) {
                    $wpdb->insert($item_tbl, $data);
                }
            }
        }
    }

    public static function login_request()
    {
        if (isset($_REQUEST['request_login'])) {
            wc_add_notice('You need to login before add gift registry!', 'notice');
        }
    }

    public static function update_giftregistry_item_action()
    {
        if (isset($_REQUEST['trash'])) {
            foreach ($_REQUEST['trash'] as $trashes => $trash) {
                Magenest_Giftregistry_Model::delete_giftregistry_item($trashes);
            }
            if(!is_admin()){
                wc_add_notice(__('Gift Registry item is deleted.', 'GIFTREGISTRY_TEXT_DOMAIN'));
                wp_safe_redirect(wc_get_page_permalink('myaccount') . '/my-gift-registry');
            }else {
                header("Refresh:0");
            }
            exit;
        }

        //delete button
        if (isset($_REQUEST['delete']) && is_array($_REQUEST['delete']) && !empty($_REQUEST['delete']) && isset($_REQUEST['delete_button'])) {
            foreach ($_REQUEST['delete'] as $deletes => $delete) {
                Magenest_Giftregistry_Model::delete_giftregistry_item($deletes);
            }
            if(!is_admin()){
                wc_add_notice(__('Gift Registry items are deleted.', 'GIFTREGISTRY_TEXT_DOMAIN'));
                wp_safe_redirect(wc_get_page_permalink('myaccount') . '/my-gift-registry');
            }else {
                header("Refresh:0");
            }
            exit;
        }
        // save button
        if (isset($_REQUEST['update_giftregistry_item']) && !isset($_REQUEST['delete_button'])) {
            if (isset($_REQUEST['priority']) && is_array($_REQUEST['priority']) && !empty($_REQUEST['priority'])) {
                foreach ($_REQUEST['priority'] as $priorities => $priority) {
                    Magenest_Giftregistry_Model::update_giftregistry_priority($priorities, $priority);
                }
            }
            if (isset($_REQUEST['desired_item']) && is_array($_REQUEST['desired_item']) && !empty($_REQUEST['desired_item'])) {
                foreach ($_REQUEST['desired_item'] as $item_id => $qty) {
                    $item = Magenest_Giftregistry_Model::get_item_giftregistry_by_item_id($item_id );
                    if ($qty >= 0) {
                        Magenest_Giftregistry_Model::update_giftregistry_item($item_id, $item['received_qty'] + $qty);
                    } else {
                        Magenest_Giftregistry_Model::delete_giftregistry_item($item_id);
                    }
                }

            }
            if(!is_admin()){
                wc_add_notice(__("Gift Registry item's information are updated.", 'GIFTREGISTRY_TEXT_DOMAIN'));
                wp_safe_redirect(wc_get_page_permalink('myaccount') . '/my-gift-registry');
            }else {
                header("Refresh:0");
            }
            exit;
        }
    }

    public static function remove_from_giftregifttry_action()
    {
        $product_id = $_REQUEST['data_giftregistry']['product-id-remove'];
        $items = Magenest_Giftregistry_Model::get_wishlist_item_by_product_id($product_id);
        if (is_array($items) && !empty ($items)) {
            $item = $items[0];
            Magenest_Giftregistry_Model::delete_giftregistry_item($item['id']);
        }
    }

    public static function remove_giftregifttry_form_product_detail_action()
    {
        $product_id = $_REQUEST['data_giftregistry']['product-id-remove'];
        $item = Magenest_Giftregistry_Model::get_wishlist_item_by_product_id($product_id);
        Magenest_Giftregistry_Model::delete_giftregistry_item($item['id']);
        wc_add_notice(__('Item is removed from your gift registry', GIFTREGISTRY_TEXT_DOMAIN), 'success');
    }

    public static function set_priority_giftregifttry_action()
    {
        $priority = $_REQUEST['data_giftregistry']['priority'];
        $product_id = $_REQUEST['data_giftregistry']['product-id-set-priority'];
        $item = Magenest_Giftregistry_Model::get_wishlist_item_by_product_id($product_id);
        Magenest_Giftregistry_Model::update_giftregistry_priority($item['id'], $priority);
    }

    /*reder result filter to table*/
    public static function filter_giftregistry(){
        if($_REQUEST['action'] == 'filter_giftregistry'){
            $items = Magenest_Giftregistry_Model::filter_items_in_giftregistry($_REQUEST['id'], $_REQUEST['filter'], $_REQUEST['level']);
            ob_start();
            $template_path = GIFTREGISTRY_PATH.'template/';
            $default_path = GIFTREGISTRY_PATH.'template/';
            wc_get_template( 'table_giftregistry.php', array( 'items' => $items , 'id' => $_REQUEST['id'] ),$template_path,$default_path );
            wp_die(ob_get_clean());
        }
    }

}

Magenest_Giftregistry_Form_Handler::init();