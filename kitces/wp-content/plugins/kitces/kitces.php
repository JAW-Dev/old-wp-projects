<?php
/**
 * Plugin Name: Kitces - Core
 * Description: Custom features for kitces.com
 * Version:     1.0.0
 * Author:      Objectiv
 * Author URI:  https://objectiv.co/
 * License:     GNU General Public License v2 or later
 * Text Domain: kitces
 * Domain Path: /languages
 *
 * @package    Kitces
 * @author     Objectiv
 * @copyright  Copyright (c) 2020, Objectiv
 * @license    GNU General Public License v2 or later
 * @version    1.0.0
 */

namespace Kitces;

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
 * Core Autoloader
 */
if ( file_exists( WP_CONTENT_DIR . trailingslashit( 'vendor' ) . 'autoload.php' ) ) {
	require_once WP_CONTENT_DIR . trailingslashit( 'vendor' ) . 'autoload.php';
}

/**
 * Load Environment (.env)
 */
// ( new \Dotenv\Dotenv( str_replace( 'wp/', '', ABSPATH ) ) )->load();

/**
 * Constants
 */
if ( file_exists( trailingslashit( plugin_dir_path( __FILE__ ) ) . trailingslashit( 'includes' ) . 'constants.php' ) ) {
	require_once trailingslashit( plugin_dir_path( __FILE__ ) ) . trailingslashit( 'includes' ) . 'constants.php';
}



/**
 * Name
 *
 * @author Objectiv
 * @since  1.0.0
 */
class Kitces {

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
	public function init() {

		// Load translated strings for plugin.
		load_plugin_textdomain( 'kitces', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

		// Init Classes.
		new Includes\Classes\Init();

		require_once trailingslashit( plugin_dir_path( __FILE__ ) ) . trailingslashit( 'includes' ) . 'functions/index.php';

	}
}

/**
 * Return an instance of the plugin class.
 *
 * @author Objectiv
 * @since  1.0.0
 *
 * @return Kitces instance of plugin class.
 */
function kitces() {
	return Kitces::get_instance();
}

if ( class_exists( 'genesis' ) ) {
	// Required to use Genesis functionality.
	add_action( 'after_setup_theme', array( kitces(), 'init' ) );
} else {
	add_action( 'plugins_loaded', array( kitces(), 'init' ) );
}


/**
 * Activation Hook
 *
 * @author Objectiv
 * @since  1.0.0
 *
 * @return void
 */
register_activation_hook( __FILE__, 'Kitces\Includes\Classes\Activation::run' );

/**
 * Deactivation Hook
 *
 * @author Objectiv
 * @since  1.0.0
 *
 * @return void
 */
register_deactivation_hook( __FILE__, 'Kitces\Includes\Classes\Deactivation::run' );
