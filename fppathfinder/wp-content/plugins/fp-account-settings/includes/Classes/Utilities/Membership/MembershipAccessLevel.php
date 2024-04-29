<?php
/**
 * Get Membership Access Level.
 *
 * @package    Fp_Account_Settings
 * @subpackage Fp_Account_Settings/Inlcudes/Utilites/Members
 * @author     Objectiv
 * @copyright  Copyright (c) 2021, Objectiv
 * @license    GNU General Public License v2 or later
 * @since      1.0.0
 */

namespace FpAccountSettings\Includes\Utilites\Members;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

/**
 * Get Membership Access Level.
 *
 * @author Objectiv
 * @since  1.0.0
 */
class MembershipAccessLevel {

	/**
	 * Initialize the class
	 *
	 * @author Objectiv
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function __construct() {}

	/**
	 * Get Access
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param int $user_id The user ID.
	 *
	 * @return boolean
	 */
	public function get( $user_id = '' ) {
		$membership_id = fp_get_membership_package_id();

		if ( ! empty( $user_id ) ) {
			$membership_id = fp_get_membership_package_id( $user_id );
		}

		$is_group_member = rcpga_user_is_group_member( $user_id );

		if ( empty( $membership_id ) && ! $is_group_member ) {
			return 0;
		}

		$settings_level = 1;

		switch ( $membership_id ) {
			case '1': // Essentials Package.
			case '9': // Essentials 3 Month Free Trial.
				$settings_level = 2;
				break;
			case '2': // Deluxe Package.
				$settings_level = 3;
				break;
			case '6': // Premier Package.
				$settings_level = 4;
				break;
			case '5': // Enterprise Essentials.
				$settings_level = 5;
				break;
			case '4': // Enterprise Deluxe.
			case '3': // Firm-wide Enterprise Deluxe.
				$settings_level = 6;
				break;
			case '8': // Enterprise Premier.
			case '7': // Firm-wide Enterprise Premier.
				$settings_level = 7;
				break;
		}

		if ( $settings_level === 1 && $is_group_member ) {
			$settings_level = 2;
		}

		if ( current_user_can( 'administrator' ) ) {
			$settings_level = 8;
		}

		return apply_filters( FP_ACCOUNT_SETTINGS_PREFIX . '_get_membership_access_level', $settings_level );
	}
}
