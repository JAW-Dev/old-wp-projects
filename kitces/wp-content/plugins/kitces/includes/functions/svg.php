<?php
/**
 * SVG.
 *
 * @package    Fp_Account_Settings
 * @subpackage Fp_Account_Settings/Inlcudes/Functions/Members
 * @author     Objectiv
 * @copyright  Copyright (c) 2021, Objectiv
 * @license    GNU General Public License v2 or later
 * @since      1.0.0
 */

use Kitces\Includes\Classes\Svg\Init;

if ( ! function_exists( 'mk_get_svg' ) ) {
	/**
	 * SVG.
	 *
	 * @author Objectiv
	 * @since  1.0.0
	 *
	 * @param string $filename The filename.
	 *
	 * @return void
	 */
	function mk_get_svg( $filename ) {
		echo wp_kses( ( new Init() )->get( $filename ), ( new Init() )->svg_kses() );
	}
}
