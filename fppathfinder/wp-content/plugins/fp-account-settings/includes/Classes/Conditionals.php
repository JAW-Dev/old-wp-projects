<?php
/**
 * Conditionals.
 *
 * @package    Fp_Account_Settings
 * @subpackage Fp_Account_Settings/Inlcudes/Classes
 * @author     Objectiv
 * @copyright  Copyright (c) 2021, Objectiv
 * @license    GNU General Public License v2 or later
 * @since      1.0.0
 */

namespace FpAccountSettings\Includes\Classes;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

/**
 * Conditionals.
 *
 * @author Objectiv
 * @since  1.0.0
 */
class Conditionals {

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
	 * Is Essentials Member
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return boolean
	 */
	public static function is_essentials_member() {
		$check = fp_get_member_access(
			[
				'membership_level'     => 2,
				'membership_level_not' => [ 3, 4, 5, 6, 7 ],
			]
		);

		return $check;
	}

	/**
	 * Is Deluxe Member
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return boolean
	 */
	public static function is_deluxe_member() {
		$check = fp_get_member_access(
			[
				'membership_level'     => 3,
				'membership_level_not' => [ 4, 5, 6, 7 ],
			]
		);

		return $check;
	}

	/**
	 * Is Permier Member
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return boolean
	 */
	public static function is_premier_member() {
		$check = fp_get_member_access(
			[
				'membership_level'     => 4,
				'membership_level_not' => [ 5, 6, 7 ],
			]
		);

		if ( fp_is_feature_active( 'premier_features_deluxe' ) && self::is_deluxe_member() ) {
			$check = true;
		}

		return $check;
	}

	/**
	 * Is deluxe or premier member
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return boolean
	 */
	public static function is_deluxe_or_premier_member() {
		return self::is_deluxe_member() || self::is_premier_member();
	}

	/**
	 * Is Enterprise Essentials
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return boolean
	 */
	public static function is_enterprise_essentials() {
		$check = fp_get_member_access(
			[
				'membership_level'     => 5,
				'membership_level_not' => [ 6, 7 ],
			]
		);

		return $check;
	}

	/**
	 * Is Enterprise Deluxe
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return boolean
	 */
	public static function is_enterprise_deluxe() {
		$check = fp_get_member_access(
			[
				'membership_level'     => 6,
				'membership_level_not' => [ 7 ],
			]
		);

		return $check;
	}

	/**
	 * Is Enterprise Premier
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return boolean
	 */
	public static function is_enterprise_premier() {
		$check = fp_get_member_access( [ 'membership_level' => 7 ] );

		return $check;
	}

	/**
	 * Is Essentials Group Member
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return boolean
	 */
	public static function is_essentials_group_member() {
		return self::is_group_member_by_package( 5 );
	}

	/**
	 * Is Deluxe Group Member
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return boolean
	 */
	public static function is_deluxe_group_member() {
		return self::is_group_member_by_package( 6 );
	}

	/**
	 * Is Premier Group Member
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return boolean
	 */
	public static function is_premier_group_member() {
		return self::is_group_member_by_package( 7 );
	}

	/**
	 * Is Deluze or Premier Group Member
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return boolean
	 */
	public static function is_deluxe_or_premier_group_member() {
		return self::is_deluxe_group_member() || self::is_premier_group_member();
	}

	/**
	 * Is essentials member and deluxe group
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return boolean
	 */
	public static function is_essentials_member_and_deluxe_group() {
		return self::is_essentials_member() && self::is_deluxe_group_member();
	}

	/**
	 * Is essentials member and premier group
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return boolean
	 */
	public static function is_essentials_member_and_premier_group() {
		return self::is_essentials_member() && self::is_premier_group_member();
	}

	/**
	 * Is essentials member and deluxe or premier group
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return boolean
	 */
	public static function is_essentials_member_and_deluxe_or_premier_group() {
		return self::is_essentials_member() && ( self::is_premier_group_member() || self::is_deluxe_group_member() );
	}

	/**
	 * Is Essentials Group Owner
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return boolean
	 */
	public static function is_essentials_group_owner() {
		return self::is_essentials_group_member() && self::can_administer_group();
	}

	/**
	 * Is Deluxe Group Owner
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return boolean
	 */
	public static function is_deluxe_group_owner() {
		return self::is_deluxe_group_member() && self::can_administer_group();
	}

	/**
	 * Is Premier Group Owner
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return boolean
	 */
	public static function is_premier_group_owner() {
		return self::is_premier_group_member() && self::can_administer_group();
	}

	/**
	 * Can Administer Group
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param string $user_id The user ID.
	 *
	 * @return boolean
	 */
	public static function can_administer_group( $user_id = '' ) {
		if ( empty( $user_id ) ) {
			$user_id = get_current_user_id();
		}

		$can_administer = false;
		$owner          = rcpga_user_is_group_member( $user_id, 'owner' );
		$admin          = rcpga_user_is_group_member( $user_id, 'admin' );

		if ( $owner || $admin ) {
			$can_administer = true;
		}

		if ( current_user_can( 'administrator' ) ) {
			$can_administer = true;
		}

		return apply_filters( FP_ACCOUNT_SETTINGS_PREFIX . '_can_administer_group', $can_administer );
	}

	/**
	 * Get Group Member By Package
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param int $id The package object ID.
	 *
	 * @return boolean
	 */
	public static function is_group_member_by_package( $id ) {
		$user_id      = get_current_user_id();
		$group_member = rcpga_user_is_group_member( $user_id );

		if ( ! $group_member ) {
			return false;
		}

		$group_id = fp_get_group_id();

		if ( ! $group_id ) {
			return false;
		}

		$group          = rcpga_get_group( $group_id );
		$group_owner_id = ! empty( $group ) ? $group->get_owner_id() : false;

		if ( $group_owner_id ) {
			$membership_level = fp_get_membership_access_level( $group_owner_id );

			if ( $membership_level === $id ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Is Upgraded Group Member
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return mixed
	 */
	public static function is_upgraded_group_member() {
		$user_id      = get_current_user_id();
		$group_member = rcpga_user_is_group_member( $user_id );

		if ( ! $group_member ) {
			return false;
		}

		$access_level = fp_get_membership_access_level();
		$is_upgraded  = '';

		if ( $access_level === 3 || $access_level === 4 ) {
			$is_upgraded = true;
		}

		return apply_filters( FP_ACCOUNT_SETTINGS_PREFIX . '_is_upgraded_group_member', $is_upgraded );
	}

	/**
	 * Is Preier Member or Owner
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return bool
	 */
	public static function is_premier_member_or_owner() {
		return self::is_premier_group_member() || self::is_premier_member();
	}
}
