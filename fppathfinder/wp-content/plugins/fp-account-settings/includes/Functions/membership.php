<?php
/**
 * Membership.
 *
 * @package    Fp_Account_Settings
 * @subpackage Fp_Account_Settings/Inlcudes/Functions
 * @author     Objectiv
 * @copyright  Copyright (c) 2021, Objectiv
 * @license    GNU General Public License v2 or later
 * @since      1.0.0
 */

use FpAccountSettings\Includes\Utilites\Members\MembershipAccessLevel;

if ( ! function_exists( 'fp_get_membership_access_level' ) ) {
	/**
	 * Get Member Access Level.
	 *
	 * @param int $user_id The user ID.
	 *
	 * @return array
	 */
	function fp_get_membership_access_level( $user_id = '' ) {
		return ( new MembershipAccessLevel() )->get( $user_id );
	}
}
