<?php
/**
 * Save Whitelabel Settings.
 *
 * @package    Fp_Account_Settings
 * @subpackage Fp_Account_Settings/Inlcudes/Classes/Ajax
 * @author     Objectiv
 * @copyright  Copyright (c) 2021, Objectiv
 * @license    GNU General Public License v2 or later
 * @since      1.0.0
 */

namespace FpAccountSettings\Includes\Classes\Ajax;

use FpAccountSettings\Includes\Utilites\Media\Image;
use FpAccountSettings\Includes\Classes\Logo;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

/**
 * Save Whitelabel Settings.
 *
 * @author Objectiv
 * @since  1.0.0
 */
class SaveWhitelabelSettings {

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
		add_action( 'wp_ajax_whitelabel_settings', array( $this, 'save' ) );
		// add_action( 'wp_ajax_nopriv_whitelabel_settings', array( $this, 'save' ) );
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

		if ( ! wp_verify_nonce( $data['whitelabel_settings'], 'save_whitelabel_settings' ) ) {
			echo 'Nonce Failed';
			wp_die();
		}

		$color_scheme  = isset( $data['pdf-generator-color-set-color-scheme'] ) && ! empty( $data['pdf-generator-color-set-color-scheme'] ) ? $data['pdf-generator-color-set-color-scheme'] : '';
		$custom_colors = ! empty( $data['pdf-generator-color-set-is-using-custom-colors'] ) && 'true' === $data['pdf-generator-color-set-is-using-custom-colors'] ? 'custom' : '';
		$color_choice  = ! empty( $color_scheme ) ? $color_scheme : $custom_colors;

		$current_settings = get_user_meta( get_current_user_id(), 'whitelabel-settings', true );

		/*
		 * [whitelabel_settings] => ecf70ba346
		 * [_wp_http_referer] => /account-settings/
		 * [form-version] => pdf-generator
		 * [action] => whitelabel_settings
		 * [security] => bde3b1fcf1
		 * [pdf-generator-selected-colors] => {"color1":"#0f1e2c","color2":"#9e251f","color3":"#596924","color4":"#cea232"}
		 * [pdf-generator-logo-data] =>
		 * [pdf-generator-business-display-name] => Business Display Name
		 * [pdf-generator-advisor-name] => Jason Witt
		 * [pdf-generator-color-set-color-scheme] => 1
		 * [pdf-generator-color-set-is-using-custom-colors] => false
		 * [pdf-generator-color-set-custom-color-primary] => 000000
		 * [pdf-generator-color-set-custom-color-input-primary] => #000000
		 * [pdf-generator-color-set-custom-color-secondary] => 000000
		 * [pdf-generator-color-set-custom-color-input-secondary] => #000000
		 * [pdf-generator-color-set-custom-color-accent] => 000000
		 * [pdf-generator-color-set-custom-color-input-accent] => #000000
		 * [pdf-generator-color-set-custom-color-location] => 000000
		 * [pdf-generator-color-set-custom-color-input-location] => #000000
		 * [pdf-generator-second-page-title] => Back Page Title
		 * [pdf-generator-second-page-body-title] => Back Page Title
		 * [pdf-generator-second-page-body-copy] =>
		 * [pdf-generator-job-title] => Job Title
		 * [pdf-generator-address] => DO NOT SHIP
		 * [pdf-generator-email] => test@mg.test
		 * [pdf-generator-phone] => 5555555555
		 * [pdf-generator-website] =>
		 * [pdf-generator-use-advanced] => false
		 * [pdf-generator-second-page-advanced-body] => dfsdfsdf
		 * [pdf-generator-second-page-advanced-disclaimer] =>
		*/

		$logo_src = '';
		$raw_logo = '';

		if ( fp_is_feature_active( 'real_image_logos' ) ) {
			$is_base = isset( $data['pdf-generator-logo-data'] ) ? ( new Logo() )->is_base_image( $data['pdf-generator-logo-data'] ) : false;

			if ( $is_base ) {
				$raw_logo  = isset( $data['pdf-generator-logo-data'] ) ? ( new Image() )->convert( $data['pdf-generator-logo-data'] ) : '';
				$logo      = ! empty( $raw_logo ) ? ( new Logo() )->save_logo_image( $raw_logo, get_current_user_id(), 'individual', true ) : '';
				$logo_data = wp_get_attachment_image_src( $logo, 'full' );
				$logo_src  = ! empty( $logo_data[0] ) ? $logo_data[0] : '';
			} else {
				$logo_src = isset( $data['pdf-generator-logo-data'] ) ? $data['pdf-generator-logo-data'] : '';
			}
		} else {
			$logo_src = isset( $data['pdf-generator-logo-data'] ) ? ( new Image() )->convert( $data['pdf-generator-logo-data'] ) : '';
		}

		$new_settings = array(
			'logo'                   => $logo_src,
			'color_set'              => isset( $data['pdf-generator-selected-colors'] ) ? (array) json_decode( $data['pdf-generator-selected-colors'] ) : '',
			'color_set_choice'       => $color_choice,
			'business_display_name'  => isset( $data['pdf-generator-business-display-name'] ) ? trim( $data['pdf-generator-business-display-name'] ) : '',
			'advisor_name'           => isset( $data['pdf-generator-advisor-name'] ) ? trim( $data['pdf-generator-advisor-name'] ) : '',
			'second_page_title'      => isset( $data['pdf-generator-second-page-title'] ) ? trim( $data['pdf-generator-second-page-title'] ) : '',
			'second_page_body_title' => isset( $data['pdf-generator-second-page-body-title'] ) ? trim( $data['pdf-generator-second-page-body-title'] ) : '',
			'second_page_body_copy'  => isset( $data['pdf-generator-second-page-body-copy'] ) ? trim( $data['pdf-generator-second-page-body-copy'] ) : '',
			'job_title'              => isset( $data['pdf-generator-job-title'] ) ? trim( $data['pdf-generator-job-title'] ) : '',
			'address'                => isset( $data['pdf-generator-address'] ) ? trim( $data['pdf-generator-address'] ) : '',
			'email'                  => isset( $data['pdf-generator-email'] ) ? trim( $data['pdf-generator-email'] ) : '',
			'phone'                  => isset( $data['pdf-generator-phone'] ) ? trim( $data['pdf-generator-phone'] ) : '',
			'website'                => isset( $data['pdf-generator-website'] ) ? trim( $data['pdf-generator-website'] ) : '',
			'use_advanced'           => isset( $data['pdf-generator-use-advanced'] ) ? trim( $data['pdf-generator-use-advanced'] ) : '',
			'advanced_body'          => isset( $data['pdf-generator-second-page-advanced-body'] ) ? trim( $data['pdf-generator-second-page-advanced-body'] ) : '',
			'logo_base64'            => $raw_logo,
		);

		$user       = get_user_by( 'id', get_current_user_id() );
		$first_name = get_user_meta( get_current_user_id(), 'first_name', true );
		$last_name  = get_user_meta( get_current_user_id(), 'last_name', true );

		if ( ! empty( $first_name ) || ! empty( $last_name ) ) {
			$new_settings['advisor_name'] = $first_name . ' ' . $last_name;
		}

		$display_name = $user->display_name;

		if ( ! empty( $display_name ) ) {
			$new_settings['advisor_name'] = $display_name;
		}

		$return = wp_parse_args( $new_settings, $current_settings );

		if ( ! empty( $data ) ) {
			update_user_meta( get_current_user_id(), 'whitelabel-settings', $return );
			delete_transient( get_current_user_id() . '_whitelabel_resource_transient' );
			delete_transient( get_current_user_id() . '_whitelabel_back_page_transient' );
			delete_transient( get_current_user_id() . '_whitelabel_back_page_advanced_transient' );

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
