<?php

namespace FP_Core;

use FP_Core\Essentials_Trial_Membership\Settings;

/**
 * This class handles users registering and modifications to that flow.
 */
class RegistrationController {
	public function __construct() {
		add_filter( 'fppathfinder_available_registration_membership_levels', array( $this, 'limit_available_membership_levels' ), 10, 3 );
		remove_action( 'rcp_before_subscription_form_fields', 'rcp_display_change_membership_message' );
		add_filter( 'price_block_button', array( $this, 'filter_register_buttons' ), 10, 2 );
		add_action( 'wp', array( $this, 'redirect_to_prevent_multiple_individual_memberships' ) );
	}

	/**
	 * Limit Available Membership Levels
	 *
	 * On the register.php template we need to prevent users from seeing a membership level they are not eligible to sign up for.
	 * @see filter 'fppathfinder_available_registration_membership_levels'
	 *
	 * @return array $available_levels
	 */
	public function limit_available_membership_levels( array $membership_levels ) {
		$is_available = function ( $level ) {
			$id                  = $level->id;
			$is_essentials_trial = FP_ESSENTIALS_WITH_TRIAL_ID === (string) $id;
			return Level_Eligibility_Controller::check( $level->id ) && ! $is_essentials_trial;
		};

		return array_filter( $membership_levels, $is_available );
	}

	public function filter_register_buttons( array $button_details, int $membership_level_id ) {
		if ( ! $membership_level_id ) { // block is informational and not for a specific membership level, such as a Contact Us button
			return $button_details;
		}

		$user_id = get_current_user_id();

		if ( ! $user_id ) {
			return $button_details;
		}

		$member     = new \FP_Core\Member( $user_id );
		$membership = $member->get_membership();

		if ( $membership ) { // They have an individual membership of some kind.
			global $rcp_options;

			$membership_id = intval( $membership->get_object_id() );

			$url = add_query_arg(
				array(
					'registration_type' => 'renewal',
					'membership_id'     => $membership->get_id(),
					'rcp_level'         => $membership_level_id,
				),
				rcp_get_membership_upgrade_url( $membership->get_id() )
			);

			if ( (int) $membership->get_object_id() !== (int) $membership_level_id ) {
				$url = add_query_arg(
					array(
						'registration_type' => 'upgrade',
						'membership_id'     => $membership->get_id(),
						'rcp_level'         => $membership_level_id,
					),
					rcp_get_membership_upgrade_url( $membership->get_id() )
				);
			}

			return array(
				'url'   => $url,
				'title' => ( $membership_level_id === $membership_id ? 'Renew' : 'Change' ) . ' Membership',
			);
		}

		if ( ! $member->is_active() ) { // They don't have an indivual membership and they're not active so they're not in an active group.
			return $button_details;
		}

		if ( ! Level_Eligibility_Controller::check( $membership_level_id ) ) { // They're in an active group and they're not eligible for this level.
			return array();
		}

		return $button_details; // They're in an active group and they're eligible for this level.
	}

	public function redirect_to_prevent_multiple_individual_memberships() {
		if ( ! function_exists( 'rcp_get_registration' ) ) {
			return;
		}

		if ( ! rcp_is_registration_page() ) {
			return;
		}

		if ( 'new' !== rcp_get_registration()->get_registration_type() ) {
			return;
		}

		$user_id = get_current_user_id();

		if ( ! $user_id ) {
			return;
		}

		$member = new Member( $user_id );

		if ( ! $member->is_active() ) {
			return;
		}

		// if they have their own membership or a group membership
		if ( ! $member->get_membership() ) {
			return;
		}

		global $rcp_options;

		wp_safe_redirect( get_permalink( $rcp_options['account_page'] ), 307 );
	}
}
