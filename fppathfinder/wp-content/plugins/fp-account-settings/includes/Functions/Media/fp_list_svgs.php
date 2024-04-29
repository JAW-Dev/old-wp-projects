<?php
/**
 * List SVGs.
 *
 * @package    Fp_Account_Settings
 * @subpackage Fp_Account_Settings/Inlcudes/Functions/Media
 * @author     Objectiv
 * @copyright  Copyright (c) 2021, Objectiv
 * @license    GNU General Public License v2 or later
 * @since      1.0.0
 */

use FpAccountSettings\Includes\Utilites\Media\SVG;

if ( ! function_exists( 'fp_list_svgs' ) ) {
	/**
	 * List SVGs.
	 *
	 * @param string $dir_path The directories to scan for SVGs.
	 *
	 * @return array
	 */
	function fp_list_svgs( string $dir_path = '' ) {
		return ( new SVG() )->list( $dir_path );
	}
}
