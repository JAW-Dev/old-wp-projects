<?php
/**
 * Plugin Constants.
 *
 * @package    Kitces
 * @subpackage Kitces/Inlcudes
 * @author     Objectiv
 * @copyright  Copyright (c) 2020, Objectiv
 * @license    GPL-2.0
 * @since      1.0.0
 */

if ( ! defined( 'KITCES_VERSION' ) ) {
	define( 'KITCES_VERSION', '1.0.0.' );
}

if ( ! defined( 'KITCES_DIR_URL' ) ) {
	define( 'KITCES_DIR_URL', trailingslashit( plugin_dir_url( dirname( __FILE__ ) ) ) );
}

if ( ! defined( 'KITCES_DIR_PATH' ) ) {
	define( 'KITCES_DIR_PATH', trailingslashit( plugin_dir_path( dirname( __FILE__ ) ) ) );
}

if ( ! defined( 'KITCES_PRFIX' ) ) {
	define( 'KITCES_PRFIX', 'kitces' );
}

if ( ! defined( 'KITCES_ENVIRONMENT' ) ) {
	define( 'KITCES_ENVIRONMENT', 'production' );
}

if ( ! defined( 'KITCES_CHARGEBEE_API_KEY' ) ) {
	if ( KITCES_ENVIRONMENT !== 'production' ) {
		define( 'KITCES_CHARGEBEE_API_KEY', 'test_I0bJ2FtqnsZAF9XAZiRcuJcd9KRbcByOU1' );
	} else {
		define( 'KITCES_CHARGEBEE_API_KEY', 'live_rFgkk8fXHznL4tvIWdavqz0HQROD6cdFF' );
	}
}

if ( ! defined( 'KITCES_CHARGEBEE_SITE' ) ) {
	if ( KITCES_ENVIRONMENT !== 'production' ) {
		define( 'KITCES_CHARGEBEE_SITE', 'kitces-test' );
	} else {
		define( 'KITCES_CHARGEBEE_SITE', 'kitces' );
	}
}

if ( ! defined( 'KITCES_AC_API_URL' ) ) {
	define( 'KITCES_AC_API_URL', 'https://kitces.api-us1.com' );
}

if ( ! defined( 'KITCES_AC_API_KEY' ) ) {
	define( 'KITCES_AC_API_KEY', 'befc79f7a64aad8af849ad6f8d417c99d00b879807377d39ab36d98f45e05738161a83c3' );
}

if ( ! defined( 'KITCES_EXPONEA_PROJECT_TOKEN' ) ) {
	define( 'KITCES_EXPONEA_PROJECT_TOKEN', 'b59db11e-e4c9-11eb-a9c2-f203e23dca9e' );
}

if ( ! defined( 'KITCES_EXPONEA_API_KEY' ) ) {
	define( 'KITCES_EXPONEA_API_KEY', '36pdjqj7fmn8h661o57gor2o7hlj8nkgdbqr1u5alnshfz6ddlcegtarkyg8furd' );
}

if ( ! defined( 'KITCES_EXPONEA_API_SECRET' ) ) {
	define( 'KITCES_EXPONEA_API_SECRET', '5kmxrfvj93hfpuuf7rz5seixe8hlnt65w3g3ukm5o0qzqylj3ds6es2golf71wgb' );
}
