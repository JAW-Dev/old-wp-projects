<?php
/**
 * Get Share Link Settings.
 *
 * @package    Fp_Account_Settings
 * @subpackage Fp_Account_Settings/Inlcudes/Functions
 * @author     Objectiv
 * @copyright  Copyright (c) 2021, Objectiv
 * @license    GNU General Public License v2 or later
 * @since      1.0.0
 */

use FpAccountSettings\Includes\Utilites\Settings\ShareLinkSettings;

if ( ! function_exists( 'fp_get_share_link_settings' ) ) {
	/**
	 * Get Share Link Settings.
	 *
	 * @param array $user_settings The user_aettings.
	 *
	 * @return array
	 */
	function fp_get_share_link_settings( $user_settings = [] ) {
		return ( new ShareLinkSettings() )->get( $user_settings );
	}
}
