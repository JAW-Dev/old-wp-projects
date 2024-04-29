<?php

namespace FP_Core\Integrations\Active_Campaign\Custom_Fields;

use FP_Core\Integrations\Active_Campaign\Custom_Fields\Utilities;

class Membership_Expiration_Date extends Custom_Field {
	public function __construct() {
		parent::__construct();
	}

	public function get_tag(): string {
		return 'MEMBERSHIP_EXPIRATION_DATE';
	}

	public function add_hooks(): void {
		add_filter( 'rcp_activecampaign_subscribe_data', $this->build_safe_method( array( $this, 'subscribe_filter' ) ), 10, 3 );
		add_action( 'rcp_transition_membership_expiration_date', $this->build_safe_method( array( $this, 'rcp_transition_membership_expiration_date_handler' ) ), 10, 3 );
	}

	public function rcp_transition_membership_expiration_date_handler( string $old_date, string $new_date, int $membership_id, $group_id = '' ) {
		if ( empty( $new_date ) || empty( $membership_id ) ) {
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

		if ( ! empty( $group_id ) ) {
			if ( Utilities::is_member_invited( $user_id, $group_id ) ) {
				return;
			}
		}

		$membership = rcp_get_membership( $membership_id );

		if ( empty( $membership ) ) {
			return;
		}

		$is_group_membership = rcpga_is_level_group_accounts_enabled( $membership->get_object_id() );

		if ( $is_group_membership ) {
			return;
		}

		$email = ( new \WP_User( $user_id ) )->user_email;

		if ( empty( $email ) ) {
			return;
		}

		parent::add_update( $email, substr( $new_date, 0, 10 ) );
	}

	/**
	 * Runs when a contact is initially subscribed to AC.
	 */
	public function subscribe_filter( $data, $rcp_member, $list ): array {
		if ( ( defined( 'OBJECTIV_DEV_SITE' ) && OBJECTIV_DEV_SITE ) ) {
			// error_log( 'Membership_Expiration_Date subscribe_filter: Run' ); // phpcs:ignore
		}

		$member = new \FP_Core\Member( $rcp_member->ID );

		if ( empty( $email ) ) {
			// error_log( 'Membership_Expiration_Date subscribe_filter ERROR: No user email' ); // phpcs:ignore
		}

		$expiration_date = $member->get_membership()->get_expiration_date( false );

		if ( empty( $expiration_date ) ) {
			// error_log( 'Membership_Expiration_Date subscribe_filter ERROR: No expiration_date' ); // phpcs:ignore
		}

		if ( 'none' === $expiration_date ) {
			return $data;
		}

		$formatted = substr( $expiration_date, 0, 10 );

		$key = parent::get_field_key();

		if ( empty( $key ) ) {
			// error_log( 'Membership_Expiration_Date subscribe_filter ERROR: No key' ); // phpcs:ignore
		}

		$data[ $key ] = $formatted;

		return $data;
	}

	public function update_user( int $user_id ) {
		if ( ( defined( 'OBJECTIV_DEV_SITE' ) && OBJECTIV_DEV_SITE ) ) {
			// error_log( 'Membership_Expiration_Date update_user: Run' ); // phpcs:ignore
		}

		$email = ( new \WP_User( $user_id ) )->user_email;

		if ( empty( $email ) ) {
			// error_log( 'Membership_Expiration_Date update_user ERROR: No user email' ); // phpcs:ignore
		}

		$member = new \FP_Core\Member( $user_id );

		if ( empty( $member ) ) {
			// error_log( 'Membership_Expiration_Date update_user ERROR: Not member' ); // phpcs:ignore
		}

		$membership = $member->get_membership();

		if ( empty( $membership ) ) {
			// error_log( 'Membership_Expiration_Date update_user ERROR: No membership' ); // phpcs:ignore
		}

		if ( ! $membership || 'none' === $membership->get_expiration_date() ) {
			return;
		}

		$expiration_date = substr( $membership->get_expiration_date( false ), 0, 10 );

		if ( empty( $expiration_date ) ) {
			// error_log( 'Membership_Expiration_Date update_user ERROR: No expiration_date' ); // phpcs:ignore
		}

		parent::add_update( $email, $expiration_date );
	}
}
