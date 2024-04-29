<?php
/**
 *
 * Plugin Name: fpPathfinder PDF Generator
 * Description: Generate PDFs from SVGs.
 * Version:     1.0.0
 * Author:      Objectiv
 * Author URI:  https://objectiv.co/
 * License:     GPLv2
 * Domain Path: /languages
 *
 * @package    FPPathfinder_Enterprise_Essentials
 * @author     Objectiv
 * @copyright  Copyright (c) 2019, Objectiv
 * @license    GNU General Public License v2 or later
 * @version    1.0.0
 **/

namespace FP_PDF_Generator;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

if ( ! defined( 'FPPDFG_DIR_URL' ) ) {
	define( 'FPPDFG_DIR_URL', trailingslashit( plugin_dir_url( __FILE__ ) ) );
}

if ( ! defined( 'FPPDFG_DIR_PATH' ) ) {
	define( 'FPPDFG_DIR_PATH', trailingslashit( plugin_dir_path( __FILE__ ) ) );
}

if ( ! defined( 'FPPDFG_PREFIX' ) ) {
	define( 'FPPDFG_PREFIX', 'fppdfg' );
}

require dirname( __FILE__ ) . '/vendor/autoload.php';

/**
 * Run
 *
 * @author Objectiv
 * @since  1.0.0
 */
class FPPDFG_Run {

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
		load_plugin_textdomain( 'plugin_boilerplate_name', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

		// Init Classes.
		new Main();
	}
}

/**
 * Return an instance of the plugin class.
 *
 * @author Objectiv
 * @since  1.0.0
 *
 * @return FPPDFG_Run instance of plugin class.
 */
function fppdfg_run() {
	return FPPDFG_Run::get_instance();
}

add_action( 'plugins_loaded', array( fppdfg_run(), 'run' ) );
