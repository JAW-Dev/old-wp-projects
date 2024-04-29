<?php
/**
 * Has Logo Image Saved.
 *
 * @package    Fp_Account_Settings
 * @subpackage Fp_Account_Settings/Inlcudes/Functions
 * @author     Objectiv
 * @copyright  Copyright (c) 2021, Objectiv
 * @license    GNU General Public License v2 or later
 * @since      1.0.0
 */

use FpAccountSettings\Includes\Classes\Logo;

if ( ! function_exists( 'fp_has_image_logo_saved' ) ) {
	/**
	 * Has Logo Image Saved.
	 *
	 * @param int $user_id The user ID.
	 *
	 * @return boolean
	 */
	function fp_has_image_logo_saved( int $user_id = null ) {
		return ( new Logo() )->has_logo_image_saved( $user_id );
	}
}
