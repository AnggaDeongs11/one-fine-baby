<?php
if (!defined('ABSPATH'))
    exit; // Exit if accessed directly
class Magenest_Giftregistry_Frontend
{

    public function __construct()
    {
        add_action('woocommerce_before_cart', array($this, 'gift_registry'));
        add_filter('woocommerce_add_cart_item_data', array($this, 'set_gift_registry_cart'));
        add_action('woocommerce_before_checkout_shipping_form', array($this, 'set_shipping_address_for_giftregistry'));
        add_action('woocommerce_before_order_notes', array($this, 'set_message_field'));
        add_action('woocommerce_thankyou', array($this, 'after_checkout_form'),10);
        add_filter( 'woocommerce_get_item_data', array( &$this, 'get_item_data' ), 50, 2 );
        add_action('woocommerce_order_status_refunded', array($this, 'refunded_giftregistry'),10);
        add_filter('woocommerce_update_cart_validation', array($this, 'check_validation_quantity_before_update'));
        add_filter('woocommerce_add_to_cart_validation', array($this, 'check_validation_quantity_before_add'));
    }

    public function set_shipping_address_for_giftregistry()
    {
        if (isset ($_SESSION ['buy_for_giftregistry_id'])) {

            echo "<strong><span id='note_shipping_giftregistry' style='color: #fb0000'>" . __('All items is shipped to gift registry address.', GIFTREGISTRY_TEXT_DOMAIN) . " </span></strong>";
            $w_id = $_SESSION ['buy_for_giftregistry_id'];
            $wishlist = Magenest_Giftregistry_Model::get_wishlist($w_id);
            $customer_id = $wishlist->user_id;

            $name = 'shipping';
            $address = array(
                'first_name' => $wishlist->shipping_first_name,
                'last_name' => $wishlist->shipping_last_name,
                'company' => $wishlist->shipping_company,
                'address' => $wishlist->shipping_address,
                'city' => $wishlist->shipping_city,
                'postcode' => $wishlist->shipping_postcode,
                'country' => $wishlist->shipping_country
            );

            if(get_option('giftregistry_shipping_restrict') == 'yes'){
                ?>
                <script type="text/javascript">
                    jQuery(document).ready(function () {
                            jQuery('#ship-to-different-address-checkbox').prop('checked', true);
                            jQuery('#shipping_first_name').val('<?php echo $address['first_name'] ?>');
                            jQuery('#shipping_last_name').val('<?php echo $address['last_name'] ?>');
                            jQuery('#shipping_company').val('<?php echo $address['company']  ?>');

                            jQuery('#shipping_address_1').val('<?php echo $address['address'] ?>');
                            jQuery('#shipping_city').val('<?php echo $address['city']  ?>');
                            jQuery('#shipping_postcode').val('<?php echo $address['postcode']  ?>');
                            jQuery('#shipping_country').val('<?php echo $address['country']  ?>');

                            //shipping_state
                        }
                    );
                </script>
                <?php
            }
        }
    }

    public function gift_registry()
    {
        $http_schema = 'http://';
        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']) {
            $http_schema = 'https://';
        }

        $request_link = $http_schema . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];


        //
        if (isset($_SESSION['buy_for_giftregistry_id'])) {

            $wishlist_id = $_SESSION['buy_for_giftregistry_id'];
            $wishlist = Magenest_Giftregistry_Model::get_wishlist($wishlist_id);

            $registrantname = $wishlist->registrant_firstname . ' ' . $wishlist->registrant_lastname;
            $coregistrantname = $wishlist->coregistrant_firstname . ' ' . $wishlist->coregistrant_lastname;

            $registry_name = $registrantname;

            $giftregistry_page = get_permalink(get_option('follow_up_emailgiftregistry_page_id'));
            //
//            if (strpos($request_link, '?') > 0) {
//                $giftregistry_link = $giftregistry_page . '&giftregistry_id=' . $wishlist_id;
//                $giftregistry_end_purchase = $giftregistry_page . '&end_buy_giftregistry=' . $wishlist_id;
//            } else {
                $giftregistry_link = $giftregistry_page . '?giftregistry_id=' . $wishlist_id;
                $giftregistry_end_purchase = $giftregistry_page . '?end_buy_giftregistry=' . $wishlist_id;
//            }
            //


            if ($coregistrantname != ' ')
                $registry_name .= __(' and', GIFTREGISTRY_TEXT_DOMAIN) . " " . $coregistrantname;
            echo "<span id='giftregistry-cart' > <a href={$giftregistry_link}>" . __('Find more gifts for ', GIFTREGISTRY_TEXT_DOMAIN) . $registry_name . "</a></span><br/>";
            echo "<span id='giftregistry-cart' > <a href={$giftregistry_end_purchase}>" . __('Change gifts to regular purchase ', GIFTREGISTRY_TEXT_DOMAIN) ."</a></span>";
        }
    }

    public function set_gift_registry_cart($cart_item_meta)
    {
//        if (isset($_REQUEST['buy_for_giftregistry_id']) && isset($_REQUEST['add-to-cart'])) {
//            error_log('buy gift 1');
//            $_SESSION['message'] = $_REQUEST['message'];
//            $_SESSION['buy_for_giftregistry_id'] = $_REQUEST['buy_for_giftregistry_id'];
//            wc_add_notice(__('You have add items for gift regisry', GIFTREGISTRY_TEXT_DOMAIN), 'success');
//
//        }
        if (isset($_REQUEST['buy_for_giftregistry_id']) && isset($_REQUEST['add-to-cart'])) {
            $cart_item_meta['gift_registry']['buy_for_giftregistry_id'] = array(
                'name' => esc_html(__('Gift regitry ID', GIFTREGISTRY_TEXT_DOMAIN)),
                'value' => esc_html($_REQUEST['buy_for_giftregistry_id'])
            );
        }
        return $cart_item_meta;
    }

    public function after_checkout_form(){
        if(isset($_SESSION['buy_for_giftregistry_id'])){
            unset($_SESSION['buy_for_giftregistry_id']);
        }
        if(isset($_SESSION['messages'])){
            unset($_SESSION['messages']);
        }
    }

    public function get_item_data( $other_data, $cart_item ) {
        if(isset($_SESSION['buy_for_giftregistry_id'])){
            $id = $_SESSION['buy_for_giftregistry_id'];
            $wishlist = Magenest_Giftregistry_Model::get_wishlist($id);
            $url = get_permalink(get_option('follow_up_emailgiftregistry_page_id')). '?giftregistry_id=' . $id . '#view';
            $other_data[] = array(
                'name'    => 'Gift registry\'s title',
                'value'   => html_entity_decode('<a href="'.$url.'">'.$wishlist->title.'</a>')
            );
            $other_data[] = array(
                'name'    => __('Registrant\'s name',GIFTREGISTRY_TEXT_DOMAIN),
                'value'   =>  $wishlist->registrant_firstname . ' ' . $wishlist->registrant_lastname
            );
            if(isset($wishlist->coregistrant_firstname) || isset($wishlist->coregistrant_lastname)){
                $other_data[] = array(
                    'name'    => __('Coregistrant\'s name',GIFTREGISTRY_TEXT_DOMAIN),
                    'value'   => $wishlist->coregistrant_firstname . ' ' . $wishlist->coregistrant_lastname
                );
            }
        }
        return $other_data;
    }

    public function set_message_field(){
        if (isset ($_SESSION ['buy_for_giftregistry_id'])) {
            ?>
            <div id="message_GR">
                <strong style="color: #fb0000;">
                <label style="font-weight:600 !important"><?= __('Message ') ?>
                    <span class="optional"><?= __('(optional)', GIFTREGISTRY_TEXT_DOMAIN) ?></span>
                </label>
                </strong>
                <textarea name="message"
                          id="message"
                          placeholder="<?= __('Message to gift registry\'s owner.', GIFTREGISTRY_TEXT_DOMAIN) ?>"
                          rows="3" cols="5"></textarea>
            </div>
            <?php
        }
    }

    public static function refunded_giftregistry($order_id){
        $order = new WC_Order($order_id);
        $refund_items = $order->get_items();
        $wishlist_id = Magenest_Giftregistry_Model::get_wishlist_id_by_refund_order($order_id);
        $user_id = Magenest_Giftregistry_Model::get_user_id_by_wishlist_id($wishlist_id);
        $refund_qty = 0;

        foreach ($refund_items as $refund_item){
            if($refund_item['variation_id'] != 0){
                $gr_item = Magenest_Giftregistry_Model::get_item_by_wishlist_id_and_product_id($wishlist_id, $refund_item['variation_id']);
                if($gr_item){
                    Magenest_Giftregistry_Model::update_column_in_giftregistry_item_table('received_qty', $gr_item['received_qty'] - $refund_item['quantity'], $refund_item['variation_id'], $wishlist_id);
                    $received_refund = $received_orders = explode(';', $gr_item['received_order']);
                    foreach ($received_orders as $key => $received_order){
                        if($received_order == $order_id){
                            unset($received_refund[$key]);
                        }
                    }
                    Magenest_Giftregistry_Model::update_column_in_giftregistry_item_table('received_order', implode(';', $received_refund), $refund_item['variation_id'], $wishlist_id);
                }
            }else{
                $gr_item = Magenest_Giftregistry_Model::get_item_by_wishlist_id_and_product_id($wishlist_id, $refund_item['product_id']);
                if($gr_item){
                    Magenest_Giftregistry_Model::update_column_in_giftregistry_item_table('received_qty', $gr_item['received_qty'] - $refund_item['quantity'], $refund_item['product_id'], $wishlist_id);
                    $received_refund = $received_orders = explode(';', $gr_item['received_order']);
                    foreach ($received_orders as $key => $received_order){
                        if($received_order == $order_id){
                            unset($received_refund[$key]);
                        }
                    }

                    Magenest_Giftregistry_Model::update_column_in_giftregistry_item_table('received_order', implode(';', $received_refund), $refund_item['product_id'], $wishlist_id);
                }
            }

            if($gr_item){
                $refund_qty += $refund_item['quantity'];
            }
        }

        // change the purchased after refund
        if(!empty(get_user_meta($user_id, 'gr_purchased'))){
            update_user_meta($user_id, 'gr_purchased', get_user_meta($user_id, 'gr_purchased')[0] - $refund_qty);
        }
    }

    public function check_validation_quantity_before_update()
    {
        if (isset($_SESSION['buy_for_giftregistry_id'])) {
            $wishlist_id = $_SESSION['buy_for_giftregistry_id'];
            $items = Magenest_Giftregistry_Model::get_items_in_giftregistry($wishlist_id);
            $wishlist = Magenest_Giftregistry_Model::get_wishlist($wishlist_id);
            if (isset($_SESSION['buy_for_giftregistry_id']) && !$wishlist->option_quantity) {
                $cart_totals  = isset( $_POST['cart'] ) ? wp_unslash( $_POST['cart'] ) : '';
                foreach (WC()->cart->get_cart() as $cart_item_key => $values) {
                    if ( ! isset( $cart_totals[ $cart_item_key ] ) || ! isset( $cart_totals[ $cart_item_key ]['qty'] ) ) {
                        continue;
                    }
                    $quantity = apply_filters( 'woocommerce_stock_amount_cart_item', wc_stock_amount( preg_replace( '/[^0-9\.]/', '', $cart_totals[ $cart_item_key ]['qty'] ) ), $cart_item_key );
                    foreach ($items as $item) {
                        if($values['product_id'] == $item['product_id']){
                            if($item['quantity'] - $item['received_qty'] < $quantity ){
                                wc_clear_notices();
                                wc_add_notice( __( "Your quantity is greater than gift registry's desired quantity", GIFTREGISTRY_TEXT_DOMAIN ), 'error' );
                                return false;
                            }
                        }
                    }
                }
            }
            return true;
        }else{
            return true;
        }
    }

    public static function check_validation_quantity_before_add()
    {
        if (isset($_SESSION['buy_for_giftregistry_id'])) {
            $wishlist_id = $_SESSION['buy_for_giftregistry_id'];
            $items = Magenest_Giftregistry_Model::get_items_in_giftregistry($wishlist_id);
            $wishlist = Magenest_Giftregistry_Model::get_wishlist($wishlist_id);
            if(isset($_REQUEST['add-to-cart'])){
                $product_id = apply_filters('woocommerce_add_to_cart_product_id', absint($_REQUEST['add-to-cart']));
            }else{
                return true;
            }
            $variation_id = empty($_REQUEST['variation_id']) ? '' : absint(wp_unslash($_REQUEST['variation_id']));
            $adding_to_cart = wc_get_product($product_id);
            if (!$adding_to_cart) {
                return false;
            }
            if ($adding_to_cart->is_type('variation')) {
                $product_id = $adding_to_cart->get_parent_id();
            }
            if (isset($_SESSION['buy_for_giftregistry_id']) && !$wishlist->option_quantity) {
                $quantity_add = empty($_REQUEST['quantity']) ? 1 : wc_stock_amount($_REQUEST['quantity']);
                foreach (WC()->cart->get_cart() as $cart_item_key => $values) {
                    if ($product_id == $values['product_id'] && $values['variation_id'] == $variation_id) {
                        foreach ($items as $item) {
                            if ($item['product_id'] == $product_id) {
                                if ($item['quantity'] - $item['received_qty'] - $values['quantity'] < $quantity_add) {
                                    $_SESSION['validation_quantity_before_add'] = false;
                                    return false;
                                }
                            }
                            if ($item['variation_id'] == $variation_id) {
                                if ($item['quantity'] - $item['received_qty'] - $values['quantity'] < $quantity_add) {
                                    $_SESSION['validation_quantity_before_add'] = false;
                                    return false;
                                }
                            }
                        }
                    }
                }
            }
            return true;
        }else{
            return true;
        }
    }
}

return new Magenest_Giftregistry_Frontend();