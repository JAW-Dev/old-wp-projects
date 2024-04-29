<?php
/**
 * Get Group Permissions.
 *
 * @package    Fp_Account_Settings
 * @subpackage Fp_Account_Settings/Inlcudes/Functions
 * @author     Objectiv
 * @copyright  Copyright (c) 2021, Objectiv
 * @license    GNU General Public License v2 or later
 * @since      1.0.0
 */

use FpAccountSettings\Includes\Utilites\Settings\GroupPermissions;

if ( ! function_exists( 'fp_get_group_permissions' ) ) {
	/**
	 * Get Group Permissions.
	 *
	 * @param array $user_settings The user_settings.
	 *
	 * @return array
	 */
	function fp_get_group_permissions( array $user_settings = [] ) {
		return ( new GroupPermissions() )->get( $user_settings );
	}
}
