<?php
/**
 * UsernameChange
 *
 * @package    FP_Core/
 * @subpackage FP_Core/Users
 * @author     Objectiv
 * @copyright  Copyright (c) 2020, Objectiv
 * @license    GPL-2.0
 * @version    1.0.0
 */

namespace FP_Core\Users;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

/**
 * UsernameChange
 *
 * @author Jason Witt
 * @since  1.0.0
 */
class UsernameChange {

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
	 * Init
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function hooks() {
		add_filter( 'acf/save_post', array( $this, 'update_username' ) );
	}

	/**
	 * Populate Username
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param mixed $post_id The post ID.
	 *
	 * @return void
	 */
	public function update_username( $post_id ) {
		global $wpdb;

		if ( strpos( $post_id, 'user_' ) === false ) {
			return;
		}

		$user_id  = (int) str_replace( 'user_', '', $post_id );
		$values   = get_fields( $post_id );
		$username = $values['fp_new_username'];

		if ( empty( $username ) ) {
			return;
		}

		$wpdb->update(
			$wpdb->users,
			array( 'user_login' => $username ),
			array( 'ID' => $user_id )
		);

		wp_cache_flush();
	}
}
