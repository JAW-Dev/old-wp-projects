<?php
/**
 * Settings Fields.
 *
 * @package    Fp_Account_Settings
 * @subpackage Fp_Account_Settings/Inlcudes/Utilites/ACF
 * @author     Objectiv
 * @copyright  Copyright (c) 2021, Objectiv
 * @license    GNU General Public License v2 or later
 * @since      1.0.0
 */

namespace FpAccountSettings\Includes\Utilities\Transients;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

/**
 * Settings Fields.
 *
 * @author Objectiv
 * @since  1.0.0
 */
class SettingsFields {

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
	 * Delete User Settings Field Transients
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return string
	 */
	public function delete_current_user_settings_transients() {
		$cleared    = false;
		$user_id    = get_current_user_id();
		$user       = get_userdata( $user_id );
		$user_email = $user->user_email;
		$message    = '';
		$transients = [
			'_whitelabel_resource_transient',
			'_whitelabel_back_page_transient',
			'_whitelabel_back_page_advanced_transient',
			'_group_whitelabel_resource_transient',
			'_group_whitelabel_back_page_transient',
			'_group_whitelabel_back_page_advanced_transient',
		];

		foreach ( $transients as $transient ) {
			$deleted = $this->delete_set_transient( $user_id . $transient );

			if ( $deleted && $cleared === false ) {
				$cleared = true;
			}
		}

		if ( ! $cleared ) {
			$message = 'No settings cache cleared for ' . $user_email . '!';
		}

		if ( $cleared ) {
			$message = 'Settings cache for ' . $user_email . ' have been cleared!';
		}

		return $message;
	}

	/**
	 * Delete All Users Settings Transients
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return string
	 */
	public function delete_all_users_settings_transients(): string {
		set_time_limit( 0 );

		$cleared = 0;
		$message = '';
		$users   = get_users(
			[
				'number' => -1,
				'fields' => 'ID',
			]
		);

		foreach ( $users as $user_id ) {
			$transients = [
				'_whitelabel_resource_transient',
				'_whitelabel_back_page_transient',
				'_whitelabel_back_page_advanced_transient',
				'_group_whitelabel_resource_transient',
				'_group_whitelabel_back_page_transient',
				'_group_whitelabel_back_page_advanced_transient',
			];

			foreach ( $transients as $transient ) {
				$deleted = $this->delete_set_transient( $user_id . $transient );

				if ( $deleted ) {
					$cleared++;
				}
			}
		}

		if ( $cleared === 1 ) {
			$message = $cleared . ' user settings cache cleared!';
		} elseif ( $cleared > 1 ) {
			$message = $cleared . ' user settings cache cleared!';
		} else {
			$message = 'No settings cache cleared!';
		}

		return $message;
	}

	/**
	 * Delete Set Transient
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param string $transient_name The name of the transient.
	 *
	 * @return bool
	 */
	public function delete_set_transient( string $transient_name ): bool {
		$transient = get_transient( $transient_name );

		if ( ! empty( $transient ) ) {
			return delete_transient( $transient_name );
		}

		return false;
	}
}
