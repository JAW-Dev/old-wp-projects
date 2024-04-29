<?php

namespace FP_Core\Integrations\Active_Campaign\Custom_Fields;

use \FP_Core\Member;
use FP_Core\Integrations\Active_Campaign\Custom_Fields\Utilities;

class Membership_Start_Date extends Custom_Field {
	public function __construct() {
		parent::__construct();
	}

	public function get_tag(): string {
		return 'MEMBERSHIP_START_DATE';
	}

	public function add_hooks(): void {
		add_action( 'rcp_successful_registration', $this->build_safe_method( array( $this, 'successful_registration_handler' ) ), 10, 2 );
		add_action( 'rcp_transition_group_member_role', $this->build_safe_method( array( $this, 'add_member' ) ), 10, 3 );
	}

	public function successful_registration_handler( $member, $customer ) {
		if ( empty( $customer ) ) {
			return;
		}

		$user_id = $customer->get_user_id();

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

	public function update_user( int $user_id, $group_id = '' ) {
		if ( empty( $user_id ) ) {
			return;
		}

		if ( ! empty( $group_id ) ) {
			if ( Utilities::is_member_invited( $user_id, $group_id ) ) {
				return;
			}
		}

		$email = ( new \WP_User( $user_id ) )->user_email;

		if ( empty( $email ) ) {
			return;
		}

		$start_date = $this->get_value( $user_id );

		if ( empty( $start_date ) ) {
			return;
		}

		parent::add_update( $email, $start_date ? substr( $start_date, 0, 10 ) : '' );
	}

	public function get_value( int $user_id ) {
		if ( empty( $user_id ) ) {
			return;
		}

		$dates         = [];
		$group_members = rcpga_get_group_members( array( 'user_id' => $user_id ) );

		if ( $group_members ) {
			foreach ( $group_members as $member ) {
				$dates[] = $member->get_date_added( false );
			}
		}

		$customer = rcp_get_customer_by_user_id( $user_id );

		if ( $customer ) {
			$dates[] = $customer->get_date_registered( false );
		}

		sort( $dates, SORT_STRING );

		return array_shift( $dates );
	}
}
