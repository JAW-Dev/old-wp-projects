<?php
/**
 * SVG.
 *
 * @package    Fp_Account_Settings
 * @subpackage Fp_Account_Settings/Inlcudes/Utilites/Media
 * @author     Objectiv
 * @copyright  Copyright (c) 2021, Objectiv
 * @license    GNU General Public License v2 or later
 * @since      1.0.0
 */

namespace FpAccountSettings\Includes\Utilites\Media;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

/**
 * SVG.
 *
 * @author Objectiv
 * @since  1.0.0
 */
class SVG {

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
	 * @param string $dir_path The directories to scan for SVGs.
	 * @param string $filename The svg filename to get.
	 *
	 * @return string
	 */
	public function get( string $dir_path = '', string $filename = '' ) {
		if ( empty( $dir_path ) || empty( $filename ) ) {
			return;
		}

		$files    = fp_list_svgs( $dir_path );
		$filepath = '';

		foreach ( $files as $file ) {
			$info = pathinfo( $file );

			if ( $info['basename'] === $filename . '.svg' ) {
				$filepath = $file;
			}
		}

		$file_contents = ! empty( $filepath ) ? file_get_contents( $filepath ) : ''; // phpcs:ignore

		return apply_filters( FP_ACCOUNT_SETTINGS_PREFIX . '_get_svg', $file_contents, $dir_path, $filename );
	}

	/**
	 * List
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param string $dir_path The directories to scan for SVGs.
	 *
	 * @return array
	 */
	public function list( string $dir_path = '' ) {
		if ( empty( $dir_path ) ) {
			return [];
		}

		$dir      = new \RecursiveDirectoryIterator( $dir_path );
		$iterator = new \RecursiveIteratorIterator( $dir );
		$files    = new \RegexIterator( $iterator, '/.*svg/', \RegexIterator::GET_MATCH );
		$icons    = [];

		foreach ( $files as $file ) {
			$icons = array_merge( $icons, $file );
		}

		return apply_filters( FP_ACCOUNT_SETTINGS_PREFIX . '_list_svgs', $icons, $dir_path );
	}

	/**
	 * SVG Kses
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return array
	 */
	public function kses() {
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

		return apply_filters( FP_ACCOUNT_SETTINGS_PREFIX . '_svg_kses', $allowed_tags );
	}
}
