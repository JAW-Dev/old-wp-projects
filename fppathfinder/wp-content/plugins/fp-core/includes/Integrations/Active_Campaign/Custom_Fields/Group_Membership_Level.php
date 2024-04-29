<?php

namespace FP_Core\Integrations\Active_Campaign\Custom_Fields;

use FP_Core\Integrations\Active_Campaign\Custom_Fields\Utilities;

class Group_Membership_Level extends Custom_Field {
	public function __construct() {
		parent::__construct();
	}

	public function get_tag(): string {
		return 'GROUP_MEMBERSHIP_LEVEL';
	}

	public function add_hooks(): void {
		add_action( 'rcp_transition_membership_status', $this->build_safe_method( array( $this, 'transition_membership_status_handler' ) ), 10, 3 );
		add_action( 'rcpga_remove_member', $this->build_safe_method( array( $this, 'remove_member' ) ) );
		add_action( 'rcp_transition_group_member_role', $this->build_safe_method( array( $this, 'add_member' ) ), 10, 3 );
	}

	/**
	 * Transition Membership Status Handler
	 *
	 * Runs when new user is added to a group
	 *
	 * @param string $old_role      The old role.
	 * @param string $new_role      The new role.
	 * @param int    $membership_id The membership ID.
	 *
	 * @return void
	 */
	public function transition_membership_status_handler( $old_status, $new_status, int $membership_id ) {
		if ( empty( $membership_id ) ) {
			return;
		}

		$membership = rcp_get_membership( $membership_id );

		if ( empty( $membership ) ) {
			return;
		}

		$is_group_membership = rcpga_is_level_group_accounts_enabled( $membership->get_object_id() );

		$group = $is_group_membership ? rcpga_get_group_by( 'membership_id', $membership->get_id() ) : '';

		if ( empty( $group ) ) {
			return;
		}

		$group_members = $group->get_members();

		if ( empty( $group_members ) ) {
			return;
		}

		foreach ( $group_members as $group_member ) {
			$this->update_user( $group_member->get_user_id() );
		}
	}

	/**
	 * Add Member
	 *
	 * @param string $old_role        The old role.
	 * @param string $new_role        The new role.
	 * @param int    $group_member_id The member ID.
	 *
	 * @return void
	 */
	public function add_member( string $old_role, string $new_role, int $group_member_id ) {
		if ( empty( $old_role ) || empty( $new_role ) || empty( $group_member_id ) ) {
			return;
		}

		$handler = Utilities::handle_transistion_group_member_role( $old_role, $new_role, $group_member_id );

		if ( ! empty( $handler ) ) {
			$this->update_user( $handler['user_id'], $handler['group_id'] );
		}
	}

	/**
	 * Remove Member
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function remove_member( $user_id, $group_id = '' ) {
		if ( empty( $user_id ) || empty( $group_id ) ) {
			return;
		}

		$this->update_user( $user_id, $group_id );
	}

	public function update_user( $user_id, $group_id = '' ) {
		if ( Utilities::is_member_invited( $user_id, $group_id ) ) {
			return;
		}

		$member = new \FP_Core\Member( $user_id );

		if ( empty( $member ) ) {
			return;
		}

		$email = ( new \WP_User( $user_id ) )->user_email;

		if ( empty( $email ) ) {
			return;
		}

		if ( ! $member->get_group_membership() || ! $member->get_group_membership()->is_active() ) {
			parent::add_update( $email, '' );
			return;
		}

		$value = rcp_get_subscription_name( $member->get_group_membership()->get_object_id() );

		if ( empty( $value ) ) {
			return;
		}

		parent::add_update( $email, $value );
	}
}
