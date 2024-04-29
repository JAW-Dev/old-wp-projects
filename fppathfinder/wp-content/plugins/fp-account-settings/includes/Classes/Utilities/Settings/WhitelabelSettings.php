<?php
/**
 * Whitelabel Settings.
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
 * Whitelabel Settings.
 *
 * @author Objectiv
 * @since  1.0.0
 */
class WhitelabelSettings {

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

		$individual = ! empty( $user_settings['whitelabel'] ) ? $user_settings['whitelabel'] : [];
		$group      = ! empty( $user_settings['group_whitelabel_settings'] ) ? $user_settings['group_whitelabel_settings'] : [];
		$advisor    = ! empty( $user_settings['group_settings'] ) ? $user_settings['group_settings'] : [];

		$base_settings = array(
			'logo'                  => ! empty( $individual['logo'] ) ? $individual['logo'] : '',
			'business_display_name' => ! empty( $individual['business_display_name'] ) ? $individual['business_display_name'] : '',
			'color_set'             => ! empty( $individual['color_set'] ) ? $individual['color_set'] : '',
			'color_set_choice'      => ! empty( $individual['color_set_choice'] ) ? $individual['color_set_choice'] : '',
		);

		$backpage_settings = array(
			'second_page_title'      => ! empty( $individual['second_page_title'] ) ? $individual['second_page_title'] : '',
			'second_page_body_title' => ! empty( $individual['second_page_body_title'] ) ? $individual['second_page_body_title'] : '',
			'second_page_body_copy'  => ! empty( $individual['second_page_body_copy'] ) ? $individual['second_page_body_copy'] : '',
			'job_title'              => ! empty( $individual['job_title'] ) ? $individual['job_title'] : '',
			'address'                => ! empty( $individual['address'] ) ? $individual['address'] : '',
			'email'                  => ! empty( $individual['email'] ) ? $individual['email'] : '',
			'phone'                  => ! empty( $individual['phone'] ) ? $individual['phone'] : '',
			'website'                => ! empty( $individual['website'] ) ? $individual['website'] : '',
			'use_advanced'           => ! empty( $individual['use_advanced'] ) ? trim( $individual['use_advanced'] ) : '',
			'advanced_body'          => ! empty( $individual['advanced_body'] ) ? trim( $individual['advanced_body'] ) : '',
		);

		if ( ! empty( $group ) ) {
			$group_logo                   = ! empty( $group['logo'] ) ? $group['logo'] : '';
			$group_business_display_name  = ! empty( $group['business_display_name'] ) ? $group['business_display_name'] : '';
			$group_color_set              = ! empty( $group['color_set'] ) ? $group['color_set'] : '';
			$group_color_set_choice       = ! empty( $group['color_set_choice'] ) ? $group['color_set_choice'] : '';
			$group_second_page_title      = ! empty( $group['second_page_title'] ) ? $group['second_page_title'] : '';
			$group_second_page_body_title = ! empty( $group['second_page_body_title'] ) ? $group['second_page_body_title'] : '';
			$group_second_page_body_copy  = ! empty( $group['second_page_body_copy '] ) ? $group['second_page_body_copy '] : '';
			$group_job_title              = ! empty( $group['job_title'] ) ? $group['job_title'] : '';
			$group_address                = ! empty( $group['address'] ) ? $group['address'] : '';
			$group_email                  = ! empty( $group['email'] ) ? $group['email'] : '';
			$group_phone                  = ! empty( $group['phone'] ) ? $group['phone'] : '';
			$group_website                = ! empty( $group['website'] ) ? $group['website'] : '';
			$group_use_advanced           = ! empty( $group['use_advanced'] ) ? $group['use_advanced'] : '';
			$group_advanced_body          = ! empty( $group['advanced_body'] ) ? $group['advanced_body'] : '';
		}

		if ( ! empty( $group ) && ! Conditionals::can_administer_group( $user_settings['user_id'] ) ) {
			$permissions = array(
				'logo'                   => ( ! empty( $advisor['logo'] ) && $advisor['logo'] === 'on' ) ? true : false,
				'business_display_name'  => ( ! empty( $advisor['business_display_name'] ) && $advisor['business_display_name'] === 'on' ) ? true : false,
				'color_set'              => ( ! empty( $advisor['color_set'] ) && $advisor['color_set'] === 'on' ) ? true : false,
				'second_page_title'      => ( ! empty( $group['second_page_title_permission'] ) && $group['second_page_title_permission'] === 'on' ) ? true : false,
				'second_page_body_title' => ( ! empty( $group['second_page_body_title_permission'] ) && $group['second_page_body_title_permission'] === 'on' ) ? true : false,
				'second_page_body_copy'  => ( ! empty( $group['second_page_body_copy_permission'] ) && $group['second_page_body_copy_permission'] === 'on' ) ? true : false,
				'job_title'              => ( ! empty( $group['job_title_permission'] ) && $group['job_title_permission'] === 'on' ) ? true : false,
				'address'                => ( ! empty( $group['address_permission'] ) && $group['address_permission'] === 'on' ) ? true : false,
				'email'                  => ( ! empty( $group['email_permission'] ) && $group['email_permission'] === 'on' ) ? true : false,
				'phone'                  => ( ! empty( $group['phone_permission'] ) && $group['phone_permission'] === 'on' ) ? true : false,
				'website'                => ( ! empty( $group['website_permission'] ) && $group['website_permission'] === 'on' ) ? true : false,
				'advanced_body'          => ( ! empty( $group['advanced_body_permission'] ) && $group['advanced_body_permission'] === 'on' ) ? true : false,
			);

			$base_settings = array(
				'logo'                  => isset( $permissions['logo'] ) ? ( ! empty( $base_settings['logo'] ) ? $base_settings['logo'] : $group_logo ) : $group_logo,
				'business_display_name' => isset( $permissions['business_display_name'] ) ? ( ! empty( $base_settings['business_display_name'] ) ? $base_settings['business_display_name'] : $group_business_display_name ) : $group_business_display_name,
				'color_set'             => isset( $permissions['color_set'] ) ? ( ! empty( $backpage_settings['color_set'] ) ? $backpage_settings['color_set'] : $group_color_set ) : $group_color_set,
				'color_set_choice'      => ! empty( $backpage_settings['color_set_choice'] ) ? $backpage_settings['color_set_choice'] : $group_color_set_choice,
			);

			$backpage_settings = array(
				'second_page_title'      => $permissions['second_page_title'] ? ( ! empty( $backpage_settings['second_page_title'] ) ? $backpage_settings['second_page_title'] : $group_second_page_title ) : $group_second_page_title,
				'second_page_body_title' => $permissions['second_page_body_title'] ? ( ! empty( $backpage_settings['second_page_body_title'] ) ? $backpage_settings['second_page_body_title'] : $group_second_page_body_title ) : $group_second_page_body_title,
				'second_page_body_copy'  => $permissions['second_page_body_copy'] ? ( ! empty( $backpage_settings['second_page_body_copy'] ) ? $backpage_settings['second_page_body_copy'] : $group_second_page_body_copy ) : $group_second_page_body_copy,
				'job_title'              => $permissions['job_title'] ? ( ! empty( $backpage_settings['job_title'] ) ? $backpage_settings['job_title'] : $group_job_title ) : $group_job_title,
				'address'                => $permissions['address'] ? ( ! empty( $backpage_settings['address'] ) ? $backpage_settings['address'] : $group_address ) : $group_address,
				'email'                  => $permissions['email'] ? ( ! empty( $backpage_settings['email'] ) ? $backpage_settings['email'] : $group_email ) : $group_email,
				'phone'                  => $permissions['phone'] ? ( ! empty( $backpage_settings['phone'] ) ? $backpage_settings['phone'] : $group_phone ) : $group_phone,
				'website'                => $permissions['website'] ? ( ! empty( $backpage_settings['website'] ) ? $backpage_settings['website'] : $group_website ) : $group_website,
				'use_advanced'           => ! empty( $backpage_settings['use_advanced'] ) ? $backpage_settings['use_advanced'] : $group_use_advanced,
				'advanced_body'          => $permissions['advanced_body'] ? ( ! empty( $backpage_settings['advanced_body'] ) ? $backpage_settings['advanced_body'] : $group_advanced_body ) : $group_advanced_body,
			);
		}

		if ( ! empty( $group ) && Conditionals::can_administer_group( $user_settings['user_id'] ) ) {
			$base_settings = array(
				'logo'                  => ! empty( $base_settings['logo'] ) ? $base_settings['logo'] : $group_logo,
				'business_display_name' => ! empty( $base_settings['business_display_name'] ) ? $base_settings['business_display_name'] : $group_business_display_name,
				'color_set'             => ! empty( $base_settings['color_set'] ) ? $base_settings['color_set'] : $group_color_set,
				'color_set_choice'      => ! empty( $base_settings['color_set_choice'] ) ? $base_settings['color_set_choice'] : $group_color_set_choice,
			);

			$backpage_settings = array(
				'second_page_title'      => ! empty( $backpage_settings['second_page_title'] ) ? $backpage_settings['second_page_title'] : $group_second_page_title,
				'second_page_body_title' => ! empty( $backpage_settings['second_page_body_title'] ) ? $backpage_settings['second_page_body_title'] : $group_second_page_body_title,
				'second_page_body_copy'  => ! empty( $backpage_settings['second_page_body_copy'] ) ? $backpage_settings['second_page_body_copy'] : $group_second_page_body_copy,
				'job_title'              => ! empty( $backpage_settings['job_title'] ) ? $backpage_settings['job_title'] : $group_job_title,
				'address'                => ! empty( $backpage_settings['address'] ) ? $backpage_settings['address'] : $group_address,
				'email'                  => ! empty( $backpage_settings['email'] ) ? $backpage_settings['email'] : $group_email,
				'phone'                  => ! empty( $backpage_settings['phone'] ) ? $backpage_settings['phone'] : $group_phone,
				'website'                => ! empty( $backpage_settings['website'] ) ? $backpage_settings['website'] : $group_website,
				'use_advanced'           => ! empty( $backpage_settings['use_advanced'] ) ? $backpage_settings['use_advanced'] : $group_use_advanced,
				'advanced_body'          => ! empty( $backpage_settings['advanced_body'] ) ? $backpage_settings['advanced_body'] : $group_advanced_body,
			);
		}

		if ( isset( $user_settings['user_id'] ) ) {
			$base_settings['user_id'] = $user_settings['user_id'];
		}

		if ( isset( $base_settings['user_id'] ) ) {
			$base_settings['advisor_name'] = fp_get_advisor_name( $base_settings['user_id'] );
		}

		$settings = array_merge( $base_settings, $backpage_settings );

		return apply_filters( FP_ACCOUNT_SETTINGS_PREFIX . '_get_whitelabel_settings', $settings, $user_settings );
	}
}
