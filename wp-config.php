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
define( 'AUTH_KEY',         'hV|8D3Yq#R[CQ|&xpuS~4TnJiCm+q_Y@cn0K-R?Q*jqBJG-3)!p81X}2Y7]fky]4' );
define( 'SECURE_AUTH_KEY',  'ExLI}Y4{u#5b_:~Ou89*4=H|lY.}cWk*S#OKcner.8&}h[UgMl1Pb[isxotW%8jJ' );
define( 'LOGGED_IN_KEY',    '=%`~LYKc#|o,.^)DOaJ_Xo(F+O)HLT-z:y<`]Wxl1Ctv`.kNj=e=]W>lQiJWq~7{' );
define( 'NONCE_KEY',        '1w@Ec9KG^u?U/teN!rp]URw~qw(zmY=/FrqhnqPgE$4deYv~&*{2]%.Dh;tRP:_$' );
define( 'AUTH_SALT',        'kNPJr*HEUDmX^b?QQu#6K2gqw}3#-U;,^i (5:26vkBD)`l:Q%m=, >}#HRw}6l+' );
define( 'SECURE_AUTH_SALT', 'vJe>K+)|@t6FfE[2Q-f<a+@JEI=Mn#7H*BUV?ZzDBA3u57#btaj}p+!cX*xz>I)i' );
define( 'LOGGED_IN_SALT',   'i|`-y{!`I Y=Q?U-EG~MU+dPHd)yRz/ 3[)&=^y fpMd7)b#/{R#Yu4JU76.63p9' );
define( 'NONCE_SALT',       '6{4p>q0)@R=|1BU/P53L9YPX!kSs3 58sOF<2BF`7ou!0[k?l.y_=9Rk.?$x,PkJ' );

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



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
