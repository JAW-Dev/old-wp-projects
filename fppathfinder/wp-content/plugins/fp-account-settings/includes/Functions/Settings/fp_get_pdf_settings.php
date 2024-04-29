<?php
/**
 * Get PDF Settings.
 *
 * @package    Fp_Account_Settings
 * @subpackage Fp_Account_Settings/Inlcudes/Functions
 * @author     Objectiv
 * @copyright  Copyright (c) 2021, Objectiv
 * @license    GNU General Public License v2 or later
 * @since      1.0.0
 */

use FpAccountSettings\Includes\Utilites\Settings\PDFSettings;

if ( ! function_exists( 'fp_get_pdf_settings' ) ) {
	/**
	 * Get PDF Settings.
	 *
	 * @param array $settings The whotelabel or post PDF settings.
	 *
	 * @return array
	 */
	function fp_get_pdf_settings( array $settings = [] ): array {
		return ( new PDFSettings() )->get( $settings );
	}
}
