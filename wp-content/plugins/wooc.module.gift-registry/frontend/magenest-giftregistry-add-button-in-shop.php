<?php
/**
 * Created by PhpStorm.
 * User: mdq
 * Date: 13/09/2018
 * Time: 13:10
 */
if (!defined('ABSPATH'))
    exit; // Exit if accessed directly
?>
    <script>

    </script>
<?php

class Magenest_Add_Button
{
//    public function __construct()
//    {
//        add_action('woocommerce_shop_loop',array($this,'add_giftregistry_button'));
//    }

    public function add_giftregistry_button()
    {
        $product_id = get_the_ID();
        /*check is simple product, true => add button*/
        global $product;
        if ($product->is_type('simple')) {
            if (is_user_logged_in()) {
                wp_enqueue_style('button-GR-in-shop-page');
                wp_enqueue_script('ajax-button-shop-page');
                $wl_items = Magenest_Giftregistry_Model::get_wishlist_items_for_current_user();
                $wid = Magenest_Giftregistry_Model::get_wishlist_id();
                $wishlist = Magenest_Giftregistry_Model::get_wishlist($wid);

                if (empty($wishlist->shipping_first_name) || empty($wishlist->shipping_last_name) || empty($wishlist->shipping_country)
                    || empty($wishlist->shipping_address) || empty($wishlist->shipping_postcode) || empty($wishlist->shipping_city)) {
                    $addr = true;
                }else{
                    $addr = false;
                }
                if (get_option('giftregistry_shipping_restrict') == 'no') {
                    $show = true;
                } else {
                    if (isset($addr) && !$addr) {
                        $show = true;
                    } else {
                        $show = false;
                    }
                }

                $r_id = Magenest_Giftregistry_Form_Handler::get_giftregistry_id();

                // check whether customer create gift registry
                if ($r_id) {
                    if($show){
                        if (!empty($wl_items)) {
                            $is_add_to_giftregistry_list = false;
                            foreach ($wl_items as $item) {
                                if ($item['product_id'] == $product_id) {
                                    ?>
                                    <style>
                                        #add_product_<?= $product_id ?> {
                                            display: none !important;
                                        }

                                        #set-giftregistry-detail-<?= $product_id ?> {
                                            display: inline-block !important;
                                        }
                                    </style>
                                    <div class="wgr-add-product-shop-page">
                                        <div class="add_button_loading">
                                            <button class="button button-add-giftregistry-shop-page"
                                                    id="add_product_<?= $product_id ?>"
                                                    url="<?= admin_url('admin-ajax.php') ?>" name="<?= $product_id ?>"
                                                    onclick="addit(this)">
                                                <span id="text_<?= $product_id ?>"><?php echo __('Add to gift registry', GIFTREGISTRY_TEXT_DOMAIN); ?></span>
                                            </button>
                                            <div class="add_loader" id="add_loader_<?= $product_id ?>" style="display: none;">
                                                <img src="<?= GIFTREGISTRY_URL ?>assets/loading.gif" id='add_loader'
                                                     alt="Loading...">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="set-giftregistry-detail-shop-page"
                                         id="set-giftregistry-detail-<?= $product_id ?>">
                                        <div class="wgr-remove-product-shop-page">
                                            <button class="button button-remove-giftregistry-shop-page"
                                                    id="remove_product_<?= $product_id ?>"
                                                    url="<?= admin_url('admin-ajax.php') ?>" name="<?= $product_id ?>"
                                                    onclick="removeit(this)">
                                                <span><?php echo __('Remove from <br> gift registry', GIFTREGISTRY_TEXT_DOMAIN); ?></span>
                                            </button>
                                        </div>
                                        <div class="wgr-priority-shop-page">
                                            <span><strong><?= __('Priority:', GIFTREGISTRY_TEXT_DOMAIN) ?></strong></span>
                                            <img src="<?= $item['priority'] == '1' ? GIFTREGISTRY_URL . 'assets/img/high-priority.png' : GIFTREGISTRY_URL . 'assets/img/low-priority.png' ?>"
                                                 title="<?= __('Change priority', GIFTREGISTRY_TEXT_DOMAIN) ?>"
                                                 onclick="priorityit(this)"
                                                 id="priority_<?= $product_id ?>" name="<?= $product_id ?>"
                                                 url="<?= admin_url('admin-ajax.php') ?>">
                                        </div>
                                    </div>
                                    <?php
                                    $is_add_to_giftregistry_list = true;
                                    break;
                                }
                            }
                            if ($is_add_to_giftregistry_list == false) {
                                ?>
                                <div class="wgr-add-product-shop-page">
                                    <div class="add_button_loading">
                                        <button class="button button-add-giftregistry-shop-page"
                                                id="add_product_<?= $product_id ?>"
                                                url="<?= admin_url('admin-ajax.php') ?>" name="<?= $product_id ?>"
                                                onclick="addit(this)">
                                            <span id="text_<?= $product_id ?>"><?php echo __('Add to gift registry', GIFTREGISTRY_TEXT_DOMAIN); ?></span>
                                        </button>
                                        <div class="add_loader" id="add_loader_<?= $product_id ?>" style="display: none;">
                                            <img src="<?= GIFTREGISTRY_URL ?>assets/loading.gif" id='add_loader'
                                                 alt="Loading...">
                                        </div>
                                    </div>
                                </div>
                                <div class="set-giftregistry-detail-shop-page" id="set-giftregistry-detail-<?= $product_id ?>">
                                    <div class="wgr-remove-product-shop-page">
                                        <button class="button button-remove-giftregistry-shop-page"
                                                id="remove_product_<?= $product_id ?>"
                                                url="<?= admin_url('admin-ajax.php') ?>" name="<?= $product_id ?>"
                                                onclick="removeit(this)">
                                            <span><?php echo __('Remove from gift registry', GIFTREGISTRY_TEXT_DOMAIN); ?></span>
                                        </button>
                                    </div>
                                    <div class="wgr-priority-shop-page">
                                        <span><strong><?= __('Priority:', GIFTREGISTRY_TEXT_DOMAIN) ?></strong></span>
                                        <img src="<?= GIFTREGISTRY_URL . 'assets/img/low-priority.png' ?>"
                                             title="<?= __('Change priority', GIFTREGISTRY_TEXT_DOMAIN) ?>"
                                             onclick="priorityit(this)"
                                             id="priority_<?= $product_id ?>" name="<?= $product_id ?>"
                                             url="<?= admin_url('admin-ajax.php') ?>">
                                    </div>
                                </div>
                                <?php
                            }
                        } else {
                            ?>
                            <div class="wgr-add-product-shop-page">
                                <div class="add_button_loading">
                                    <button class="button button-add-giftregistry-shop-page" id="add_product_<?= $product_id ?>"
                                            url="<?= admin_url('admin-ajax.php') ?>" name="<?= $product_id ?>"
                                            onclick="addit(this)">
                                        <span id="text_<?= $product_id ?>"><?php echo __('Add to gift registry', GIFTREGISTRY_TEXT_DOMAIN); ?></span>
                                    </button>
                                    <div class="add_loader" id="add_loader_<?= $product_id ?>" style="display: none;">
                                        <img src="<?= GIFTREGISTRY_URL ?>assets/loading.gif" id='add_loader' alt="Loading...">
                                    </div>
                                </div>
                            </div>
                            <div class="set-giftregistry-detail-shop-page" id="set-giftregistry-detail-<?= $product_id ?>">
                                <div class="wgr-remove-product-shop-page">
                                    <button class="button button-remove-giftregistry-shop-page"
                                            id="remove_product_<?= $product_id ?>"
                                            url="<?= admin_url('admin-ajax.php') ?>" name="<?= $product_id ?>"
                                            onclick="removeit(this)">
                                        <span><?php echo __('Remove from gift registry', GIFTREGISTRY_TEXT_DOMAIN); ?></span>
                                    </button>
                                </div>
                                <div class="wgr-priority-shop-page">
                                    <span><strong><?= __('Priority:', GIFTREGISTRY_TEXT_DOMAIN) ?></strong></span>
                                    <img src="<?= GIFTREGISTRY_URL . 'assets/img/low-priority.png' ?>"
                                         title="<?= __('Change priority', GIFTREGISTRY_TEXT_DOMAIN) ?>"
                                         onclick="priorityit(this)"
                                         id="priority_<?= $product_id ?>" name="<?= $product_id ?>"
                                         url="<?= admin_url('admin-ajax.php') ?>">
                                </div>
                            </div>
                            <?php
                        }
                    }else{
                        ?>
                        <div class="wgr-add-product-shop-page">
                            <div class="add_button_loading">
                                <button class="button button-add-giftregistry-shop-page" id="add_product_<?= $product_id ?>"
                                        name="<?= $product_id ?>"
                                        onclick="window.location.href ='<?= wc_get_page_permalink('myaccount') .'/my-gift-registry/' ?>'">
                                    <span id="text_<?= $product_id ?>"><?php echo __('Add to gift registry', GIFTREGISTRY_TEXT_DOMAIN); ?></span>
                                </button>
                            </div>
                        </div>
                        <?php
                    }
                    return;
                } else {
                    ?>
                    <div class="wgr-add-product-shop-page">
                        <div class="add_button_loading">
                            <button class="button button-add-giftregistry-shop-page" id="add_product_<?= $product_id ?>"
                                    name="<?= $product_id ?>"
                                    onclick="window.location.href ='<?= wc_get_page_permalink('myaccount') .'/my-gift-registry/' ?>'">
                                <span id="text_<?= $product_id ?>"><?php echo __('Add to gift registry', GIFTREGISTRY_TEXT_DOMAIN); ?></span>
                            </button>
                        </div>
                    </div>
                    <?php
                }
            } else {
                ?>
                <div class="wgr-add-product-shop-page">
                    <div class="add_button_loading">
                        <button class="button button-add-giftregistry-shop-page" id="add_product_<?= $product_id ?>"
                                name="<?= $product_id ?>"
                                onclick="window.location.href ='<?= wc_get_page_permalink('myaccount') ?>?request_login=true'">
                            <span id="text_<?= $product_id ?>"><?php echo __('Add to gift registry', GIFTREGISTRY_TEXT_DOMAIN); ?></span>
                        </button>
                    </div>
                </div>
                <?php
            }
        }
    }
}

return new Magenest_Giftregistry_Frontend();
?>