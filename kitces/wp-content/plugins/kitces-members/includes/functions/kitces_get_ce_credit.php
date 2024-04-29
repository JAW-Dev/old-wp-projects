<?php
/**
 * Kitces Get CE Credits.
 *
 * @package    Kitces_Members
 * @subpackage Kitces_Members/Inlcudes/Functions
 * @author     Objectiv
 * @copyright  Copyright (c) 2021, Objectiv
 * @license    GNU General Public License v2 or later
 * @since      1.0.0
 */

use Kitces_Members\Includes\Classes\CeCredits;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

if ( ! function_exists( 'kitces_get_ce_credit' ) ) {
    /**
     * Kitces Get CE Credits.
     *
     * @param string $field_name
     * @param string $user_id
     * @return string|void
     */
	function kitces_get_ce_credit( string $field_name, $user_id = '' ) {
		return ( new CeCredits() )->get_field( $field_name, $user_id );
	}
}
