<?php
/**
 * SVG Kses.
 *
 * @package    Fp_Account_Settings
 * @subpackage Fp_Account_Settings/Inlcudes/Functions/Media
 * @author     Objectiv
 * @copyright  Copyright (c) 2021, Objectiv
 * @license    GNU General Public License v2 or later
 * @since      1.0.0
 */

use FpAccountSettings\Includes\Utilites\Media\SVG;

if ( ! function_exists( 'fp_svg_kses' ) ) {
	/**
	 * SVG Kses.
	 *
	 * @return array
	 */
	function fp_svg_kses() {
		return ( new SVG() )->kses();
	}
}
