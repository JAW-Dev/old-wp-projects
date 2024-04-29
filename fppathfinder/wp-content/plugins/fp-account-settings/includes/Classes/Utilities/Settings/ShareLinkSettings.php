<?php
/**
 * Share Link Settings.
 *
 * @package    Fp_Account_Settings
 * @subpackage Fp_Account_Settings/Inlcudes/Utilites/Settings
 * @author     Objectiv
 * @copyright  Copyright (c) 2021, Objectiv
 * @license    GNU General Public License v2 or later
 * @since      1.0.0
 */

namespace FpAccountSettings\Includes\Utilites\Settings;

use FpAccountSettings\Includes\Classes\Conditionals;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

/**
 * Share Link Settings.
 *
 * @author Objectiv
 * @since  1.0.0
 */
class ShareLinkSettings {

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

		if ( empty( $user_settings ) ) {
			return [];
		}

		$individual = ! empty( $user_settings['share_link'] ) ? $user_settings['share_link'] : [];
		$group      = ! empty( $user_settings['group_link_share_settings'] ) ? $user_settings['group_link_share_settings'] : [];

		$base_settings = array(
			'heading_text'     => ! empty( $individual['heading_text'] ) ? $individual['heading_text'] : '',
			'disclaimer'       => ! empty( $individual['disclaimer'] ) ? $individual['disclaimer'] : '',
			'share_link_email' => ! empty( $individual['share_link_email'] ) ? $individual['share_link_email'] : '',
			'share_link_phone' => ! empty( $individual['share_link_phone'] ) ? $individual['share_link_phone'] : '',
		);

		if ( ! empty( $group ) ) {
			$group_heading_text = ! empty( $group['heading_text'] ) ? $group['heading_text'] : '';
			$group_disclaimer   = ! empty( $group['disclaimer'] ) ? $group['disclaimer'] : '';
			$group_email        = ! empty( $group['share_link_email'] ) ? $group['share_link_email'] : '';
			$group_phone        = ! empty( $group['share_link_phone'] ) ? $group['share_link_phone'] : '';
		}

		if ( ! empty( $group ) && ! Conditionals::can_administer_group( $user_settings['user_id'] ) ) {
			$permissions = array(
				'heading_text'     => ( ! empty( $group['heading_text_permission'] ) && $group['heading_text_permission'] === 'on' ) ? true : false,
				'disclaimer'       => ( ! empty( $group['disclaimer_permission'] ) && $group['disclaimer_permission'] === 'on' ) ? true : false,
				'share_link_email' => ( ! empty( $group['share_link_email_permission'] ) && $group['share_link_email_permission'] === 'on' ) ? true : false,
				'share_link_phone' => ( ! empty( $group['share_link_phone_permission'] ) && $group['share_link_phone_permission'] === 'on' ) ? true : false,
			);

			$base_settings = array(
				'heading_text'     => $permissions['heading_text'] ? ( ! empty( $base_settings['heading_text'] ) ? $base_settings['heading_text'] : $group_heading_text ) : $group_heading_text,
				'disclaimer'       => $permissions['disclaimer'] ? ( ! empty( $base_settings['disclaimer'] ) ? $base_settings['disclaimer'] : $group_disclaimer ) : $group_disclaimer,
				'share_link_email' => $permissions['share_link_email'] ? ( ! empty( $base_settings['share_link_email'] ) ? $base_settings['share_link_email'] : $group_email ) : $group_email,
				'share_link_phone' => $permissions['share_link_phone'] ? ( ! empty( $base_settings['share_link_phone'] ) ? $base_settings['share_link_phone'] : $group_phone ) : $group_phone,
			);
		}

		if ( ! empty( $group ) && Conditionals::can_administer_group( $user_settings['user_id'] ) ) {
			$base_settings = array(
				'heading_text'     => ! empty( $base_settings['heading_text'] ) ? $base_settings['heading_text'] : $group_heading_text,
				'disclaimer'       => ! empty( $base_settings['disclaimer'] ) ? $base_settings['disclaimer'] : $group_disclaimer,
				'share_link_email' => ! empty( $base_settings['share_link_email'] ) ? $base_settings['share_link_email'] : $group_email,
				'share_link_phone' => ! empty( $base_settings['share_link_phone'] ) ? $base_settings['share_link_phone'] : $group_phone,
			);
		}

		if ( isset( $user_settings['user_id'] ) ) {
			$base_settings['user_id'] = $user_settings['user_id'];
		}

		if ( isset( $base_settings['user_id'] ) ) {
			$base_settings['advisor_name'] = fp_get_advisor_name( $base_settings['user_id'] );
		}

		$settings = $base_settings;

		return apply_filters( FP_ACCOUNT_SETTINGS_PREFIX . '_get_whitelabel_settings', $settings, $user_settings );
	}
}
