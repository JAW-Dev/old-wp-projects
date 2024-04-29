<?php
/**
 * User Settings.
 *
 * @package    Fp_Account_Settings
 * @subpackage Fp_Account_Settings/Inlcudes/Utilites/Settings
 * @author     Objectiv
 * @copyright  Copyright (c) 2021, Objectiv
 * @license    GNU General Public License v2 or later
 * @since      1.0.0
 */

namespace FpAccountSettings\Includes\Utilites\Settings;

use FP_Core\Member;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

/**
 * User Settings.
 *
 * @author Objectiv
 * @since  1.0.0
 */
class UserSettings {

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
		$user_id     = ! is_null( $user_id ) ? $user_id : get_current_user_id();
		$old_version = get_user_meta( $user_id, 'pdf-generator-settings', true );
		$whitelabel  = get_user_meta( $user_id, 'whitelabel-settings', true );
		$share_link  = get_user_meta( $user_id, 'share-link-settings', true );
		$settings    = [];

		$settings['whitelabel'] = $whitelabel;

		if ( empty( $whitelabel ) && ! empty( $old_version ) ) {
			$format = [
				'logo'                   => $old_version['logo'] ?? '',
				'color_set'              => $old_version['colorSet'] ?? [],
				'color_set_choice'       => $old_version['colorSetChoice'] ?? '',
				'business_display_name'  => $old_version['requiredFields']['businessDisplayName'] ?? '',
				'advisor_name'           => $old_version['requiredFields']['adviserName'] ?? '',
				'second_page_title'      => $old_version['secondPageInfo']['secondPageTitle'] ?? '',
				'second_page_body_title' => $old_version['secondPageInfo']['secondPageBodyTitle'] ?? '',
				'second_page_body_copy'  => $old_version['secondPageInfo']['secondPageBodyCopy'] ?? '',
				'job_title'              => $old_version['secondPageInfo']['jobTitle'] ?? '',
				'address'                => $old_version['secondPageInfo']['address'] ?? '',
				'email'                  => $old_version['secondPageInfo']['email'] ?? '',
				'phone'                  => $old_version['secondPageInfo']['phone'] ?? '',
				'website'                => $old_version['secondPageInfo']['website'] ?? '',
			];

			$settings['whitelabel'] = $format;
		}

		$settings['share_link'] = $share_link;

		if ( empty( $share_link ) && ! empty( $old_version ) ) {
			$format = [
				'heading_text' => $old_version['shareLinkInfo']['headingText'] ?? '',
				'disclaimer'   => $old_version['shareLinkInfo']['disclaimer'] ?? '',
			];

			$settings['share_link'] = $format;
		}

		if ( rcpga_user_is_group_member( $user_id ) ) {
			$group = ( new Member( $user_id ) )->get_group();

			if ( $group ) {
				$group_id                               = $group->get_group_id();
				$settings['group_whitelabel_settings']  = get_metadata( 'rcp_group', $group_id, 'group_whitelabel_settings', true );
				$settings['group_link_share_settings']  = get_metadata( 'rcp_group', $group_id, 'group_link_share_settings', true );
				$settings['group_settings_permissions'] = get_metadata( 'rcp_group', $group_id, 'group_settings_permissions', true );
				$settings['group_settings']             = get_metadata( 'rcp_group', $group_id, 'enabled_permissions', true );
				$settings['other_settings']             = get_metadata( 'rcp_group', $group_id, 'group_settings_other', true );
			}
		}

		$settings['user_id'] = $user_id;

		return apply_filters( FP_ACCOUNT_SETTINGS_PREFIX . '_get_user_settings', $settings, $user_id );
	}
}
