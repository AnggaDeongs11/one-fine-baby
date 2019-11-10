<?php
get_header();
$st_w =  TInvWL_Public_Wishlist_View::instance();
$wishlist = $st_w->get_current_wishlist();

if (empty($wishlist)) {
    $id = get_query_var('tinvwlID', null);
    if (empty($id) && ( is_user_logged_in() || !tinv_get_option('general', 'require_login') )) {
        return $st_w->wishlist_empty(array(), array(
                    'ID' => '',
                    'author' => get_current_user_id(),
                    'title' => apply_filters('tinvwl_default_wishlist_title', tinv_get_option('general', 'default_title')),
                    'status' => 'private',
                    'type' => 'default',
                    'share_key' => '',
                ));
    }

    return $st_w->wishlist_null();
}

if ('private' === $wishlist['status'] && !$wishlist['is_owner']) {
    return $st_w->wishlist_null();
}
if ('default' !== $wishlist['type'] && !tinv_get_option('general', 'multi')) {
    if ($wishlist['is_owner']) {
        printf('<p><a href="%s">%s</p><script type="text/javascript">window.location.href="%s"</script>', esc_attr(tinv_url_wishlist_default()), esc_html__('Return to Wishlist', 'ti-woocommerce-wishlist'), esc_attr(tinv_url_wishlist_default()));

        return false;
    } else {
        return $st_w->wishlist_null();
    }
}

$lists_per_page = 8;

if (10 === $lists_per_page && is_array($st_w->get_current_products_query())) {
    $products = $st_w->current_products_query;
} else {
    $products = $st_w->get_current_products($wishlist, true, $lists_per_page);
}

//$wla = new TInvWL_Analytics($wishlist, $st_w->_name);
//$wla->view_products($wishlist, $wishlist['is_owner']);

foreach ($products as $key => $product) {
    if (!isset($product['data'])) {
        unset($products[$key]);
    }
}

if (empty($products)) {
    return $st_w->wishlist_empty($products, $wishlist);
}

$wishlist_table_row = tinv_get_option('product_table');
$wishlist_table_row['text_add_to_cart'] = apply_filters('tinvwl_add_to_cart_text', tinv_get_option('product_table', 'text_add_to_cart'));

$data = array(
    'products' => $products,
    'wishlist' => $wishlist,
    'wishlist_table' => tinv_get_option('table'),
    'wishlist_table_row' => $wishlist_table_row,
);

$paged = get_query_var('wl_paged', 1);
$paged = 1 < $paged ? $paged : 1;

if (1 < $paged) {
    add_action('tinvwl_pagenation_wishlist', array($st_w, 'page_prev'));
}
$pages = ceil(absint($st_w->wishlist_products_helper->get_wishlist(array(
                    'count' => 9999999,
                    'external' => false,
                        ), true)) / absint($lists_per_page));

if (1 < $pages) {
    $st_w->pages = $pages;
    add_action('tinvwl_pagenation_wishlist', array($st_w, 'pages'));
}
if ($pages > $paged) {
    add_action('tinvwl_pagenation_wishlist', array($st_w, 'page_next'));
}

if ($wishlist['is_owner']) {
//    tinv_wishlist_template('ti-wishlist.php', $data); // ORIG
   include( locate_template( 'template-parts/wishlist/wishlist-table.php', false, false ) ); 
} else {
//    if (class_exists('WC_Catalog_Visibility_Options')) {
//        global $wc_cvo;
//        if ('secured' === $wc_cvo->setting('wc_cvo_atc' && isset($data['wishlist_table_row']['add_to_cart']))) {
//            unset($data['wishlist_table_row']['add_to_cart']);
//        }
//        if ('secured' === $wc_cvo->setting('wc_cvo_prices' && isset($data['wishlist_table_row']['colm_price']))) {
//            unset($data['wishlist_table_row']['colm_price']);
//        }
//    }
//
//    tinv_wishlist_template('ti-wishlist-user.php', $data);
}
get_footer();