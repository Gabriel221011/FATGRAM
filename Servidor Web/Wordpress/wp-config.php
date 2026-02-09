<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the website, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'wordpress' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', 'usuario2' );

/** Database hostname */
define( 'DB_HOST', 'fatgram-rds.cqg5ve3796m7.us-east-1.rds.amazonaws.com' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

define('WP_HOME','http://23.21.75.85');
define('WP_SITEURL','http://23.21.75.85');

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
define( 'AUTH_KEY',         'i^Z75GEAC%#zWy $}v5Uvd0d;+Crhc{V|)_9Pz1<3hGET+)g(>;RC_H]^SPYLuyb' );
define( 'SECURE_AUTH_KEY',  '9{0Kc{/SzPB{HiU*jU2N.W<;fYjZ,d?MY4{6#yB%;kKdVhe7`-OfGcbjN#GL8w-U' );
define( 'LOGGED_IN_KEY',    '2B|$~J:#nhwc>J+uZ0RS)TiTY7pt1vA8R;(AZF#N !DvIUyh:Cxbd!(2cTu.eK|V' );
define( 'NONCE_KEY',        'QBC`!^>=tj4aV3]QKiCf}-N<?Xz)g 6VkOY!WH.#q.K)M/n[ g]>!eg/R[8ot> }' );
define( 'AUTH_SALT',        ']Zj#A74zQ~u~~PB@RP6aHgUYl:~Q}VYA-7b,wSuFfp;$1H=[x=*U/l9xghPi0bIO' );
define( 'SECURE_AUTH_SALT', 'sdVKIAr_@,yr}BID;_?Z v4u5WU],8-x#YDalqFWXUBKa:4pRol)t|.yj7jhKg&D' );
define( 'LOGGED_IN_SALT',   'Q;*YmDCWT]2*tHe@q<wT,S,X{;PuJt W0Gs4s{J7,<T:HrSYQ7BS2Q~z~di7)T;9' );
define( 'NONCE_SALT',       'Mx|KP#l,:ii|S+,[f1G}t25Bbx!6iRo%21q4V%Db]A4OC%vp:hVmc6a(a@IY:F~g' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 *
 * At the installation time, database tables are created with the specified prefix.
 * Changing this value after WordPress is installed will make your site think
 * it has not been installed.
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/#table-prefix
 */
$table_prefix = 'wp_';

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
 * @link https://developer.wordpress.org/advanced-administration/debug/debug-wordpress/
 */
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



define( 'SURECART_ENCRYPTION_KEY', '2B|$~J:#nhwc>J+uZ0RS)TiTY7pt1vA8R;(AZF#N !DvIUyh:Cxbd!(2cTu.eK|V' );
/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';

