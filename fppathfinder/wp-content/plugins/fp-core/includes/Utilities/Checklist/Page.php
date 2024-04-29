<?php
/**
 * Page
 *
 * @package    FP_Core/
 * @subpackage FP_Core/Utilities/Checklist
 * @author     Objectiv
 * @copyright  Copyright (c) 2020, Objectiv
 * @license    GPL-2.0
 * @version    1.0.0
 */

namespace FP_Core\Utilities\Checklist;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

/**
 * Page
 *
 * @author Jason Witt
 * @since  1.0.0
 */
class Page {

	/**
	 * Initialize the class
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function __construct() {}

	/**
	 * Is Interactive Resource Type
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param string $type The type of resource to check.
	 *
	 * @return boolean
	 */
	public static function is_interactive_resource_type( string $type = '' ) {
		if ( ! is_singular( $type ) ) {
			return false;
		}

		return true;
	}
}
