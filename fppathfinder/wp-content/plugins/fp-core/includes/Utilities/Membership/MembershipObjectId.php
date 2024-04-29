<?php
/**
 * Membership Object ID.
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
 * Membership Object ID.
 *
 * @author Objectiv
 * @since  1.0.0
 */
class MembershipObjectId {

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
		$membership_id = 0;
		$memberships   = fp_get_user_memberships( $user_id );

		if ( empty( $memberships ) ) {
			return $membership_id;
		}

		foreach ( $memberships as $membership ) {
			if ( method_exists( $membership, 'get_user_id' ) && $user_id !== $membership->get_user_id() ) {
				continue;
			}

			$object_id = method_exists( $membership, 'get_object_id' ) && $membership->get_object_id() ? $membership->get_object_id() : [];

			if ( ! empty( $object_id ) ) {
				$membership_id = $object_id;
				break;
			}
		}

		return apply_filters( FP_CORE_PREFIX . '_get_membership_object_id', $membership_id );
	}
}
