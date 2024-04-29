<?php

namespace FP_Core;

class Member {

	/**
	 * @var int $user_id
	 */
	private $user_id = 0;

	/**
	 * @var array $rcp_memberships
	 */
	private $rcp_memberships = array();

	/**
	 * @var array $rcp_group_memberships
	 */
	private $rcp_group_memberships = array();

	/**
	 * @var array $rcp_group_memberships
	 */
	private $rcp_groups = array();

	public function __construct( int $user_id ) {
		$this->user_id               = (int) $user_id;
		$this->rcp_groups            = Utilities::get_groups( $user_id );
		$this->rcp_group_memberships = fp_get_group_user_membership( $user_id );
		$memberships                 = fp_get_user_memberships( $user_id );

		if ( empty( $memberships ) ) {
			return;
		}

		// If member is group owner, group membership will also be in $memberships, this is to filter those out.
		foreach ( $memberships as $membership ) {
			foreach ( $this->rcp_group_memberships as $group_membership ) {
				$membership_id       = method_exists( $membership, 'get_id' ) ? $membership->get_id() : 0;
				$group_membership_id = method_exists( $group_membership, 'get_id' ) ? $group_membership->get_id() : 0;

				if ( ( $membership_id && $group_membership_id ) && ( $membership_id === $group_membership_id ) ) {
					continue 2; // don't add it to $this->rcp_memberships
				}
			}

			$is_active = method_exists( $membership, 'is_active' ) ? $membership->is_active() : 0;

			//sort the active memberships to the front of the array
			if ( $is_active ) {
				array_unshift( $this->rcp_memberships, $membership );
			} else {
				$this->rcp_memberships[] = $membership;
			}
		}
	}

	/**
	 * Is Active
	 *
	 * Check if this user has an active membership, group or otherwise.
	 */
	public function is_active() {
		$all_memberships = array_merge( $this->rcp_memberships, $this->rcp_group_memberships );

		foreach ( $all_memberships as $membership ) {
			if ( method_exists( $membership, 'is_active' ) && $membership->is_active() ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Is Active At Level
	 *
	 * @param string $membership_level_id
	 *
	 * @return bool
	 */
	public function is_active_at_level( string $membership_level_id ) {
		$all_memberships = array_merge( $this->rcp_memberships, $this->rcp_group_memberships );

		foreach ( $all_memberships as $membership ) {
			if ( Utilities::rcp_membership_is_active_at_level( $membership, $membership_level_id ) ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Is Group Member
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function is_group_member() {
		foreach ( $this->rcp_group_memberships as $membership ) {
			if ( $membership->get_object_type() === 'membership' ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Get group Membership
	 *
	 * Get the group membership for this user if it exists.
	 * Uses current() because even though RCP supports a member being in more than one group, fpPathfinder does not.
	 *
	 * @return \RCP_Membership|bool
	 */
	public function get_group_membership() {
		return current( $this->rcp_group_memberships );
	}

	/**
	 * Get Membership
	 *
	 * Get the membership for this user if it exists.
	 * Uses current() because even though RCP supports a member having more than one membership, fpPathfinder does not.
	 *
	 * @return \RCP_Membership|bool
	 */
	public function get_membership() {
		return current( $this->rcp_memberships );
	}

	/**
	 * Get Group
	 *
	 * Get the rcp group for this user if it exists.
	 * Uses current() because even though RCP supports a member being in more than one group, fpPathfinder does not.
	 *
	 * @return \RCPGA_Group|bool
	 */
	public function get_group() {
		return current( $this->rcp_groups );
	}

	/**
	 * Can Upgrade
	 *
	 * Determine if a member has an available individual membership level they can upgrade to.
	 *
	 * @return bool
	 */
	public function can_upgrade() {
		if ( ! $this->is_active() ) {
			return false;
		}

		$individual_membership_is_active = $this->get_membership() && $this->get_membership()->is_active();
		$active_membership               = $individual_membership_is_active ? $this->get_membership() : $this->get_group_membership();
		$current_access                  = rcp_get_subscription_access_level( $active_membership->get_object_id() );

		foreach ( Level_Eligibility_Controller::get_user_registerable_levels() as $level ) {
			$proposed_access = rcp_get_subscription_access_level( $level );

			if ( Level_Eligibility_Controller::check( $level, $this->user_id ) && $proposed_access > $current_access ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Get Upgrade Link
	 *
	 * Get a url a user can use to upgrade to a higher individual
	 */
	public function get_upgrade_url() {
		if ( ! $this->can_upgrade() ) {
			return false;
		}

		if ( $this->get_membership() && $this->get_membership()->is_active() ) {
			return rcp_get_membership_upgrade_url( $this->get_membership()->get_id() );
		}

		return rcp_get_registration_page_url();
	}

	/**
	 * Get Highest Access Level
	 */
	public function get_highest_access_level( string $type = 'all' ): int {

		if ( 'individual' === $type ) {
			$all_memberships = $this->rcp_memberships;
		} elseif ( 'group' === $type ) {
			$all_memberships = $this->rcp_group_memberships;
		} else {
			$all_memberships = array_merge( $this->rcp_memberships, $this->rcp_group_memberships );
		}

		$highest_access = 0;

		foreach ( $all_memberships as $membership ) {
			if ( ! $membership->is_active() ) {
				continue;
			}

			$access_level = (int) rcp_get_subscription_access_level( $membership->get_object_id() );

			if ( $access_level <= $highest_access ) {
				continue;
			}

			$highest_access = $access_level;
		}

		return $highest_access;
	}
}
