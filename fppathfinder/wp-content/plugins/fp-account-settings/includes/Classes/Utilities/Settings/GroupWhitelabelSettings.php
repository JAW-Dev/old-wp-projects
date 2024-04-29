<?php
/**
 * Group Whitelabel Settings.
 *
 * @package    Fp_Account_Settings
 * @subpackage Fp_Account_Settings/Inlcudes/Utilites/Settings
 * @author     Objectiv
 * @copyright  Copyright (c) 2021, Objectiv
 * @license    GNU General Public License v2 or later
 * @since      1.0.0
 */

namespace FpAccountSettings\Includes\Utilites\Settings;

use FP_Core\Member;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

/**
 * Group Whitelabel Settings.
 *
 * @author Objectiv
 * @since  1.0.0
 */
class GroupWhitelabelSettings {

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
		$group         = ! empty( $user_settings['group_whitelabel_settings'] ) ? $user_settings['group_whitelabel_settings'] : [];

		if ( empty( $group ) ) {
			if ( rcpga_user_is_group_member( get_current_user_id() ) ) {
				$group = ( new Member( get_current_user_id() ) )->get_group();

				if ( $group ) {
					$group_id = $group->get_group_id();
					$group    = get_metadata( 'rcp_group', $group_id, 'group_whitelabel_settings', true );
				}
			}
		}

		$base_settings = [
			'logo'                  => ! empty( $group['logo'] ) ? $group['logo'] : '',
			'business_display_name' => ! empty( $group['business_display_name'] ) ? $group['business_display_name'] : '',
			'color_set'             => ! empty( $group['color_set'] ) ? $group['color_set'] : '',
			'color_set_choice'      => ! empty( $group['color_set_choice'] ) ? $group['color_set_choice'] : '',
		];

		$backpage_settings = [
			'second_page_title'      => ! empty( $group['second_page_title'] ) ? $group['second_page_title'] : '',
			'second_page_body_title' => ! empty( $group['second_page_body_title'] ) ? $group['second_page_body_title'] : '',
			'second_page_body_copy'  => ! empty( $group['second_page_body_copy'] ) ? $group['second_page_body_copy'] : '',
			'job_title'              => ! empty( $group['job_title'] ) ? $group['job_title'] : '',
			'address'                => ! empty( $group['address'] ) ? $group['address'] : '',
			'email'                  => ! empty( $group['email'] ) ? $group['email'] : '',
			'phone'                  => ! empty( $group['phone'] ) ? $group['phone'] : '',
			'website'                => ! empty( $group['website'] ) ? $group['website'] : '',
			'use_advanced'           => ! empty( $group['use_advanced'] ) ? trim( $group['use_advanced'] ) : '',
			'advanced_body'          => ! empty( $group['advanced_body'] ) ? trim( $group['advanced_body'] ) : '',
		];

		$permissions = [
			'logo_permission'                   => ! empty( $group['logo_permission'] ) ? $group['logo_permission'] : 'off',
			'business_display_name_permission'  => ! empty( $group['business_display_name_permission'] ) ? $group['business_display_name_permission'] : 'off',
			'color_set_permission'              => ! empty( $group['color_set_permission'] ) ? $group['color_set_permission'] : 'off',
			'second_page_title_permission'      => ! empty( $group['second_page_title_permission'] ) ? $group['second_page_title_permission'] : 'off',
			'second_page_body_title_permission' => ! empty( $group['second_page_body_title_permission'] ) ? $group['second_page_body_title_permission'] : 'off',
			'second_page_body_copy_permission'  => ! empty( $group['second_page_body_copy_permission'] ) ? $group['second_page_body_copy_permission'] : 'off',
			'job_title_permission'              => ! empty( $group['job_title_permission'] ) ? $group['job_title_permission'] : 'off',
			'address_permission'                => ! empty( $group['address_permission'] ) ? $group['address_permission'] : 'off',
			'email_permission'                  => ! empty( $group['email_permission'] ) ? $group['email_permission'] : 'off',
			'phone_permission'                  => ! empty( $group['phone_permission'] ) ? $group['phone_permission'] : 'off',
			'website_permission'                => ! empty( $group['website_permission'] ) ? $group['website_permission'] : 'off',
			'advanced_body_permission'          => ! empty( $group['advanced_body_permission'] ) ? $group['advanced_body_permission'] : 'off',
		];

		$settings = array_merge( $base_settings, $backpage_settings, $permissions );

		return apply_filters( FP_ACCOUNT_SETTINGS_PREFIX . '_get_group_whitelabel_settings', $settings, $user_settings );
	}
}
