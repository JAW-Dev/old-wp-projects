<?php
/**
 * Get Advisor Name.
 *
 * @package    Fp_Account_Settings
 * @subpackage Fp_Account_Settings/Inlcudes/Functions
 * @author     Objectiv
 * @copyright  Copyright (c) 2021, Objectiv
 * @license    GNU General Public License v2 or later
 * @since      1.0.0
 */

use FpAccountSettings\Includes\Utilites\Settings\BackPage;

if ( ! function_exists( 'fp_do_generate_back_page' ) ) {
	/**
	 * Get Advisor Name.
	 *
	 * @param array $settings The settings array.
	 *
	 * @return string
	 */
	function fp_do_generate_back_page( array $settings = [] ) {
		return ( new BackPage() )->generate( $settings );
	}
}
