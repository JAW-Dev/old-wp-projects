<?php
/**
 * Get SVG.
 *
 * @package    Fp_Account_Settings
 * @subpackage Fp_Account_Settings/Inlcudes/Functions/Media
 * @author     Objectiv
 * @copyright  Copyright (c) 2021, Objectiv
 * @license    GNU General Public License v2 or later
 * @since      1.0.0
 */

use FpAccountSettings\Includes\Utilites\Media\SVG;

if ( ! function_exists( 'fp_get_svg' ) ) {
	/**
	 * Get SVG.
	 *
	 * @param string $dir_path The directories to scan for SVGs.
	 * @param string $filename The svg filename to get.
	 *
	 * @return string
	 */
	function fp_get_svg( string $dir_path = '', string $filename = '' ) {
		return ( new SVG() )->get( $dir_path, $filename );
	}
}
