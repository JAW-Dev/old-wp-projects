<?php

/**
 * Kitces Member Can Acceess Stuff
 *
 * @package    Kitces_Members
 * @subpackage Kitces_Members/Inlcudes/Functions
 * @author     Objectiv
 * @copyright  Copyright (c) 2021, Objectiv
 * @license    GNU General Public License v2 or later
 * @since      1.0.0
 */

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

// A function that checks whether the members plugin gives access to this user
if ( ! function_exists( 'kitces_member_can_access_post' ) ) {
	/**
	 * Kitces Member Can Access Post.
	 *
	 * @return bool
	 *
	 * @author Eldon
	 */
	function kitces_member_can_access_post( $post_id = null, $user_id = null ): bool {

		if ( function_exists( 'members_can_user_view_post' ) ) {
			return members_can_user_view_post( $post_id, $user_id );
		} else {
			error_log( 'Looks like the Members plugin may have been removed or deactivated.' );
			return false;
		}

	}
}

// A function that uses the checks in $CGD_CECredits to see whether a user can access a quiz or not
if ( ! function_exists( 'kitces_member_can_take_quiz' ) ) {
	/**
	 * Kitces Member Can Take Quiz
	 *
	 * @return bool
	 *
	 * @author Eldon
	 */
	function kitces_member_can_take_quiz( $post_id = null ): bool {

		if ( empty( $post_id ) ) {
			return false;
		}

        global $CGD_CECredits; // phpcs:ignore
        $cecredits = $CGD_CECredits; // phpcs:ignore
		return $cecredits->can_member_access_quiz( $post_id );
	}
}
