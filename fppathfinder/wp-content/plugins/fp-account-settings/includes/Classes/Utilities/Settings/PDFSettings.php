<?php
/**
 * PDF Settings.
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
 * PDF Settings.
 *
 * @author Objectiv
 * @since  1.0.0
 */
class PDFSettings {

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
	public function get( array $user_settings = [] ): array {
		$post           = $_POST;
		$settings       = isset( $user_settings['whitelabel'] ) ? $user_settings['whitelabel'] : fp_get_whitelabel_settings();
		$group_settings = [];
		$nonce          = isset( $post['whitelabel_settings'] ) ? $post['whitelabel_settings'] : '';
		$action         = ! empty( $post['action'] ) ? $post['action'] : '';
		$resource_type  = ! empty( $post['resource_type'] ) ? $post['resource_type'] : '';

		if ( empty( $resource_type ) ) {
			if ( ! empty( $post ) && $post['action'] !== 'generate_bundle_test' && ! isset( $post['whitelabel_settings'] ) ) {
				return [];
			}

			if ( ! empty( $post ) && $post['action'] !== 'generate_bundle_test' && wp_verify_nonce( $nonce, 'save_whitelabel_settings' ) ) {
				$settings = fp_get_preview_settings();
			}
		}

		if ( is_array( $settings ) ) {
			$group_settings = ! empty( $settings['group_whitelabel_settings'] ) ? $settings['group_whitelabel_settings'] : fp_get_group_whitelabel_settings( $settings );
		}

		if ( empty( $group_settings ) ) {
			$group_settings = [];
		}

		if ( rcpga_user_is_group_member( get_current_user_id() ) && ! Conditionals::can_administer_group() ) {
			$settings = array(
				'logo'                   => ! empty( $settings['logo'] ) ? $settings['logo'] : ( ! empty( $group_settings['logo'] ) ? $group_settings['logo'] : '' ),
				'business_display_name'  => ! empty( $settings['business_display_name'] ) ? $settings['business_display_name'] : ( ! empty( $group_settings['business_display_name'] ) ? $group_settings['business_display_name'] : '' ),
				'color_set'              => ! empty( $settings['color_set'] ) ? $settings['color_set'] : ( ! empty( $group_settings['color_set'] ) ? $group_settings['color_set'] : '' ),
				'advisor_name'           => ! empty( $settings['advisor_name'] ) ? $settings['advisor_name'] : false,
				'second_page_body_copy'  => ! empty( $settings['second_page_body_copy'] ) ? $settings['second_page_body_copy'] : ( ! empty( $group_settings['second_page_body_copy'] ) ? $group_settings['second_page_body_copy'] : '' ),
				'second_page_body_title' => ! empty( $settings['second_page_body_title'] ) ? $settings['second_page_body_title'] : ( ! empty( $group_settings['second_page_body_title'] ) ? $group_settings['second_page_body_title'] : '' ),
				'second_page_title'      => ! empty( $settings['second_page_title'] ) ? $settings['second_page_title'] : ( ! empty( $group_settings['second_page_title'] ) ? $group_settings['second_page_title'] : '' ),
				'job_title'              => ! empty( $settings['job_title'] ) ? $settings['job_title'] : ( ! empty( $group_settings['job_title'] ) ? $group_settings['job_title'] : '' ),
				'address'                => ! empty( $settings['address'] ) ? $settings['address'] : ( ! empty( $group_settings['address'] ) ? $group_settings['address'] : '' ),
				'email'                  => ! empty( $settings['email'] ) ? stripslashes( $settings['email'] ) : ( ! empty( $group_settings['email'] ) ? $group_settings['email'] : '' ),
				'phone'                  => ! empty( $settings['phone'] ) ? $settings['phone'] : ( ! empty( $group_settings['phone'] ) ? $group_settings['phone'] : '' ),
				'website'                => ! empty( $settings['website'] ) ? $settings['website'] : ( ! empty( $group_settings['website'] ) ? $group_settings['website'] : '' ),
				'use_advanced'           => ! empty( $settings['use_advanced'] ) ? $settings['use_advanced'] : ( ! empty( $group_settings['use_advanced'] ) ? $group_settings['use_advanced'] : '' ),
				'advanced_body'          => ! empty( $settings['advanced_body'] ) ? $settings['advanced_body'] : ( ! empty( $group_settings['advanced_body'] ) ? $group_settings['advanced_body'] : '' ),
			);
		}

		$no_advisor_name         = fp_use_advisor_name();
		$use_advanced_body       = ! empty( $settings['use_advanced'] ) ? $settings['use_advanced'] : 'false';
		$group_use_advanced_body = ! empty( $group_settings['use_advanced'] ) ? $group_settings['use_advanced'] : 'false';

		if ( $no_advisor_name ) {
			$business_display_name    = ! empty( $settings['business_display_name'] ) ? $settings['business_display_name'] : '';
			$settings['advisor_name'] = $business_display_name;
		}

		$advanced_body         = ! empty( $settings['advanced_body'] ) ? $settings['advanced_body'] : '';
		$group_advanced_body   = ! empty( $group_settings['advanced_body'] ) ? $group_settings['advanced_body'] : '';
		$second_page_body_copy = ! empty( $settings['second_page_body_copy'] ) ? $settings['second_page_body_copy'] : '';

		if ( $use_advanced_body === 'false' && ( $group_use_advanced_body === 'true' && ! empty( $group_advanced_body ) ) ) {
			$use_advanced_body         = 'true';
			if ( ! empty( $settings['advanced_body'] ) ) {
				$settings['advanced_body'] = $advanced_body;
			}
		}

		if ( ! empty( $settings['advanced_body'] ) ) {
			$settings['advanced_body'] = stripslashes( $advanced_body );
			$settings['advanced_body'] = str_replace( 'src="image/', 'src="data:image/', $advanced_body );
		}

		if ( ! empty( $settings['second_page_body_copy'] ) ) {
			$settings['second_page_body_copy'] = stripslashes( $second_page_body_copy );
			$settings['second_page_body_copy'] = str_replace( 'src="image/', 'src="data:image/', $second_page_body_copy );
		}

		if ( empty( $settings ) ) {
			return [];
		}

		foreach ( $settings as $key => $value ) {
			if ( is_array( $value ) ) {
				continue;
			}

			$settings[ $key ] = trim( $value );
		}

		if ( ! is_array( $settings ) ) {
			return [];
		}

		return apply_filters( FP_ACCOUNT_SETTINGS_PREFIX . '_get_pdf_settings', $settings );
	}
}
