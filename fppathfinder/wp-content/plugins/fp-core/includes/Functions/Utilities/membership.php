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

use FP_Core\Utilities\Membership\MembershipPackageId;
use FP_Core\Utilities\Membership\MembershipObjectId;
use FP_Core\Utilities\Membership\Memberships;

if ( ! function_exists( 'fp_get_membership_package_id' ) ) {
	/**
	 * Get Membershipe ID.
	 *
	 * @param int $user_id The user ID.
	 *
	 * @return array
	 */
	function fp_get_membership_package_id( $user_id = '' ) {
		return ( new MembershipPackageId() )->get( $user_id );
	}
}

if ( ! function_exists( 'fp_get_membership_object_id' ) ) {
	/**
	 * Get Membership Obect ID.
	 *
	 * @param int $user_id The user ID.
	 *
	 * @return array
	 */
	function fp_get_membership_object_id( $user_id = '' ) {
		return ( new MembershipObjectId() )->get( $user_id );
	}
}

if ( ! function_exists( 'fp_get_user_memberships' ) ) {
	/**
	 * Get Membership Obect ID.
	 *
	 * @param int $user_id The user ID.
	 *
	 * @return array
	 */
	function fp_get_user_memberships( int $user_id = null ) {
		return ( new Memberships() )->get( $user_id );
	}
}

if ( ! function_exists( 'fp_get_individual_user_membership' ) ) {
	/**
	 * Get Membership Obect ID.
	 *
	 * @param int $user_id The user ID.
	 *
	 * @return array
	 */
	function fp_get_individual_user_membership( int $user_id = null ) {
		return ( new Memberships() )->maybe_get_individual( $user_id );
	}
}

if ( ! function_exists( 'fp_get_group_user_membership' ) ) {
	/**
	 * Get Membership Obect ID.
	 *
	 * @param int $user_id The user ID.
	 *
	 * @return array
	 */
	function fp_get_group_user_membership( int $user_id = null ) {
		return ( new Memberships() )->maybe_get_group( $user_id );
	}
}
