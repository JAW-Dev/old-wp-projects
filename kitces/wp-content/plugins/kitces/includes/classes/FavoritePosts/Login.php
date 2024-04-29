<?php
/**
 * Login.
 *
 * @package    Kitces
 * @subpackage Kitces/Includes/Classes/FavoritePosts
 * @author     Objectiv
 * @copyright  Copyright (c) 2020, Objectiv
 * @license    GNU General Public License v2 or later
 * @since      1.0.0
 */

namespace Kitces\Includes\Classes\FavoritePosts;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

/**
 * Login.
 *
 * @author Objectiv
 * @since  1.0.0
 */
class Login {

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
		add_filter( 'login_redirect', array( $this, 'redirect' ), 10, 2 );
	}

	/**
	 * Redirect
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return string
	 */
	public function redirect( $redirect_to ) {
		$url = home_url() . '/login/?redirect_to=';

		if ( strpos( $redirect_to, $url ) !== false ) {
			return str_replace( $url, '', $redirect_to );
		}

		return $redirect_to;
	}
}
