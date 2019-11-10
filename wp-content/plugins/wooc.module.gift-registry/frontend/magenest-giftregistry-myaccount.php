<?php
if (!defined('ABSPATH'))
    exit; // Exit if accessed directly
class Magenest_Giftregistry_MyAccount
{
    public function __construct()
    {
//        add_action('woocommerce_after_add_to_cart_button', array($this,'show_gift_registry_link'));

        //account page
//        add_action('woocommerce_after_my_account', array($this,'show_my_registry'));
    }

    public function show_overview_statistics()
    {
        ob_start();

        $template_path = GIFTREGISTRY_PATH . 'template/account/';
        $default_path = GIFTREGISTRY_PATH . 'template/account/';

        wc_get_template('overview-statistics.php', array(), $template_path, $default_path);
        echo ob_get_clean();
    }

    public function show_gift_registry_link()
    {
        $product_id = get_the_ID();
        $item = Magenest_Giftregistry_Model::get_wishlist_item_by_product_id($product_id);
        if (get_option('giftregistry_enable_permission') == 'yes' || is_user_logged_in()) {
            wp_enqueue_script('send_ajax_giftregistry');
            wp_enqueue_script('magenest-giftregistry-myaccount');
            global $product;
            if ($product->is_type('simple')) {
                wp_enqueue_style('button-in-simple-product');
                if (empty($item)) {
                    ?>
                    <div class="button-add-giftregistry">
                        <div id='loader'>
                            <img src="<?=GIFTREGISTRY_URL?>assets/loading.gif" id='ajax-loader' alt="Loading...">
                        </div>
                        <input type="hidden" name="action" value="add_giftregifttry_to_list"/>
                        <input type="hidden" name="message" value="success" id="message-gift-registry"/>
                        <input type="hidden" name="add-registry" id="add-registry"/>
                        <input type="hidden" name="quantity" id="add-registry-qty" value="1"/>
                        <input type="hidden" name="add-to-giftregistry" id="add-to-giftregistry"
                               value="<?= $product_id; ?>"/>
                        <input type="hidden" name="giftregistry_variation_id" id="giftregistry_variation_id" value="0"
                               style="display: none;"/>
                        <button class="button alt" id="add-to-giftregistry-list">
                            <?php echo __('Add to gift registry', GIFTREGISTRY_TEXT_DOMAIN); ?>
                        </button>
                    </div>
                    <?php
                } else {
                    ?>
                    <style>
                        .button-add-giftregistry {
                            display: none !important;
                        }

                        .giftregistry-detail {
                            display: inline-block !important;
                        }
                    </style>
                    <div class="giftregistry-detail">
                        <div class="wgr-priority">
                            <img src="<?= $item['priority'] == '1' ? GIFTREGISTRY_URL . 'assets/img/high-priority.png' :
                                GIFTREGISTRY_URL . 'assets/img/low-priority.png' ?>"
                                 onclick="priorityItProduct(this)"
                                 id="priority_<?= $product_id ?>" name="<?= $product_id ?>"
                                 url="<?= admin_url('admin-ajax.php') ?>">
                            <p style="margin-right: 5%;float: right"
                               id="product-priority-<?= $product_id ?>"><?= __('Item priority: ', GIFTREGISTRY_TEXT_DOMAIN) ?></p>
                            <!--                <span class="tooltiptext" id="tooltiptext-high---><?//= $product_id
                            ?><!--">Change priority</span>-->
                        </div>
                        <div class="wgr-remove-product">
                            <button class="button button-remove-giftregistry" id="remove_product_<?= $product_id ?>"
                                    url="<?= admin_url('admin-ajax.php') ?>" name="<?= $product_id ?>"
                                    onclick="removeItProduct(this)">
                                <?php echo __('Remove from gift registry', GIFTREGISTRY_TEXT_DOMAIN); ?>
                            </button>
                        </div>
                    </div>
                    <?php
                }
            } elseif ($product->is_type('variable')) {
                wp_enqueue_style('icon-loading');
                wp_enqueue_style('button-in-variable-product');
                $items = Magenest_Giftregistry_Model::get_wishlist_item_variable_by_product_id($product_id);
                $array_product_id = array();
                $array_product_priority = array();
                for ($i = 0; $i < count($items); $i++) {
                    $item = $items[$i];
                    array_push($array_product_id, $item['variation_id']);
                    array_push($array_product_priority, $item['priority']);
                }
                $product_variable = array('array_product_id' => $array_product_id, 'array_product_priority' => $array_product_priority, 'url' => GIFTREGISTRY_URL,
                    'array_product_variation' => $product_id);

                if (isset($product_variable)) {
                    wp_localize_script('send_ajax_giftregistry', 'product_variable', $product_variable);
                }
                ?>
                <div id='loader' style="display: none;">
                    <img src="<?=GIFTREGISTRY_URL?>assets/loading.gif" id='ajax-loader' alt="Loading...">
                </div>
                <div class="button-add-giftregistry">
                    <input type="hidden" name="action" value="add_giftregifttry_to_list"/>
                    <input type="hidden" name="message" value="success" id="message-gift-registry"/>
                    <input type="hidden" name="add-registry" id="add-registry"/>
                    <input type="hidden" name="quantity" id="add-registry-qty" value="1"/>
                    <input type="hidden" name="add-to-giftregistry" id="add-to-giftregistry"
                           value="<?= $product_id; ?>"/>
                    <input type="hidden" name="giftregistry_variation_id" id="giftregistry_variation_id" value="0"
                           style="display: none;"/>
                    <button class="button alt" id="add-to-giftregistry-list">
                        <?php echo __('Add to gift registry', GIFTREGISTRY_TEXT_DOMAIN); ?>
                    </button>
                </div>
                <div class="giftregistry-detail">
                    <div class="wgr-priority">
                        <img src="<?= $item['priority'] == '1' ? GIFTREGISTRY_URL . 'assets/img/high-priority.png' :
                            GIFTREGISTRY_URL . 'assets/img/low-priority.png' ?>"
                             onclick="priorityItProductVariable(this)"
                             id="priority_<?= $product_id ?>" name="<?= $product_id ?>"
                             url="<?= admin_url('admin-ajax.php') ?>">
                        <p style="margin-right: 5%;float: right"
                           id="product-priority-<?= $product_id ?>"><?= __('Item priority: ', GIFTREGISTRY_TEXT_DOMAIN) ?></p>
                        <!--                <span class="tooltiptext" id="tooltiptext-high---><?//= $product_id
                        ?><!--">Change priority</span>-->
                    </div>
                    <div class="wgr-remove-product">
                        <button class="button button-remove-giftregistry" id="remove_product_<?= $product_id ?>"
                                url="<?= admin_url('admin-ajax.php') ?>" name="<?= $product_id ?>"
                                onclick="removeItProductVariable(this)">
                            <?php echo __('Remove from gift registry', GIFTREGISTRY_TEXT_DOMAIN); ?>
                        </button>
                    </div>
                </div>
                <?php
            }
        }
    }

    public function show_my_registry()
    {
        echo $this->show_create_giftregistry_part();

        $wl_items = Magenest_Giftregistry_Model::get_wishlist_items_for_current_user();

        echo $this->show_my_giftregistry_part($wl_items);

        //shared part
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
        echo $this->share_links($giftregistry_page_url);
    }

    /**
     * @return string
     */
    public function show_create_giftregistry_part()
    {

        $wid = Magenest_Giftregistry_Model::get_wishlist_id();

        //if (is_numeric($wid))
        ob_start();

        $template_path = GIFTREGISTRY_PATH . 'template/account/';
        $default_path = GIFTREGISTRY_PATH . 'template/account/';


        wc_get_template('add-giftregistry.php', array(
            'wid' => $wid,
            'order_id' => '2',
        ), $template_path, $default_path
        );
        return ob_get_clean();
    }

    /**
     *
     * @param unknown $items
     * @return stringOnly required for left/right tabs -->
     */
    public function show_my_giftregistry_part($items)
    {
        $wid = Magenest_Giftregistry_Model::get_wishlist_id();

        ob_start();

        $template_path = GIFTREGISTRY_PATH . 'template/account/';
        $default_path = GIFTREGISTRY_PATH . 'template/account/';


        wc_get_template('my-giftregistry.php', array(
            'items' => $items,
            'wid' => $wid
        ), $template_path, $default_path
        );
        return ob_get_clean();
    }

    public function share_links($url)
    {
        ob_start();

        $template_path = GIFTREGISTRY_PATH . 'template/account/';
        $default_path = GIFTREGISTRY_PATH . 'template/account/';

        wc_get_template('giftregistry-share.php', array(
            'url' => $url,
        ), $template_path, $default_path
        );
        return ob_get_clean();
    }
}

return new Magenest_Giftregistry_MyAccount();
?>