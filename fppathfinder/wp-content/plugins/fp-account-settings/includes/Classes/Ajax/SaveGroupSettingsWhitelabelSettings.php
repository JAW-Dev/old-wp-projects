<?php
/**
 * Save Group Settings Whitelabel Permissions.
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
 * Save Group Settings Whitelabel Permissions.
 *
 * @author Objectiv
 * @since  1.0.0
 */
class SaveGroupSettingsWhitelabelSettings {

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
		add_action( 'wp_ajax_group_settings_whitelabel_settings', array( $this, 'save' ) );
		add_action( 'wp_ajax_nopriv_group_settings_whitelabel_settings', array( $this, 'save' ) );
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

		/*
		 * [whitelabel_settings] => ecf70ba346
		 * [_wp_http_referer] => /account-settings/
		 * [form-version] => group-settings-pdf
		 * [action] => group_settings_whitelabel_settings
		 * [security] => ef5235c740
		 * [group-settings-pdf-selected-colors] => {"color1":"#0f1e2c","color2":"#9e251f","color3":"#596924","color4":"#cea232"}
		 * [group-settings-pdf-logo-data] =>
		 * [group-settings-pdf-business-display-name] =>
		 * [group-settings-pdf-color-set-color-scheme] => 1
		 * [group-settings-pdf-color-set-is-using-custom-colors] => false
		 * [group-settings-pdf-color-set-custom-color-primary] => 000000
		 * [group-settings-pdf-color-set-custom-color-input-primary] => #000000
		 * [group-settings-pdf-color-set-custom-color-secondary] => 000000
		 * [group-settings-pdf-color-set-custom-color-input-secondary] => #000000
		 * [group-settings-pdf-color-set-custom-color-accent] => 000000
		 * [group-settings-pdf-color-set-custom-color-input-accent] => #000000
		 * [group-settings-pdf-color-set-custom-color-location] => 000000
		 * [group-settings-pdf-color-set-custom-color-input-location] => #000000
		 * [group-settings-pdf-second-page-title] =>
		 * [group-settings-pdf-second-page-title-permission] => on
		 * [group-settings-pdf-second-page-body-title] =>
		 * [group-settings-pdf-second-page-body-title-permission] => on
		 * [group-settings-pdf-second-page-body-copy] =>
		 * [group-settings-pdf-second-page-body-copy-permission] => on
		 * [group-settings-pdf-job-title] =>
		 * [group-settings-pdf-job-title-permission] => on
		 * [group-settings-pdf-address] =>
		 * [group-settings-pdf-address-permission] => on
		 * [group-settings-pdf-email] =>
		 * [group-settings-pdf-email-permission] => on
		 * [group-settings-pdf-phone] =>
		 * [group-settings-pdf-phone-permission] => on
		 * [group-settings-pdf-website] =>
		 * [group-settings-pdf-website-permission] => on
		 * [group-settings-pdf-use-advanced] => false
		 * [group-settings-pdf-second-page-advanced-body] =>
		 * [group-settings-pdf-second-page-advanced-body-permission] => on
		 * [group-settings-pdf-second-page-advanced-disclaimer] =>
		 * [group-settings-pdf-second-page-advanced-disclaimer-permission] => on
		*/

		$color_scheme  = isset( $data['group-settings-pdf-color-set-color-scheme'] ) && ! empty( $data['group-settings-pdf-color-set-color-scheme'] ) ? $data['group-settings-pdf-color-set-color-scheme'] : '';
		$custom_colors = ! empty( $data['group-settings-pdf-color-set-is-using-custom-colors'] ) && 'true' === $data['group-settings-pdf-color-set-is-using-custom-colors'] ? 'custom' : '';
		$color_choice  = ! empty( $color_scheme ) ? $color_scheme : $custom_colors;
		$logo_src      = '';
		$raw_logo      = '';

		if ( fp_is_feature_active( 'real_image_logos' ) ) {
			$is_base = isset( $data['group-settings-pdf-logo-data'] ) ? ( new Logo() )->is_base_image( $data['group-settings-pdf-logo-data'] ) : false;

			if ( $is_base ) {
				$raw_logo  = isset( $data['group-settings-pdf-logo-data'] ) ? ( new Image() )->convert( $data['group-settings-pdf-logo-data'] ) : '';
				$logo      = ! empty( $raw_logo ) ? ( new Logo() )->save_logo_image( $raw_logo, get_current_user_id(), 'group', true ) : '';
				$logo_data = wp_get_attachment_image_src( $logo, 'full' );
				$logo_src  = ! empty( $logo_data[0] ) ? $logo_data[0] : '';
			} else {
				$logo_src = isset( $data['group-settings-pdf-logo-data'] ) ? $data['group-settings-pdf-logo-data'] : '';
			}
		} else {
			$logo_src = isset( $data['group-settings-pdf-logo-data'] ) ? ( new Image() )->convert( $data['group-settings-pdf-logo-data'] ) : '';
		}

		$settings = array(
			'logo'                              => $logo_src,
			'logo_permission'                   => isset( $data['group-settings-pdf-logo-permission'] ) ? $data['group-settings-pdf-logo-permission'] : '',
			'color_set'                         => isset( $data['group-settings-pdf-selected-colors'] ) ? (array) json_decode( $data['group-settings-pdf-selected-colors'] ) : '',
			'color_set_choice'                  => $color_choice,
			'color_set_permission'              => isset( $data['group-settings-pdf-color-set-permission'] ) ? $data['group-settings-pdf-color-set-permission'] : '',
			'business_display_name'             => isset( $data['group-settings-pdf-business-display-name'] ) ? trim( $data['group-settings-pdf-business-display-name'] ) : '',
			'business_display_name_permission'  => isset( $data['group-settings-pdf-business-display-name-permission'] ) ? $data['group-settings-pdf-business-display-name-permission'] : '',
			'second_page_title'                 => isset( $data['group-settings-pdf-second-page-title'] ) ? trim( $data['group-settings-pdf-second-page-title'] ) : '',
			'second_page_title_permission'      => isset( $data['group-settings-pdf-second-page-title-permission'] ) ? $data['group-settings-pdf-second-page-title-permission'] : '',
			'second_page_body_title'            => isset( $data['group-settings-pdf-second-page-body-title'] ) ? trim( $data['group-settings-pdf-second-page-body-title'] ) : '',
			'second_page_body_title_permission' => isset( $data['group-settings-pdf-second-page-body-title-permission'] ) ? $data['group-settings-pdf-second-page-body-title-permission'] : '',
			'second_page_body_copy'             => isset( $data['group-settings-pdf-second-page-body-copy'] ) ? trim( $data['group-settings-pdf-second-page-body-copy'] ) : '',
			'second_page_body_copy_permission'  => isset( $data['group-settings-pdf-second-page-body-copy-permission'] ) ? $data['group-settings-pdf-second-page-body-copy-permission'] : '',
			'job_title'                         => isset( $data['group-settings-pdf-job-title'] ) ? trim( $data['group-settings-pdf-job-title'] ) : '',
			'job_title_permission'              => isset( $data['group-settings-pdf-job-title-permission'] ) ? $data['group-settings-pdf-job-title-permission'] : '',
			'address'                           => isset( $data['group-settings-pdf-address'] ) ? trim( $data['group-settings-pdf-address'] ) : '',
			'address_permission'                => isset( $data['group-settings-pdf-address-permission'] ) ? $data['group-settings-pdf-address-permission'] : '',
			'email'                             => isset( $data['group-settings-pdf-email'] ) ? trim( $data['group-settings-pdf-email'] ) : '',
			'email_permission'                  => isset( $data['group-settings-pdf-email-permission'] ) ? $data['group-settings-pdf-email-permission'] : '',
			'phone'                             => isset( $data['group-settings-pdf-phone'] ) ? trim( $data['group-settings-pdf-phone'] ) : '',
			'phone_permission'                  => isset( $data['group-settings-pdf-phone-permission'] ) ? $data['group-settings-pdf-phone-permission'] : '',
			'website'                           => isset( $data['group-settings-pdf-website'] ) ? trim( $data['group-settings-pdf-website'] ) : '',
			'website_permission'                => isset( $data['group-settings-pdf-website-permission'] ) ? $data['group-settings-pdf-website-permission'] : '',
			'use_advanced'                      => isset( $data['group-settings-pdf-use-advanced'] ) ? trim( $data['group-settings-pdf-use-advanced'] ) : '',
			'advanced_body'                     => isset( $data['group-settings-pdf-advanced-body'] ) ? trim( $data['group-settings-pdf-advanced-body'] ) : '',
			'advanced_body_permission'          => isset( $data['group-settings-pdf-advanced-body-permission'] ) ? trim( $data['group-settings-pdf-advanced-body-permission'] ) : '',
			'logo_base64'                       => $raw_logo,
		);

		if ( ! empty( $data ) ) {
			$group_id = fp_get_group_id();

			if ( ! $group_id ) {
				wp_die();
			}

			delete_metadata( 'rcp_group', $group_id, 'group_whitelabel_settings' );
			update_metadata( 'rcp_group', $group_id, 'group_whitelabel_settings', $settings );
			delete_transient( get_current_user_id() . '_group_whitelabel_resource_transient' );
			delete_transient( get_current_user_id() . '_group_whitelabel_back_page_transient' );
			delete_transient( get_current_user_id() . '_group_whitelabel_back_page_advanced_transient' );

			$group_members = rcpga_get_group_members( array( 'group_id' => $group_id ) );

			foreach ( $group_members as $group_member ) {
				$group_user_id = $group_member->get_user_id();
				delete_transient( $group_user_id . '_whitelabel_resource_transient' );
				delete_transient( $group_user_id . '_whitelabel_back_page_transient' );
				delete_transient( $group_user_id . '_whitelabel_back_page_advanced_transient' );
			}


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
