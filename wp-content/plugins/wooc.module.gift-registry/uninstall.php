<?php
//check that file was called from wordpress admin
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit();
}

global $wpdb;

//delete tables
$wpdb->query( "DROP TABLE IF EXISTS " . $wpdb->prefix . "magenest_giftregistry_wishlist" );
$wpdb->query( "DROP TABLE IF EXISTS " . $wpdb->prefix . "magenest_giftregistry_item" );
$wpdb->query( "DROP TABLE IF EXISTS " . $wpdb->prefix . "magenest_giftregistry_event" );

//delete options
$wpdb->query( "DELETE FROM $wpdb->options WHERE option_name LIKE '%magenest_giftregistry_version%';" );
?>