<?php
/**
 * Plugin Name: fpPathfinder Account Settings
 * Description: Member Account Settings
 * Version:     1.0.0
 * Author:      Objectiv
 * Author URI:  Member Account Settings
 * License:     GNU General Public License v2 or later
 * Text Domain: fp-account-settings
 * Domain Path: /languages
 *
 * @package    Fp_Account_Settings
 * @author     Objectiv
 * @copyright  Copyright (c) 2021, Objectiv
 * @license    GNU General Public License v2 or later
 * @version    1.0.0
 */

namespace FpAccountSettings;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

/**
 * Autoloader
 */
if ( file_exists( trailingslashit( plugin_dir_path( __FILE__ ) ) . trailingslashit( 'vendor' ) . 'autoload.php' ) ) {
	require_once trailingslashit( plugin_dir_path( __FILE__ ) ) . trailingslashit( 'vendor' ) . 'autoload.php';
}

/**
 * Constants
 */
if ( file_exists( trailingslashit( plugin_dir_path( __FILE__ ) ) . trailingslashit( 'includes' ) . 'constants.php' ) ) {
	require_once trailingslashit( plugin_dir_path( __FILE__ ) ) . trailingslashit( 'includes' ) . 'constants.php';
}

/**
 * FP Account Settings
 *
 * @author Objectiv
 * @since  1.0.0
 */
class Fp_Account_Settings {

	/**
	 * Singleton instance of plugin.
	 *
	 * @var   static
	 * @since 1.0.0
	 */
	protected static $single_instance = null;

	/**
	 * Creates or returns an instance of this class.
	 *
	 * @author Objectiv
	 * @since  1.0.0
	 *
	 * @return static
	 */
	public static function get_instance() {
		if ( null === self::$single_instance ) {
			self::$single_instance = new self();
		}

		return self::$single_instance;
	}

	/**
	 * Run
	 *
	 * @author Objectiv
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function run() {
		// Load translated strings for plugin.
		load_plugin_textdomain( 'fp-account-settings', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

		// Init Classes.
		new Includes\Classes\Init();
	}
}

/**
 * Return an instance of the plugin class.
 *
 * @author Objectiv
 * @since  1.0.0
 *
 * @return Fp_Account_Settings instance of plugin class.
 */
function fp_account_settings() {
	return Fp_Account_Settings::get_instance();
}

if ( class_exists( 'genesis' ) ) {
	// Required to use Genesis functionality.
	add_action( 'after_setup_theme', array( fp_account_settings(), 'run' ) );
} else {
	add_action( 'plugins_loaded', array( fp_account_settings(), 'run' ) );
}


/**
 * Activation Hook
 *
 * @author Objectiv
 * @since  1.0.0
 *
 * @return void
 */
register_activation_hook( __FILE__, 'FpAccountSettings\Includes\Classes\Activation::run' );

/**
 * Deactivation Hook
 *
 * @author Objectiv
 * @since  1.0.0
 *
 * @return void
 */
register_deactivation_hook( __FILE__, 'FpAccountSettings\Includes\Classes\Deactivation::run' );
