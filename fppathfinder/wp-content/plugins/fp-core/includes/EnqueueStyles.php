<?php
/**
 * Enqueue Styles
 *
 * @package    FP_Core/
 * @subpackage FP_Core/Includes/EnqueueStyles
 * @author     Objectiv
 * @copyright  Copyright (c) 2020, Objectiv
 * @license    GPL-2.0
 * @version    1.0.0
 */

namespace FP_Core;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

/**
 * Enqueue Styles
 *
 * @author Jason Witt
 * @since  1.0.0
 */
class EnqueueStyles {

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
		add_action( 'wp_enqueue_scripts', array( $this, 'styles' ) );
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
		$filename = 'src/css/global.css';
		$file     = FP_CORE_DIR_PATH . $filename;
		$version  = file_exists( $file ) ? filemtime( $file ) : '1.0.0';

		wp_register_style( 'fp-core-global', FP_CORE_DIR_URL . $filename, array(), $version, 'all' );
		wp_enqueue_style( 'fp-core-global' );
	}
}
