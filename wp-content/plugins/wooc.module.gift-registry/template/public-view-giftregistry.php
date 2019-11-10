<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
} // Exit if accessed directly
wp_enqueue_style( '1', GIFTREGISTRY_URL . '/assets/css/bootstrap.min.css' );
wp_enqueue_style( '2', GIFTREGISTRY_URL . '/assets/css/font-awesome.css' );
wp_enqueue_style( '3', GIFTREGISTRY_URL . '/assets/css/margin-top: 10px; margin-bottom: 10px;responsive.css' );
wp_enqueue_style( '4', GIFTREGISTRY_URL . '/assets/css/bootstrap-theme.css' );
wp_enqueue_style( '5', GIFTREGISTRY_URL . '/assets/css/my-style.css' );
wp_enqueue_style( '6', 'https://fonts.googleapis.com/css?family=Arizonia' );
wp_enqueue_script( '11', GIFTREGISTRY_URL . '/assets/js/bootstrap.min.js' );
wp_enqueue_script( '22', GIFTREGISTRY_URL . '/assets/js/jquery.plugin.js' );
wp_enqueue_script( '33', GIFTREGISTRY_URL . '/assets/js/jquery.countdown.js' );

wc_print_notices();

if ( ! $id ) {
    return;
}

$wishlist = Magenest_Giftregistry_Model::get_wishlist( $id );

$registrantname   = $wishlist->registrant_firstname . ' ' . $wishlist->registrant_lastname;
$coregistrantname = $wishlist->coregistrant_firstname . ' ' . $wishlist->coregistrant_lastname;

$registry_name = $registrantname;

if ( $coregistrantname != ' ' ) {
    $registry_name .= __( ' and', GIFTREGISTRY_TEXT_DOMAIN ) . " " . $coregistrantname;
}


$items       = Magenest_Giftregistry_Model::get_items_in_giftregistry( $id );
$http_schema = 'http://';
if ( isset( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] ) {
    $http_schema = 'https://';
}

$request_link = $http_schema . $_SERVER ["HTTP_HOST"] . $_SERVER ["REQUEST_URI"];
$year = date_i18n( 'Y', strtotime( $wishlist->event_date_time ) );
$month = date_i18n( 'F', strtotime( $wishlist->event_date_time ) );
$day = date_i18n( 'j', strtotime( $wishlist->event_date_time ) );
?>
<script type="text/javascript">
    function giftit(obj) {
        var qty_ele = jQuery(obj).attr('name');
        var qty = jQuery('#qty' + qty_ele).val();

        var message_ele = jQuery(obj).attr('name');
        var message = jQuery('#message' + message_ele).val();

        var submit_link = jQuery(obj).attr('data-buy') + '&quantity=' + qty + '&message=' + message;

        var base_url = window.location.origin;

        var url_string = window.location.pathname;
        var url = base_url + url_string;

        var link = url + submit_link;

        jQuery.ajax(
            {
                method: 'post',
                url: link,
            }
        ).done(function () {
            window.location.href = url + '/cart/';
        });

    }
</script>
<style>
    table.cart .product-thumbnail img {
        margin : 0px !important;
    }

    .about-us {
        background-image : url('<?php echo wp_get_attachment_image_src($wishlist->background_image, 'full')[0] ?>') !important
    }

    .about-us .image-bg {
        display             : block;
        width               : 100%;
        height              : 0;
        padding-bottom      : 60%;
        background-position : center;
        background-size     : cover;
        background-repeat   : no-repeat;
        overflow            : hidden;
    }

    .event-wedding p {
        color : white
    }
</style>
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
                <div class="image-bg"
                     style="background-image: url(<?php echo wp_get_attachment_image_src( $wishlist->registrant_image, 'full' )[0] ?>)"></div>
                <div class="caption">
                    <h3 class="name text-center"><?php echo $wishlist->registrant_firstname . ' ' . $wishlist->registrant_lastname ?></h3>
                    <p><?php echo $wishlist->registrant_description ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-sm-6">
            <div class="thumbnail">
                <div class="image-bg"
                     style="background-image: url(<?php echo wp_get_attachment_image_src( $wishlist->coregistrant_image, 'full' )[0] ?>)"></div>
                <div class="caption">
                    <h3 class="name text-center"><?php echo $wishlist->coregistrant_firstname . ' ' . $wishlist->coregistrant_lastname ?></h3>
                    <p><?php echo $wishlist->coregistrant_description ?></p>
                </div>
            </div>
        </div>
    </div>
</section>
<h2><?php echo $registry_name ?></h2>
<hr>
<?php
if ( $wishlist->image != '' ) :
    ?>
    <img class="wishlist-image" src="<?php echo $wishlist->image ?>"/>
<?php endif; ?>

<!-- Event date shortcode-->
<?php
if ( $wishlist->event_date_time != '' ) :
    ?>
    <p><strong><?php echo __( 'Event date', GIFTREGISTRY_TEXT_DOMAIN ) ?> </strong>:</p>
    <span><?php echo $day. ' ' . $month . ' , ' .$year; ?></span>
<?php endif; ?>

<!-- Note -->
<?php //str_replace('\\', '',$wishlist->message)
if ( $wishlist->message != '' ) :
    ?>
    <p><strong><?php echo __( 'Message', GIFTREGISTRY_TEXT_DOMAIN ) ?> </strong>:</p>
    <span><?php echo str_replace( '\\', '', $wishlist->message ) ?></span>
<?php endif; ?>


<!--Table shortcode-->
<table class="shop_table cart" cellspacing="0">
    <thead>
    <tr>
        <th class="product-thumbnail"><?php __( 'Image', GIFTREGISTRY_TEXT_DOMAIN );
            echo $_REQUEST ['giftregistry_id']; ?>
        </th>
        <th class="product-name"><?=__( 'Product', GIFTREGISTRY_TEXT_DOMAIN ); ?></th>
        <th class="product-price"><?=__( 'Price', GIFTREGISTRY_TEXT_DOMAIN ); ?></th>
        <th class="product-quantity"><?=__( 'Desired Quantity', GIFTREGISTRY_TEXT_DOMAIN); ?></th>
        <th class="product-priority"><?=__( 'Priority', GIFTREGISTRY_TEXT_DOMAIN ); ?></th>
        <th class="product-quantity"><?=__( 'Quantity', GIFTREGISTRY_TEXT_DOMAIN ); ?></th>
        <th class="product-message"><?=__( 'Message', GIFTREGISTRY_TEXT_DOMAIN ); ?></th>
        <th class="product-buy"></th>
    </tr>
    </thead>
    <tbody>
    <?php
    if ( ! empty ( $items ) ) {
        foreach ( $items as $item ) {
            $_product = wc_get_product( $item ['product_id'] );

            $request = unserialize( $item['info_request'] );

            $request_st = Magenest_Giftregistry_Model::show_info_request( $item, $id );
            ?>
            <tr>

                <td class="product-thumbnail">
                    <?php
                    $thumbnail = $_product->get_image();
                    printf( '<a href="%s">%s</a>', $_product->get_permalink(), $thumbnail );
                    ?>
                </td>
                <td class="product-name">
                    <?php
                    echo sprintf( '<a href="%s">%s</a>', $_product->get_permalink(), $_product->get_title() )
                    ?>
                </td>
                <td class="product-price">
                    <?php
                    echo $_product->get_price_html();
                    ?>
                </td>
                <td style="width: 30px;" class="product-quantity">
                    <?php if ( isset( $item['quantity'] ) ) {
                        $receive_qty = 0;
                        if ( isset( $item['received_qty'] ) ) {
                            $receive_qty = $item['received_qty'];
                        }
                        $remain_qty = $item['quantity'] - $receive_qty;//received_qty
                        if ( $remain_qty < 0 ) {
                            $remain_qty = 0;
                        }
                        echo $remain_qty;
                    }
                    ?>
                </td>
                <td class="product-price">
                    <?php
                    $priority = $item['priority'];
                    if ( $priority == 1 ) {
                        echo __( 'High', GIFTREGISTRY_TEXT_DOMAIN );
                    } elseif ( $priority == 2 ) {
                        echo __( 'Medium', GIFTREGISTRY_TEXT_DOMAIN );
                    } else {
                        echo __( 'Low', GIFTREGISTRY_TEXT_DOMAIN );
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
                    <?php
                    $stock_status = $_product->get_stock_status();
                    if ( $stock_status == 'instock' ) {
                        ?>
                        <button data-buy="<?php echo $request_st ?>"
                                name="<?php echo $item['id'] ?>"
                                class="single_add_to_cart_button button alt"
                                onclick="giftit(this)"
                        >
                            <?php echo __( 'Add to cart', GIFTREGISTRY_TEXT_DOMAIN ) ?>
                        </button>
                        <?php
                    } else {
                        ?>
                        <button class="single_add_to_cart_button button alt">
                            <?php echo __( 'Out stock', GIFTREGISTRY_TEXT_DOMAIN ) ?>
                        </button>
                        <?php
                    }
                    ?>

                </td>

            </tr>
        <?php }
    }
    ?>
    </tbody>
</table>