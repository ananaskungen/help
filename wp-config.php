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
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'digitalspace' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', 'root' );

/** Database hostname */
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
define( 'AUTH_KEY',         '<K #(f9h+_qrbAe}BkN[#5;:O3>oQGC;,j8El5L!H9P^0XSplY++u0vIxj JE]UF' );
define( 'SECURE_AUTH_KEY',  '{z`mCAH_3E)g~AVFHz 57E# z5|=!r5|q9D#&_g{e;AA+P?-&0e~.Ug%R9gT,myF' );
define( 'LOGGED_IN_KEY',    '!he`g)sh`mA(dV8R03X`}^i%Hpao[Wnvm(Ny[!Z+a1-*:)?xLM{aMJ4!iZ/DE$4[' );
define( 'NONCE_KEY',        '$yUKIG74V_mlEQxu~iwf>p=C=F<oYDe8%#>=xJjCsMobFWyb*KST{HZDCX)(cn]%' );
define( 'AUTH_SALT',        '*hKVBA> GfS<ok9 [Y?AfXMnNX6&yhl]raN`1x`deBO^$7Ecx/7M!L$aOA4 %R,J' );
define( 'SECURE_AUTH_SALT', 'Efe{^W(<*D(kx]nVt60F45``;S2|WJDj=pp&lb$m!*F4/|T?Dg|/N-0ja?<<o<80' );
define( 'LOGGED_IN_SALT',   'Hvm_^;X,Jv/[xXdV <;ef!~zNTG/IOXaWP*{#=M=V;]Ifxo]^5+Dki#q(SX)q&@`' );
define( 'NONCE_SALT',       '8/O}6;Kxe[=7kg+*5wz#X8hAa04@,72$<Ow+oe98Na|BFQ@2@6Q%Hj{BlTLeMmxk' );

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
define( 'WP_DEBUG', true );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
