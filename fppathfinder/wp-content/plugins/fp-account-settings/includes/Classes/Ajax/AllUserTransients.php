<?php
/**
 * All User Transients
 *
 * @package    Fp_Account_Settings
 * @subpackage Fp_Account_Settings/Inlcudes/Classes/Ajax
 * @author     Objectiv
 * @copyright  Copyright (c) 2021, Objectiv
 * @license    GNU General Public License v2 or later
 * @since      1.0.0
 */

namespace FpAccountSettings\Includes\Classes\Ajax;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

/**
 * All User Transients
 *
 * @author Jason Witt
 * @since  1.0.0
 */
class AllUserTransients {

	/**
	 * Initialize the class
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function __construct() {
		add_action( 'wp_ajax_all_user_transients', array( $this, 'clear' ) );
		add_action( 'wp_ajax_nopriv_all_user_transients', array( $this, 'clear' ) );
	}

	/**
	 * Clear All User Transients
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function clear() {
		$transients = function_exists( 'fp_delete_all_users_settings_transients' ) ? fp_delete_all_users_settings_transients() : 0;

		if ( ! empty( $transients ) ) {
			echo esc_html( $transients );
			wp_die();
		}

		echo esc_html( 'Unable to clear transients' );
		wp_die();
	}
}
