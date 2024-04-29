<?php
/**
 * Get Preview Settings.
 *
 * @package    Fp_Account_Settings
 * @subpackage Fp_Account_Settings/Inlcudes/Functions
 * @author     Objectiv
 * @copyright  Copyright (c) 2021, Objectiv
 * @license    GNU General Public License v2 or later
 * @since      1.0.0
 */

use FpAccountSettings\Includes\Utilites\Settings\PreviewSettings;

if ( ! function_exists( 'fp_get_preview_settings' ) ) {
	/**
	 * Get Preview Settings.
	 *
	 * @return array
	 */
	function fp_get_preview_settings() {
		return ( new PreviewSettings() )->get();
	}
}
