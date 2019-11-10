<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
switch ($_SERVER['SERVER_NAME']) {

    case 'ofb':
        define('DB_NAME', 'ofb');
        define('DB_USER', 'root');
        define('DB_PASSWORD', 'root');
        define('DB_HOST', 'localhost');
        define('WP_DEBUG', false); // TODO: Should be true before go live
        define('DB_CHARSET', 'utf8');
        define('DB_COLLATE', '');
        define('FS_METHOD', 'direct');
        define('WP_SITEURL', 'http://' . $_SERVER['SERVER_NAME'] . '/');
        define('WP_HOME', 'http://' . $_SERVER['HTTP_HOST'] . '/');
    	break;

}

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'S-J!vz|BGSB_#EhN-<g$?5j{6*NBe%27@24Kt96+2D@V-Gm@4f>op)RX7+&P:tB8');
define('SECURE_AUTH_KEY',  '}l+uY=);R=_.):bOi2NMmHjn;14$wW#.t%AM0$@; +z3rcHK6}[Ti)67$Y/Z`x%~');
define('LOGGED_IN_KEY',    'mX~W0jdy,-OtVC#D t2^]BMyKWE+eEF|MaQ_a3Qqz$pard*[3qbT3a.-7W5c85!~');
define('NONCE_KEY',        '1{tu?qjFoyzeL}@p^>(W9NqXmc__0PDaRil4ZYYPa>vT7<WSB7h9{#&ziyUl1+QG');
define('AUTH_SALT',        'aP>[T;m:Oyx-dnIht!>5i=Grn*]Ne*+Lsv-bFFlr<;>)+6/s uym+suaw_+=cN,y');
define('SECURE_AUTH_SALT', 'W#Ch&}wsi$Fj$6kCEB].r+F rH):x~/tyvs(U($R0GDu:;aLf!vFva$|3Bo8l{](');
define('LOGGED_IN_SALT',   'Dw?nbt2;>*x1dX[D&[f7MAs-)dF011Zj:RyH@ p7=pO>mRYU@_0ui$:EGkO*u(xh');
define('NONCE_SALT',       ':wYv|sLJBGj2Sdrvc5#seiR4TAfa24P8SEzw*O>mMquaMYN2jX%fCo{5A.GlPbH2');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'ofbb_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define( 'WP_DEBUG', false );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/** Sets up WordPress vars and included files. */
require_once( ABSPATH . 'wp-settings.php' );
