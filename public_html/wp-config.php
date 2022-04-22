<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'insotrav_wp976' );

/** MySQL database username */
define( 'DB_USER', 'insotrav_wp976' );

/** MySQL database password */
define( 'DB_PASSWORD', 'K(LFSs112)@q].pw' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         '3yz1je4px7fmkhxwebaqsk1bdmkcmbuc4xytncne7xomodrtmd23qyzwub6rxmiy' );
define( 'SECURE_AUTH_KEY',  '6xinz81q5cyx1dhqnaetjwbqrggqq4jzl4sxgmc1xxensgavnvgmjwx3g2hqcg1c' );
define( 'LOGGED_IN_KEY',    '8enq3lohkwsyrruafkkc7s3u4vkgg1ao90pegjgsacwscyttxdfmosjfe8xhbpik' );
define( 'NONCE_KEY',        'jp64oiuewbmfpikimxttwhopdblznbrajzkwz4ffbp7e68knnlzm02eimq3hzl0o' );
define( 'AUTH_SALT',        'acavp9yjgfjpmi4xbi13kgeiinboooajup9vntbxgtt8hu2jhsarwhxw2kecxsqe' );
define( 'SECURE_AUTH_SALT', 'bchxvsu1vnhwoc7hxnwfa7nfzfukkkyukjr4mduhasgxznsborxhvt7agoawjt7a' );
define( 'LOGGED_IN_SALT',   'bylw7mwdkys5rx2k3makkrgjfejmpgeockowsqjzmtwqi05f5r6owo6o1keo7hxv' );
define( 'NONCE_SALT',       'eejz1zgiytpjmmbpcltzcijvton0yl5z7327z5a0kau95n2jz0njkf5rqqq1thqs' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wpes_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
