<?php
/**
 * Group Permissions.
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
 * Group Permissions.
 *
 * @author Objectiv
 * @since  1.0.0
 */
class GroupPermissions {

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

		$settings = [
			'logo_permission'                   => isset( $user_settings['group_whitelabel_settings']['logo_permission'] ) ? $user_settings['group_whitelabel_settings']['logo_permission'] : '',
			'color_set_permission'              => isset( $user_settings['group_whitelabel_settings']['color_set_permission'] ) ? $user_settings['group_whitelabel_settings']['color_set_permission'] : '',
			'business_display_name_permission'  => isset( $user_settings['group_whitelabel_settings']['business_display_name_permission'] ) ? $user_settings['group_whitelabel_settings']['business_display_name_permission'] : '',
			'second_page_title_permission'      => isset( $user_settings['group_whitelabel_settings']['second_page_title_permission'] ) ? $user_settings['group_whitelabel_settings']['second_page_title_permission'] : '',
			'second_page_body_title_permission' => isset( $user_settings['group_whitelabel_settings']['second_page_body_title_permission'] ) ? $user_settings['group_whitelabel_settings']['second_page_body_title_permission'] : '',
			'second_page_body_copy_permission'  => isset( $user_settings['group_whitelabel_settings']['second_page_body_copy_permission'] ) ? $user_settings['group_whitelabel_settings']['second_page_body_copy_permission'] : '',
			'job_title_permission'              => isset( $user_settings['group_whitelabel_settings']['job_title_permission'] ) ? $user_settings['group_whitelabel_settings']['job_title_permission'] : '',
			'address_permission'                => isset( $user_settings['group_whitelabel_settings']['address_permission'] ) ? $user_settings['group_whitelabel_settings']['address_permission'] : '',
			'email_permission'                  => isset( $user_settings['group_whitelabel_settings']['email_permission'] ) ? $user_settings['group_whitelabel_settings']['email_permission'] : '',
			'phone_permission'                  => isset( $user_settings['group_whitelabel_settings']['phone_permission'] ) ? $user_settings['group_whitelabel_settings']['phone_permission'] : '',
			'website_permission'                => isset( $user_settings['group_whitelabel_settings']['website_permission'] ) ? $user_settings['group_whitelabel_settings']['website_permission'] : '',
			'advanced_body_permission'          => isset( $user_settings['group_whitelabel_settings']['advanced_body_permission'] ) ? $user_settings['group_whitelabel_settings']['advanced_body_permission'] : '',
		];

		$use_advanced = isset( $user_settings['group_whitelabel_settings']['use_advanced'] ) ? $user_settings['group_whitelabel_settings']['use_advanced'] : 'false';

		if ( $use_advanced === 'false' ) {
			$settings['advanced_body_permission'] = '';
		}

		if ( Conditionals::can_administer_group() ) {
			foreach ( $settings as $key => $value ) {
				if ( $key !== 'advanced_body_permission' ) {
					$settings[ $key ] = 'on';
				}
			}
		}

		$group_whitelabel_settings = isset( $user_settings['group_whitelabel_settings']['use_advanced'] ) ? $user_settings['group_whitelabel_settings']['use_advanced'] : 'false';

		if ( Conditionals::can_administer_group() && empty( $settings['advanced_body_permission'] ) && $group_whitelabel_settings === 'true' ) {
			$settings['advanced_body_permission'] = 'on';
		}

		return apply_filters( FP_ACCOUNT_SETTINGS_PREFIX . '_get_group_permissions', $settings, $user_settings );
	}
}
