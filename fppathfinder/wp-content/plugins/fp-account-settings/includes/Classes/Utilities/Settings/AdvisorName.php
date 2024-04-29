<?php
/**
 * Set Advisor Name.
 *
 * @package    Fp_Account_Settings
 * @subpackage Fp_Account_Settings/Inlcudes/Utilites/Settings
 * @author     Objectiv
 * @copyright  Copyright (c) 2021, Objectiv
 * @license    GNU General Public License v2 or later
 * @since      1.0.0
 */

namespace FpAccountSettings\Includes\Utilites\Settings;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

/**
 * Set Advisor Name.
 *
 * @author Objectiv
 * @since  1.0.0
 */
class AdvisorName {

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
	 * Get Advisor Name
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param int $user_id The user ID.
	 *
	 * @return string
	 */
	public function get( int $user_id = null ) {
		$user_id         = ! is_null( $user_id ) ? $user_id : null;
		$advisor_name    = fp_get_member_name( $user_id );
		$no_advisor_name = $this->use_name( $user_id );
		$name            = $no_advisor_name ? fp_get_business_display_name( fp_get_user_settings( $user_id ) ) : $advisor_name;

		return apply_filters( FP_ACCOUNT_SETTINGS_PREFIX . '_get_advisor_name', $name, $user_id );
	}

	/**
	 * Use Advisor Name
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param int $user_id The user ID.
	 *
	 * @return boolean
	 */
	public function use_name( int $user_id = null ) {
		$user_settings   = fp_get_user_settings( $user_id );
		$group_settings  = fp_get_group_settings( $user_settings );
		$no_advisor_name = ! empty( $group_settings['enabled_no_advisor_name'] ) ? $group_settings['enabled_no_advisor_name'] : '';
		$use_name        = false;

		if ( $no_advisor_name ) {
			$use_name = true;
		}

		return apply_filters( FP_ACCOUNT_SETTINGS_PREFIX . '_use_advisor_name', $use_name, $user_id );
	}
}
