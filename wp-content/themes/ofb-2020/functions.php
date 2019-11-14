<?php
// Define the version so we can easily replace it throughout the theme
require __DIR__ .'/api/google-feed.php';

define('ONE_FINE_BABY_VERSION', 1.0);


/* ------------------------- ADD RSS FEED ------------------------- */

add_theme_support('automatic-feed-links');


/* ------------------------- IMAGE SUPPORT ------------------------- */

add_theme_support('post-thumbnails');


/* ------------------------- MENUS ------------------------- */
register_nav_menus(
        array(
            'primary' => __('Main Menu', 'ofb'),
        )
);
remove_action('woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30);
remove_action('woocommerce_before_shop_loop', 'woocommerce_result_count', 20);
//function skyverge_change_default_sorting_name( $catalog_orderby ) {
//    $catalog_orderby = str_replace("Default sorting", "Our sorting", $catalog_orderby);
//    return $catalog_orderby;
//}
//add_filter( 'woocommerce_catalog_orderby', 'skyverge_change_default_sorting_name' );
//add_filter( 'woocommerce_default_catalog_orderby_options', 'skyverge_change_default_sorting_name' );
/* ------------------------- WIDGETS ------------------------- */

if (function_exists('register_sidebar'))
    register_sidebar(array(
        'name' => 'Footer - Left',
        'before_widget' => '<div class = "widgetizedArea">',
        'after_widget' => '</div>',
        'before_title' => '<h3>',
        'after_title' => '</h3>',
            )
    );

if (function_exists('register_sidebar'))
    register_sidebar(array(
        'name' => 'Footer - Middle Left',
        'before_widget' => '<div class = "widgetizedArea">',
        'after_widget' => '</div>',
        'before_title' => '<h3>',
        'after_title' => '</h3>',
            )
    );

if (function_exists('register_sidebar'))
    register_sidebar(array(
        'name' => 'Footer - Middle Right',
        'before_widget' => '<div class = "widgetizedArea">',
        'after_widget' => '</div>',
        'before_title' => '<h3>',
        'after_title' => '</h3>',
            )
    );

if (function_exists('register_sidebar'))
    register_sidebar(array(
        'name' => 'Footer - Right',
        'before_widget' => '<div class = "widgetizedArea">',
        'after_widget' => '</div>',
        'before_title' => '<h3>',
        'after_title' => '</h3>',
            )
    );


/* ------------------------- ENQUEUE STYLES AND SCRIPTS ------------------------- */

function ofb_scripts() {
//	wp_enqueue_style('style.css', get_stylesheet_directory_uri() . '/style.css');
    if (is_vendor_page()) {
        wp_enqueue_style('style.css', get_stylesheet_directory_uri() . '/dist/style.css');
        wp_enqueue_style('bootstrap-4.css', get_template_directory_uri() . '/styles/bootstrap.min.css');
        wp_enqueue_script('popper', 'https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js');
        wp_enqueue_script('bootstrap-4.js', get_template_directory_uri() . '/js/bootstrap.min.js');
    } else {
        wp_enqueue_style('seneview-style', get_stylesheet_uri(), array(), '1.1.1.7.1.27 ');
    }

    wp_enqueue_style('j-ui', 'https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css', array(), '1.12.1');
    wp_enqueue_style('oc-theme', 'https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css', array(), '4.2.0');
    wp_enqueue_style('oc', 'https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css', array(), '4.2.0');
    wp_enqueue_style('prefix-font-awesome', 'http://netdna.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.css', array(), '4.2.0');
    wp_enqueue_style('select2', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/css/select2.min.css', array(), '4.0.10');

    wp_enqueue_script('ofb-fitvid', get_template_directory_uri() . '/js/jquery.fitvids.js', array('jquery'), ONE_FINE_BABY_VERSION, true);

    wp_enqueue_script('ofb', get_template_directory_uri() . '/js/theme.min.js', array(), ONE_FINE_BABY_VERSION, true);
    wp_enqueue_script('j-ui', 'https://code.jquery.com/ui/1.12.1/jquery-ui.js', array(), ONE_FINE_BABY_VERSION);
    wp_enqueue_script('oc', 'https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js', array(), ONE_FINE_BABY_VERSION, true);
    wp_enqueue_script('select2', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/js/select2.min.js', array(), ONE_FINE_BABY_VERSION, true);
    if (is_page('cart')) {
        wp_enqueue_script('st-cart', get_template_directory_uri() . '/js/st-cart.js', array('jquery'), ONE_FINE_BABY_VERSION, true);
    }
}

add_action('wp_enqueue_scripts', 'ofb_scripts');


/* ------------------------- REMOVE ADMIN MENU ITEMS ------------------------- */

function sb_remove_admin_menus() {
// Check that the built-in WordPress function remove_menu_page() exists in the current installation
    if (function_exists('remove_menu_page')) {
        /* Remove unwanted menu items by passing their slug to the remove_menu_item() function.
          You can comment out the items you want to keep. */
        remove_menu_page('edit-comments.php'); // Comments
        remove_menu_page('edit.php?post_type=feedback'); // Feedback
    }
}

// Add our function to the admin_menu action
add_action('admin_menu', 'sb_remove_admin_menus');


/* ------------------------- DISABLE NEW EDITOR ------------------------- */

add_filter('use_block_editor_for_post_type', 'd4p_32752_completly_disable_block_editor');

function d4p_32752_completly_disable_block_editor($use_block_editor) {
    return false;
}

add_action('woocommerce_register_form_start', 'bbloomer_add_name_woo_account_registration');

function bbloomer_add_name_woo_account_registration() {
    ?>

    <p class="form-row form-row-first">
        <label for="reg_billing_first_name"><?php _e('First name', 'woocommerce'); ?> <span class="required">*</span></label>
        <input type="text" class="input-text" name="billing_first_name" id="reg_billing_first_name" value="<?php if (!empty($_POST['billing_first_name'])) esc_attr_e($_POST['billing_first_name']); ?>" />
    </p>

    <p class="form-row form-row-last">
        <label for="reg_billing_last_name"><?php _e('Last name', 'woocommerce'); ?> <span class="required">*</span></label>
        <input type="text" class="input-text" name="billing_last_name" id="reg_billing_last_name" value="<?php if (!empty($_POST['billing_last_name'])) esc_attr_e($_POST['billing_last_name']); ?>" />
    </p>

    <div class="clear"></div>

    <?php
}

///////////////////////////////
// 2. VALIDATE FIELDS

add_filter('woocommerce_registration_errors', 'bbloomer_validate_name_fields', 10, 3);

function bbloomer_validate_name_fields($errors, $username, $email) {
    if (isset($_POST['billing_first_name']) && empty($_POST['billing_first_name'])) {
        $errors->add('billing_first_name_error', __('<strong>Error</strong>: First name is required!', 'woocommerce'));
    }
    if (isset($_POST['billing_last_name']) && empty($_POST['billing_last_name'])) {
        $errors->add('billing_last_name_error', __('<strong>Error</strong>: Last name is required!.', 'woocommerce'));
    }
    return $errors;
}

///////////////////////////////
// 3. SAVE FIELDS

add_action('woocommerce_created_customer', 'bbloomer_save_name_fields');

function bbloomer_save_name_fields($customer_id) {
    if (isset($_POST['billing_first_name'])) {
        update_user_meta($customer_id, 'billing_first_name', sanitize_text_field($_POST['billing_first_name']));
        update_user_meta($customer_id, 'first_name', sanitize_text_field($_POST['billing_first_name']));
    }
    if (isset($_POST['billing_last_name'])) {
        update_user_meta($customer_id, 'billing_last_name', sanitize_text_field($_POST['billing_last_name']));
        update_user_meta($customer_id, 'last_name', sanitize_text_field($_POST['billing_last_name']));
    }
}

// Add the code below to your theme's functions.php file to add a confirm password field on the register form under My Accounts.
add_filter('woocommerce_registration_errors', 'registration_errors_validation', 10, 3);

function registration_errors_validation($reg_errors, $sanitized_user_login, $user_email) {
    global $woocommerce;
    extract($_POST);
    if (strcmp($password, $password2) !== 0) {
        return new WP_Error('registration-error', __('Passwords do not match.', 'woocommerce'));
    }
    return $reg_errors;
}

/* * ***************** PASSWORD REPEATER REGISTRATION *************************** */

function st_wc_register_form_password_repeat() {
    ?>
    <p class="form-row form-row-wide">
        <label for="reg_password2"><?php _e('Password Repeat', 'woocommerce'); ?> <span class="required">*</span></label>
        <input type="password" class="input-text" name="password2" id="reg_password2" value="<?php if (!empty($_POST['password2'])) echo esc_attr($_POST['password2']); ?>" />
    </p>
    <?php
}

add_action('woocommerce_register_form', 'st_wc_register_form_password_repeat');


/* * ***************** CUSTOM PRODUCT SEARCH ************************************ */

function st_woo_custom_product_searchform($form) {
    $form = '<form role="search" method="get" id="searchform" action="' . esc_url(home_url('/')) . '">
    <div>
      <label class="screen-reader-text" for="s">' . __('Search for:', 'woocommerce') . '</label>
      <input type="text" value="' . get_search_query() . '" name="s" id="s" placeholder="' . __('My Search form', 'woocommerce') . '" />
      <input type="submit" id="searchsubmit" value="' . esc_attr__('Search', 'woocommerce') . '" />
      <input type="hidden" name="post_type" value="product" />
    </div>
  </form>';
    return $form;
}

add_filter('get_product_search_form', 'st_woo_custom_product_searchform');

/* * **************** PRODUCT NEW ARRIVALS SHORTCODE FOR HOME PAGE ************** */

function st_new_arrivals_shrtcode($atts, $content = null) {
    ?>
    <div class="new-products-section">
        <div class="container woocommerce">
            <ul class="products item-list owl-carousel owl-theme" id="newarrivals" style="background:#fff;">
                <?php
                $args = array(
                    'post_type' => 'product',
                    'posts_per_page' => $atts['limit'],
                    'orderby' => 'date',
                    'order' => 'desc'
                );
                $loop = new WP_Query($args);
                if ($loop->have_posts()) {
                    while ($loop->have_posts()) : $loop->the_post();
                        global $product;
                        wc_get_template_part('content', 'product');
                    endwhile;
                } else {
                    echo __('No products found');
                }
                wp_reset_query();
                ?>
            </ul>
        </div>
    </div>
    <?php
}

add_shortcode('new_arrivals', 'st_new_arrivals_shrtcode');

/* * ***************** PRODUCT TRENDING SHORTCODE FOR HOME PAGE ******************** */

function st_trending_shrtcode($atts, $content = null) {
    ?>
    <div class="trending-products-section">
        <div class="container woocommerce">
            <ul class="products item-list owl-carousel owl-theme" id="trending" style="background:#fff;">
                <?php
                $args = array(
                    'post_type' => 'product',
                    'meta_key' => 'total_sales',
                    'orderby' => 'meta_value_num',
                    'posts_per_page' => $atts['limit'],
                );
                $loop = new WP_Query($args);
                if ($loop->have_posts()) {
                    while ($loop->have_posts()) : $loop->the_post();
                        global $product;
                        wc_get_template_part('content', 'product');
                    endwhile;
                } else {
                    echo __('No products found');
                }
                wp_reset_query();
                ?>
            </ul>
        </div>
    </div>
    <?php
}

add_shortcode('trending', 'st_trending_shrtcode');

/* * ************************ TRENDING CATEGORIES { AS PER XD DESIGN } SHORTCODE FOR HOME PAGE **************** */

function st_trending_category_shrtcode($atts, $content = null) {
    ?>
    <div class="container">
        <div class="item-list owl-carousel owl-theme" id="trending" style="background:#fff;">
            <?php
            $x = 1;

            while ($x <= 4) {
                echo '<a href="' . get_field('trending_category_' . $x . '_link') . '" class="d-block">';
                echo '<img src="' . get_field('trending_category_' . $x . '_image') . '" width="65px" height="115px" />';
                echo '<div class="trending-cat-text-box">';
                echo '<div class="trending-category-name">' . get_field('trending_category_' . $x . '_name') . '</div>';
                echo '<div class="trend-category-text">' . get_field('trending_category_' . $x . '_sub_text') . '</div>';
                echo '<i class="fas fa-chevron-right"></i>';
                echo '</div>';
                echo '</a>';
                $x++;
            }
            ?>
        </div><!--/.products-->
    </div><!--/.container-->
    <?php
}

add_shortcode('trending_category', 'st_trending_category_shrtcode');

/* * *************** DISPLAY PRODUCT BRAND AND NAME ON PRODUCT ITEM  ********************** */

function get_vendor_and_item() {
    $brands = get_the_terms($post->ID, 'pwb-brand');
    $cats = get_the_terms($post->ID, 'product_cat');
    ?>
    <?php if ($brands == null): ?>
        <h3 class="product-name"><?php the_title(); ?></h3>
        <p><?php echo($cats !== null) ? $cats[0]->name : ''; ?></p>
    <?php else: ?>
        <h2 class="woocommerce-loop-product__title"><a href="<?php echo get_term_link($brands[0]->term_id, 'pwb-brand'); ?>"><?php echo $brands[0]->name; ?></a></h2>
        <h3 class="product-name"><?php the_title(); ?></h3>
        <p><?php echo($cats !== null) ? $cats[0]->name : ''; ?></p>
    <?php endif; ?>
    <?php
}

/* * ********************* REPLACE DECIMAL PLACE OF THE PRICE ON PRODUCTS ************************** */
if (is_product()) {
    add_filter('woocommerce_price_trim_zeros', 'st_wc_hide_trailing_zeros', 10, 1);

    function st_wc_hide_trailing_zeros($trim) {
        // set to false to show trailing zeros
        return false;
    }

}
add_action('widgets_init', 'st_register_sidebars');

/* * ********************************* ARCHIVE PAGE SIDEBAR ********************************* */

function st_register_sidebars() {

    // GENDER SIDEBAR FILTER
    register_sidebar(
            array(
                'id' => 'gender',
                'name' => __('Gender'),
                'description' => __('Filter Gender'),
                'before_widget' => '<div id="%1$s" class="widget %2$s">',
                'after_widget' => '</div>',
                'before_title' => '<h5 class="widget-title">',
                'after_title' => '</h5>'
            )
    );
    // AGE SIDEBAR FILTER
    register_sidebar(
            array(
                'id' => 'age_group',
                'name' => __('Age Group'),
                'description' => __('Filter Age Group'),
                'before_widget' => '<div id="%1$s" class="widget %2$s">',
                'after_widget' => '</div>',
                'before_title' => '<h5 class="widget-title">',
                'after_title' => '</h5>'
            )
    );
    // SIZE SIDEBAR FILTER
    register_sidebar(
            array(
                'id' => 'size',
                'name' => __('Size'),
                'description' => __('Filter Size'),
                'before_widget' => '<div id="%1$s" class="widget %2$s">',
                'after_widget' => '</div>',
                'before_title' => '<h5 class="widget-title">',
                'after_title' => '</h5>'
            )
    );
    // BRAND SIDEBAR FILTER
    register_sidebar(
            array(
                'id' => 'brand',
                'name' => __('Brand'),
                'description' => __('Filter Brand'),
                'before_widget' => '<div id="%1$s" class="widget %2$s">',
                'after_widget' => '</div>',
                'before_title' => '<h5 class="widget-title">',
                'after_title' => '</h5>'
            )
    );
    // COLOR SIDEBAR FILTER
    register_sidebar(
            array(
                'id' => 'color',
                'name' => __('Color'),
                'description' => __('Filter Color'),
                'before_widget' => '<div id="%1$s" class="widget %2$s">',
                'after_widget' => '</div>',
                'before_title' => '<h5 class="widget-title">',
                'after_title' => '</h5>'
            )
    );
}

function custom_dropdown_choice($args) {
    global $product;
    $args['show_option_none'] = "Select a " . wc_attribute_label($args['attribute'], $product);
    return $args;
}

add_filter('woocommerce_dropdown_variation_attribute_options_args', 'custom_dropdown_choice', 10);

function remove_breadcrumbs() {
    if (!is_product()) {
        remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0);
    }
}

add_filter('woocommerce_before_main_content', 'remove_breadcrumbs');

function moveElement(&$array, $a, $b) {
    $p1 = array_splice($array, $a, 1);
    $p2 = array_splice($array, 0, $b);
    $array = array_merge($p2, $p1, $array);
}

add_filter('woocommerce_show_page_title', '__return_false');

/* * *********************** CHANGE NUMBER OF COLUMNS PER ROW *************************** */
add_filter('loop_shop_columns', 'loop_columns', 999);
if (!function_exists('loop_columns')) {

    function loop_columns() {
        return 3; // 3 products per row
    }

}

/* * ******************* CHANGE WOOCOMMERCE QUANTITY INPUT TO DROPDOWN ******************* */

function woocommerce_quantity_input($args = array(), $product = null, $echo = true) {

    if (is_null($product)) {
        $product = $GLOBALS['product'];
    }

    $defaults = array(
        'input_id' => uniqid('quantity_'),
        'input_name' => 'quantity',
        'input_value' => '1',
        'classes' => apply_filters('woocommerce_quantity_input_classes', array('input-text', 'qty', 'text'), $product),
        'max_value' => apply_filters('woocommerce_quantity_input_max', -1, $product),
        'min_value' => apply_filters('woocommerce_quantity_input_min', 0, $product),
        'step' => apply_filters('woocommerce_quantity_input_step', 1, $product),
        'pattern' => apply_filters('woocommerce_quantity_input_pattern', has_filter('woocommerce_stock_amount', 'intval') ? '[0-9]*' : ''),
        'inputmode' => apply_filters('woocommerce_quantity_input_inputmode', has_filter('woocommerce_stock_amount', 'intval') ? 'numeric' : ''),
        'product_name' => $product ? $product->get_title() : '',
    );

    $args = apply_filters('woocommerce_quantity_input_args', wp_parse_args($args, $defaults), $product);

    // Apply sanity to min/max args - min cannot be lower than 0.
    $args['min_value'] = max($args['min_value'], 0);
    // Note: change 20 to whatever you like
    $args['max_value'] = 0 < $args['max_value'] ? $args['max_value'] : 5;

    // Max cannot be lower than min if defined.
    if ('' !== $args['max_value'] && $args['max_value'] < $args['min_value']) {
        $args['max_value'] = $args['min_value'];
    }

    $options = '';

    for ($count = $args['min_value']; $count <= $args['max_value']; $count = $count + $args['step']) {

        // Cart item quantity defined?
        if ('' !== $args['input_value'] && $args['input_value'] > 1 && $count == $args['input_value']) {
            $selected = 'selected';
        } else
            $selected = '';

        $options .= '<option value="' . $count . '"' . $selected . '>' . $count . '</option>';
    }

    $string = '<div class="st-quantity variation"><select class="qty st-select2" name="' . $args['input_name'] . '">' . $options . '</select></div>';

    if ($echo) {
        echo $string;
    } else {
        return $string;
    }
}

/* * ******************************** CHANGE ORDER AND LAYOUT OF THE CHECKOUT PAGE FORM ********************* */

function st_custom_remove_woo_checkout_fields($fields) {

    // Setting priority
    $fields['billing']['billing_email']['priority'] = 1;
    $fields['billing']['billing_phone']['priority'] = 2;
    $fields['billing']['billing_spacer']['priority'] = 3;
    $fields['billing']['billing_first_name']['priority'] = 4;
    $fields['billing']['billing_last_name']['priority'] = 5;
    $fields['billing']['billing_address_1']['priority'] = 6;
    $fields['billing']['billing_address_2']['priority'] = 7;
    $fields['billing']['billing_city']['priority'] = 8;
    $fields['billing']['billing_country']['priority'] = 9;
    $fields['billing']['billing_state']['priority'] = 10;
    $fields['billing']['billing_postcode']['priority'] = 11;

    // Removing unwanted fields
    unset($fields['billing']['billing_company']);
    unset($fields['order']);

    // Changing Class
    $fields['billing']['billing_email']['class'] = $fields['billing']['billing_address_1']['class'] = $fields['billing']['billing_state']['class'] = $fields['billing']['billing_city']['class'] = ['form-row-first'];
    $fields['billing']['billing_phone']['class'] = $fields['billing']['billing_address_2']['class'] = $fields['billing']['billing_country']['class'] = $fields['billing']['billing_postcode']['class'] = ['form-row-last'];
    $fields['billing']['billing_spacer']['class'] = ['spacer', 'form-row-wide'];
    $fields['billing']['billing_spacer']['label_class'] = 'h4';
    $fields['billing']['billing_email']['class'][] = $fields['billing']['billing_phone']['class'][] = "text-uppercase";

    // Changing Labels
    $fields['billing']['billing_address_2']['label'] = "Apt/Suite/Floor";
    $fields['billing']['billing_city']['label'] = "City";
    $fields['billing']['billing_address_1']['label'] = "Address";
    $fields['billing']['billing_email']['label'] = "Email";
    $fields['billing']['billing_phone']['label'] = "Phone Number";
    $fields['billing']['billing_spacer']['label'] = 'Shipping detail';

    // Changing Placehoders
    $fields['billing']['billing_email']['placeholder'] = 'Email Address';
    $fields['billing']['billing_phone']['placeholder'] = 'Phone Number';
    $fields['billing']['billing_first_name']['placeholder'] = 'First Name';
    $fields['billing']['billing_last_name']['placeholder'] = 'Last name';
    $fields['billing']['billing_address_1']['placeholder'] = 'Address';
    $fields['billing']['billing_address_2']['placeholder'] = 'Apt/Suite/Floor';
    $fields['billing']['billing_city']['placeholder'] = 'City';
    $fields['billing']['billing_country']['placeholder'] = 'Country';
    $fields['billing']['billing_state']['placeholder'] = 'State';
    $fields['billing']['billing_postcode']['placeholder'] = 'Postcode';

    return $fields;
}

add_filter('woocommerce_checkout_fields', 'st_custom_remove_woo_checkout_fields');

function billing_country_update_checkout() {
    if (!is_checkout())
        return;
    ?>
    <script type="text/javascript">
        jQuery(function ($) {
            $('select#billing_country, select#shipping_country').on('change', function () {
                var t = {updateTimer: !1, dirtyInput: !1,
                    reset_update_checkout_timer: function () {
                        clearTimeout(t.updateTimer);
                    },
                    trigger_update_checkout: function () {
                        t.reset_update_checkout_timer(), t.dirtyInput = !1,
                                $(document.body).trigger("update_checkout");
                    }
                };
                $(document.body).trigger('update_checkout');
            });
        });
    </script>
    <?php
}

add_action('wp_footer', 'billing_country_update_checkout', 50);

/* * ****************************  AJAX REMOVE ITEM FROM THE POPUP CART ************************* */

function st_remove_cart_item() {
    $cat_item_key = $_GET['cart-item-key'];
    WC()->cart->remove_cart_item($cat_item_key);
    ob_start();
    $data = st_cart_template();
    wp_send_json($data);
}

add_action('wc_ajax_st_remove_cart_item', 'st_remove_cart_item');

/* * ****************************  AJAX QUANTITY UPDATE FOR THE POPUP CART ************************* */

function st_cart_item_quantity_change() {
    $cat_item_key = $_GET['cart-item-key'];
    $qty = $_GET['qty'];
    WC()->cart->set_quantity($cat_item_key, $qty);
    $data = st_cart_template();
    wp_send_json($data);
}

add_action('wc_ajax_st_cart_item_quantity_change', 'st_cart_item_quantity_change');


/* * ****************************  AJAX POP CART REFRESH ******************************************* */

function st_cart_template($echo = false) {
    if (isset($_GET['echo'])) {
        $echo = $_GET['echo'];
    }
    ob_start();
    if (!WC()->cart->is_empty()) :
        wc_get_template('cart/cart.php');
    else:
        wc_get_template('cart/cart-empty.php');
    endif;
    $data = [];
    $data['cart'] = ob_get_clean();
    $data['subtotal'] = get_cart_subtotal();
    if ($echo) {
        wp_send_json($data);
        exit();
    } else {
        return $data;
    }
}

add_action('wc_ajax_st_cart_template', 'st_cart_template');

/* * ************************** AJAX CART SUBTOTAL ************************************************* */

function get_cart_subtotal() {
    ob_start();
    get_template_part('template-parts/cart/cart', 'subtotal');
    $subtotal = ob_get_clean();
    return $subtotal;
}

/* * ************************* INJECT MODIFIED SHIPPING FRAGMENTS INTO WOO ************************** */

function st_websites_depot_order_fragments_split_shipping($order_fragments) {
    $packages = WC()->shipping()->get_packages();
    $first = true;

    foreach ($packages as $i => $package) {
        $chosen_method = isset(WC()->session->chosen_shipping_methods[$i]) ? WC()->session->chosen_shipping_methods[$i] : '';
        $product_names = array();

        if (count($packages) > 1) {
            foreach ($package['contents'] as $item_id => $values) {
                $product_names[$item_id] = $values['data']->get_name() . ' &times;' . $values['quantity'];
            }
            $product_names = apply_filters('woocommerce_shipping_package_details_array', $product_names, $package);
        }
        ob_start();

        wc_get_template(
                'checkout/shipping-order-review.php', array(
            'package' => $package,
            'available_methods' => $package['rates'],
            'show_package_details' => count($packages) > 1,
            'show_shipping_calculator' => is_cart() && $first,
            'package_details' => implode(', ', $product_names),
            /* translators: %d: shipping package number */
            'package_name' => apply_filters('woocommerce_shipping_package_name', ( ( $i + 1 ) > 1 ) ? sprintf(_x('Shipping %d', 'shipping packages', 'woocommerce'), ( $i + 1)) : _x('Shipping', 'shipping packages', 'woocommerce'), $i, $package),
            'index' => $i,
            'chosen_method' => $chosen_method,
            'formatted_destination' => WC()->countries->get_formatted_address($package['destination'], ', '),
            'has_calculated_shipping' => WC()->customer->has_calculated_shipping(),
                )
        );

        $first = false;
    }
    $order_fragments['.woocommerce-shipping-methods'] = ob_get_clean();
    return $order_fragments;
}

add_filter('woocommerce_update_order_review_fragments', 'st_websites_depot_order_fragments_split_shipping', 10, 1);


/* * **************************  ADD CUSTOM FIELDS INTO WOO NEW PRODUCT SECTION ********************************** */

function st_additional_product_custom_fields_admin() {

    echo '<p class="form-field"><h4>&nbsp;&nbsp;PRODUCT CUSTOM FIELDS</h4></p>';
    // PRODUCT INFORMATION
    woocommerce_wp_textarea_input(
            array(
                'id' => 'st_custom_product_information',
                'data_type' => 'textarea',
                'label' => __('Product Information', 'woocommerce'),
                'placeholder' => __('Product information', 'woocommerce'),
                'description' => __('Enter product information here', 'woocommerce'),
                'desc_tip' => true // Si "true", la description s'affichera en infobulle
            )
    );

    // SHIPPING INFROMATION
    woocommerce_wp_textarea_input(
            array(
                'id' => 'st_custom_shipping_form',
                'data_type' => 'textarea',
                'label' => __('Shipping Form', 'woocommerce'),
                'placeholder' => __('Shipping From', 'woocommerce'),
                'desc_tip' => true,
                'description' => __('Add Shipping location here ', 'woocommerce'),
            )
    );

    // DELIVERY INFORMATION
    woocommerce_wp_textarea_input(
            array(
                'id' => 'st_custom_delivery_and_return',
                'data_type' => 'textarea',
                'label' => __('Delivery & Return', 'woocommerce'),
                'placeholder' => __('Delivery & Return', 'woocommerce'),
                'desc_tip' => true,
                'description' => __('Add delivery and return details here', 'woocommerce'),
            )
    );
}

add_action('woocommerce_product_options_advanced', 'st_additional_product_custom_fields_admin');

/* * ***************************** SAVE CUSTOMLY ADDED FIELDS ON DATABASE ********************************** */

function st_save_product_custom_fields_admin($product_id, $post, $update) {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
        return;

    if ($post->post_type == 'product') {

        // save Product Information
        if (isset($_POST['st_custom_product_information'])) {
            $st_custom_product_information = $_POST['st_custom_product_information'];
            update_post_meta($product_id, 'st_custom_product_information', $st_custom_product_information);
        }
        // Save Shipping From
        if (isset($_POST['st_custom_shipping_form'])) {
            $st_custom_shipping_form = $_POST['st_custom_shipping_form'];
            update_post_meta($product_id, 'st_custom_shipping_form', $st_custom_shipping_form);
        }

        // Save Delivery & Returns
        if (isset($_POST['st_custom_delivery_and_return'])) {
            $st_custom_delivery_and_return = $_POST['st_custom_delivery_and_return'];
            update_post_meta($product_id, 'st_custom_delivery_and_return', $st_custom_delivery_and_return);
        }
    }
}

add_action('save_post', 'st_save_product_custom_fields_admin', 10, 3);

/* * ********************** AJAX REMOVE WISHLIST ITEM ******************************************* */

function st_remove_wishlist_item() {
    $st_w = TInvWL_Public_Wishlist_View::instance();
    $wishlist = $st_w->get_current_wishlist();
    if (!$wishlist['is_owner']) {
        return false;
    }
    $product = $_GET['removal_id'];
    if (0 === $wishlist['ID']) {
        $wlp = TInvWL_Product_Local::instance();
    } else {
        $wlp = new TInvWL_Product($wishlist);
    }
    if (empty($wlp)) {
        return false;
    }
    $product_data = $wlp->get_wishlist(array('ID' => $product));
    $product_data = array_shift($product_data);
    if (empty($product_data)) {
        return false;
    }
    $title = sprintf(__('&ldquo;%s&rdquo;', 'ti-woocommerce-wishlist'), $product_data['data']->get_title());
    if ($wlp->remove($product_data)) {
        add_action('tinvwl_before_wishlist', array(
            'TInvWL_Public_Wishlist_View',
            'check_cart_hash',
                ), 99, 1);
        add_action('woocommerce_set_cart_cookies', array(
            'TInvWL_Public_Wishlist_View',
            'reset_cart_hash',
                ), 99, 1);
        wp_send_json(true);
    } else {
        wp_send_json(false);
    }
    exit();
}

add_action('wc_ajax_st_remove_wishlist_item', 'st_remove_wishlist_item');

/* * ****************************** CHECK PARTICULAR ITEM IN WISHLIST ************************************ */

function st_item_in_wishlist() {
    $product_id = $_GET['product_id'];
    $variation_id = $_GET['variation_id'];

    $st_w = TInvWL_Public_Wishlist_View::instance();
    $wishlist = $st_w->get_current_wishlist();
    if (!$st_w->wishlist_products_helper) {
        $wlp = null;
        if (isset($wishlist['ID']) && 0 === $wishlist['ID']) {
            $wlp = TInvWL_Product_Local::instance();
        } else {
            $wlp = new TInvWL_Product($wishlist);
        }
        $st_w->wishlist_products_helper = $wlp;
    } else {
        $wlp = $st_w->wishlist_products_helper;
    }
    $product_data = array(
        'external' => true,
        'order_by' => 'date',
        'order' => 'DESC',
    );

    $wishlist_items = $wlp->get_wishlist($product_data);

    $exists_in_wishlist = false;
    foreach ($wishlist_items as $choosen) {
        if ($product_id == $choosen['product_id'] && $variation_id == $choosen['variation_id']) {
            $exists_in_wishlist = $choosen;
            break;
        }
    }
    wp_send_json($exists_in_wishlist);
}

add_action('wc_ajax_st_item_in_wishlist', 'st_item_in_wishlist');

/* * ************************************* REMOVE ATTRIBUTES FROM PRODUCT TILE IN CART AND CHECKOUT ************************** */

function st_custom_product_variation_title($should_include_attributes, $product) {
    $should_include_attributes = false;
    return $should_include_attributes;
}

add_filter('woocommerce_product_variation_title_include_attributes', 'st_custom_product_variation_title', 10, 2);

/* * **************************** ADD AN ID TO VIEW CART BUTTON ON WOO ALERT FOR DISPLAY POPUP CART ************************** */

function st_add_id_to_view_cart_button_on_message($message, $products) {
    $altered_message = str_replace('class="', 'id="st-cart-btn" class="', $message);
    return $altered_message;
}

add_filter('wc_add_to_cart_message_html', 'st_add_id_to_view_cart_button_on_message', 10, 2);

/* * *************************** INCREASE RELATED PRODUCTS ON SINGLE PAGE ********************************************** */
add_filter('woocommerce_output_related_products_args', function($args) {
    $args['posts_per_page'] = 12;
    return $args;
});

/* * *********************************** ADD PAYMENT OPTION AFTER SHIPPING DETAILS IN CHECKOUT PAGE *************************** */
remove_action('woocommerce_checkout_order_review', 'woocommerce_checkout_payment', 20);
add_action('woocommerce_review_order_after_shipping', 'woocommerce_checkout_payment', 20);

/* * **************************** PRODUCT CATEGORY TREE ***************************** */

function st_category_heirarchy($parent = 0) {

    $terms = get_terms(
            'product_cat', array(
        'parent' => $parent,
        'hierarchical' => 1,
        'hide_empty' => 0
            )
    );
    $h = [];
    foreach ($terms as $term) {
        $child = st_category_heirarchy($term->term_id);
        $h[$term->term_id] = $child;
    }
    return $h;
}

/* * ************************* THEME SUPPORT FOR WOO ********************************** */
add_action('after_setup_theme', 'st_ofb_setup_woocommerce_support');

function st_ofb_setup_woocommerce_support() {
    add_theme_support('woocommerce');
    add_theme_support('wc-product-gallery-zoom');
    add_theme_support('wc-product-gallery-lightbox');
    add_theme_support('wc-product-gallery-slider');
}

/* * *********************** CHANGE THE POSITION OF WISHLIST ON PRODUCT FOR STYLING ******************* */
remove_action('woocommerce_after_add_to_cart_button', 'tinvwl_view_addto_html', 0);
add_action('woocommerce_after_add_to_cart_form', 'tinvwl_view_addto_html', 0);

function is_vendor_page() {
    if (is_page_template('vendor-dashboard.php') && is_user_logged_in()) {
        return true;
    }

    return false;
}

if (!function_exists('get_page_title')) {
    function get_page_title($object_id = null)
    {
        global $wp;
        $uri = $wp->request;
        switch ($uri) {
            case strpos($uri, 'dashboard/product/edit') !== false:
                return isset($object_id) ? 'Edit Product' : 'Add Product';
            case 'dashboard/product':
                return 'Products';
            case 'dashboard/order':
                return 'Orders';
            case 'dashboard/settings':
                return 'Settings';
            default:
                return get_the_title();
        }
    }
}

if (!function_exists('get_page_subtitle')) {
    function get_page_subtitle($object_id = null)
    {
        global $wp;
        $uri = $wp->request;
        switch ($uri) {
            case strpos($uri, 'dashboard/product/edit') !== false:
                return 'View and edit your products';
            case 'dashboard/product':
                return 'View and edit your products';
            case 'dashboard/order':
                return 'Manage your orders';
            case 'dashboard/settings':
                return 'View and edit your account settings.';
            default:
                return get_field("subtitles");
        }
    }
}

function get_store_name($class = '') {
    $user_data  = get_userdata( get_current_user_id() );
    $store_name = apply_filters( 'wcv_default_store_name', ucfirst( $user_data->display_name ) . __( ' Store', 'wcvendors-pro' ), $user_data );

    // Store Name
    WCVendors_Pro_Form_Helper::input(
        apply_filters(
            'wcv_vendor_store_name',
            array(
                'id'                => '_wcv_store_name',
                'label'             => __( 'Store Name', 'wcvendors-pro' ),
                'placeholder'       => __( 'Enter Store Name', 'wcvendors-pro' ),
                'type'              => 'text',
                'class' => '$class',
                'value'             => $store_name,
                'custom_attributes' => array(
                    'required'                   => '',
                    'data-parsley-error-message' => __( 'Store Name is required' ),
                ),
            )
        )
    );
}

function get_store_url() {
    $value = get_user_meta( get_current_user_id(), '_wcv_company_url', true );

    // Company URL
    WCVendors_Pro_Form_Helper::input(
        apply_filters(
            'wcv_vendor_company_url',
            array(
                'id'          => '_wcv_company_url',
                'label'       => __( 'Website url', 'wcvendors-pro' ),
                'placeholder' => __( 'Enter URL', 'wcvendors-pro' ),
                'type'        => 'url',
                'value'       => $value,
            )
        )
    );
}

function get_store_instagram() {
    $value = get_user_meta( get_current_user_id(), '_wcv_instagram_username', true );

    // Instagram Username
    WCVendors_Pro_Form_Helper::input(
        apply_filters(
            'wcv_vendor_instagram_username',
            array(
                'id'          => '_wcv_instagram_username',
                'label'       => '&nbsp;',
                'placeholder' => __( 'Your Instagram username without the url.', 'wcvendors-pro' ),
                'type'        => 'text',
                'value'       => $value,
            )
        )
    );
}

function get_store_facebook() {
    $value = get_user_meta( get_current_user_id(), '_wcv_facebook_url', true );

    // Facebook URL
    WCVendors_Pro_Form_Helper::input(
        apply_filters(
            'wcv_vendor_facebook_url',
            array(
                'id'          => '_wcv_facebook_url',
                'placeholder' => __( 'Your Facebook url', 'wcvendors-pro' ),
                'type'        => 'text',
                'value'       => $value,
            )
        )
    );
}

function get_owner_title() {
    $value = get_user_meta( get_current_user_id(), 'wcv_owner_title', true );

    WCVendors_Pro_Form_Helper::select(
        apply_filters(
            'wcv_owner_title',
            array(
                'id'                => '_wcv_owner_title',
                'class'             => 'custom-select',
                'value'             => $value,
                'options'           => [
                        'mr' => 'Mr.',
                        'ms' => 'Ms.',
                        'mrs' => 'Mrs.',
                ],
                'label'             => '&nbsp;',
            )
        )
    );
}

function get_owner_firstname() {
    $value = get_user_meta( get_current_user_id(), 'first_name', true );

    WCVendors_Pro_Form_Helper::input(
        apply_filters(
            'first_name',
            array(
                'id'          => 'first_name',
                'placeholder' => __( 'First Name', 'wcvendors-pro' ),
                'type'        => 'text',
                'value'       => $value,
                'show_label'       => false,
                'class'             => 'form-control',
            )
        )
    );
}

function get_owner_lastname() {
    $value = get_user_meta( get_current_user_id(), 'last_name', true );

    WCVendors_Pro_Form_Helper::input(
        apply_filters(
            'last_name',
            array(
                'id'          => 'last_name',
                'placeholder' => __( 'Last Name', 'wcvendors-pro' ),
                'type'        => 'text',
                'value'       => $value,
                'show_label'       => false,
                'class'             => 'form-control',
            )
        )
    );
}

function get_owner_integration() {
    $value = get_user_meta( get_current_user_id(), 'integration_option', true );

    WCVendors_Pro_Form_Helper::select(
        apply_filters(
            '_integration_option',
            array(
                'id'                => 'integration_option',
                'class'             => 'custom-select',
                'value'             => $value,
                'options'           => [
                        '' => 'No Integration',
                        'shopify' => 'Shopify',
                        'bigcommerce' => 'BigCommerce',
                        'googlefeed' => 'Google Shopping Feed'
                ],
                'label'             => '&nbsp;',
            )
        )
    );
}

function get_owner_contact() {
    $value = get_user_meta( get_current_user_id(), '_wcv_store_phone', true );

    // Store Name
    WCVendors_Pro_Form_Helper::input(
        apply_filters(
            'wcv_vendor_store_phone',
            array(
                'id'                => '_wcv_store_phone',
                'placeholder'       => __( 'Store phone number', 'wcvendors-pro' ),
                'type'              => 'text',
                'value'             => $value,
                'custom_attributes' => ['required' => ''],
                'show_label'       => false,
            )
        )
    );

}

function get_business_reg_num() {
    $value = get_user_meta( get_current_user_id(), 'business_reg_num', true );

    WCVendors_Pro_Form_Helper::input(
        apply_filters(
            'business_reg_num',
            array(
                'id'          => 'business_reg_num',
                'placeholder' => __( 'Business Registration Number', 'wcvendors-pro' ),
                'type'        => 'text',
                'value'       => $value,
                'show_label'       => false,
                'class'             => 'form-control',
            )
        )
    );
}

function get_business_abn() {
    $value = get_user_meta( get_current_user_id(), 'business_abn', true );

    WCVendors_Pro_Form_Helper::input(
        apply_filters(
            'business_abn',
            array(
                'id'          => 'business_abn',
                'placeholder' => __( 'ABN', 'wcvendors-pro' ),
                'type'        => 'text',
                'value'       => $value,
                'show_label'       => false,
                'class'             => 'form-control',
            )
        )
    );
}

function get_business_tin() {
    $value = get_user_meta( get_current_user_id(), 'business_tin', true );

    WCVendors_Pro_Form_Helper::input(
        apply_filters(
            'business_tin',
            array(
                'id'          => 'business_tin',
                'placeholder' => __( 'Tax ID Number', 'wcvendors-pro' ),
                'type'        => 'text',
                'value'       => $value,
                'show_label'       => false,
                'class'             => 'form-control',
            )
        )
    );
}

function get_bank_account_name() {
    $value = get_user_meta( get_current_user_id(), 'wcv_bank_account_name', true );

    // Paypal address
    WCVendors_Pro_Form_Helper::input(
        apply_filters(
            'wcv_vendor_bank_account_name',
            array(
                'id'          => 'wcv_bank_account_name',
                'label'       => __( 'Bank Account Name', 'wcvendors-pro' ),
                'placeholder' => __( '', 'wcvendors-pro' ),
                'type'        => 'text',
                'value'       => $value,
            )
        )
    );
} // bank_account_name()

add_action( 'wp_ajax_nopriv_import_google_feed', 'import_google_feed' );
add_action( 'wp_ajax_import_google_feed', 'import_google_feed' );

function import_google_feed() {

    $merchantId = $_POST['merchant_id'];
    $google_feed = new GoogleFeedClient;

    $url = site_url() . '/google-shopping/google-api.php';

    $google_feed->setMerchant($merchantId);

    $google_feed->setURL($url);

     echo $google_feed->getAllProducts();



    die;
}


function create_product_variation( $product_id, $variation_data ){

    $product = wc_get_product($product_id);

    $variation_post = array(
        'post_title'  => $product->get_title(),
        'post_name'   => 'product-'.$product_id.'-variation',
        'post_status' => 'publish',
        'post_parent' => $product_id,
        'post_type'   => 'product_variation',
        'guid'        => $product->get_permalink()
    );


    $variation_id = wp_insert_post( $variation_post );


    $variation = new WC_Product_Variation( $variation_id );


    foreach ($variation_data['attributes'] as $attribute => $term_name )
    {
        $taxonomy = 'pa_'.$attribute;

        if( ! taxonomy_exists( $taxonomy ) ){
            register_taxonomy(
                $taxonomy,
               'product_variation',
                array(
                    'hierarchical' => false,
                    'label' => ucfirst( $attribute ),
                    'query_var' => true,
                    'rewrite' => array( 'slug' => sanitize_title($attribute) )
                )
            );
        }

        if( ! term_exists( $term_name, $taxonomy ) )
            wp_insert_term( $term_name, $taxonomy );

        $term_slug = get_term_by('name', $term_name, $taxonomy )->slug;
        $post_term_names =  wp_get_post_terms( $product_id, $taxonomy, array('fields' => 'names') );

        if( ! in_array( $term_name, $post_term_names ) )
            wp_set_post_terms( $product_id, $term_name, $taxonomy, true );

        update_post_meta( $variation_id, 'attribute_'.$taxonomy, $term_slug );
    }

    if( ! empty( $variation_data['sku'] ) )
        $variation->set_sku( $variation_data['sku'] );

    if( empty( $variation_data['sale_price'] ) ){
        $variation->set_price( $variation_data['regular_price'] );
    } else {
        $variation->set_price( $variation_data['sale_price'] );
        $variation->set_sale_price( $variation_data['sale_price'] );
    }
    $variation->set_regular_price( $variation_data['regular_price'] );

    if( ! empty($variation_data['stock_qty']) ){
        $variation->set_stock_quantity( $variation_data['stock_qty'] );
        $variation->set_manage_stock(true);
        $variation->set_stock_status('');
    } else {
        $variation->set_manage_stock(false);
    }

    $variation->set_weight('');

    $variation->save();
}
