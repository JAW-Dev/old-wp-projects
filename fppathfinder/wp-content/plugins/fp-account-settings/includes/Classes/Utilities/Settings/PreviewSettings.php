<?php
/**
 * Preview Settings.
 *
 * @package    Fp_Account_Settings
 * @subpackage Fp_Account_Settings/Inlcudes/Utilites/Settings
 * @author     Objectiv
 * @copyright  Copyright (c) 2021, Objectiv
 * @license    GNU General Public License v2 or later
 * @since      1.0.0
 */

namespace FpAccountSettings\Includes\Utilites\Settings;

use FpAccountSettings\Includes\Utilites\Media\Image;
use FpAccountSettings\Includes\Classes\Logo;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

/**
 * Preview Settings.
 *
 * @author Objectiv
 * @since  1.0.0
 */
class PreviewSettings {

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
	 * @return array
	 */
	public function get() {
		$post = $_POST;

		if ( ! wp_verify_nonce( $post['whitelabel_settings'], 'save_whitelabel_settings' ) ) {
			return [];
		}

		$form_version = $post['form-version'];
		$defult_logo  = isset( $post[ $form_version . '-logo-default-logo' ] ) ? $post[ $form_version . '-logo-default-logo' ] : '';

		$settings = array(
			'logo'                   => isset( $post[ $form_version . '-logo-data' ] ) ? $post[ $form_version . '-logo-data' ] : $defult_logo,
			'color_set'              => isset( $post[ $form_version . '-selected-colors' ] ) ? (array) json_decode( stripslashes( $post[ $form_version . '-selected-colors' ] ) ) : '',
			'business_display_name'  => isset( $post[ $form_version . '-business-display-name' ] ) ? $post[ $form_version . '-business-display-name' ] : '',
			'advisor_name'           => fp_get_advisor_name(),
			'second_page_body_copy'  => isset( $post[ $form_version . '-second-page-body-copy' ] ) ? $post[ $form_version . '-second-page-body-copy' ] : '',
			'second_page_body_title' => isset( $post[ $form_version . '-second-page-body-title' ] ) ? $post[ $form_version . '-second-page-body-title' ] : '',
			'second_page_title'      => isset( $post[ $form_version . '-second-page-title' ] ) ? $post[ $form_version . '-second-page-title' ] : '',
			'job_title'              => isset( $post[ $form_version . '-job-title' ] ) ? $post[ $form_version . '-job-title' ] : '',
			'address'                => isset( $post[ $form_version . '-address' ] ) ? $post[ $form_version . '-address' ] : '',
			'email'                  => isset( $post[ $form_version . '-email' ] ) ? $post[ $form_version . '-email' ] : '',
			'phone'                  => isset( $post[ $form_version . '-phone' ] ) ? $post[ $form_version . '-phone' ] : '',
			'website'                => isset( $post[ $form_version . '-website' ] ) ? $post[ $form_version . '-website' ] : '',
			'use_advanced'           => isset( $post[ $form_version . '-use-advanced' ] ) ? $post[ $form_version . '-use-advanced' ] : '',
			'advanced_body'          => isset( $post[ $form_version . '-second-page-advanced-body' ] ) ? $post[ $form_version . '-second-page-advanced-body' ] : '',
		);

		$no_advisor_name = fp_use_advisor_name();

		if ( $no_advisor_name ) {
			$settings['advisor_name'] = $settings['business_display_name'];
		}

		foreach ( $settings as $key => $value ) {
			if ( is_array( $value ) ) {
				continue;
			}

			$settings[ $key ] = trim( $value );
		}

		return apply_filters( FP_ACCOUNT_SETTINGS_PREFIX . '_get_gpost_settings', $settings, $post );
	}
}
