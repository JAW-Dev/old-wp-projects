<?php
/**
 * Group Share Link Settings.
 *
 * @package    Fp_Account_Settings
 * @subpackage Fp_Account_Settings/Inlcudes/Utilites/Settings
 * @author     Objectiv
 * @copyright  Copyright (c) 2021, Objectiv
 * @license    GNU General Public License v2 or later
 * @since      1.0.0
 */

namespace FpAccountSettings\Includes\Utilites\Settings;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

/**
 * Group Share Link Settings.
 *
 * @author Objectiv
 * @since  1.0.0
 */
class GroupShareLinkSettings {

	/**
	 * Initialize the class
	 *
	 * @author Objectiv
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function __construct() {}

	/**
	 * Get
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param array $user_settings The user settings array.
	 *
	 * @return array
	 */
	public function get( array $user_settings = [] ) {
		$user_settings = ! empty( $user_settings ) ? $user_settings : fp_get_user_settings();
		$group         = ! empty( $user_settings['group_link_share_settings'] ) ? $user_settings['group_link_share_settings'] : [];

		if ( empty( $group ) ) {
			return [];
		}

		$base_settings = array(
			'heading_text'     => ! empty( $group['heading_text'] ) ? $group['heading_text'] : '',
			'disclaimer'       => ! empty( $group['disclaimer'] ) ? $group['disclaimer'] : '',
			'share_link_email' => ! empty( $group['share_link_email'] ) ? $group['share_link_email'] : '',
			'share_link_phone' => ! empty( $group['share_link_phone'] ) ? $group['share_link_phone'] : '',
		);

		$permissions = array(
			'heading_text_permission'     => ! empty( $group['heading_text_permission'] ) ? $group['heading_text_permission'] : 'off',
			'disclaimer_permission'       => ! empty( $group['disclaimer_permission'] ) ? $group['disclaimer_permission'] : 'off',
			'share_link_email_permission' => ! empty( $group['share_link_email_permission'] ) ? $group['share_link_email_permission'] : 'off',
			'share_link_phone_permission' => ! empty( $group['share_link_phone_permission'] ) ? $group['share_link_phone_permission'] : 'off',
		);

		$settings = array_merge( $base_settings, $permissions );

		return apply_filters( FP_ACCOUNT_SETTINGS_PREFIX . '_get_group_share_link_settings', $settings, $user_settings );
	}
}
