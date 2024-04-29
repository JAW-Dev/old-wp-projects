<?php
/**
 * Transients Functions.
 *
 * @package    Fp_Account_Settings
 * @subpackage Fp_Account_Settings/Inlcudes/Functions
 * @author     Objectiv
 * @copyright  Copyright (c) 2021, Objectiv
 * @license    GNU General Public License v2 or later
 * @since      1.0.0
 */

use FpAccountSettings\Includes\Utilities\Transients\SettingsFields;

if ( ! function_exists( 'fp_delete_current_user_settings_transients' ) ) {
	/**
	 * Delete Current User Settings Transients.
	 *
	 * @return string
	 */
	function fp_delete_current_user_settings_transients(): string {
		if ( class_exists( '\FpAccountSettings\Includes\Utilities\Transients\SettingsFields' ) ) {
			return ( new SettingsFields() )->delete_current_user_settings_transients();
		}

		return '';
	}
}

if ( ! function_exists( 'fp_delete_all_users_settings_transients' ) ) {
	/**
	 * Delete All User's Settings Transients.
	 *
	 * @return string
	 */
	function fp_delete_all_users_settings_transients(): string {
		if ( class_exists( '\FpAccountSettings\Includes\Utilities\Transients\SettingsFields' ) ) {
			return ( new SettingsFields() )->delete_all_users_settings_transients();
		}

		return '';
	}
}
