<?php
/**
 * Default Value.
 *
 * @package    Fp_Account_Settings
 * @subpackage Fp_Account_Settings/Inlcudes/Utilites/ACF
 * @author     Objectiv
 * @copyright  Copyright (c) 2021, Objectiv
 * @license    GNU General Public License v2 or later
 * @since      1.0.0
 */

namespace FpAccountSettings\Includes\Utilites\ACF;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

/**
 * Default Value.
 *
 * @author Objectiv
 * @since  1.0.0
 */
class DefaultValue {

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
	 * @param string $field_name The name of the ACF field.
	 * @param string $group_name The name of the ACF group.
	 *
	 * @return boolean
	 */
	public function get( string $field_name = '', string $group_name = '' ) {
		if ( empty( $field_name ) || empty( $group_name ) ) {
			return false;
		}

		$groups         = function_exists( 'acf_get_field_groups' ) ? acf_get_field_groups() : [];
		$group_settings = [];

		if ( empty( $groups ) ) {
			return false;
		}

		foreach ( $groups as $group ) {
			if ( $group['title'] === $group_name ) {
				$group_settings = $group;
			}
		}

		$fields        = function_exists( 'acf_get_fields' ) ? acf_get_fields( $group_settings['ID'] ) : [];
		$default_value = '';

		if ( empty( $fields ) ) {
			return false;
		}

		foreach ( $fields as $field ) {
			if ( $field['name'] === $field_name ) {
				$default_value = $field['default_value'];
			}
		}

		$set_value = function_exists( 'get_field' ) ? get_field( $field_name, 'options' ) : null;
		$value     = $set_value !== null ? $set_value : $default_value;

		if ( empty( $value ) ) {
			return false;
		}

		return apply_filters( FP_ACCOUNT_SETTINGS_PREFIX . '_get_default_acf_field_value', $value, $field_name, $group_name );
	}
}
