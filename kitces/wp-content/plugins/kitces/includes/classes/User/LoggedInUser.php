<?php
/**
 * Username
 *
 * @package    Kitces
 * @subpackage Kitces/Includes/Classes/User
 * @author     Objectiv
 * @copyright  Copyright (c) 2020, Objectiv
 * @license    GNU General Public License v2 or later
 * @since      1.0.0
 */

namespace Kitces\Includes\Classes\User;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

/**
 * Username
 *
 * @author Jason Witt
 * @since  1.0.0
 */
class LoggedInUser {

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
		add_action( 'wp_enqueue_scripts', array( $this, 'scripts' ) );
	}

	/**
	 * Scripts
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function scripts() {
		if ( ! is_singular( 'post' ) ) {
			return;
		}

		$file        = 'src/js/user.js';
		$file_path   = KITCES_DIR_PATH . $file;
		$file_url    = KITCES_DIR_URL . $file;
		$file_exists = file_exists( $file_path );
		$file_time   = $file_exists ? filemtime( $file_path ) : '1.0.0';
		$handle      = 'kitces-user-info';

		wp_register_script( $handle, $file_url, array( 'shared-counts' ), $file_time, true );
		wp_enqueue_script( $handle );

		$user = get_userdata( get_current_user_id() );

		$user_first_name   = $user->first_name;
		$user_last_name    = $user->last_name;
		$user_display_name = $user->display_name;
		$user_name         = ! empty( $user_display_name ) ? $user_display_name : $user_first_name . ' ' . $user_last_name;

		wp_localize_script(
			$handle,
			KITCES_PRFIX . 'UserInfo',
			array(
				'ajax_url' => admin_url( 'admin-ajax.php' ),
				'user'     => array(
					'name'  => $user_name,
					'email' => $user->user_email,
				),
			)
		);
	}
}
