<?php
/**
 * SVG.
 *
 * @package    Kitces_Members
 * @subpackage Kitces_Members/Inlcudes/Classes/Utilities
 * @author     Objectiv
 * @copyright  Copyright (c) 2021, Objectiv
 * @license    GNU General Public License v2 or later
 * @since      1.0.0
 */

namespace Kitces_Members\Includes\Classes\Utilities;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}


/**
 * SVG.
 *
 * @author Objectiv
 * @since  1.0.0
 */
class Svg {

	/**
	 * Initialize the class
	 *
	 * @author Jason Witt
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
	 * @return string
	 */
	public static function get( $filename ) {
		$filepath = KICTES_MEMBERS_DIR_PATH . 'assets/images/' . $filename . '.svg';

		$file_contents = ! empty( $filepath ) ? file_get_contents( $filepath ) : ''; // phpcs:ignore

		return apply_filters( KICTES_MEMBERS_PRFIX . '_get_svg', $file_contents );
	}

	/**
	 * SVG Kses
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return array
	 */
	public static function svg_kses() {
		$defaults = wp_kses_allowed_html( 'post' );

		$svg = array(
			'svg'   => array(
				'class'           => true,
				'aria-hidden'     => true,
				'aria-labelledby' => true,
				'role'            => true,
				'xmlns'           => true,
				'width'           => true,
				'height'          => true,
				'viewbox'         => true,
				'fill'            => true,
				'stroke'          => true,
			),
			'g'     => array( 'fill' => true ),
			'title' => array( 'title' => true ),
			'path'  => array(
				'd'               => true,
				'fill'            => true,
				'stroke-linecap'  => true,
				'stroke-linejoin' => true,
				'stroke-width'    => true,
			),
		);

		$allowed_tags = array_merge( $defaults, $svg );

		return apply_filters( KICTES_MEMBERS_PRFIX . '_svg_kses', $allowed_tags );
	}
}
