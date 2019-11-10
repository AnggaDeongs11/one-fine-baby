<?php
if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

wp_enqueue_style('1', GIFTREGISTRY_URL . '/assets/css/bootstrap.min.css');
wp_enqueue_style('2', GIFTREGISTRY_URL . '/assets/css/font-awesome.css');
//wp_enqueue_style('3', GIFTREGISTRY_URL . '/assets/css/responsive.css');
wp_enqueue_style('4', GIFTREGISTRY_URL . '/assets/css/bootstrap-theme.css');
wp_enqueue_style('5', GIFTREGISTRY_URL . '/assets/css/style.css');
wp_enqueue_style('6', 'http://fonts.googleapis.com/css?family=Arizonia');
wp_enqueue_script('11', GIFTREGISTRY_URL . '/assets/js/bootstrap.min.js');
wp_enqueue_script('22', GIFTREGISTRY_URL . '/assets/js/jquery.plugin.js');
wp_enqueue_script('33', GIFTREGISTRY_URL . '/assets/js/jquery.countdown.js');

wc_print_notices();

if (!$id)
    return;

$wishlist = Magenest_Giftregistry_Model::get_wishlist($id);

$registrantname = $wishlist->registrant_firstname . ' ' . $wishlist->registrant_lastname;
$coregistrantname = $wishlist->coregistrant_firstname . ' ' . $wishlist->coregistrant_lastname;

$registry_name = $registrantname;

if ($coregistrantname != ' ')
    $registry_name .= __(' and', GIFTREGISTRY_TEXT_DOMAIN) . " " . $coregistrantname;


$items = Magenest_Giftregistry_Model::get_items_in_giftregistry($id);

$year = date('Y', strtotime($wishlist->event_date_time));
$month = date('m', strtotime($wishlist->event_date_time));
$day = date('d', strtotime($wishlist->event_date_time));

?>
<script type="text/javascript">
    function giftit(obj) {
        var qty_ele = jQuery(obj).attr('name');
        console.log(qty_ele);
        var qty = jQuery('#qty' + qty_ele).val();
        console.log('qty ');
        console.log(qty);

        var message_ele = jQuery(obj).attr('name');
        var message = jQuery('#message' + message_ele).val();

        var submit_link = jQuery(obj).attr('data-buy') + '&quantity=' + qty + '&message=' + message;
        console.log(submit_link);

        var base_url = window.location.origin;
        console.log('origin');
        console.log(base_url);

        var url_string = window.location.pathname;
        var url = base_url + url_string;
        console.log(url);

        var link = url + submit_link;
        console.log('links');
        console.log(link);

        jQuery.ajax(
            {
                method: 'post',
                url: link,
            }
        ).done(function () {
            window.location.href = url + '/cart/';
        });
    }

    jQuery(function () {
        var austDay = new Date();
        /*austDay = new Date(austDay.getFullYear() + 1, 1 - 1, 10);*/
        austDay = new Date(<?php echo $year ?>, <?php echo $month ?>-1, <?php echo $day ?>);
        jQuery('#defaultCountdown').countdown({until: austDay});
        jQuery('#year').text(austDay.getFullYear());
    });
</script>
<style>
    .about-us {
        background-image: url('<?php echo wp_get_attachment_image_src($wishlist->background_image, 'full')[0] ?>') !important
    }

    .event-wedding p {
        color: white
    }
</style>

<div id="main">
    <section class="about-us box">
        <div class="heading">
            <h2 class="title text-center">About Us</h2>
            <div class="bottom text-center">
                <span class="line"></span><i class="fa fa-heart"></i><span class="line">
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 col-sm-6">
                <div class="thumbnail">
                    <?php echo wp_get_attachment_image($wishlist->registrant_image) ?>
                    <div class="caption">
                        <h3 class="name text-center"><?php echo $wishlist->registrant_firstname . ' ' . $wishlist->registrant_lastname ?></h3>
                        <p><?php echo $wishlist->registrant_description ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-sm-6">
                <div class="thumbnail">
                    <?php echo wp_get_attachment_image($wishlist->coregistrant_image) ?>
                    <div class="caption">
                        <h3 class="name text-center"><?php echo $wishlist->coregistrant_firstname . ' ' . $wishlist->coregistrant_lastname ?></h3>
                        <p><?php echo $wishlist->coregistrant_description ?></p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="countdown-wedding box">
        <div class="row">
            <div class="col-md-5 col-sm-12 heading">
                <h2 class="title text-center">Countdown to Wedding Ceremony</h2>
                <div class="bottom text-center">
                    <span class="line"></span><i class="fa fa-heart"></i><span class="line">
                </div>
            </div>
            <div class="col-md-7 col-sm-12 col-md-offset-0 text-center">
                <div id="defaultCountdown"></div>
            </div>
        </div>
    </section>

    <section class="event-wedding box">
        <div class="row">
            <div class="col-md-5 col-sm-6 col-md-offset-1 text-center heading">
                <h2 class="title text-center">Our<br>Special<br>events!</h2>
                <div class="bottom text-center">
                    <span class="line"></span><i class="fa fa-heart"></i><span class="line">
                </div>
            </div>
            <div class="col-md-5 col-sm-6 col-md-offset-0">
                <div class="thumbnail">
                    <h3 class="title text-center">WEDDING PARTY</h3>
                    <div class="caption-time text-center">
                        <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <i class="fa fa-calendar"></i>
                                <p><?php echo $wishlist->event_date_time ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="caption">
                        <p><?php echo str_replace('\\', '', $wishlist->message) ?></p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="out-photo box">
        <div class="heading">
            <h2 class="title text-center">our photo</h2>
            <div class="bottom text-center">
                <span class="line"></span><i class="fa fa-heart"></i><span class="line">
            </div>
        </div>
        <div class="row">
            <?php
            $wid = Magenest_Giftregistry_Model::get_wishlist_id();
            $upload_dir = wp_upload_dir();
            $dir = $upload_dir['basedir'] . '/files/' . $wid;
            if (is_dir($dir)) {
                if ($dh = opendir($dir)) {
                    while (($file = readdir($dh)) !== false) {
                        preg_match('![^?#]+\.(?:jpe?g|png|gif)!Ui', $file, $match);
                        if (isset($match['0'])) {
                            echo '<div class="col-md-3 col-sm-3">';
                            echo '<img src="' . $upload_dir['baseurl'] . '/files/' . $wid . '/' . $file . '" class="img-responsive">';
                            echo '</div>';
                        }
                    }
                    closedir($dh);
                }
            }
            ?>
        </div>
    </section>

    <section class="message box">
        <div class="container">
            <div class="heading">
                <h2 class="title text-center">Lorem Ipsum</h2>
                <div class="bottom text-center">
                    <span class="line"></span><i class="fa fa-heart"></i><span class="line">
                </div>
            </div>
            <div class="thumbnail">
                <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the
                    industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type
                    and scrambled it to make a type specimen book. It has survived not only five centuries, but also the
                    leap into electronic typesetting, remaining essentially unchanged</p>
                <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the
                    industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type
                    and scrambled it to make a type specimen book. It has survived not only five centuries, but also the
                    leap into electronic typesetting, remaining essentially unchanged</p>
            </div>
        </div>
    </section>

    <section class="table-cart box">
        <div class="heading">
            <h2 class="title text-center">Lorem Ipsum</h2>
            <div class="bottom text-center">
                <span class="line"></span><i class="fa fa-heart"></i><span class="line">
            </div>
        </div>
        <table class="table table-bordered">
            <thead>
            <tr>
                <th class="product-thumbnail"><?=__('Image', GIFTREGISTRY_TEXT_DOMAIN); ?></th>
                <th class="product-name"><?=__('Product', GIFTREGISTRY_TEXT_DOMAIN); ?></th>
                <th class="product-price"><?=__('Price', GIFTREGISTRY_TEXT_DOMAIN); ?></th>
                <th class="product-quantity"><?=__('Desired Quantity', GIFTREGISTRY_TEXT_DOMAIN); ?> </th>
                <th class="product-priority"><?=__('Priority', GIFTREGISTRY_TEXT_DOMAIN); ?></th>
                <th class="product-quantity"><?=__('Quantity', GIFTREGISTRY_TEXT_DOMAIN); ?></th>
                <th class="product-message"><?=__('Message', GIFTREGISTRY_TEXT_DOMAIN); ?></th>
                <th class="product-buy">&nbsp;</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($items as $item) :
                $_product = wc_get_product($item ['product_id']);
                $request = unserialize($item['info_request']);
                $request_st = Magenest_Giftregistry_Model::show_info_request($item, $id);
                $thumbnail = $_product->get_image();
                ?>
                <tr>
                    <td><?php printf('<a href="%s">%s</a>', $_product->get_permalink(), $thumbnail) ?></td>
                    <td><?php echo sprintf('<a href="%s">%s</a>', $_product->get_permalink(), $_product->get_title()) ?></td>
                    <td><?php echo $_product->get_price_html(); ?></td>
                    <td><?php if (isset($item['quantity'])) {
                            $receive_qty = 0;
                            if (isset($item['received_qty']))
                                $receive_qty = $item['received_qty'];
                            $remain_qty = $item['quantity'] - $receive_qty;//received_qty
                            if ($remain_qty < 0)
                                $remain_qty = 0;
                            echo $remain_qty;
                        } ?></td>
                    <td class="product-price">
                        <?php
                        $priority = $item['priority'];
                        if ($priority == 1) {
                            echo __('High', GIFTREGISTRY_TEXT_DOMAIN);
                        } elseif ($priority == 2) {
                            echo __('Medium', GIFTREGISTRY_TEXT_DOMAIN);
                        } else {
                            echo __('Low', GIFTREGISTRY_TEXT_DOMAIN);
                        }
                        ?>
                    </td>
                    <td>
                        <input style="width: 40px" type="text" id="qty<?php echo $item['id'] ?>"/>
                    </td>
                    <td class="product-message">
                        <textarea cols="10" style="width: 100px;" id="message<?php echo $item['id'] ?>"></textarea>
                    </td>
                    <td>
                        <button data-buy="<?php echo $request_st ?>"
                                name="<?php echo $item['id'] ?>"
                                class="single_add_to_cart_button button alt"
                                onclick="giftit(this)"
                        >
                            <?php echo __('Buy') ?>
                        </button>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </section>
</div>
