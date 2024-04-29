<?php
/**
 * Kitces Is Valid Student Member.
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

if ( ! function_exists( 'kitces_is_valid_student_member' ) ) {
    /**
     * Kitces Is Valid Student Member.
     *
     * @return bool
     * @since  1.0.0
     *
     * @author Jason Witt
     */
	function kitces_is_valid_student_member(): bool {
		return ( new Member() )->is_valid_student_member();
	}
}
