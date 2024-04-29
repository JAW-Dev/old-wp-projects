<?php

namespace FP_Core;

/**
 * This class serves as the single source of truth for if a user is eligible to sign up for any given membership level.
 * This should not be used to prohibit manual registrations by a site admin.
 */
class Level_Eligibility_Controller {
	static function check( string $membership_level_id, int $user_id = 0 ) {
		$user_id = $user_id ? $user_id : get_current_user_id();

		// users themselves can only sign up for individual membership levels
		if ( ! in_array( $membership_level_id, self::get_user_registerable_levels() ) ) {
			return false;
		}

		// new user can sign up for any individual membership
		if ( ! $user_id ) {
			return true;
		}

		$member = new Member( $user_id );

		if ( $member->is_active_at_level( $membership_level_id ) ) {
			return false;
		}

		// no individual memberships below a user's level of group access
		// for instance an enterprise deluxe member shouldn't be able to sign up for an individual essentials membership
		if ( $member->get_group_membership() && $member->get_group_membership()->is_active() ) {
			$group_membership           = $member->get_group_membership();
			$group_membership_level_id  = $group_membership->get_object_id();
			$group_membership_access    = rcp_get_subscription_access_level( $group_membership_level_id );
			$proposed_membership_access = rcp_get_subscription_access_level( $membership_level_id );
			return $proposed_membership_access > $group_membership_access;
		}

		return true;
	}

	/**
	 * Get User Registerable Levels
	 *
	 * Users can only register themselves for individual membership levels.
	 *
	 * @return array
	 */
	static function get_user_registerable_levels() {
		return array( FP_DELUXE_ID, FP_ESSENTIALS_ID, FP_PREMIER_ID, FP_ESSENTIALS_WITH_TRIAL_ID );
	}
}
