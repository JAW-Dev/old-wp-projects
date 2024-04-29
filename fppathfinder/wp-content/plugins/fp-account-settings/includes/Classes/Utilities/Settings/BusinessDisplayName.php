<?php
/**
 * Get Business Display Name.
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
 * Get Business Display Name.
 *
 * @author Objectiv
 * @since  1.0.0
 */
class BusinessDisplayName {

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

		$business_display_name = ! empty( $individual['business_display_name'] ) ? $individual['business_display_name'] : '';

		if ( ! empty( $group ) ) {
			$group_business_display_name = ! empty( $group['business_display_name'] ) ? $group['business_display_name'] : '';
		}

		if ( ! empty( $group ) && ! Conditionals::can_administer_group( $user_settings['user_id'] ) ) {
			$permissions           = ( ! empty( $group['business_display_name_permission'] ) && $group['business_display_name_permission'] === 'on' ) ? true : false;
			$business_display_name = $permissions ? $business_display_name : $group_business_display_name;
		}

		if ( ! empty( $group ) && Conditionals::can_administer_group( $user_settings['user_id'] ) ) {
			$business_display_name = ! empty( $business_display_name ) ? $business_display_name : $group_business_display_name;
		}

		return apply_filters( FP_ACCOUNT_SETTINGS_PREFIX . '_get_business_display_name_settings', $business_display_name, $user_settings );
	}
}
