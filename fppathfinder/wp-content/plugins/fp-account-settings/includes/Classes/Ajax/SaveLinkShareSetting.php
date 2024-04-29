<?php
/**
 * Save Share Link Settings.
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
 * Save Share Link Settings.
 *
 * @author Objectiv
 * @since  1.0.0
 */
class SaveShareLinkSettings {

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
		add_action( 'wp_ajax_share_link_settings', array( $this, 'save' ) );
		add_action( 'wp_ajax_nopriv_share_link_settings', array( $this, 'save' ) );
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
			echo 'Empty POST';
			wp_die();
		}

		parse_str( $post['data'], $data );

		if ( empty( $data ) ) {
			echo 'No Data';
			wp_die();
		}

		if ( ! wp_verify_nonce( $data['share_link_settings'], 'save_share_link_settings' ) ) {
			echo 'Nonce Failed';
			wp_die();
		}

		$current_settings = get_user_meta( get_current_user_id(), 'share-link-settings', true );

		$new_settings = array(
			'heading_text'     => isset( $data['share-link-heading-text'] ) ? trim( $data['share-link-heading-text'] ) : '',
			'disclaimer'       => isset( $data['share-link-disclaimer'] ) ? trim( $data['share-link-disclaimer'] ) : '',
			'share_link_email' => isset( $data['share-link-email'] ) ? $data['share-link-email'] : '',
			'share_link_phone' => isset( $data['share-link-phone'] ) ? $data['share-link-phone'] : '',
		);

		$return = wp_parse_args( $new_settings, $current_settings );

		if ( ! empty( $data ) ) {
			update_user_meta( get_current_user_id(), 'share-link-settings', $return );
			delete_transient( get_current_user_id() . '_share_link_transient' );

			echo 'save success';
			wp_die();
		}

		wp_die();
	}
}
