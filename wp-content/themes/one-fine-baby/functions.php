<?php

// Define the version so we can easily replace it throughout the theme
define( 'ONE_FINE_BABY_VERSION', 1.0 );


/*------------------------- ADD RSS FEED -------------------------*/

add_theme_support( 'automatic-feed-links' );


/*------------------------- IMAGE SUPPORT -------------------------*/

add_theme_support( 'post-thumbnails' );


/*------------------------- MENUS -------------------------*/
register_nav_menus( 
	array(
		'primary'	=>	__( 'Main Menu', 'ofb' ),
		'mobile-menu'	=>	__( 'Mobile Menu', 'ofb' ),
	)
);


/*------------------------- WIDGETS -------------------------*/

if ( function_exists('register_sidebar') )
  register_sidebar(array(
    'name' => 'Little Black Book - Melbourne',
    'before_widget' => '<div class = "widgetizedArea">',
    'after_widget' => '</div>',
    'before_title' => '<h3>',
    'after_title' => '</h3>',
  )
);

if ( function_exists('register_sidebar') )
  register_sidebar(array(
    'name' => 'Little Black Book - Sydney',
    'before_widget' => '<div class = "widgetizedArea">',
    'after_widget' => '</div>',
    'before_title' => '<h3>',
    'after_title' => '</h3>',
  )
);

if ( function_exists('register_sidebar') )
  register_sidebar(array(
    'name' => 'Footer - Sponsors',
    'before_widget' => '<div class = "widgetizedArea">',
    'after_widget' => '</div>',
    'before_title' => '<h3>',
    'after_title' => '</h3>',
  )
);

if ( function_exists('register_sidebar') )
  register_sidebar(array(
    'name' => 'Footer - Left',
    'before_widget' => '<div class = "widgetizedArea">',
    'after_widget' => '</div>',
    'before_title' => '<h3>',
    'after_title' => '</h3>',
  )
);

if ( function_exists('register_sidebar') )
  register_sidebar(array(
    'name' => 'Footer - Middle Left',
    'before_widget' => '<div class = "widgetizedArea">',
    'after_widget' => '</div>',
    'before_title' => '<h3>',
    'after_title' => '</h3>',
  )
);

if ( function_exists('register_sidebar') )
  register_sidebar(array(
    'name' => 'Footer - Middle Right',
    'before_widget' => '<div class = "widgetizedArea">',
    'after_widget' => '</div>',
    'before_title' => '<h3>',
    'after_title' => '</h3>',
  )
);

if ( function_exists('register_sidebar') )
  register_sidebar(array(
    'name' => 'Footer - Right',
    'before_widget' => '<div class = "widgetizedArea">',
    'after_widget' => '</div>',
    'before_title' => '<h3>',
    'after_title' => '</h3>',
  )
);


/*------------------------- ENQUEUE STYLES AND SCRIPTS -------------------------*/

function ofb_scripts()  { 

	wp_enqueue_style('style.css', get_stylesheet_directory_uri() . '/style.css');
	
	wp_enqueue_style( 'prefix-font-awesome', 'http://netdna.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.css', array(), '4.2.0' );
	
	wp_enqueue_script( 'ofb-fitvid', get_template_directory_uri() . '/js/jquery.fitvids.js', array( 'jquery' ), ONE_FINE_BABY_VERSION, true );
	
	wp_enqueue_script( 'ofb', get_template_directory_uri() . '/js/theme.min.js', array(), ONE_FINE_BABY_VERSION, true );
	
}
add_action( 'wp_enqueue_scripts', 'ofb_scripts' );


/*------------------------- REMOVE ADMIN MENU ITEMS -------------------------*/

function sb_remove_admin_menus (){
  // Check that the built-in WordPress function remove_menu_page() exists in the current installation
  if ( function_exists('remove_menu_page') ) { 
    /* Remove unwanted menu items by passing their slug to the remove_menu_item() function.
    You can comment out the items you want to keep. */
    remove_menu_page('edit-comments.php'); // Comments
  }
}
// Add our function to the admin_menu action
add_action('admin_menu', 'sb_remove_admin_menus');


/*------------------------- DISABLE NEW EDITOR -------------------------*/

add_filter('use_block_editor_for_post_type', 'd4p_32752_completly_disable_block_editor');
function d4p_32752_completly_disable_block_editor($use_block_editor) {
  return false;
}


/*------------------------- CUSTOM POSTS -------------------------*/

function codex_custom_init() {

register_post_type(
    'event', array(
        'labels' => array('name' => __( 'Events' ), 'singular_name' => __( 'Event' ) ),
        'public' => true,
        'has_archive' => true,
		'hierarchical' => true,
		'taxonomies'  => array( 'event-categories'),
        'supports' => array('title', 'editor', 'thumbnail', 'page-attributes')
    )
);

register_post_type(
    'vendor', array(
        'labels' => array('name' => __( 'Vendors' ), 'singular_name' => __( 'Vendor' ) ),
        'public' => true,
        'has_archive' => true,
		'hierarchical' => true,
		'taxonomies'  => array( 'vendor-categories', 'vendor-tags'),
        'supports' => array('title', 'thumbnail', 'redirect-updates')
    )
);
	
register_post_type(
    'banner', array(
        'labels' => array('name' => __( 'Ad Banners' ), 'singular_name' => __( 'Ad Banner' ) ),
        'public' => true,
        'has_archive' => true,
		'hierarchical' => true,
		'taxonomies'  => array( 'banner-categories'),
        'supports' => array('title', 'thumbnail')
    )
);

}
add_action( 'init', 'codex_custom_init' );


function event_categories_taxonomy() {
 
  $event_cats = array(
    'name' => _x( 'Event Categories', 'taxonomy general name' ),
    'singular_name' => _x( 'Event Category', 'taxonomy singular name' ),
    'search_items' =>  __( 'Search Event Categories' ),
    'all_items' => __( 'All Event Categories' ),
    'parent_item' => __( 'Parent Event Category' ),
    'parent_item_colon' => __( 'Parent Event Category:' ),
    'edit_item' => __( 'Edit Event Category' ), 
    'update_item' => __( 'Update Event Category' ),
    'add_new_item' => __( 'Add New Event Category' ),
    'new_item_name' => __( 'New Event Category' ),
    'menu_name' => __( 'Event Categories' ),
  );    
 
  register_taxonomy('event_categories',array('event'), array(
    'hierarchical' => true,
    'labels' => $event_cats,
    'show_ui' => true,
    'show_admin_column' => true,
    'query_var' => true,
    'rewrite' => array( 'slug' => 'event categories' ),
  ));
 
}
add_action( 'init', 'event_categories_taxonomy', 0 );


function vendor_categories_taxonomy() {
 
  $vendor_cats = array(
    'name' => _x( 'All', 'taxonomy general name' ),
    'singular_name' => _x( 'Category', 'taxonomy singular name' ),
    'search_items' =>  __( 'Categories' ),
    'all_items' => __( 'All Vendor Categories' ),
    'parent_item' => __( 'Parent Vendor Category' ),
    'parent_item_colon' => __( 'Parent Vendor Category:' ),
    'edit_item' => __( 'Edit Vendor Category' ), 
    'update_item' => __( 'Update Vendor Category' ),
    'add_new_item' => __( 'Add New Vendor Category' ),
    'new_item_name' => __( 'New Vendor Category' ),
    'menu_name' => __( 'Vendor Categories' ),
  );    
 
  register_taxonomy('vendor_categories',array('vendor'), array(
    'hierarchical' => true,
    'labels' => $vendor_cats,
    'show_ui' => true,
    'show_admin_column' => true,
    'query_var' => true,
    'rewrite' => array( 'slug' => 'vendor categories' ),
  ));
  
  $vendor_tags = array(
    'name' => _x( 'Vendor Tags', 'taxonomy general name' ),
    'singular_name' => _x( 'Vendor Tag', 'taxonomy singular name' ),
    'search_items' =>  __( 'Vendor Tags' ),
    'all_items' => __( 'All Vendor Tags' ),
    'parent_item' => __( 'Parent Vendor Tag' ),
    'parent_item_colon' => __( 'Parent Vendor Tag:' ),
    'edit_item' => __( 'Edit Vendor Tag' ), 
    'update_item' => __( 'Update Vendor Tag' ),
    'add_new_item' => __( 'Add New Vendor Tag' ),
    'new_item_name' => __( 'New Vendor Tag' ),
    'menu_name' => __( 'Vendor Tags' ),
  );

  register_taxonomy('vendor_tags','vendor',array(
    'hierarchical' => true,
    'labels' => $vendor_tags,
    'show_ui' => true,
    'show_admin_column' => true,
    'update_count_callback' => '_update_post_term_count',
    'query_var' => true,
    'rewrite' => array( 'slug' => 'vendor tags' ),
  ));
 
}
add_action( 'init', 'vendor_categories_taxonomy', 0 );


function banner_categories_taxonomy() {
 
  $banner_cats = array(
    'name' => _x( 'Banner Ad Categories', 'taxonomy general name' ),
    'singular_name' => _x( 'Banner Ad Category', 'taxonomy singular name' ),
    'search_items' =>  __( 'Search Banner Ad Categories' ),
    'all_items' => __( 'All Banner Ad Categories' ),
    'parent_item' => __( 'Parent Banner Ad Category' ),
    'parent_item_colon' => __( 'Parent Banner Ad Category:' ),
    'edit_item' => __( 'Edit Banner Ad Category' ), 
    'update_item' => __( 'Update Banner Ad Category' ),
    'add_new_item' => __( 'Add New Banner Ad Category' ),
    'new_item_name' => __( 'New Banner Ad Category' ),
    'menu_name' => __( 'Categories' ),
  );    
 
  register_taxonomy('banner_categories',array('banner'), array(
    'hierarchical' => true,
    'labels' => $banner_cats,
    'show_ui' => true,
    'show_admin_column' => true,
    'query_var' => true,
    'rewrite' => array( 'slug' => 'banner categories' ),
  ));
 
}
add_action( 'init', 'banner_categories_taxonomy', 0 );