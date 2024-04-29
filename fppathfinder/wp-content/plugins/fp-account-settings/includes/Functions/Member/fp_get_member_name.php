<?php
/**
 * Get Member Name.
 *
 * @package    Fp_Account_Settings
 * @subpackage Fp_Account_Settings/Inlcudes/Functions/Members
 * @author     Objectiv
 * @copyright  Copyright (c) 2021, Objectiv
 * @license    GNU General Public License v2 or later
 * @since      1.0.0
 */

use FpAccountSettings\Includes\Utilites\Members\MemberName;

if ( ! function_exists( 'fp_get_member_name' ) ) {
	/**
	 * Get Member Name.
	 *
	 * @param int $user_id The user ID.
	 *
	 * @return array
	 */
	function fp_get_member_name( int $user_id = null ) {
		return ( new MemberName() )->get( $user_id );
	}
}
