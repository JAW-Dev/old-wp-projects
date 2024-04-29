<?php
/**
 * Username
 *
 * @package    Kitces
 * @subpackage Kitces/Includes/Classes/Utils
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
class Username {

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
		$username = $values['kitces_username'];

		// Clean up user meta at this stage
        delete_user_meta( $user_id, 'kitces_username' );

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
