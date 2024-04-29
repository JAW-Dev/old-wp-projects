<?php
/**
 * Sandbox
 *
 * Just a file to do stuff not part of
 * the actual plugin.
 *
 * @package    FP_Core/
 * @subpackage FP_Core/Includes/Utilities
 * @author     Objectiv
 * @copyright  Copyright (c) 2020, Objectiv
 * @license    GPL-2.0
 * @version    1.0.0
 */

namespace FP_Core\Utilities;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

/**
 * Sandbox
 *
 * @author Jason Witt
 * @since  1.0.0
 */
class Sandbox {

	/**
	 * Initialize the class
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function __construct() {
		$this->init();
	}

	/**
	 * Init
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function init() {
	}

	/**
	 * Finded Invited Members With Membership
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function find_invited_with_membership() {
		$invited     = rcpga_get_group_members( [ 'role' => 'invited', 'number' => '9999' ] );
		$invited_ids = [];

		foreach ( $invited as $member ) {
			array_push( $invited_ids, $member->get_user_id() );
		}

		$memberships    = rcp_get_memberships( [ 'object_id' => '2', 'number' => '9999' ] );
		$membership_ids = [];

		foreach ( $memberships as $member ) {
			array_push( $membership_ids, $member->get_user_id() );
		}

		$invited_memberships = array_intersect( $invited_ids, $membership_ids );
	}
}
