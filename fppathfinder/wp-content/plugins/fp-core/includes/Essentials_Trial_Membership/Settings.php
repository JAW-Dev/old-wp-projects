<?php

namespace FP_Core\Essentials_Trial_Membership;

class Settings {
	public function __construct() {}

	static public function init() {
		acf_add_options_sub_page(
			array(
				'page_title'  => 'Essentials Trial Settings',
				'menu_title'  => 'Essentials Trial Settings',
				'parent_slug' => 'rcp-members',
			)
		);
	}

	static public function get_registration_page_id() {
		return get_field( 'essentials_trial_registration_page', 'options' );
	}

	static public function get_codes() {
		$field = get_field( 'essentials_trial_access_codes', 'options' );

		if ( empty( $field ) ) {
			return array();
		}

		$codes = array();

		foreach ( $field as $code_array ) {
			$codes[ $code_array['code'] ] = $code_array;
		}

		return $codes;
	}

	/**
	 * Get Request Code
	 *
	 * Check the current $_REQUEST for a valid code. If found return the array of 'name' and 'code'
	 *
	 * @return array|null
	 */
	static public function get_request_code() {
		$codes      = self::get_codes();
		$code_param = $_REQUEST['essentials_trial'] ?? null;

		if ( ! $code_param ) {
			return null;
		}

		return $codes[ $code_param ] ?? null;
	}
}
