<?php
/**
 *
 * Plugin Name: Kitces - Star Rating Plugin
 * Description: Add Star Ratings to posts. With optional A/B testing.
 * Version:     1.0.0
 * Author:      Objectiv
 * Author URI:  https://objectiv.co/
 * License:     GNU General Public License v2 or later
 * Text Domain: kitces_star_rating
 * Domain Path: /languages
 *
 * @package    Kitces_Star_Rating
 * @author     Objectiv
 * @copyright  Copyright (c) Plugin_Boilerplate_Date, Objectiv
 * @license    GNU General Public License v2 or later
 * @version    1.0.0
 */

namespace KitcesStarRating;

use KitcesStarRating\Includes\Classes as Classes;
use KitcesStarRating\Includes\Classes\Admin as Admin;
use KitcesStarRating\Includes\Classes\Tables as Tables;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

/**
 * Autoloader
 */
if ( file_exists( trailingslashit( plugin_dir_path( __FILE__ ) ) . trailingslashit( 'vendor' ) . 'autoload.php' ) ) {
	require_once trailingslashit( plugin_dir_path( __FILE__ ) ) . trailingslashit( 'vendor' ) . 'autoload.php';
}

if ( ! defined( 'KITCES_STAR_RAITING_VERSION' ) ) {
	define( 'KITCES_STAR_RAITING_VERSION', '1.0.0.' );
}

if ( ! defined( 'KITCES_STAR_RAITING_DIR_URL' ) ) {
	define( 'KITCES_STAR_RAITING_DIR_URL', trailingslashit( plugin_dir_url( __FILE__ ) ) );
}

if ( ! defined( 'KITCES_STAR_RAITING_DIR_PATH' ) ) {
	define( 'KITCES_STAR_RAITING_DIR_PATH', trailingslashit( plugin_dir_path( __FILE__ ) ) );
}

if ( ! defined( 'KITCES_STAR_RAITING_PREFIX' ) ) {
	define( 'KITCES_STAR_RAITING_PREFIX', 'kitces_star_rating' );
}

if ( ! defined( 'KITCES_STAR_RAITING_TABLE_VERSION' ) ) {
	define( 'KITCES_STAR_RAITING_TABLE_VERSION', '1' );
}

if ( ! defined( 'KITCES_STAR_RAITING_TABLE_NAME' ) ) {
	define( 'KITCES_STAR_RAITING_TABLE_NAME', 'kitces_star_rating' );
}

if ( ! defined( 'KITCES_STAR_RAITING_TABLE_OPTION_NAME' ) ) {
	define( 'KITCES_STAR_RAITING_TABLE_OPTION_NAME', 'kitces_star_ratings_table_version' );
}

if ( ! class_exists( __NAMESPACE__ . '\\Kitces_Star_Rating' ) ) {

	/**
	 * Name
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 */
	class Kitces_Star_Rating {

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
		 * @author Jason Witt
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
		 * Init
		 *
		 * @author Jason Witt
		 * @since  1.0.0
		 *
		 * @return void
		 */
		public function init() {

			// Load translated strings for plugin.
			load_plugin_textdomain( 'kitces_star_rating', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

			/**
			 * Initialize the PHP Library
			 */
			if ( function_exists( 'nbpl_init' ) ) {
				nbpl_init();
			}

			// Include Classes.
			$this->include_classes();
		}

		/**
		 * Include Classes.
		 *
		 * @author Jason Witt
		 * @since  1.0.0
		 *
		 * @return void
		 */
		public function include_classes() {
			if ( is_admin() ) {
				new Admin\AdminPage();
			}

			new Classes\ACF();
			new Classes\AJAX();
			new Classes\Post();
			new Classes\EnqueueStyles( $this->enqueue_styles() );
			new Classes\EnqueueScripts( $this->enqueue_scripts() );
			new Tables\Query();
			new Classes\Endpoint();
		}

		/**
		 * Enqueue Styles
		 *
		 * @author Jason Witt
		 * @since  1.0.0
		 *
		 * @return array
		 */
		public function enqueue_styles() {
			return array(
				'dir_path'     => KITCES_STAR_RAITING_DIR_PATH,
				'dir_url'      => KITCES_STAR_RAITING_DIR_URL,
				'styles'       => array(
					array(
						'handle' => 'kitces-star-ratings-styles',
						'file'   => 'src/styles/index.css',
					),
				),
				'admin_styles' => array(
					array(
						'handle' => 'kitces-star-ratings-admin-styles',
						'file'   => 'src/styles/admin.css',
						'hook'   => 'toplevel_page_star-ratings-test',
					),
				),
			);
		}

		/**
		 * Enqueue Scrips
		 *
		 * @author Jason Witt
		 * @since  1.0.0
		 *
		 * @return array
		 */
		public function enqueue_scripts() {
			return array(
				'dir_path'      => KITCES_STAR_RAITING_DIR_PATH,
				'dir_url'       => KITCES_STAR_RAITING_DIR_URL,
				'scripts'       => array(
					array(
						'handle' => 'kitces-star-ratings-scripts',
						'file'   => 'src/scripts/index.js',
					),
				),
				'admin_scripts' => array(
					array(
						'handle' => 'kitces-star-ratings-admin-scripts',
						'file'   => 'src/scripts/admin.js',
						'hook'   => 'toplevel_page_star-ratings-test',
					),
				),
				'localized'     => array(
					array(
						'handle' => 'kitces-star-ratings-scripts',
						'name'   => 'StarRating',
						'data'   => array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ),
					),
				),
			);
		}

		/**
		 * Activate the plugin.
		 *
		 * @author Jason Witt
		 * @since  1.0.0
		 *
		 * @return void
		 */
		public function _activate() {
			// Install the Star Ratings table.
			require_once ABSPATH . 'wp-admin/includes/upgrade.php';
			Includes\Classes\Tables\Table::activate();
			flush_rewrite_rules();
		}

		/**
		 * Decativate the plugin.
		 *
		 * @author Jason Witt
		 * @since  1.0.0
		 *
		 * @return void
		 */
		public function _deactivate() {
			$delete = get_field( 'kitces_ratings_data', 'option' );

			if ( $delete ) {
				global $wpdb;

				$table = $wpdb->prefix . KITCES_STAR_RAITING_TABLE_NAME;

				$wpdb->query( "DROP TABLE IF EXISTS $table" ); // phpcs:ignore

				delete_option( KITCES_STAR_RAITING_TABLE_OPTION_NAME );
			}
		}
	}
}

/**
 * Return an instance of the plugin class.
 *
 * @author Jason Witt
 * @since  1.0.0
 *
 * @return Kitces_Star_Rating instance of plugin class.
 *
 * @SuppressWarnings(PHPMD)
 */
function kitces_star_rating() {
	return Kitces_Star_Rating::get_instance();
}
add_action( 'plugins_loaded', array( kitces_star_rating(), 'init' ) );

/**
 * Activateion Hook
 */
register_activation_hook( __FILE__, array( kitces_star_rating(), '_activate' ) );

/**
 * Deactivation Hook
 */
register_deactivation_hook( __FILE__, array( kitces_star_rating(), '_deactivate' ) );
