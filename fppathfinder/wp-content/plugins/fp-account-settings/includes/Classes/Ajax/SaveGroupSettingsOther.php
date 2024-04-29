<?php
/**
 * Save Group Settings Other.
 *
 * @package    Fp_Account_Settings
 * @subpackage Fp_Account_Settings/Inlcudes/Classes/Ajax
 * @author     Objectiv
 * @copyright  Copyright (c) 2021, Objectiv
 * @license    GNU General Public License v2 or later
 * @since      1.0.0
 */

namespace FpAccountSettings\Includes\Classes\Ajax;

use FP_Core\Group_Settings\Database;
use FP_Core\Member;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

/**
 * Save Group Settings Other.
 *
 * @author Objectiv
 * @since  1.0.0
 */
class SaveGroupSettingsOther {

	/**
	 * Initialize the class
	 *
	 * @author Objectiv
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function __construct() {
		$this->hooks();
	}

	/**
	 * Hooks
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function hooks() {
		add_action( 'wp_ajax_group_settings_other', array( $this, 'save' ) );
		add_action( 'wp_ajax_nopriv_group_settings_other', array( $this, 'save' ) );
	}

	/**
	 * Save
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function save() {
		$post = sanitize_post( wp_unslash( $_POST ) ) ?? '';

		if ( empty( $post ) ) {
			echo 'Empty POST';
			wp_die();
		}

		parse_str( $post['data'], $data );

		if ( empty( $data ) ) {
			echo 'No Data';
			wp_die();
		}

		if ( ! wp_verify_nonce( $data['group_settings_other'], 'save_group_settings_other' ) ) {
			echo 'Nonce Failed';
			wp_die();
		}

		if ( ! empty( $data ) ) {
			$member   = new Member( get_current_user_id() );
			$group    = method_exists( $member, 'get_group' ) ? $member->get_group() : '';
			$group_id = ! empty( $group ) && method_exists( $group, 'get_group_id' ) ? $group->get_group_id() : '';

			if ( empty( $group_id ) ) {
				wp_die();
			}

			$settings = array(
				'no_advisor_name' => isset( $data['other-settings-advisor-name-permission'] ) ? $data['other-settings-advisor-name-permission'] : '',
			);

			update_metadata( 'rcp_group', $group_id, 'group_settings_other', $settings );

			echo 'save success';
			wp_die();
		}

		wp_die();
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
	public function convert_image( $image ) {
		$pos       = strpos( $image, ';' );
		$type      = explode( ':', substr( $image, 0, $pos ) )[1];
		$new_image = '';

		if ( 'image/png' === $type ) {
			$new_image = $this->convert_image_to_jpeg( $image );
		}

		if ( ! empty( $new_image ) ) {
			return $new_image;
		}

		return $image;
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
	public function convert_image_to_jpeg( $data ) {
		$data   = $this->add_image_bg_color( $data );
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
	public function add_image_bg_color( $data ) {
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
}
