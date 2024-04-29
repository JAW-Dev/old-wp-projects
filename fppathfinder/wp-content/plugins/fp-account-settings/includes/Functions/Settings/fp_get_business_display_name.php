<?php
/**
 * Get Bisiness Display Name.
 *
 * @package    Fp_Account_Settings
 * @subpackage Fp_Account_Settings/Inlcudes/Functions
 * @author     Objectiv
 * @copyright  Copyright (c) 2021, Objectiv
 * @license    GNU General Public License v2 or later
 * @since      1.0.0
 */

use FpAccountSettings\Includes\Utilites\Settings\BusinessDisplayName;

if ( ! function_exists( 'fp_get_business_display_name' ) ) {
	/**
	 * Get Bisiness Display Name.
	 *
	 * @param array $user_settings The user_settings.
	 *
	 * @return array
	 */
	function fp_get_business_display_name( array $user_settings = [] ) {
		return ( new BusinessDisplayName() )->get( $user_settings );
	}
}
