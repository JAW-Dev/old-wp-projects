<?php
/**
 * Get Member Name.
 *
 * @package    Fp_Account_Settings
 * @subpackage Fp_Account_Settings/Inlcudes/Utilites/Members
 * @author     Objectiv
 * @copyright  Copyright (c) 2021, Objectiv
 * @license    GNU General Public License v2 or later
 * @since      1.0.0
 */

namespace FpAccountSettings\Includes\Utilites\Members;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

/**
 * Get Member Name.
 *
 * @author Objectiv
 * @since  1.0.0
 */
class MemberName {

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
	 * Get
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param int $user_id The user ID.
	 *
	 * @return array
	 */
	public function get( int $user_id = null ) {
		$user_id    = ! is_null( $user_id ) ? $user_id : get_current_user_id();
		$first_name = get_user_meta( $user_id, 'first_name', true );
		$last_name  = get_user_meta( $user_id, 'last_name', true );
		$name       = $first_name . ' ' . $last_name;

		return apply_filters( FP_ACCOUNT_SETTINGS_PREFIX . '_get_member_name_settings', $name, $user_id );
	}
}
