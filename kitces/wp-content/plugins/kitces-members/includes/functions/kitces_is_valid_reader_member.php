<?php
/**
 * Kitces Is Valid Reader Member.
 *
 * @package    Kitces_Members
 * @subpackage Kitces_Members/Inlcudes/Functions
 * @author     Objectiv
 * @copyright  Copyright (c) 2021, Objectiv
 * @license    GNU General Public License v2 or later
 * @since      1.0.0
 */

use Kitces_Members\Includes\Classes\Access\Member;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

if ( ! function_exists( 'kitces_is_valid_reader_member' ) ) {
	/**
	 * Kitces Is Valid Reader Member.
	 *
	 * @return bool
	 *
	 * @author Eldon
	 */
	function kitces_is_valid_reader_member(): bool {
		return ( new Member() )->is_valid_reader_member();
	}
}
