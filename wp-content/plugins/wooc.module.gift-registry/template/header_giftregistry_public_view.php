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
wp_enqueue_style('2', GIFTREGISTRY_URL . '/assets/css/font-awesome.css');
wp_enqueue_style('3', GIFTREGISTRY_URL . '/assets/css/responsive.css');
wp_enqueue_style('4', GIFTREGISTRY_URL . '/assets/css/bootstrap-theme.css');
wp_enqueue_style('5', GIFTREGISTRY_URL . '/assets/css/my-style.css');
wp_enqueue_style('6', 'https://fonts.googleapis.com/css?family=Arizonia');
wp_enqueue_script('11', GIFTREGISTRY_URL . '/assets/js/bootstrap.min.js');
wp_enqueue_script('22', GIFTREGISTRY_URL . '/assets/js/jquery.plugin.js');
wp_enqueue_script('33', GIFTREGISTRY_URL . '/assets/js/jquery.countdown.js');
wp_enqueue_style('header_giftregistry_public_view');
wc_print_notices();

if (!$id) {
    return;
}
$wishlist = Magenest_Giftregistry_Model::get_wishlist($id);

$registrantname = $wishlist->registrant_firstname . ' ' . $wishlist->registrant_lastname;
$coregistrantname = $wishlist->coregistrant_firstname . ' ' . $wishlist->coregistrant_lastname;
$registry_name = $registrantname;
/*security gift registry*/
if($wishlist->role == 1 && !isset($_COOKIE[get_current_user_id().'open'.$wishlist->id]) ){
    $redirect_url = get_permalink(get_option('follow_up_emailgiftregistry_page_id')) . '?wishlist_id='.$id.'&checkpass_giftregistry=1';
    ?>
    <script>
        window.location.href = '<?=$redirect_url?>';
    </script>
    <?php
}

if ($coregistrantname != ' ') {
    $registry_name .= __(' and', GIFTREGISTRY_TEXT_DOMAIN) . " " . $coregistrantname;
}

$year = date_i18n('Y', strtotime($wishlist->event_date_time));
$month = date_i18n('F', strtotime($wishlist->event_date_time));
$day = date_i18n('j', strtotime($wishlist->event_date_time));
?>
<div class="header-view-search" id="view">
    <?php
        if(!empty($wishlist->title)){
            ?>
                <h1 id="giftregistry-title"><?=$wishlist->title?></h1>
                <style>
                    #giftregistry-title {
                        text-align: center;
                        position: relative;
                        margin-bottom: 50px;
                    }
                    #giftregistry-title:before {
                        content: "";
                        position: absolute;
                        width: 100px;
                        height: 5px;
                        background: #000;
                        left: 50%;
                        bottom: -10px;
                        transform: translateX(-50%);
                    }
                </style>
            <?php
        }
        if (!empty($registry_name)){
            ?>
                <h2 id="giftregistry-name"><?php echo $registry_name ?></h2>
                <style>
                    #giftregistry-name {
                        font-size: 20px;
                        font-weight: bold;
                    }
                </style>
            <?php
        }
    ?>
    <hr>
    <?php
    if ($wishlist->image != '') :
        ?>
        <img class="wishlist-image" src="<?php echo $wishlist->image ?>"/>
    <?php endif; ?>

    <!-- Event date shortcode-->
    <?php
    if ($wishlist->event_date_time != '') :
        ?>
        <label><strong><?php echo __('Event date', GIFTREGISTRY_TEXT_DOMAIN) ?> </strong>:</label>
        <span><?php echo $day . ' ' . $month . ' , ' . $year; ?></span>
    <?php endif; ?>
    <br>

    <!-- Note -->
    <?php //str_replace('\\', '',$wishlist->message)
    if ($wishlist->message != '') :
        ?>
        <label><strong><?php echo __('Message', GIFTREGISTRY_TEXT_DOMAIN) ?> </strong>:</label>
        <span><?php echo str_replace('\\', '', $wishlist->message) ?></span>
    <?php endif; ?>
</div>
<script>
    window.location.hash = '#view';
</script>