<?php
/**
 * Save Password Reset
 *
 * @package    Kitces_Members
 * @subpackage Kitces_Members/Inlcudes/Classes/Ajax
 * @author     Objectiv
 * @copyright  Copyright (c) 2021, Objectiv
 * @license    GNU General Public License v2 or later
 * @since      1.0.0
 */

namespace Kitces_Members\Includes\Classes\Ajax;

use Kitces_Members\Includes\Classes\ActiveCampaign\CustomFields;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

/**
 * Save Password Reset
 *
 * @author Jason Witt
 * @since  1.0.0
 */
class SavePasswordReset {

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
		add_action( 'wp_ajax_kitces_password_reset', array( $this, 'save' ) );
		add_action( 'wp_ajax_nopriv_kitces_password_reset', array( $this, 'save' ) );
	}

	/**
	 * Save
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function save() {
		$post = sanitize_post( wp_unslash( $_POST ) ) ?? '';

		if ( empty( $post ) ) {
			echo wp_json_encode(
				array(
					'success' => 0,
					'message' => 'Post Empty',
				)
			);
			wp_die();
		}

		parse_str( $post['data'], $data );

		if ( empty( $data ) ) {
			echo wp_json_encode(
				array(
					'success' => 0,
					'message' => 'No Data',
				)
			);
			wp_die();
		}

		if ( wp_verify_nonce( $data['password_reset_form_submit'], 'password_reset_form_submit' ) ) {
			echo wp_json_encode(
				array(
					'success' => 0,
					'message' => 'Nonce Failed',
				)
			);
			wp_die();
		}

		if ( $data['password-1'] !== $data['password-2'] ) {
			echo wp_json_encode(
				array(
					'success' => 0,
					'message' => 'Password Mismatch',
				)
			);
			wp_die();
		}

		$user = wp_get_current_user();

		wp_set_password( $data['password-2'], $user->ID );
		wp_set_auth_cookie( $user->ID );
		wp_set_current_user( $user->ID );
		delete_user_meta( get_current_user_id(), 'needs_password_reset' );
		delete_user_meta( get_current_user_id(), 'member_activation_key' );
		delete_user_meta( get_current_user_id(), 'member_activation_key_expiry' );
		mk_ac_sync_member_non_ajax( get_current_user_id() );

		if ( ! empty( $data['redirect-url'] ) ) {
			echo wp_json_encode(
				array(
					'success' => 1,
					'url'     => $data['redirect-url'],
				)
			);
			wp_die();
		}

		echo wp_json_encode(
			array(
				'success' => 1,
				'message' => 'Success',
			)
		);

		wp_die();
	}
}
