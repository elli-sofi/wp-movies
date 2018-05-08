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
define('DB_NAME', 'wp_test');

/** MySQL database username */
define('DB_USER', 'www');

/** MySQL database password */
define('DB_PASSWORD', 'www');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'Q U[4bEmxoC)e#HVR#Q$N(hptjAr-N(XCU{gy+-=k7YP9>zrtb!|HRN[77&LStwu');
define('SECURE_AUTH_KEY',  '.qXy1{+OeTT .nnwKu):szN<ioTCY=`/u_<&KooR,_gHyH(=LY9Z9|||fPYw$`u>');
define('LOGGED_IN_KEY',    'f9pic[)yC`K<CTSiHk^D|GI[<R%X=JFeXm3qmmqj(>~!vDS;P9X:D Xm]Hk`qjl^');
define('NONCE_KEY',        'rvoR|YW>+)Hk[wsda.NP^LWQ6 (?uUN8Gb[P7X@{W&:OVJxpE0F5o6Je^$2XK[=`');
define('AUTH_SALT',        '?+?1uVjDzn@$g,=6I60+&CYEG_wOQa-[j8Sy?R;Ek)>]:)_$Pj;h)2EX[S[%k;OL');
define('SECURE_AUTH_SALT', '(5<2a_I`Z_/2aq|m)V6jwUvAxr1BP!L?3(#;}M<1_bSNv|hj3]UN&8P``]^w<6mg');
define('LOGGED_IN_SALT',   '>n7>mu7[Y0.&#FpC1d|zSRgOBY cGSe8 KBV2|a+CWSm2[eH*7301n.@SNuHC;82');
define('NONCE_SALT',       'aQe0V]kT:0%~y-~FTE3#bfcYJ,7|S|W;i!aukGrbcI@<i6R[4qWnU(gp~E2H}>-!');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

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
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
