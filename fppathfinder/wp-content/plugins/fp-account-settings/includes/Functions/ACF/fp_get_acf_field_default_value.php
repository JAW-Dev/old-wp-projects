<?php
/**
 * Get Group ID.
 *
 * @package    Fp_Account_Settings
 * @subpackage Fp_Account_Settings/Inlcudes/Functions/ACF
 * @author     Objectiv
 * @copyright  Copyright (c) 2021, Objectiv
 * @license    GNU General Public License v2 or later
 * @since      1.0.0
 */

use FpAccountSettings\Includes\Utilites\ACF\DefaultValue;

if ( ! function_exists( 'fp_get_acf_field_default_value' ) ) {
	/**
	 * Get Group ID.
	 *
	 * @param string $field_name The name of the ACF field.
	 * @param string $group_name The name of the ACF group.
	 *
	 * @return boolean
	 */
	function fp_get_acf_field_default_value( string $field_name = '', string $group_name = '' ) {
		return ( new DefaultValue() )->get( $field_name, $group_name );
	}
}
