<?php
/**
 * Plugin Constants.
 *
 * @package    Fp_Account_Settings
 * @subpackage Fp_Account_Settings/Inlcudes
 * @author     Objectiv
 * @copyright  Copyright (c) 2021, Objectiv
 * @license    GNU General Public License v2 or later
 * @since      1.0.0
 */

if ( ! defined( 'FP_ACCOUNT_SETTINGS_VERSION' ) ) {
	define( 'FP_ACCOUNT_SETTINGS_VERSION', '1.0.0.' );
}

if ( ! defined( 'FP_ACCOUNT_SETTINGS_DIR_URL' ) ) {
	define( 'FP_ACCOUNT_SETTINGS_DIR_URL', trailingslashit( plugin_dir_url( dirname( __FILE__ ) ) ) );
}

if ( ! defined( 'FP_ACCOUNT_SETTINGS_DIR_PATH' ) ) {
	define( 'FP_ACCOUNT_SETTINGS_DIR_PATH', trailingslashit( plugin_dir_path( dirname( __FILE__ ) ) ) );
}

if ( ! defined( 'FP_ACCOUNT_SETTINGS_PREFIX' ) ) {
	define( 'FP_ACCOUNT_SETTINGS_PREFIX', 'fp_account_settings' );
}
