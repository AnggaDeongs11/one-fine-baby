<?php
/**
 * Created by PhpStorm.
 * User: mdq
 * Date: 07/08/2018
 * Time: 13:45
 */
if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly
wp_enqueue_style('my-bootstrap');
wp_enqueue_style('icon-loading');
wc_print_notices();

if (!$id) {
    return;
}

$wishlist = Magenest_Giftregistry_Model::get_wishlist($id);
$gr_id = Magenest_Giftregistry_Model::get_wishlist_id();

/*check whether redirect from search form or filter ajax*/
if(!isset($items)){
    $items = Magenest_Giftregistry_Model::get_items_in_giftregistry($id);
}
$http_schema = 'http://';
if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']) {
    $http_schema = 'https://';
}
if(isset($_REQUEST['buy'])) {
    if(!isset($_SESSION['validation_quantity_before_add'])){
        $_SESSION['buy_for_giftregistry_id'] = $id;
        ?>
        <script>
            jQuery(document).ready(function ($) {
                $("html, body").animate({scrollTop:$('#bought').position().top - 100}, 1000);
                return false;
            });
        </script>
        <div class="woocommerce" id="bought">
            <div class="woocommerce-message" role="alert">
                <a href="<?= get_permalink('cart') . '/cart' ?>"
                   class="button wc-forward"><?= __('View cart', GIFTREGISTRY_TEXT_DOMAIN) ?></a><?= __('The item has been added to your cart.', GIFTREGISTRY_TEXT_DOMAIN) ?>
            </div>
        </div>
        <?php
    }elseif(!$_SESSION['validation_quantity_before_add']){
        $_SESSION['buy_for_giftregistry_id'] = $id;
        ?>
        <script>
            jQuery(document).ready(function ($) {
                $("html, body").animate({scrollTop:$('#bought').position().top - 100}, 1000);
                return false;
            });
        </script>
        <div class="woocommerce" id="bought">
            <div class="woocommerce-error" role="alert">
                <a href="<?= get_permalink('cart') . '/cart' ?>"
                   class="button wc-forward"><?= __('View cart', GIFTREGISTRY_TEXT_DOMAIN) ?></a><?= __('The quantity of item in cart is greater than desired quantity in giftregistry list.', GIFTREGISTRY_TEXT_DOMAIN) ?>
            </div>
        </div>
        <?php
        unset($_SESSION['validation_quantity_before_add']);
    }
}
$request_link = $http_schema . $_SERVER ["HTTP_HOST"] . $_SERVER ["REQUEST_URI"];

global $woocommerce;
$carts = $woocommerce->cart->cart_contents;
foreach ($carts as $cart => $values) {
    if(isset($values['gift_registry']['buy_for_giftregistry_id']['value'])){
        if (isset($_SESSION['buy_for_giftregistry_id']) && $values['gift_registry']['buy_for_giftregistry_id']['value'] != $id) {
            ?>
            <div class="woocommerce">
                <div class="woocommerce-message"
                     role="alert"><?= __('You can not add products from multiple gift registry', GIFTREGISTRY_TEXT_DOMAIN) ?></div>
            </div>
            <script>
                jQuery(document).ready(function ($) {
                    $('.shop_table .single_add_to_cart_button').attr('disabled', '');
                    $('#filter-giftregistry').attr('disabled', '');
                    $('#btn_filter').attr('disabled', '');
                    $('#level_filter').attr('disabled', '');
                });
            </script>
            <?php
            $_SESSION['giftregistry_id'] = $values['gift_registry']['buy_for_giftregistry_id']['value'];
            break;
        }else{
            if (isset($_REQUEST['giftregistry_id'])) {
                $_SESSION['giftregistry_id'] = $_REQUEST['giftregistry_id'];
            }
        }
    }
}
if (!empty ($items)) {
    $catalog_orderby_options = array(
        'menu_order' => __( 'Default sorting', 'woocommerce' ),
        'desired_quantity' => __( 'Sort by desired quantity', GIFTREGISTRY_TEXT_DOMAIN ),
        'priority'   => __( 'Sort by priority', GIFTREGISTRY_TEXT_DOMAIN ),
        'price' => __( 'Sort by price', GIFTREGISTRY_TEXT_DOMAIN ),
    ) ;
    $level_filter = array(
        'high' => __('High to low',GIFTREGISTRY_TEXT_DOMAIN),
        'low' => __('Low to high',GIFTREGISTRY_TEXT_DOMAIN)
    );
    ?>
    <div id='loader' style="display: none;">
        <img src="<?=GIFTREGISTRY_URL?>assets/loading.gif" id='ajax-loader' alt="Loading...">
    </div>
    <div class="filter-giftregistry">
        <input type="hidden" name="action" value="filter_giftregistry">
        <select name="orderby" class="orderby" id="filter-giftregistry">
            <?php foreach ( $catalog_orderby_options as $key => $name ) : ?>
                <option value="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $name ); ?></option>
            <?php endforeach; ?>
        </select>
        <select name="level" class="level" id="level_filter">
            <?php foreach ( $level_filter as $key => $name ) : ?>
                <option value="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $name ); ?></option>
            <?php endforeach; ?>
        </select>
        <button id="btn_filter"><?=__('Filter',GIFTREGISTRY_TEXT_DOMAIN)?></button>
    </div>
    <?php
    ob_start();
    $template_path = GIFTREGISTRY_PATH.'template/';
    $default_path = GIFTREGISTRY_PATH.'template/';
    wc_get_template( 'table_giftregistry.php', array( 'items' => $items , 'id' => $id ),$template_path,$default_path );
    echo  ob_get_clean();
} else {
    if ($gr_id == $_REQUEST['giftregistry_id']) {
        ?>
        <div class="woocommerce empty" style="margin-top: 30px"><p
                    class="cart-empty"><?= __('Your gift registry item list is empty', GIFTREGISTRY_TEXT_DOMAIN) ?></p>
            <p class="return-to-shop">
                <a class="button wc-backward" href="<?= wc_get_page_permalink('shop') ?>">
                    <?= __('Add More Products', GIFTREGISTRY_TEXT_DOMAIN) ?>
                </a>
            </p>
        </div>
        <?php
    }
}
?>
<?php
if ($gr_id == $_REQUEST['giftregistry_id']) {
    echo do_shortcode('[magenest_share_giftregistry]');
}
?>
<script>
    /* make margin-top table change*/
    jQuery(document).ready(function ($) {
        var MessageHeight = $('#view span:last-child').height();
        $('#table').attr('style', `margin-top: ${MessageHeight}px`);
        $('.empty').attr('style', `margin-top: ${MessageHeight}px`);
    });
</script>
