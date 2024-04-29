<?php
/**
 * Main.
 *
 * @package    FP_Core
 * @author     Objectiv
 * @copyright  Copyright (c) 2021, Objectiv
 * @license    GNU General Public License v2 or later
 * @since      1.0.0
 */

use FP_Core\Main;
use FP_Core\EnqueueScripts;

if ( ! function_exists( 'fp_get_interactive_lists_post_types' ) ) {
	/**
	 * Get the interactive lists post types.
	 *
	 * @return array
	 */
	function fp_get_interactive_lists_post_types() {
		return ( new Main() )->get_interactive_lists_post_types();
	}
}

if ( ! function_exists( 'fp_get_interactive_lists_crms' ) ) {
	/**
	 * Get interactive lists CRMs.
	 *
	 * @return array
	 */
	function fp_get_interactive_lists_crms() {
		return ( new Main() )->get_interactive_lists_crms();
	}
}

if ( ! function_exists( 'fp_load_scripts' ) ) {
	/**
	 * Enqueue scripts
	 *
	 * @param array $args The arguments.
	 *
	 * @return array
	 */
	function fp_enqueue_scripts( array $args = [] ) {
		return ( new EnqueueScripts() )->fp_enqueue_scripts( $args );
	}
}
