<?php

namespace FP_Core\Integrations\Active_Campaign\Custom_Fields;

use FP_Core\Integrations\Active_Campaign\Custom_Fields\Utilities;

class Group_Name extends Custom_Field {
	public function __construct() {
		parent::__construct();
	}

	public function get_tag(): string {
		return 'GROUP_NAME';
	}

	public function add_hooks(): void {
		add_action( 'rcp_transition_group_member_role', $this->build_safe_method( array( $this, 'add_member' ) ), 10, 3 );
		add_action( 'rcpga_remove_member', $this->build_safe_method( array( $this, 'rcpga_remove_member_handler' ) ) );
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

	public function rcpga_remove_member_handler( int $user_id ) {
		if ( empty( $user_id ) ) {
			return;
		}

		$email = ( new \WP_User( $user_id ) )->user_email;

		if ( empty( $email ) ) {
			return;
		}

		parent::add_update( $email, '' );
	}

	public function update_user( int $user_id, $group_id = '' ) {
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

		$group = $member->get_group();

		if ( empty( $group ) ) {
			return;
		}

		$email = ( new \WP_User( $user_id ) )->user_email;

		if ( empty( $email ) ) {
			return;
		}

		parent::add_update( $email, $group->get_name() );
	}
}
