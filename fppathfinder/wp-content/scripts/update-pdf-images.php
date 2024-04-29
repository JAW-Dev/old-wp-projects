<?php
/**
 * Custom Script
 *
 * @package fpPathfinder
 */

set_time_limit( 0 );
require '../../wp-load.php';

/**
 * Update users
 */
function update_users() {
	$args = array(
		'number'      => -1,
		'count_total' => false,
		'meta_key'    => 'pdf-generator-settings',
		'fields'      => 'ids',
	);

	$users = get_users( $args );

	foreach ( $users as $user_id ) {
		$data         = get_user_meta( $user_id, 'pdf-generator-settings', true );
		$data['logo'] = convert_image( $data['logo'] );

		update_user_meta( $user_id, 'pdf-generator-settings', $data );
	}

	echo 'Update Complete!';
}

/**
 * Convert Image
 *
 * @author Jason Witt
 * @since  1.0.0
 *
 * @param string $image The image's base64 encoded string.
 *
 * @return string
 */
function convert_image( $image ) {
	$pos       = strpos( $image, ';' );
	$type      = explode( ':', substr( $image, 0, $pos ) )[1];
	$new_image = '';

	if ( $type === 'image/png' ) {
		$new_image = convert_image_to_jpeg( $image );
	}

	if ( ! empty( $new_image ) ) {
		return $new_image;
	}

	return $image;
}

/**
 * Add Image Background Color
 *
 * @author Jason Witt
 * @since  1.0.0
 *
 * @param string $data The base64 encoded image.
 *
 * @return string
 */
function add_image_bg_color( $data ) {
	$rgb_color = array(
		'red'   => 255,
		'green' => 255,
		'blue'  => 255,
	);

	$clean  = str_replace( 'data:image/png;base64,', '', $data );
	$string = base64_decode( $clean ); // phpcs:ignore
	$image  = imagecreatefromstring( $string );
	$width  = imagesx( $image );
	$height = imagesy( $image );

	$background_image = imagecreatetruecolor( $width, $height );
	$color            = imagecolorallocate( $background_image, $rgb_color['red'], $rgb_color['green'], $rgb_color['blue'] );

	imagefill( $background_image, 0, 0, $color );
	imagecopy( $background_image, $image, 0, 0, 0, 0, $width, $height );

	ob_start();
	header( 'Content-Type: image/png' );
	imagepng( $background_image );
	imagedestroy( $image );
	$new = ob_get_clean();

	return 'data:image/png;base64,' . base64_encode( $new ); // phpcs:ignore
}

/**
 * Convet Image to jpeg
 *
 * @author Jason Witt
 * @since  1.0.0
 *
 * @param string $data The base64 encoded image.
 *
 * @return string
 */
function convert_image_to_jpeg( $data ) {
	$data   = add_image_bg_color( $data );
	$clean  = str_replace( 'data:image/png;base64,', '', $data );
	$string = base64_decode( $clean ); // phpcs:ignore
	$image  = imagecreatefromstring( $string );
	$base   = '';

	if ( $image !== false ) {
		ob_start();
		header( 'Content-Type: image/jpeg' );
		imagejpeg( $image );
		imagedestroy( $image );
		$new = ob_get_clean();
	}

	return 'data:image/jpeg;base64,' . base64_encode( $new ); // phpcs:ignore
}

update_users();
