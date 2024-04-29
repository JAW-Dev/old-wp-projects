<?php
/**
 * Get Membership Access.
 *
 * @package    Fp_Account_Settings
 * @subpackage Fp_Account_Settings/Inlcudes/Functions/Members
 * @author     Objectiv
 * @copyright  Copyright (c) 2021, Objectiv
 * @license    GNU General Public License v2 or later
 * @since      1.0.0
 */

 use FpAccountSettings\Includes\Utilites\Members\MemberAccess;

if ( ! function_exists( 'fp_get_member_access' ) ) {
	/**
	 * Get Membership Access.
	 *
	 * @param array  $args {
	 *     The arguments for settings access.
	 *
	 *     @type int     $membership_level The membership_level required to access.
	 *         1 = Essentials Package, Enterprise Essentials, Essentials 3 Month Free Trial.
	 *         2 = Deluxe Package.
	 *         3 = Premier Package.
	 *         4 = Enterprise Deluxe, Firm-wide Enterprise Deluxe.
	 *         5 = Enterprise Premier, Firm-wide Enterprise Premier.
	 *     @type boolean $administer       If the members is a group Admin or Owner.
	 *     @type boolean $upgraded         The membership level of the group member with upgraded membership.
	 * }
	 * @param array $callback      The function to init.
	 * @param array $callback_args The arguments to pass to the callback function.
	 *
	 * @return array
	 */
	function fp_get_member_access( array $args = [], array $callback = [], array $callback_args = [] ) {
		return ( new MemberAccess() )->get( $args, $callback, $callback_args );
	}
}
