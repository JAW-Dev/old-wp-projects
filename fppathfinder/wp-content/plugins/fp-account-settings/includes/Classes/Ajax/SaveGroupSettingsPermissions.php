<?php
/**
 * Save Group Settings Permissions.
 *
 * @package    Fp_Account_Settings
 * @subpackage Fp_Account_Settings/Inlcudes/Classes/Ajax
 * @author     Objectiv
 * @copyright  Copyright (c) 2021, Objectiv
 * @license    GNU General Public License v2 or later
 * @since      1.0.0
 */

namespace FpAccountSettings\Includes\Classes\Ajax;

use FP_Core\Member;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

/**
 * Save Group Settings Permissions.
 *
 * @author Objectiv
 * @since  1.0.0
 */
class SaveGroupSettingsPermissions {

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
		add_action( 'wp_ajax_group_settings_whitelabel_permissions', array( $this, 'save' ) );
		add_action( 'wp_ajax_nopriv_group_settings_whitelabel_permissions', array( $this, 'save' ) );
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

		if ( ! wp_verify_nonce( $data['group_settings_permissions'], 'save_group_settings_permissions' ) ) {
			echo 'Nonce Failed';
			wp_die();
		}

		$settings = array(
			'white_label_all'                    => isset( $data['white-label-all'] ) && $data['white-label-all'] === 'on' ? 1 : 0,
			'white_label_logo'                   => isset( $data['white-label-logo'] ) && $data['white-label-logo'] === 'on' ? 1 : 0,
			'white_label_business_display_name'  => isset( $data['white-label-business-display-name'] ) && $data['white-label-business-display-name'] === 'on' ? 1 : 0,
			'white_label_advisor_name'           => isset( $data['white-label-advisor-name'] ) && $data['white-label-advisor-name'] === 'on' ? 1 : 0,
			'white_label_color_set'              => isset( $data['white-label-color-set'] ) && $data['white-label-color-set'] === 'on' ? 1 : 0,
			'white_label_second_page_title'      => isset( $data['white-label-second-page-title'] ) && $data['white-label-second-page-title'] === 'on' ? 1 : 0,
			'white_label_second_page_body_title' => isset( $data['white-label-second-page-body-title'] ) && $data['white-label-second-page-body-title'] === 'on' ? 1 : 0,
			'white_label_second_page_body_copy'  => isset( $data['white-label-second-page-body-copy'] ) && $data['white-label-second-page-body-copy'] === 'on' ? 1 : 0,
			'white_label_job_title'              => isset( $data['white-label-job-title'] ) && $data['white-label-job-title'] === 'on' ? 1 : 0,
			'white_label_address'                => isset( $data['white-label-address'] ) && $data['white-label-address'] === 'on' ? 1 : 0,
			'white_label_email'                  => isset( $data['white-label-email'] ) && $data['white-label-email'] === 'on' ? 1 : 0,
			'white_label_phone'                  => isset( $data['white-label-phone'] ) && $data['white-label-phone'] === 'on' ? 1 : 0,
			'white_label_website'                => isset( $data['white-label-website'] ) && $data['white-label-website'] === 'on' ? 1 : 0,
			'profile_all'                        => isset( $data['profile-all'] ) && $data['profile-all'] === 'on' ? 1 : 0,
			'profile_first_name'                 => isset( $data['profile-first-name'] ) && $data['profile-first-name'] === 'on' ? 1 : 0,
			'profile_last_name'                  => isset( $data['profile-last-name'] ) && $data['profile-last-name'] === 'on' ? 1 : 0,
			'profile_display_name'               => isset( $data['profile-display-name'] ) && $data['profile-display-name'] === 'on' ? 1 : 0,
			'profile_email_address'              => isset( $data['profile-email-address'] ) && $data['profile-email-address'] === 'on' ? 1 : 0,
			'profile_job_title'                  => isset( $data['profile-job-title'] ) && $data['profile-job-title'] === 'on' ? 1 : 0,
			'profile_phone'                      => isset( $data['profile-phone'] ) && $data['profile-phone'] === 'on' ? 1 : 0,
		);

		if ( ! empty( $data ) ) {
			$group_id = ( new Member( get_current_user_id() ) )->get_group()->get_group_id();

			update_metadata( 'rcp_group', $group_id, 'group_settings_permissions', $settings );

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
