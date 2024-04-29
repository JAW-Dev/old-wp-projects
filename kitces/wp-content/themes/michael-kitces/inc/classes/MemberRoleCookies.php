<?php
/**
 * Member Role Cookies
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


/**
 * Member Role Cookies
 *
 * @author Jason Witt
 * @since  1.0.0
 */
class MemberRoleCookies {

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
		add_action( 'wp_login', array( $this, 'roles_cookie' ), 10, 2 );
	}

	/**
	 * Roles Cookie
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param string  $user_login The user login.
	 * @param WP_User $user       The User object.
	 *
	 * @return void
	 */
	public function roles_cookie( $user_login, $user ) {
		$roles = array(
			'administrator',
			'editor',
			'author',
		);

		$user_roles = ! empty( $user->roles ) ? $user->roles : array();

		if ( ! empty( $user_roles ) ) {
			foreach ( $user_roles as $role ) {
				$cookie_name = 'KitcesMember' . ucfirst( $role );
				$cookie_set  = isset( $_COOKIE[ $cookie_name ] );
				if ( in_array( $role, $roles, true ) ) {
					if ( ! $cookie_set ) {
						setcookie( $cookie_name, 'true', time() + ( 10 * 365 * 24 * 60 * 60 ), '/' );
					}
				} else {
					if ( ! isset( $_COOKIE['KitcesMemberLogin'] ) ) {
						setcookie( 'KitcesMemberLogin', 'true', time() + 48 * 3600, '/' );
					}

					if ( ! isset( $_COOKIE['kitcesreadersignedup'] ) ) {
						setcookie( 'kitcesreadersignedup', 'true', time() + ( 10 * 365 * 24 * 60 * 60 ), '/' );
					}
				}
			}
		}
	}
}
