<?php
/**
 * Get Group Whitelabel Settings.
 *
 * @package    Fp_Account_Settings
 * @subpackage Fp_Account_Settings/Inlcudes/Functions
 * @author     Objectiv
 * @copyright  Copyright (c) 2021, Objectiv
 * @license    GNU General Public License v2 or later
 * @since      1.0.0
 */

use FpAccountSettings\Includes\Utilites\Settings\GroupWhitelabelSettings;

if ( ! function_exists( 'fp_get_group_whitelabel_settings' ) ) {
	/**
	 * Get Group Whitelabel Settings.
	 *
	 * @param array $user_settings The user_aettings.
	 *
	 * @return array
	 */
	function fp_get_group_whitelabel_settings( array $user_settings = [] ) {
		return ( new GroupWhitelabelSettings() )->get( $user_settings );
	}
}
