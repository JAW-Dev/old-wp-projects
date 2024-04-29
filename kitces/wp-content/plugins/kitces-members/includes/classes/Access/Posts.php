<?php
/**
 * Posts
 *
 * @package    Kitces_Members
 * @subpackage Kitces_Members/Inlcudes/Classes/Access
 * @author     Objectiv
 * @copyright  Copyright (c) 2021, Objectiv
 * @license    GNU General Public License v2 or later
 * @since      1.0.0
 */

namespace Kitces_Members\Includes\Classes\Access;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

/**
 * Posts
 *
 * @author Jason Witt
 * @since  1.0.0
 */
class Posts {

	/**
	 * AC API
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @var object
	 */
	protected $ac_api;

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
		add_action( 'wp', array( $this, 'maybe_redirect_user_to_error_page' ) );
	}

	/**
	 * Maybe Redirect User
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function maybe_redirect_user_to_error_page() {
        if ( ! function_exists( 'members_can_current_user_view_post' ) ) {
            return;
        }

        if ( members_can_current_user_view_post( get_the_ID() ) ) {
            return;
        }

        $dest_url    = home_url( 'theres-a-problem' );

        if ( ! is_user_logged_in() ) {
            $dest_url = home_url( 'login' );
        }

        $current_url = urlencode( remove_query_arg( 'redirect_to', $_SERVER['REQUEST_URI'] ) );

        wp_redirect( add_query_arg( 'redirect_to', $_GET['redirect_to'] ? urlencode( $_GET['redirect_to'] ) : $current_url, $dest_url ), 307 );
        exit;
	}
}
