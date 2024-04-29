<?php
/**
 * Passwords.
 *
 * @package    Kitces_Members
 * @subpackage Kitces_Members/Inlcudes/Classes
 * @author     Objectiv
 * @copyright  Copyright (c) 2021, Objectiv
 * @license    GNU General Public License v2 or later
 * @since      1.0.0
 */

namespace Kitces_Members\Includes\Classes;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}


/**
 * Passwords.
 *
 * @author Objectiv
 * @since  1.0.0
 */
class Passwords {

	/**
	 * Initialize the class
	 *
	 * @author Objectiv
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function __construct() {
		$this->hooks();
	}

	/**
	 * Hooks
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function hooks() {
		add_action( 'password_reset', array( $this, 'maybe_clear_needs_password_reset' ) );
	}

	/**
	 * Maybe Clear Needs Password Reset
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function maybe_clear_needs_password_reset( \WP_User $user ) {
		$user_id = $user->ID;

		$needs_password_reset = get_user_meta( $user_id, 'needs_password_reset', true );

		if ( $needs_password_reset ) {
			delete_user_meta( $user_id, 'needs_password_reset' );
		}
	}
}
