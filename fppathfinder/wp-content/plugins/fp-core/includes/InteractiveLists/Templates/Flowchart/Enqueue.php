<?php
/**
 * Enqueue
 *
 * @package    FP_Core/
 * @subpackage FP_Core/InteractiveLists/Templates/Flowchart
 * @author     Objectiv
 * @copyright  Copyright (c) 2020, Objectiv
 * @license    GPL-2.0
 * @version    1.0.0
 */

namespace FP_Core\InteractiveLists\Templates\Flowchart;

use FP_Core\InteractiveLists\Utilities\Page;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

/**
 * Enqueue
 *
 * @author Jason Witt
 * @since  1.0.0
 */
class Enqueue {

	/**
	 * Initialize the class
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function __construct() {
		$this->hooks();
	}

	/**
	 * Hooks
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function hooks() {
		add_action( 'wp_enqueue_scripts', array( $this, 'scripts' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'styles' ) );
	}

	/**
	 * Scripts
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function scripts() {
		if ( ! Page::is_interactive_resource( 'flowchart' ) ) {
			return;
		}

		$filename = 'src/js/flowcharts.js';
		$file     = FP_CORE_DIR_PATH . $filename;
		$version  = file_exists( $file ) ? filemtime( $file ) : '1.0.0';

		wp_register_script( 'fp-flowchart-vue', 'https://cdn.jsdelivr.net/npm/vue@2.6.12/dist/vue.js', array(), '2.6.12', true );
		wp_enqueue_script( 'fp-flowchart-vue' );

		wp_register_script( 'fp-flowcharts', FP_CORE_DIR_URL . $filename, array( 'fp-flowchart-vue' ), $version, true );
		wp_enqueue_script( 'fp-flowcharts' );
	}

	/**
	 * Styles
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function styles() {
		if ( ! Page::is_interactive_resource( 'flowchart' ) ) {
			return;
		}

		$filename = 'src/js/flowcharts.css';
		$file     = FP_CORE_DIR_PATH . $filename;
		$version  = file_exists( $file ) ? filemtime( $file ) : '1.0.0';

		wp_register_style( 'fp-flowcharts', FP_CORE_DIR_URL . $filename, array(), $version, 'all' );
		wp_enqueue_style( 'fp-flowcharts' );
	}
}
