<?php
/**
 * Plugin Name: The Post Grid Pro
 * Plugin URI: http://demo.radiustheme.com/wordpress/plugins/the-post-grid-pro/
 * Description: Fast & Easy way to display WordPress post in Grid, List & Isotope view ( filter by category, tag, author..)  without a single line of coding.
 * Author: RadiusTheme
 * Version: 3.6.0
 * Text Domain: the-post-grid-pro
 * Domain Path: /languages
 * Author URI: https://radiustheme.com/
 */
if (!defined('ABSPATH')) {
    exit;
}

$plugin_data = get_file_data(__FILE__, array('name' => 'Plugin Name', 'version' => 'Version', 'author' => 'Author'), false);
define('RT_TPG_PRO_VERSION', $plugin_data['version']);
define('RT_TPG_PRO_AUTHOR', $plugin_data['author']);
define('EDD_RT_TPG_STORE_URL', 'https://www.radiustheme.com');
define('EDD_RT_TPG_ITEM_ID', 3265);
define('EDD_RT_TPG_ITEM_ITEM_NAME', $plugin_data['name']);
define('RT_THE_POST_GRID_PRO_PLUGIN_PATH', dirname(__FILE__));
define('RT_THE_POST_GRID_PRO_PLUGIN_ACTIVE_FILE_NAME', __FILE__);
define('RT_THE_POST_PRO_GRID_PLUGIN_URL', plugins_url('', __FILE__));
define('RT_THE_POST_GRID_PRO_PLUGIN_SLUG', basename(dirname(__FILE__)));
define('RT_THE_POST_GRID_PRO_LANGUAGE_PATH', dirname(plugin_basename(__FILE__)) . '/languages');

require('lib/init.php');