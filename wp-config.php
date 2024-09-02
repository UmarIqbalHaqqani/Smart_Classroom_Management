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
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * Localized language
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */
define('WP_TEMP_DIR', dirname(__FILE__) . '/wp-content/temp/');
// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'u141005733_p8a1z' );

/** Database username */
define( 'DB_USER', 'u141005733_YBxWB' );

/** Database password */
define( 'DB_PASSWORD', 'tY6I3J00YL' );

/** Database hostname */
define( 'DB_HOST', '127.0.0.1' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

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
define( 'AUTH_KEY',          '`1qO9oC,>_y{ L<ZI_MVi S<`i^lv<w!(R3?u/~j(~~?=pQ4AM2cs2L&I-ZPZ~&{' );
define( 'SECURE_AUTH_KEY',   'i6PWFMn%K#N)z|w)ms<Wxd*_3Uj!sySaQB CvQS~=f.tnG3+QK0}<Q-j5g~9FZ?V' );
define( 'LOGGED_IN_KEY',     '^T1XV;iC05$.[OH(8!,=D?9F(-(&YW#U44W92~#nj?Uw,%4ns7Hh/lFaL9zo9=Xi' );
define( 'NONCE_KEY',         '%Q_J5.}y4B:$zF,0@A/E,?$C;*!Htkdk;o53>8zAyl_D)s[5J7uaz&c9@?RbNUx`' );
define( 'AUTH_SALT',         ':q[6w;;g9MI2;`J$NfIHmRw#h_;[vy%Sp@!#j@bMb9kqWXhUCO]=UOp~v-,Tcztj' );
define( 'SECURE_AUTH_SALT',  'Ux6*nuv, N6}XXUka=`@`Kux/`R@m*I2oz4`xY^.{=aMK)|X6,1[*q2,x1YQFnI1' );
define( 'LOGGED_IN_SALT',    '$han./,(4n^G-zN}(<qt}MoX[g``.Hexgu*4`|.un7>hx.rIyq-X_swke7@rs~{V' );
define( 'NONCE_SALT',        'X4Uc9Jlwl(%;qVrL}*/6V(UhWGc|.4(1EP1vR^9S<r<Y_hy-:l:xxJ2pIz;huTUn' );
define( 'WP_CACHE_KEY_SALT', 'uieIX4es}V.&aX95O_[o)e~5F~+>a2pbO&-tT~-aj7v7J`50]}!Sae%[AG* F?~w' );


/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
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
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );


/* Add any custom values between this line and the "stop editing" line. */



define( 'FS_METHOD', 'direct' );
define( 'WP_AUTO_UPDATE_CORE', 'minor' );
/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
