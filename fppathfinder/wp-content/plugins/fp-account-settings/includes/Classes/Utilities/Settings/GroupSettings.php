<?php
/**
 * Group Settings.
 *
 * @package    Fp_Account_Settings
 * @subpackage Fp_Account_Settings/Inlcudes/Utilites/Settings
 * @author     Objectiv
 * @copyright  Copyright (c) 2021, Objectiv
 * @license    GNU General Public License v2 or later
 * @since      1.0.0
 */

namespace FpAccountSettings\Includes\Utilites\Settings;

use FpAccountSettings\Includes\Classes\Conditionals;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

/**
 * Group Settings.
 *
 * @author Objectiv
 * @since  1.0.0
 */
class GroupSettings {

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
	 * @param array $user_settings The user settings array.
	 *
	 * @return array
	 */
	public function get( array $user_settings = [] ) {
		$user_settings  = ! empty( $user_settings ) ? $user_settings : fp_get_user_settings();
		$group_settings = isset( $user_settings['group_settings'] ) ? $user_settings['group_settings'] : [];

		return apply_filters( FP_ACCOUNT_SETTINGS_PREFIX . '_get_group_settings', $group_settings, $user_settings );
	}
}
