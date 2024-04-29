<?php
/**
 * Init.
 *
 * @package    Kitces
 * @subpackage Kitces/Includes/Classes/GroupPriceQuote
 * @author     Objectiv
 * @copyright  Copyright (c) 2020, Objectiv
 * @license    GNU General Public License v2 or later
 * @since      1.0.0
 */

namespace Kitces\Includes\Classes\GroupPriceQuote;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

/**
 * Init.
 *
 * @author Objectiv
 * @since  1.0.0
 */
class Init {

	/**
	 * Initialize the class
	 *
	 * @author Objectiv
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function __construct() {
		new Shortcode();
	}
}
