<?php
/**
 * Created by PhpStorm.
 * User: mdq
 * Date: 07/08/2018
 * Time: 15:22
 */
wp_enqueue_style('thickbox');
wp_enqueue_script('thickbox');
wp_enqueue_style('my-style');

$acount_page = get_page_by_path('my-account');

$account_link = get_permalink(get_option('woocommerce_myaccount_page_id')) . 'my-gift-registry/';
$my_account_page_url = get_permalink(get_option('woocommerce_myaccount_page_id'));
$w_page = get_permalink(get_option('follow_up_emailgiftregistry_page_id'));
$http_schema = 'http://';
if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']) {
    $http_schema = 'https://';
}

$request_link = $http_schema . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];

//if ( strpos( $request_link, '?' ) > 0 ) {
//    $buy_link = $w_page . '&giftregistry_id=';
//} else {
$buy_link = $w_page . '?giftregistry_id=';
global $post;
if($post->ID == get_option('follow_up_emailgiftregistry_page_id')){
    ?>
    <style>
        @font-face {
            font-family: 'MyFont'; /*a name to be used later*/
            src: url(<?=GIFTREGISTRY_URL .'/assets/fonts/font-giftregistry-page/GreatVibes-Regular.ttf'?>); /*URL to font*/
        }
        .entry-header h1{
            font-family: 'MyFont';
            text-align: center;
        }
    </style>
    <?php
}
//}
$user_id = get_current_user_id();
$permission = get_option('giftregistry_enable_permission', true);
?>
<style>
    .view-search {
        padding-top: 10%;
        background-image: url(<?= !empty(get_post_meta(get_the_ID(), 'background-image', true)) ?
            get_post_meta(get_the_ID(), 'background-image', true) : ""?>);
        background-size: 100% 500px;
        height: auto;
        background-repeat: no-repeat;
        padding-bottom: 100px;
    }
</style>
<div class="view-search">
    <div class="view-search-form">
        <div class="view">
            <?php if (!class_exists('Magenest_Giftregistry_Model')) {
                include_once GIFTREGISTRY_PATH . 'model/magenest-giftregistry-model.php';
            }

            $gr_id = Magenest_Giftregistry_Model::get_wishlist_id();
            if ($gr_id) {
                { ?>
                    <span style="list-style-type: none; "><a
                                href="<?php echo $buy_link . $gr_id ?>"
                                class="button"> <?php echo __('View my gift registry', GIFTREGISTRY_TEXT_DOMAIN) ?></a>
                    </span>
                <?php }
            } else {
                ?>
                <span style="list-style-type: none; margin-right: 5px;"><a
                            href="<?php echo $account_link ?>"
                            class="button"> <?php echo __('Create gift registry', GIFTREGISTRY_TEXT_DOMAIN) ?></a>
                    </span>
                <?php
            } ?>
            <!-- end my gift registry -->
        </div>
        <?php
        ?>
