<?php
/**
 * Image.
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
 * Image.
 *
 * @author Objectiv
 * @since  1.0.0
 */
class Image {

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
	 * Convert Image
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param string $source The base64 encoded image.
	 *
	 * @return string
	 */
	public function convert( string $source = '' ) {
		if ( empty( $source ) ) {
			return $source;
		}

		$type = $this->get_image_type( $source );

		if ( empty( $type ) ) {
			return '';
		}

		switch ( $type ) {
			case 'png':
				$source = $this->background_color( $source );
				$source = $this->resize( $source, $this->get_image_type( $source ) );
				break;
			case 'jpg':
			case 'jpeg':
				$source = $this->resize( $source, $type );
				break;
			default:
				$source = '';
		}

		return $source;
	}

	/**
	 * Convet Image to jpeg
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param string $source The base64 encoded image.
	 *
	 * @return string
	 */
	public function image_to_jpeg( $source = '' ) {
		if ( empty( $source ) ) {
			return $source;
		}

		$exploded = explode( ',', $source );
		$encoded  = $exploded[1];
		$decoded  = base64_decode( $encoded ); // phpcs:ignore
		$image    = imagecreatefromstring( $decoded );

		if ( $image !== false ) {
			ob_start();
			header( 'Content-Type: image/jpeg' );
			imagejpeg( $image, null );
			imagedestroy( $image );
			$new = ob_get_clean();
		}

		return 'data:image/jpeg;base64,' . base64_encode( $new ); // phpcs:ignore
	}

	/**
	 * Get Image Type
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param string $source The base64 encoded image.
	 *
	 * @return string
	 */
	public function get_image_type( string $source = '' ): string {
		if ( empty( $source ) ) {
			return '';
		}

		$pos  = strpos( $source, ';' );

		if ( ! isset( explode( ':', substr( $source, 0, $pos ) )[1] ) ) {
			return '';
		}

		$mime = explode( ':', substr( $source, 0, $pos ) )[1];

		if ( strpos( $mime, 'jpg' ) !== false ) {
			$type = 'jpg';
		} elseif ( strpos( $mime, 'jpeg' ) !== false ) {
			$type = 'jpeg';
		} elseif ( strpos( $mime, 'png' ) !== false ) {
			$type = 'png';
		} elseif ( strpos( $mime, 'gif' ) !== false ) {
			$type = 'gif';
		} else {
			$type = '';
		}

		return $type;
	}

	/**
	 * Add Image Background Color
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param string $source The base64 encoded image.
	 *
	 * @return string
	 */
	public function background_color( string $source = '' ): string {
		if ( empty( $source ) ) {
			return $source;
		}

		$image   = $this->image_from_string( $source );
		$image_x = imagesx( $image );
		$image_y = imagesy( $image );
		$bg      = imagecreatetruecolor( $image_x, $image_y );

		imagefill( $bg, 0, 0, imagecolorallocate( $bg, 255, 255, 255 ) );
		imagealphablending( $bg, true );
		imagecopy( $bg, $image, 0, 0, 0, 0, $image_x, $image_y );
		imagedestroy( $image );

		ob_start();
		header( 'Content-Type: image/jpeg' );
		imagejpeg( $bg, null, 100 );
		imagedestroy( $bg );
		$new = ob_get_clean();

		return 'data:image/jpeg;base64,' . base64_encode( $new ); // phpcs:ignore
	}

	/**
	 * Resize Image
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param string $source The base64 encoded image.
	 * @param string $type   The type of image (extension).
	 *
	 * @return string
	 */
	public function resize( string $source = '', string $type = '' ): string {
		if ( empty( $source ) ) {
			return $source;
		}

		$size = (int) strlen( $this->str_after( $source, 'base64,' ) );

		if ( $size >= 245760 ) {
			return $source;
		}

		if ( empty( $type ) ) {
			$type = $this->get_image_type( $source );
		}

		if ( $type === 'jpg' || $type === 'jpeg' ) {
			return $source;
		}

		$path = wp_get_upload_dir()['basedir'] . '/pdf-image-temporary/' . random_int( 1, 999999999 ) . '/';
		wp_mkdir_p( $path );

		$orig_image = $this->image_from_string( $source );

		if ( empty( $orig_image ) ) {
			return $source;
		}

		$source_width  = imagesx( $orig_image );
		$source_height = imagesy( $orig_image );
		$ratio         = $source_height / $source_width;
		$new_width     = $source_width > 460 ? 460 : $source_width;
		$new_height    = $ratio * 460;
		$image         = imagecreatetruecolor( $new_width, $new_height );

		imagecopyresampled( $image, $orig_image, 0, 0, 0, 0, $new_width, $new_height, $source_width, $source_height );
		imagedestroy( $orig_image );

		ob_start();
		header( 'Content-Type: image/jpeg' );
		imagejpeg( $image, null, 100 );
		imagedestroy( $image );
		$new = ob_get_clean();

		return 'data:image/jpeg;base64,' . base64_encode( $new ); // phpcs:ignore
	}

	/**
	 * Image From String
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param string $source The base64 encoded image.
	 *
	 * @return mixed
	 */
	public function image_from_string( string $source = '' ) {
		if ( empty( $source ) ) {
			return $source;
		}

		$exploded = explode( ',', $source );
		$encoded  = $exploded[1];
		$decoded  = base64_decode( $encoded ); // phpcs:ignore
		$image    = imagecreatefromstring( $decoded );

		return $image;
	}

	/**
	 * String After
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return string
	 */
	public function str_after( $subject, $search ) {
		if ( $search === '' ) {
			return $subject;
		}
		$pos = strpos( $subject, $search );
		if ( $pos === false ) {
			return $subject;
		}
		return substr( $subject, $pos + strlen( $search ) );
	}
}
