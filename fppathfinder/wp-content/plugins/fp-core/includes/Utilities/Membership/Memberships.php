<?php
/**
 * Memberships.
 *
 * @package    FP_Core
 * @subpackage FP_Core/Inlcudes/Utilities/Membership
 * @author     Objectiv
 * @copyright  Copyright (c) 2021, Objectiv
 * @license    GNU General Public License v2 or later
 * @since      1.0.0
 */

namespace FP_Core\Utilities\Membership;

use FP_Core\Utilities as CoreUtilities;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

/**
 * Memberships.
 *
 * @author Objectiv
 * @since  1.0.0
 */
class Memberships {

	/**
	 * Initialize the class
	 *
	 * @author Objectiv
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function __construct() {}

	/**
	 * Get Access
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param int $user_id The user ID.
	 *
	 * @return array
	 */
	public function get( int $user_id = null ) {
		$user_id               = $user_id ? $user_id : get_current_user_id();
		$individual_membership = ! empty( $this->maybe_get_individual( $user_id ) ) ? $this->maybe_get_individual( $user_id ) : [];
		$group_membership      = ! empty( $this->maybe_get_group( $user_id ) ) ? $this->maybe_get_group( $user_id ) : [];
		$memberships           = ! empty( $individual_membership ) ? $individual_membership : $group_membership;

		return apply_filters( FP_CORE_PREFIX . '_get_user_memberships', $memberships, $user_id );
	}

	/**
	 * Get Individual
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param int $user_id The user ID.
	 *
	 * @return void
	 */
	public function maybe_get_individual( int $user_id = null ) {
		$user_id    = $user_id ? $user_id : get_current_user_id();
		$membership = [];

		if ( function_exists( 'rcp_get_customer_by_user_id' ) ) {
			$customer = rcp_get_customer_by_user_id( $user_id );

			$membership = $customer ? $customer->get_memberships() : [];
		}

		return apply_filters( FP_CORE_PREFIX . '_get_individual_user_membership', $membership, $user_id );
	}

	/**
	 * Maybe get Group
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param int $user_id The user ID.
	 *
	 * @return void
	 */
	public function maybe_get_group( int $user_id = null ) {
		$user_id          = $user_id ? $user_id : get_current_user_id();
		$group_membership = [];

		$get_group_membership = function( $group ) {
			if ( empty( $group ) ) {
				return [];
			}

			return method_exists( $group, 'get_membership' ) ? $group->get_membership() : [];
		};

		$groups           = \FP_Core\Utilities::get_groups( $user_id );
		$group_membership = array_map( $get_group_membership, $groups );

		return apply_filters( FP_CORE_PREFIX . '_get_group_user_membership', $group_membership, $user_id );
	}

	/**
	 * Get Type
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param int $user_id The user ID.
	 *
	 * @return void
	 */
	public function get_type( int $user_id = null ) {
		$user_id               = $user_id ? $user_id : get_current_user_id();
		$individual_membership = ! empty( $this->maybe_get_individual( $user_id ) ) ? $this->maybe_get_individual( $user_id ) : [];
		$group_membership      = ! empty( $this->maybe_get_group( $user_id ) ) ? $this->maybe_get_group( $user_id ) : [];

		if ( ! empty( $individual_membership ) ) {
			return 'individual';
		}

		if ( ! empty( $group_membership ) ) {
			return 'group';
		}

		if ( ! empty( $individual_membership ) && ! empty( $group_membership ) ) {
			return 'individual_group';
		}

		return 'no_membership';
	}
}
