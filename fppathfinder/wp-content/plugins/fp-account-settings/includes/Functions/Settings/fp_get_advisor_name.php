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

use FpAccountSettings\Includes\Utilites\Settings\AdvisorName;

if ( ! function_exists( 'fp_get_advisor_name' ) ) {
	/**
	 * Get Advisor Name.
	 *
	 * @param int $user_id The user ID.
	 *
	 * @return string
	 */
	function fp_get_advisor_name( int $user_id = null ) {
		return ( new AdvisorName() )->get( $user_id );
	}
}
