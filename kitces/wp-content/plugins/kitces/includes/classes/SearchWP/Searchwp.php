<?php
/**
 * SearchWP.
 *
 * @package    Kitces
 * @subpackage Kitces/Includes/Classes/SearchWP
 * @author     Objectiv
 * @copyright  Copyright (c) 2020, Objectiv
 * @license    GPL-2.0
 * @since      1.0.0
 */

namespace Kitces\Includes\Classes\SearchWP;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

/**
 * SearchWP.
 *
 * @author Objectiv
 * @since  1.0.0
 */
class Searchwp {

	/**
	 * Initialize the class
	 *
	 * @author Objectiv
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
		add_filter( 'searchwp_related_auto_append', '__return_false' );

		// prevent SearchWP from automatically maintaining it's index
		add_filter( 'searchwp_background_deltas', '__return_false' );
	}
}
