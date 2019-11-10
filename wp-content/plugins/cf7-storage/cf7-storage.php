<?php
/**
 * Plugin Name: Contact Form 7 Storage
 * Description: Store all Contact Form 7 submissions (including attachments) in your WordPress dashboard.
 * Plugin URI: https://preseto.com/plugins/contact-form-7-storage
 * Author: Kaspars Dambis
 * Author URI: https://kaspars.net
 * Version: 2.0.3
 * Tested up to: 5.2
 * License: GPL2
 * Text Domain: cf7-storage
 */

$src_dir = dirname( __FILE__ );

// Until we have autoloading.
include_once $src_dir . '/src/class-cf7-storage-options-store.php';
include_once $src_dir . '/src/class-cf7-storage-meta-store.php';

include_once $src_dir . '/src/class-cf7-storage-plugin-settings-page.php';
include_once $src_dir . '/src/class-cf7-storage-plugin-settings.php';

include_once $src_dir . '/src/class-cf7-storage-form-settings-page.php';
include_once $src_dir . '/src/class-cf7-storage-form-settings.php';

include_once $src_dir . '/src/class-cf7-storage-plugin.php';
include_once $src_dir . '/src/class-cf7-storage.php';

$plugin = new Cf7_Storage_Plugin( __FILE__ );
$cf7_storage = new Cf7_Storage( $plugin );

// And we have a liftoff.
add_action( 'plugins_loaded', array( $cf7_storage, 'init' ) );
