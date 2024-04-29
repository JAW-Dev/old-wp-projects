<?php

namespace FP_Core\Integrations\Active_Campaign\Custom_Fields;

use FP_Core\Integrations\Active_Campaign\Custom_Fields\Utilities;

class Membership_Access_Level extends Custom_Field {
	public function __construct() {
		parent::__construct();
	}

	public function get_tag(): string {
		return 'MEMBERSHIP_ACCESS_LEVEL';
	}

	public function add_hooks(): void {

		add_action( 'rcp_transition_membership_status', $this->build_safe_method( array( $this, 'transition_membership_status_handler' ) ), 10, 3 );
		add_action( 'rcp_transition_group_member_role', $this->build_safe_method( array( $this, 'add_member' ) ), 10, 3 );
		add_action( 'rcpga_remove_member', $this->build_safe_method( array( $this, 'remove_member' ) ) );
		add_filter( 'rcp_activecampaign_subscribe_data', $this->build_safe_method( array( $this, 'subscribe_filter' ) ), 10, 2 );
	}

	public function transition_membership_status_handler( $old_status, $new_status, int $membership_id ) {
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

	/**
	 * Runs when a contact is initially subscribed to AC.
	 */
	public function subscribe_filter( $data, $rcp_member ): array {
		if ( empty( $data ) || empty( $rcp_member ) ) {
			return [];
		}

		$member = new \FP_Core\Member( $rcp_member->ID );

		if ( empty( $member ) ) {
			return [];
		}

		$access_level = rcp_get_subscription_access_level( $member->get_membership()->get_object_id() );

		if ( empty( $access_level ) ) {
			return [];
		}

		$key = parent::get_field_key();

		if ( empty( $key ) ) {
			return [];
		}

		$data[ $key ] = $this->get_field_value_by_access_level( $access_level );

		return $data;
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

		$value = $this->get_field_value_by_access_level( $member->get_highest_access_level() );

		if ( empty( $value ) ) {
			return;
		}

		parent::add_update( $email, $value );
	}

	/**
	 * Get the value to send to AC based on the access level of the membership
	 */
	private function get_field_value_by_access_level( int $access_level ): string {
		switch ( $access_level ) {
			case 1:
				return 'Essentials';
				break;
			case 4:
				return 'Deluxe';
				break;
			case 6:
				return 'Premier';
				break;
			default:
				return '';
		}
	}
}
