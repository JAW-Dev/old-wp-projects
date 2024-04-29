<?php
/**
 * Remove Shared Links.
 *
 * @package    Fp_Account_Settings
 * @subpackage Fp_Account_Settings/Inlcudes/Classes/Ajax
 * @author     Objectiv
 * @copyright  Copyright (c) 2021, Objectiv
 * @license    GNU General Public License v2 or later
 * @since      1.0.0
 */

namespace FpAccountSettings\Includes\Classes\Ajax;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

/**
 * Remove Shared Links.
 *
 * @author Objectiv
 * @since  1.0.0
 */
class RemoveSharedLinks {

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
		add_action( 'wp_ajax_remove_shared_link', array( $this, 'delete' ) );
		add_action( 'wp_ajax_nopriv_remove_shared_link', array( $this, 'delete' ) );
	}

	/**
	 * Save
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function delete() {
		$post = $_POST;

		if ( empty( $post ) ) {
			echo 'Empty POST';
			wp_die();
		}

		$share_key     = isset( $post['sharekey'] ) ? $post['sharekey'] : '';
		$advisor_links = get_user_meta( get_current_user_id(), 'fp_advisor_share_links', true );
		$new_array     = [];

		foreach ( $advisor_links as $key => $value ) {
			if ( $value['share_key'] === $share_key ) {
				unset( $advisor_links[ $key ] );
			}
		}

		update_user_meta( get_current_user_id(), 'fp_advisor_share_links', $advisor_links );

		echo 'success';

		wp_die();
	}
}
