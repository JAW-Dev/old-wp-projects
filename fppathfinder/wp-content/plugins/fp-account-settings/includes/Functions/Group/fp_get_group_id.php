<?php
/**
 * Get Group ID.
 *
 * @package    Fp_Account_Settings
 * @subpackage Fp_Account_Settings/Inlcudes/Functions/Group
 * @author     Objectiv
 * @copyright  Copyright (c) 2021, Objectiv
 * @license    GNU General Public License v2 or later
 * @since      1.0.0
 */

use FpAccountSettings\Includes\Utilites\Group\Id;

if ( ! function_exists( 'fp_get_group_id' ) ) {
	/**
	 * Get Group ID.
	 *
	 * @param int $user_id The user ID.
	 *
	 * @return array
	 */
	function fp_get_group_id( int $user_id = null ) {
		return ( new Id() )->get( (int) $user_id );
	}
}
