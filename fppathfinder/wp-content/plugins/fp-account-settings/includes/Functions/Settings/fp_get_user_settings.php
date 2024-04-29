<?php
/**
 * Get User Settings.
 *
 * @package    Fp_Account_Settings
 * @subpackage Fp_Account_Settings/Inlcudes/Functions
 * @author     Objectiv
 * @copyright  Copyright (c) 2021, Objectiv
 * @license    GNU General Public License v2 or later
 * @since      1.0.0
 */

use FpAccountSettings\Includes\Utilites\Settings\UserSettings;

if ( ! function_exists( 'fp_get_user_settings' ) ) {
	/**
	 * Get User Settings.
	 *
	 * @param int $user_id The user ID.
	 *
	 * @return array
	 */
	function fp_get_user_settings( int $user_id = null ) {
		return ( new UserSettings() )->get( $user_id );
	}
}
