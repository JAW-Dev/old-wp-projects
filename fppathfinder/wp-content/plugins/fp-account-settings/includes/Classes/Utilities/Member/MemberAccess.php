<?php
/**
 * Member Access.
 *
 * @package    Fp_Account_Settings
 * @subpackage Fp_Account_Settings/Inlcudes/Utilites/Members
 * @author     Objectiv
 * @copyright  Copyright (c) 2021, Objectiv
 * @license    GNU General Public License v2 or later
 * @since      1.0.0
 */

namespace FpAccountSettings\Includes\Utilites\Members;

use FpAccountSettings\Includes\Classes\Conditionals;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

/**
 * Member Access.
 *
 * @author Objectiv
 * @since  1.0.0
 */
class MemberAccess {

	/**
	 * Initialize the class
	 *
	 * @author Objectiv
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function __construct() {}

	/**
	 * Get Access
	 *
	 * @author Jason Witt
	 * @since  1.0.0
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
	 * @return boolean
	 */
	public function get( array $args = [], array $callback = [], array $callback_args = [] ) {
		$args = wp_parse_args(
			$args,
			array(
				'is_member'            => false,
				'name'                 => '',
				'not_logged_in'        => false,
				'membership_level'     => 1,
				'membership_level_not' => [],
				'group_member'         => false,
				'administer'           => false,
				'field_access'         => '',
			)
		);

		$can_access = false;

		if ( $args['is_member'] ) {
			$can_access = true;
		}

		if ( $args['not_logged_in'] && ! is_user_logged_in() ) {
			$can_access = true;
		}

		if ( is_user_logged_in() ) {

			if ( $args['membership_level'] > 1 ) {
				$membership_access   = fp_get_membership_access_level();
				$is_membership_level = $membership_access >= $args['membership_level'];

				if ( $is_membership_level ) {
					$can_access = true;
				}

				foreach ( $args['membership_level_not'] as $exception ) {
					if ( $membership_access === $exception ) {
						$can_access = false;
					}
				}
			}

			if ( $args['group_member'] ) {
				$group_member = rcpga_user_is_group_member( get_current_user_id() );

				if ( $group_member ) {
					$can_access = true;
				}
			}

			if ( $args['administer'] ) {
				$group_administer = Conditionals::can_administer_group();

				if ( $group_administer ) {
					$can_access = true;
				}
			}
		}

		if ( ! empty( $callback ) && $can_access ) {
			call_user_func_array( $callback, [ $callback_args ] );
		}

		return apply_filters( FP_ACCOUNT_SETTINGS_PREFIX . '_member_access', $can_access );
	}
}
