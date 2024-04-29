<?php
/**
 * Register.
 *
 * @package    Fp_Account_Settings
 * @subpackage Fp_Account_Settings/Inlcudes/Functions
 * @author     Objectiv
 * @copyright  Copyright (c) 2021, Objectiv
 * @license    GNU General Public License v2 or later
 * @since      1.0.0
 */

 use FP_Core\Utilities\Register\Discount;

if ( ! function_exists( 'fp_get_register_discount' ) ) {
	/**
	 * Get Membershipe ID.
	 *
	 * @param int $user_id The user ID.
	 *
	 * @return array
	 */
	function fp_get_register_discount() {
		return ( new Discount() )->get();
	}
}
