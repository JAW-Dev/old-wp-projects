<?php
/**
 * Membership Package ID.
 *
 * @package    FP_Core
 * @subpackage FP_Core/Inlcudes/Utilities/Membership
 * @author     Objectiv
 * @copyright  Copyright (c) 2021, Objectiv
 * @license    GNU General Public License v2 or later
 * @since      1.0.0
 */

namespace FP_Core\Utilities\Membership;
use FP_Core\Member;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

/**
 * Membership Package ID.
 *
 * @author Objectiv
 * @since  1.0.0
 */
class MembershipPackageId {

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
	 * @return boolean
	 */
	public function get( $user_id = '' ) {
		$user_id = empty( $user_id ) ? get_current_user_id() : $user_id;
		$member  = new Member( $user_id );

		if ( empty( $member ) ) {
			return 0;
		}

		$membership_id = current_user_can( 'administrator' ) ? '7' : fp_get_membership_object_id( $user_id );

		return apply_filters( FP_CORE_PREFIX . '_get_membership_id', $membership_id );
	}
}
