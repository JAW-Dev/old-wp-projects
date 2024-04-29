<?php

/**
 * Utilities
 *
 * @package    FP_Core
 * @subpackage FP_Core/Integrations/Active_Campaign/Custom_Fields
 * @author     Objectiv
 * @copyright  Copyright (c) 2021, Objectiv
 * @license    GPL-2.0
 * @version    1.0.0
 */

namespace FP_Core\Integrations\Active_Campaign\Custom_Fields;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

/**
 * Utilities
 *
 * @author Jason Witt
 * @since  1.0.0
 */
class Utilities {

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
	 * Is Member Invited
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param int $user_id  The user ID.
	 * @param int $group_id The group ID.
	 *
	 * @return void
	 */
	public static function is_member_invited( $user_id, $group_id = '' ) {
		$group_id = ! empty( $group_id ) ? $group_id : sanitize_text_field( wp_unslash( $_GET['rcpga-group'] ?? '' ) );

		if ( empty( $group_id ) ) {
			return false;
		}

		$group_member = rcpga_get_group_member( $user_id, absint( $group_id ) );

		if ( empty( $group_member ) ) {
			return false;
		}

		if ( 'invited' === $group_member->get_role() ) {
			return true;
		}

		return false;
	}

	/**
	 * Handle Transistion Group Member Role
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param string $old_role        The old role.
	 * @param string $new_role        The new role.
	 * @param int    $group_member_id The member ID.
	 *
	 * @return void
	 */
	public static function handle_transistion_group_member_role( string $old_role, string $new_role, int $group_member_id ) {
		if ( empty( $new_role ) || empty( $group_member_id ) ) {
			return;
		}

		if ( $new_role === 'owner' ) {
			return array();
		}

		$post = $_POST;

		if ( ! empty( $post ) ) {
			$disable_email = isset( $post['rcpga-disable-invite-email'] ) ?? '';

			if ( ! $disable_email ) {
				return array();
			}
		}

		$group_member = rcpga_get_group_member_by_id( $group_member_id );

		if ( empty( $group_member ) ) {
			return array();
		}

		$user_id = $group_member->get_user_id();

		if ( empty( $user_id ) ) {
			return array();
		}

		$group_id = $group_member->get_group_id();

		if ( empty( $group_id ) ) {
			return array();
		}

		return array(
			'user_id'  => $user_id,
			'group_id' => $group_id,
		);
	}
}
