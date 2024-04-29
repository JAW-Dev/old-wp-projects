<?php

namespace FP_Core\Integrations\Active_Campaign\Custom_Fields;

use FP_Core\Integrations\Active_Campaign\Custom_Fields\Utilities;

class Individual_Membership_Level extends Custom_Field {
	public function __construct() {
		parent::__construct();
	}

	public function get_tag(): string {
		return 'INDIVIDUAL_MEMBERSHIP_LEVEL';
	}

	public function add_hooks(): void {
		add_action( 'rcp_transition_membership_status', $this->build_safe_method( array( $this, 'membership_status_transition_handler' ) ), 10, 3 );
		add_action( 'rcp_transition_group_member_role', $this->build_safe_method( array( $this, 'add_member' ) ), 10, 3 );
	}

	public function membership_status_transition_handler( string $old_status, string $new_status, int $membership_id ): void {
		if ( empty( $membership_id ) ) {
			return;
		}

		$membership = rcp_get_membership( $membership_id );

		if ( empty( $membership ) ) {
			return;
		}

		$user_id = $membership->get_customer()->get_user_id();

		if ( empty( $user_id ) ) {
			return;
		}

		$this->update_user( $user_id );
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

	public function update_user( $user_id, $group_id = '' ) {
		if ( empty( $user_id ) ) {
			return;
		}

		if ( ! empty( $group_id ) ) {
			if ( Utilities::is_member_invited( $user_id, $group_id ) ) {
				return;
			}
		}

		$member = new \FP_Core\Member( $user_id );

		if ( empty( $member ) ) {
			return;
		}

		$email = ( new \WP_User( $user_id ) )->user_email;

		if ( empty( $email ) ) {
			return;
		}

		if ( ! $member->get_membership() || ! $member->get_membership()->is_active() ) {
			parent::add_update( $email, '' );
			return;
		}

		$value = rcp_get_subscription_name( $member->get_membership()->get_object_id() );

		if ( empty( $value ) ) {
			return;
		}

		parent::add_update( $email, $value );
	}
}
