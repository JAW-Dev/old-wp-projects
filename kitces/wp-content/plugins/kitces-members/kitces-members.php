<?php
/**
 * Plugin Name: Kitces Members
 * Description: Augments Members plugin to add Kitces.com membership functionality
 * Version:     1.0.0
 * Author:      Objectiv
 * Author URI:  https://objectiv.co/
 * License:     GNU General Public License v2 or later
 * Text Domain: kitces-members
 * Domain Path: /languages
 *
 * @package    Kitces_Members
 * @author     Objectiv
 * @copyright  Copyright (c) 2021, Objectiv
 * @license    GNU General Public License v2 or later
 * @version    1.0.0
 */

namespace Kitces_Members;

use Dotenv\Dotenv;

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
 * Load Environment (.env)
 */
( new Dotenv( str_replace( 'wp/', '', ABSPATH ) ) )->load();

/**
 * Constants
 */
if ( file_exists( trailingslashit( plugin_dir_path( __FILE__ ) ) . trailingslashit( 'includes' ) . 'constants.php' ) ) {
	require_once trailingslashit( plugin_dir_path( __FILE__ ) ) . trailingslashit( 'includes' ) . 'constants.php';
}

if ( ! class_exists( __NAMESPACE__ . '\\Kitces_Members' ) ) {

	/**
	 * Name
	 *
	 * @author Objectiv
	 * @since  1.0.0
	 */
	class Kitces_Members {

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
			load_plugin_textdomain( 'kitces-members', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

			// Init Classes.
			new Includes\Classes\Init();
		}
	}
}

/**
 * Return an instance of the plugin class.
 *
 * @author Objectiv
 * @since  1.0.0
 *
 * @return Kitces_Members instance of plugin class.
 */
function kitces_members() {
	return Kitces_Members::get_instance();
}

if ( class_exists( 'genesis' ) ) {
	// Required to use Genesis functionality.
	add_action( 'after_setup_theme', array( kitces_members(), 'run' ) );
} else {
	add_action( 'plugins_loaded', array( kitces_members(), 'run' ) );
}


/**
 * Activation Hook
 *
 * @author Objectiv
 * @since  1.0.0
 *
 * @return void
 */
register_activation_hook( __FILE__, 'Kitces_Members\Includes\Classes\Activation::run' );

/**
 * Deactivation Hook
 *
 * @author Objectiv
 * @since  1.0.0
 *
 * @return void
 */
register_deactivation_hook( __FILE__, 'Kitces_Members\Includes\Classes\Deactivation::run' );
