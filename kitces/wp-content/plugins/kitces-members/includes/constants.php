<?php
/**
 * Plugin Constants.
 *
 * @package    Kitces_Members
 * @subpackage Kitces_Members/Inlcudes
 * @author     Objectiv
 * @copyright  Copyright (c) 2021, Objectiv
 * @license    GNU General Public License v2 or later
 * @since      1.0.0
 */

if ( ! defined( 'KICTES_MEMBERS_VERSION' ) ) {
	define( 'KICTES_MEMBERS_VERSION', '1.0.0.' );
}

if ( ! defined( 'KICTES_MEMBERS_DIR_URL' ) ) {
	define( 'KICTES_MEMBERS_DIR_URL', trailingslashit( plugin_dir_url( dirname( __FILE__ ) ) ) );
}

if ( ! defined( 'KICTES_MEMBERS_DIR_PATH' ) ) {
	define( 'KICTES_MEMBERS_DIR_PATH', trailingslashit( plugin_dir_path( dirname( __FILE__ ) ) ) );
}

if ( ! defined( 'KICTES_MEMBERS_PRFIX' ) ) {
	define( 'KICTES_MEMBERS_PRFIX', 'kitces_members' );
}

if ( ! defined( 'KICTES_MEMBERS_ENVIRONMENT' ) ) {
	define( 'KICTES_MEMBERS_ENVIRONMENT', getenv( 'ENVIRONMENT' ) );
}

if ( ! defined( 'KICTES_MEMBERS_CHARGEBEE_API_KEY' ) ) {
	if ( KICTES_MEMBERS_ENVIRONMENT !== 'production' ) {
		define( 'KICTES_MEMBERS_CHARGEBEE_API_KEY', getenv( 'CHARGEBEE_DEV_API_KEY' ) );
	} else {
		define( 'KICTES_MEMBERS_CHARGEBEE_API_KEY', getenv( 'CHARGEBEE_API_KEY' ) );
	}
}

if ( ! defined( 'KICTES_MEMBERS_CHARGEBEE_SITE' ) ) {
	if ( KICTES_MEMBERS_ENVIRONMENT !== 'production' ) {
		define( 'KICTES_MEMBERS_CHARGEBEE_SITE', getenv( 'CHARGEBEE_DEV_SITE' ) );
	} else {
		define( 'KICTES_MEMBERS_CHARGEBEE_SITE', getenv( 'CHARGEBEE_SITE' ) );
	}
}

if ( ! defined( 'KICTES_MEMBERS_AC_API_URL' ) ) {
	define( 'KICTES_MEMBERS_AC_API_URL', getenv( 'AC_API_URL' ) );
}

if ( ! defined( 'KICTES_MEMBERS_AC_API_KEY' ) ) {
	define( 'KICTES_MEMBERS_AC_API_KEY', getenv( 'AC_API_KEY' ) );
}
