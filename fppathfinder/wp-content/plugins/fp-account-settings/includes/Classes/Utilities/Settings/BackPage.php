<?php
/**
 * Back Page.
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
 * GBack Page.
 *
 * @author Objectiv
 * @since  1.0.0
 */
class BackPage {

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
	 * @param array $settings The settings array.
	 *
	 * @return boolean
	 */
	public function generate( array $settings = [] ) {
		if ( empty( $settings ) ) {
			return false;
		}

		$excludes = [
			'advisor_name',
			'logo',
			'color_set',
			'business_display_name',
			'use_advanced',
			'color_set_choice',
			'user_id',
		];

		$temp = $settings;

		foreach ( $temp as $key => $value ) {
			if ( in_array( $key, $excludes, true ) ) {
				unset( $temp[ $key ] );
			}

			// Just to make sure empty item are removed.
			if ( empty( $value ) ) {
				unset( $temp[ $key ] );
			}
		}

		if ( ! empty( $settings['use_advanced'] ) && $settings['use_advanced'] === 'false' ) {
			unset( $temp['advanced_body'] );
		}

		$filled_out_fields = array_filter( $temp );
		$is_customized     = ! empty( $filled_out_fields ) ? true : false;

		return $is_customized;
	}
}
