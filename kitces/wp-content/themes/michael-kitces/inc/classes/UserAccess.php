<?php
/**
 * User Access
 *
 * @package    Kitces
 * @subpackage Kitces/Classes
 * @author     Objectiv
 * @copyright  2020 (c) Date, Objectiv
 * @license    GPL-2.0
 * @version    1.0.0
 * @since      1.0.0
 */

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

if ( ! class_exists( 'UserAccess' ) ) {

	/**
	 * User Quiz Data
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 */
	class UserAccess {

		/**
		 * Initialize the class
		 *
		 * @author Jason Witt
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
			add_filter( 'login_form_defaults', array( $this, 'login_form_defaults') );
		}

		/**
		 * Logged In Redirect
		 *
		 * If the user is logged in and has a redirect_to param
		 * redirect to the URL instead of showing You are logged in page.
		 * Else if the user is not logged in and is on the There's a Problem page
		 * with a redirect param set. Redirect the user to the login page.
		 * Else let Memberium do it's normal thing.
		 *
		 * @author Jason Witt
		 * @since  1.0.0
		 *
		 * @return void
		 */
		public function login_form_defaults( $args ) : array {
            if ( isset( $_GET['redirect_to'] ) ) {
                $args['redirect'] = $_GET['redirect_to'];
            }

			return $args;
		}
	}
}
