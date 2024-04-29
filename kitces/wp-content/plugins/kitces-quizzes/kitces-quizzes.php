<?php
/**
 * Plugin Name: Kitces Timed Quiz Entries
 * Description: Custom Quiz functionality
 * Version:     1.0.0
 * Author:      Objectiv
 * Author URI:  https://objectiv.co/
 * License:     GNU General Public License v2 or later
 * Text Domain: kitces-quizzes
 * Domain Path: /languages
 *
 * @package    Kitces_Quizzes
 * @author     Objectiv
 * @copyright  Copyright (c) 2022, Objectiv
 * @license    GNU General Public License v2 or later
 * @version    1.0.0
 */

namespace KitcesQuizzes;

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

if ( ! class_exists( __NAMESPACE__ . '\\Kitces_Quizzes' ) ) {

	/**
	 * Name
	 *
	 * @author Objectiv
	 * @since  1.0.0
	 */
	class Kitces_Quizzes {

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
			load_plugin_textdomain( 'mk-quizzes', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

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
 * @return Kitces_Quizzes instance of plugin class.
 */
function kitces_quizzes() {
	return Kitces_Quizzes::get_instance();
}

if ( class_exists( 'genesis' ) ) {
	// Required to use Genesis functionality.
	add_action( 'after_setup_theme', array( kitces_quizzes(), 'run' ) );
} else {
	add_action( 'plugins_loaded', array( kitces_quizzes(), 'run' ) );
}


/**
 * Activation Hook
 *
 * @author Objectiv
 * @since  1.0.0
 *
 * @return void
 */
register_activation_hook( __FILE__, 'KitcesQuizzes\Includes\Classes\Activation::run' );

/**
 * Deactivation Hook
 *
 * @author Objectiv
 * @since  1.0.0
 *
 * @return void
 */
register_deactivation_hook( __FILE__, 'KitcesQuizzes\Includes\Classes\Deactivation::run' );
