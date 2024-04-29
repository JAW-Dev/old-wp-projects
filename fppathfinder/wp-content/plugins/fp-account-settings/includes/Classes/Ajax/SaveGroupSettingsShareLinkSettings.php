<?php
/**
 * Save Group Settings Share Link Settings.
 *
 * @package    Fp_Account_Settings
 * @subpackage Fp_Account_Settings/Inlcudes/Classes/Ajax
 * @author     Objectiv
 * @copyright  Copyright (c) 2021, Objectiv
 * @license    GNU General Public License v2 or later
 * @since      1.0.0
 */

namespace FpAccountSettings\Includes\Classes\Ajax;

use FP_Core\Member;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

/**
 * Save Group Settings Share Link Settings.
 *
 * @author Objectiv
 * @since  1.0.0
 */
class SaveGroupSettingsShareLinkSettings {

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
		add_action( 'wp_ajax_group_settings_share_link_settings', array( $this, 'save' ) );
		add_action( 'wp_ajax_nopriv_group_settings_share_link_settings', array( $this, 'save' ) );
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

		if ( ! wp_verify_nonce( $data['group_settings_share_link_settings'], 'group_settings_save_share_link_settings' ) ) {
			echo 'Nonce Failed';
			wp_die();
		}

		$settings = array(
			'heading_text'                => isset( $data['group-settings-heading-text'] ) ? trim( $data['group-settings-heading-text'] ) : '',
			'heading_text_permission'     => isset( $data['group-settings-heading-text-permission'] ) ? trim( $data['group-settings-heading-text-permission'] ) : '',
			'disclaimer'                  => isset( $data['group-settings-disclaimer'] ) ? trim( $data['group-settings-disclaimer'] ) : '',
			'disclaimer_permission'       => isset( $data['group-settings-disclaimer-permission'] ) ? trim( $data['group-settings-disclaimer-permission'] ) : '',
			'share_link_email'            => isset( $data['group-settings-share-link-email'] ) ? $data['group-settings-share-link-email'] : '',
			'share_link_email_permission' => isset( $data['group-settings-share-link-email-permission'] ) ? trim( $data['group-settings-share-link-email-permission'] ) : '',
			'share_link_phone'            => isset( $data['group-settings-share-link-phone'] ) ? $data['group-settings-share-link-phone'] : '',
			'share_link_phone_permission' => isset( $data['group-settings-share-link-phone-permission'] ) ? trim( $data['group-settings-share-link-phone-permission'] ) : '',
		);

		if ( ! empty( $data ) ) {
			$member   = new Member( get_current_user_id() );
			$group    = ! empty( $member ) && method_exists( $member, 'get_group' ) ? $member->get_group() : '';
			$group_id = ! empty( $group ) && method_exists( $group, 'get_group_id' ) ? $group->get_group_id() : '';

			if ( ! $group_id ) {
				echo 'No a Group Admin';
				wp_die();
			}

			delete_metadata( 'rcp_group', $group_id, 'group_link_share_settings' );
			update_metadata( 'rcp_group', $group_id, 'group_link_share_settings', $settings );
			delete_transient( get_current_user_id() . '_group_share_link_transient' );

			$group_members = rcpga_get_group_members( array( 'group_id' => $group_id ) );

			foreach ( $group_members as $group_member ) {
				$group_user_id = $group_member->get_user_id();
				delete_transient( $group_user_id . '_share_link_transient' );
			}

			echo 'save success';
			wp_die();
		}

		wp_die();
	}
}
