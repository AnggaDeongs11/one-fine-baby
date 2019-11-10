<?php
if (!defined('ABSPATH'))
    exit (); // Exit if accessed directly

require_once WP_PLUGIN_DIR . '/woocommerce/includes/abstracts/abstract-wc-settings-api.php';
require_once WP_PLUGIN_DIR . '/woocommerce/includes/emails/class-wc-email.php';

class Magenest_Giftregistry_Model
{
    public static function get_all_giftregistry()
    {
        global $wpdb;
        $prefix = $wpdb->prefix;
        $rTb = "{$prefix}magenest_giftregistry_wishlist";

        $row = $wpdb->get_results("select * from {$rTb}", ARRAY_A);

        if ($row) {
            return $row;
        }
    }

    public static function update_giftregistry_item($id, $qty)
    {
        global $wpdb;
        $prefix = $wpdb->prefix;
        $tbl = "{$prefix}magenest_giftregistry_item";
        $wpdb->update($tbl, array('quantity' => $qty), array('id' => $id));

    }

    public static function update_giftregistry_priority($id, $priority)
    {
        global $wpdb;
        $prefix = $wpdb->prefix;
        $tbl = $prefix . 'magenest_giftregistry_item';
        $wpdb->update($tbl, array('priority' => $priority), array('id' => $id));
    }

    public static function delete_giftregistry_item($id)
    {
        global $wpdb;
        $prefix = $wpdb->prefix;
        $tbl = "{$prefix}magenest_giftregistry_item";
        $wpdb->delete($tbl, array('id' => $id));
    }

    public static function delete_giftregistry($id)
    {
        global $wpdb;
        $prefix = $wpdb->prefix;
        $tbl = "{$prefix}magenest_giftregistry_wishlist";
        update_user_meta(self::get_wishlist($id)->user_id, 'gr_purchased', 0);
        $wpdb->delete($tbl, array('id' => $id));
    }

    public static function get_wishlist_items_for_current_user()
    {
        $wid = self::get_wishlist_id();
        if ($wid) {
            return self::get_items_in_giftregistry($wid);
        }
    }

    public static function get_wishlist_id()
    {
        global $wpdb;
        $prefix = $wpdb->prefix;
        $rTb = "{$prefix}magenest_giftregistry_wishlist";

        $user_id = get_current_user_id();
        $sql = $wpdb->prepare("SELECT * FROM $rTb WHERE `user_id` = %s", $user_id);
        $row = $wpdb->get_row($sql, OBJECT);
        if ($row) {
            return $row->id;
        }
    }

    public static function get_items_in_giftregistry($wishlist_id)
    {
        global $wpdb;
        $prefix = $wpdb->prefix;
        $tbl = "{$prefix}magenest_giftregistry_item";

//        $user_id = get_current_user_id();
//        $sql = 'SELECT * FROM '.$tbl.' WHERE wishlist_id='.$wishlist_id;//' ORDER BY priority ASC

        $sql = $wpdb->prepare("SELECT * FROM $tbl WHERE `wishlist_id` = %d", $wishlist_id);
        $rows = $wpdb->get_results($sql, ARRAY_A);

        if ($rows) {
            return $rows;
        }
    }

    public static function show_info_request($item, $wishlist_id)
    {
        $request = unserialize($item['info_request']);

        $request_st = '?';
        if (!empty ($request)) {
            $i = 0;
            foreach ($request as $k => $v) {
//                error_log($k);
//                error_log($v);
                $i++;
                if ($k == 'add-to-giftregistry') {
                    $k = 'add-to-cart';
                }
                if ($k == 'giftregistry_variation_id') {
                    $k = 'variation_id';
                }
                if ($k != 'quantity')
                    $request_st .= $k . '=' . $v;

                if ($i != count($request)) {

                    $request_st .= '&';
                }

            }
            $request_st .= '&buy_for_giftregistry_id=' . $wishlist_id;
        }

        return $request_st;
    }

    public static function after_buy_gift($order_id)
    {
        global $wpdb;
        $prefix = $wpdb->prefix;
        $tbl = $prefix . "magenest_giftregistry_item";
        $orderItemTbl = $prefix . 'woocommerce_order_items';
        $orderItemMetaTbl = $prefix . 'woocommerce_order_itemmeta';

        foreach (WC()->cart->get_cart_contents() as $cart_item) {
            if (!empty($cart_item['gift_registry'])) {
                # code...
                $w_id = $cart_item['gift_registry']['buy_for_giftregistry_id']['value'];
            }
        }

        if(isset($_POST['message'])){
            $_SESSION['message'] = $_POST['message'];
        }

        $order = wc_get_order($order_id);
        $order_items = $order->get_items(apply_filters('woocommerce_purchase_order_item_types', 'line_item'));
        $sql = $wpdb->prepare("SELECT * FROM $orderItemTbl WHERE `order_id` = %d", $order_id);
        $result = $wpdb->get_row($sql, ARRAY_A);
        $orderItem_id = $result['order_item_id'];
        $sql = $wpdb->prepare("SELECT * FROM $orderItemMetaTbl WHERE `order_item_id` = %d", $orderItem_id);
        $results = $wpdb->get_results($sql, ARRAY_A);

        // get current gift registry purchased of the user
        $user_id = self::get_wishlist($w_id)->user_id;
        $current_gr_purchased = self::get_current_gr_purchased($user_id, $w_id);

        foreach ($order_items as $item_id => $item) {
            $product = $item->get_product();
            if (!empty($results)) {
                $product_id = $product->get_id();
                $purchased_qty = $item->get_quantity();
                $variation_id = $item->get_variation_id();
                if (!$variation_id) {

                    $query = $wpdb->prepare("SELECT * FROM $tbl WHERE `product_id` = %d AND variation_id is NULL AND `wishlist_id` = %d", $product_id, $w_id);

                } else {
                    $query = $wpdb->prepare("SELECT * FROM $tbl WHERE `product_id` = %d AND `variation_id` = %d AND `wishlist_id` = %d", $product_id, $variation_id, $w_id);

                }

                $gr_item = $wpdb->get_row($query, ARRAY_A);

                if (is_array($gr_item)) {
                    $current_gr_purchased += $purchased_qty;
                    $gr_item_id = $gr_item ['id'];
                    $received_qty = $gr_item ['received_qty'];
                    $received_quantity = $received_qty + $purchased_qty;
                    $received_order = $gr_item ['received_order'];
                    if ($received_order) {
                        $received_order .= ';' . $order_id;
                    } else {
                        $received_order .= $order_id;
                    }
                    if ($gr_item_id) {
                        $status = $wpdb->update($tbl, array(
                            'received_qty' => $received_quantity,
                            'received_order' => $received_order
                        ), array(
                            'id' => $gr_item_id
                        ));

                    }
                }
            }
        }

        //update the gift registry purchased after someone buy gift registry
        update_user_meta($user_id, 'gr_purchased', $current_gr_purchased);
    }

    public static function get_current_gr_purchased($user_id, $wishlist_id){
        $current_gr_purchased = 0;

        if(empty(get_user_meta($user_id,'gr_purchased'))){
            $items = self::get_items_in_giftregistry($wishlist_id);
            foreach ($items as $item){
                $current_gr_purchased += $item['received_qty'];
            }
            add_user_meta($user_id, 'gr_purchased', $current_gr_purchased);
        }else{
            $current_gr_purchased = get_user_meta($user_id,'gr_purchased')[0];
        }
        return $current_gr_purchased;
    }

    public static function send_email_confirm_buy_giftregistry($order_id = 0)
    {
        if(isset($_SESSION['giftregistry_id'])){
            $order = false;

            // Get the order.
            $order_id = apply_filters('woocommerce_thankyou_order_id', absint($order_id));
            $order_key = apply_filters('woocommerce_thankyou_order_key', empty($_GET['key']) ? '' : wc_clean(wp_unslash($_GET['key']))); // WPCS: input var ok, CSRF ok.

            if ($order_id > 0) {
                $order = wc_get_order($order_id);
                if (!$order || $order->get_order_key() !== $order_key) {
                    $order = false;
                }
            }

            // Empty awaiting payment session.
            unset(WC()->session->order_awaiting_payment);

            // Empty current cart.
            wc_empty_cart();

            if ( $order ){
                if ( !$order->has_status( 'failed' ) ){
                    if(isset($_SESSION['message'])){
                        $message = $_SESSION['message'];
                        unset($_SESSION['message']);
                    }
                    $w_id = $_SESSION['buy_for_giftregistry_id'];
                    $wishlist = self::get_wishlist($w_id);
                    /*  send to owner*/
                    $recipients = array();

                    $is_send_owner = get_option('giftregistry_notify_owner');
                    if ($is_send_owner == 'yes') {
                        $recipients['owner'] = $wishlist->registrant_email;
                    }

                    $is_send_registrant = get_option('giftregistry_notify_registrant');
                    if ($is_send_registrant == 'yes') {
                        $recipients['registrant'] = $wishlist->registrant_email;
                    }

                    $is_send_coregistrant = get_option('giftregistry_notify_coregistrant');
                    if ($is_send_coregistrant == 'yes') {
                        $recipients['coregistrant'] = $wishlist->coregistrant_email;
                    }

                    $is_send_admin = get_option('giftregistry_notify_admin');
                    if ($is_send_admin == 'yes') {
                        $recipients['admin'] = get_option('woocommerce_email_from_address');
                    }

                    if (!empty($recipients)) {
                        foreach ($recipients as $name => $recipient) {
                            self::sendNotificationEmail($name, $recipient, $order->get_order_number(), $message);
                        }
                        if(isset($_SESSION['giftregistry_id'])){
                            unset($_SESSION['giftregistry_id']);
                        }
                    }
                }
            }
        }
    }


    public static function get_wishlist($wishlist_id)
    {
        global $wpdb;
        $prefix = $wpdb->prefix;
        $tbl = "{$prefix}magenest_giftregistry_wishlist";

        $sql = $wpdb->prepare("SELECT * FROM $tbl WHERE `id` = %d", $wishlist_id);

        $rows = $wpdb->get_row($sql, OBJECT);

        if ($rows) {

            return $rows;
        }
    }

    public static function sendNotificationEmail($name, $to, $order_id, $message)
    {
        //shared part
        $giftregistry_page_url = get_permalink(get_option('follow_up_emailgiftregistry_page_id'));
        if(isset($_SESSION['giftregistry_id'])){
            $wid = $_SESSION['giftregistry_id'];
        }
        else {
            $wid ='';
        }

        $http_schema = 'http://';
        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']) {
            $http_schema = 'https://';
        }

        $request_link = $http_schema . $_SERVER ["HTTP_HOST"] . $_SERVER ["REQUEST_URI"];
        $giftregistry_page_url = $giftregistry_page_url . '?giftregistry_id=' . $wid;
        $order = wc_get_order($order_id);
        $billing_email = $order->get_billing_email();
        $wc_email = new WC_Email();

        $billing_lastname = $order->get_billing_last_name();
        $billing_firstname = $order->get_billing_first_name();
        $headers = array();
        $headers [] = "Content-Type: text/html";
        add_filter('wp_mail_from_name', array($wc_email, 'get_from_name'));
        $headers [] = 'From: ' . get_option('woocommerce_email_from_name') . '<' . get_option('woocommerce_email_from_address') . '>';

        if ($name == 'owner') {
            $subject = get_option('giftregistry_notify_email_subject_owner');
            $content = get_option('giftregistry_notify_email_content_owner');
        } else if ($name == 'registrant') {
            $subject = get_option('giftregistry_notify_email_subject_registrant');
            $content = get_option('giftregistry_notify_email_content_registrant');
        } else if ($name == 'coregistrant') {
            $subject = get_option('giftregistry_notify_email_subject_coregistrant');
            $content = get_option('giftregistry_notify_email_content_coregistrant');
        } else {
            $subject = get_option('giftregistry_notify_email_subject_admin');
            $content = get_option('giftregistry_notify_email_content_admin');
        }
        $replaces = array(
            '{{buyer_name}}' => $billing_firstname . ' ' . $billing_lastname,
            '{{store_url}}' => get_permalink(wc_get_page_id('shop')),
            '{{store_name}}' => get_bloginfo('name'),
            '{{order_number}}' => $order->get_order_number(),
            '{{order_url}}' => $order->get_view_order_url(),
            '{{order_items}}' => self::get_order_items($order->get_id()),
            '{{giftregistry_url}}' => $giftregistry_page_url,
            '{{break_line}}' => '<br/>'
        );

        $content = strtr($content, $replaces);
        if (empty($message)) {
            $content .= '<br/><h2>'.__("Thank you!",GIFTREGISTRY_TEXT_DOMAIN).'</h2>';
        } else {
            $content .= '<br/><h1>'.__("Message your friend: ",GIFTREGISTRY_TEXT_DOMAIN).'</h1><br/>' . $message . '<br/><h2>'.__("Thank you!",GIFTREGISTRY_TEXT_DOMAIN).'</h2>';
        }

        add_filter('wp_mail_content_type', array('Magenest_Giftregistry_Model', 'set_html_content_type'));

        wp_mail($to, $subject, $content, $headers);

        remove_filter('wp_mail_content_type', array('Magenest_Giftregistry_Model', 'set_html_content_type'));
    }

    public static function get_order_items($orderId)
    {
        ob_start();

        $template_path = GIFTREGISTRY_PATH . 'template/email/';
        $default_path = GIFTREGISTRY_PATH . 'template/email/';

        $order = new WC_Order ($orderId);

        wc_get_template('order-items.php', array(
            'order' => $order,
            'order_id' => $orderId,
        ), $template_path, $default_path
        );
        return ob_get_clean();
    }

    /**
     * set html content type for email
     */
    public static function set_html_content_type()
    {
        return 'text/html';
    }

    public static function get_wishlist_item_by_product_id($product_id)
    {
        $wishlist_id = self::get_wishlist_id();
        global $wpdb;
        $prefix = $wpdb->prefix;
        $tbl = "{$prefix}magenest_giftregistry_item";

        $sql = $wpdb->prepare("SELECT * FROM $tbl WHERE `wishlist_id` = %d and `product_id` = %d ", $wishlist_id, $product_id);

        $rows = $wpdb->get_row($sql, ARRAY_A);

        if ($rows) {
            return $rows;
        }
    }

    /* */
    public static function get_wishlist_item_variable_by_product_id($product_id)
    {
        $wishlist_id = self::get_wishlist_id();
        global $wpdb;
        $prefix = $wpdb->prefix;
        $tbl = "{$prefix}magenest_giftregistry_item";

        $sql = $wpdb->prepare("SELECT * FROM $tbl WHERE `wishlist_id` = %d and `variation` = %d ", $wishlist_id, $product_id);

        $rows = $wpdb->get_results($sql, ARRAY_A);

        if ($rows) {
            return $rows;
        }
    }

    public static function get_item_giftregistry_by_item_id($product_id)
    {
        global $wpdb;
        $prefix = $wpdb->prefix;
        $tbl = "{$prefix}magenest_giftregistry_item";

//        $user_id = get_current_user_id();
//        $sql = 'SELECT * FROM '.$tbl.' WHERE wishlist_id='.$wishlist_id;//' ORDER BY priority ASC

        $sql = $wpdb->prepare("SELECT * FROM $tbl WHERE `id` = %d", $product_id);
        $rows = $wpdb->get_row($sql, ARRAY_A);

        if ($rows) {
            return $rows;
        }
    }

    /*query filter by: price, desired qty, prioriry*/
    public static function filter_items_in_giftregistry($wishlist_id, $filter, $level)
    {
        global $wpdb;
        $prefix = $wpdb->prefix;
        $tbl = "{$prefix}magenest_giftregistry_item";
        $sql = "";

        switch ($filter){
            case 'priority':
                $sql = $wpdb->prepare("SELECT * FROM $tbl WHERE `wishlist_id` = %d ORDER BY $tbl.priority ", $wishlist_id);
                switch ($level){
                    case 'high':
                        $sql .= "ASC";
                        break;
                    case 'low':
                        $sql .= "DESC";
                        break;
                }
                break;
            case 'desired_quantity':
                $sql = $wpdb->prepare("SELECT * FROM $tbl WHERE `wishlist_id` = %d ORDER BY ( $tbl.quantity - $tbl.received_qty ) ", $wishlist_id);
                switch ($level){
                    case 'high':
                        $sql .= "DESC";
                        break;
                    case 'low':
                        $sql .= "ASC";
                        break;
                }
                break;
            case 'price':
                $sql = $wpdb->prepare("SELECT * FROM $tbl as gr_item LEFT JOIN {$prefix}postmeta as postmeta
                ON gr_item.product_id = postmeta.post_id WHERE 
                gr_item.wishlist_id = %d AND postmeta.meta_key = '_price' ORDER BY postmeta.meta_value ", $wishlist_id);
                switch ($level){
                    case 'high':
                        $sql .= "DESC";
                        break;
                    case 'low':
                        $sql .= "ASC";
                        break;
                }
                break;
            default :
                $sql = $wpdb->prepare("SELECT * FROM $tbl WHERE `wishlist_id` = %d ", $wishlist_id);
                break;
        }

        $rows = $wpdb->get_results($sql, ARRAY_A);

        if ($rows) {
            return $rows;
        }
    }

    public static function get_wishlist_id_by_refund_order($order_id){
        global $wpdb;
        $prefix = $wpdb->prefix;
        $tbl = "{$prefix}magenest_giftregistry_item";

        $sql = $wpdb->prepare("SELECT * FROM $tbl WHERE %s",true);
        $items = $wpdb->get_results($sql, ARRAY_A);

        if(!empty($items)){
            foreach ($items as $item){
                if(in_array($order_id, explode(';', $item['received_order']))){
                    return $item['wishlist_id'];
                }
            }
        }
    }

    public static function get_item_by_wishlist_id_and_product_id($wishlist_id, $product_id){
        global $wpdb;
        $prefix = $wpdb->prefix;
        $tbl = "{$prefix}magenest_giftregistry_item";

        $sql = $wpdb->prepare("SELECT * FROM $tbl WHERE wishlist_id = %d AND product_id = %d", $wishlist_id, $product_id);
        $rows = $wpdb->get_row($sql, ARRAY_A);

        if ($rows) {
            return $rows;
        }
    }

    public static function get_user_id_by_wishlist_id($wishlist_id){
        global $wpdb;
        $prefix = $wpdb->prefix;
        $tbl = "{$prefix}magenest_giftregistry_wishlist";

        $sql = $wpdb->prepare("SELECT * FROM $tbl WHERE id = %d", $wishlist_id);
        $rows = $wpdb->get_row($sql, ARRAY_A);

        if ($rows) {
            return $rows['user_id'];
        }
    }

    public static function update_column_in_giftregistry_item_table($column, $value, $product_id, $wishlist_id){
        global $wpdb;
        $prefix = $wpdb->prefix;
        $tbl = "{$prefix}magenest_giftregistry_item";
        $wpdb->update($tbl, array($column => $value), array('product_id' => $product_id , 'wishlist_id' => $wishlist_id));
    }
}