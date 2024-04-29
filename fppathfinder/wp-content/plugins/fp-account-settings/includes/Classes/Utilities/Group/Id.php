<?php
/**
 * Group ID.
 *
 * @package    Fp_Account_Settings
 * @subpackage Fp_Account_Settings/Inlcudes/Utilites/Group
 * @author     Objectiv
 * @copyright  Copyright (c) 2021, Objectiv
 * @license    GNU General Public License v2 or later
 * @since      1.0.0
 */

namespace FpAccountSettings\Includes\Utilites\Group;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

/**
 * Group ID.
 *
 * @author Objectiv
 * @since  1.0.0
 */
class Id {

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
	public function get( int $user_id ) {
		if ( isset( $_GET['rcpga-group'] ) ) {
			$group_id = ! empty( $_GET['rcpga-group'] ) ? sanitize_text_field( wp_unslash( $_GET['rcpga-group'] ) ) : '';

			if ( $group_id ) {
				return $group_id;
			}

			return;
		}

		$user_id = $user_id ? $user_id : get_current_user_id();

		if ( ! $user_id ) {
			return '';
		}

		$group_member  = function_exists( 'rcpga_user_is_group_member' ) ? rcpga_user_is_group_member( $user_id ) : false;
		$user_group_id = get_user_meta( $user_id, 'user_group_id', true );

		if ( ctype_digit( substr( $user_group_id, 0, 1 ) ) ) {
			delete_user_meta( $user_id, 'user_group_id' );
		}

		if ( ! empty( $user_group_id ) ) {
			return $user_group_id;
		}

		if ( current_user_can( 'administrator' ) && ! $group_member ) {
			$group_id = $user_id;
			update_user_meta( $user_id, 'user_group_id', $group_id );

			return apply_filters( FP_ACCOUNT_SETTINGS_PREFIX . '_get_group_id', $group_id );
		}

		if ( ! $group_member ) {
			return '';
		}

		$group_member_query_args = array(
			'user_id'      => $user_id,
			'role__not_in' => array( 'invited' ),
		);

		$groups = function_exists( 'rcpga_get_group_members' ) ? rcpga_get_group_members( $group_member_query_args ) : false;

		if ( empty( $groups ) ) {
			return '';
		}

		$group_id = isset( $groups[0] ) ? $groups[0]->get_group_id() : 0;

		if ( ! $group_id ) {
			return '';
		}

		update_user_meta( $user_id, 'user_group_id', $group_id );

		return apply_filters( FP_ACCOUNT_SETTINGS_PREFIX . '_get_group_id', $group_id, $user_id );
	}
}
