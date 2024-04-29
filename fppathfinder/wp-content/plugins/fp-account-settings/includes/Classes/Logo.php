<?php
/**
 * Logo.
 *
 * @package    Fp_Account_Settings
 * @subpackage Fp_Account_Settings/Inlcudes/Classes
 * @author     Objectiv
 * @copyright  Copyright (c) 2021, Objectiv
 * @license    GNU General Public License v2 or later
 * @since      1.0.0
 */

namespace FpAccountSettings\Includes\Classes;

use FpAccountSettings\Includes\Utilites\Media\Image;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

/**
 * Logo.
 *
 * @author Objectiv
 * @since  1.0.0
 */
class Logo {

	/**
	 * Has Logo Image Meta
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @var string
	 */
	protected $has_logo_image_set = 'has_logo_image_set';

	/**
	 * Has Group Logo Image Meta
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @var string
	 */
	protected $has_group_logo_image_set = 'has_group_logo_image_set';

	/**
	 * Debug
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @var bool
	 */
	protected $debug = false;

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
		if ( is_admin() ) {
			return;
		}

		if ( fp_is_feature_active( 'real_image_logos' ) ) {
			add_action( 'init', [ $this, 'maybe_save_logo_image' ] );
		}
	}

	/**
	 * Maybe save logo image
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function maybe_save_logo_image() {
		$user_id = get_current_user_id();

		if ( ! $user_id ) {
			return;
		}

		$whitelabel_settings = get_user_meta( $user_id, 'whitelabel-settings', true );
		$whitelabel_logo     = isset( $whitelabel_settings['logo'] ) ? $whitelabel_settings['logo'] : '';

		// $this->debug = true;
		$has_saved_logo = $this->debug ? false : $this->has_logo_image_saved( $user_id );

		if ( ! empty( $whitelabel_logo ) && $this->is_base_image( $whitelabel_logo ) && ! $has_saved_logo ) {
			$whitelabel_attatchment_id = $this->save_logo_image( $whitelabel_logo, $user_id );

			if ( ! $whitelabel_attatchment_id ) {
				return;
			}

			$whitelabel_image_scr = wp_get_attachment_image_src( $whitelabel_attatchment_id, 'full' );
			$whitelabel_logo_src  = ! empty( $whitelabel_image_scr[0] ) ? $whitelabel_image_scr[0] : '';

			if ( empty( $whitelabel_logo_src ) ) {
				return;
			}

			$whitelabel_settings['logo']        = $whitelabel_logo_src;
			$whitelabel_settings['logo_base64'] = $whitelabel_logo;

			update_user_meta( $user_id, 'whitelabel-settings', $whitelabel_settings );
			delete_transient( $user_id . '_whitelabel_resource_transient' );
			delete_transient( $user_id . '_whitelabel_back_page_transient' );
			delete_transient( $user_id . '_whitelabel_back_page_advanced_transient' );
			update_user_meta( $user_id, $this->has_logo_image_set, true );
		}

		$group_id = fp_get_group_id( $user_id );

		if ( ! empty( $group_id ) ) {

			$group_settings        = get_metadata( 'rcp_group', $group_id, 'group_whitelabel_settings', true );
			$group_whitelabel_logo = isset( $group_settings['logo'] ) ? $group_settings['logo'] : '';

			if ( ! empty( $group_whitelabel_logo ) && $this->is_base_image( $group_whitelabel_logo ) && ! $this->has_group_logo_image_saved( $user_id ) ) {
				$group_attatchment_id = $this->save_logo_image( $group_whitelabel_logo, $user_id, 'group' );

				if ( ! $group_attatchment_id ) {
					return;
				}

				$group_image_scr = wp_get_attachment_image_src( $group_attatchment_id, 'full' );
				$group_logo_src  = ! empty( $group_image_scr[0] ) ? $group_image_scr[0] : '';

				if ( empty( $group_logo_src ) ) {
					return;
				}

				$group_settings['logo'] = $group_logo_src;

				delete_metadata( 'rcp_group', $group_id, 'group_whitelabel_settings' );
				update_metadata( 'rcp_group', $group_id, 'group_whitelabel_settings', $group_settings );
				delete_transient( $user_id . '_group_whitelabel_resource_transient' );
				delete_transient( $user_id . '_group_whitelabel_back_page_transient' );
				delete_transient( $user_id . '_group_whitelabel_back_page_advanced_transient' );

				$group_members = rcpga_get_group_members( array( 'group_id' => $group_id ) );

				foreach ( $group_members as $group_member ) {
					$group_user_id = $group_member->get_user_id();
					delete_transient( $group_user_id . '_whitelabel_resource_transient' );
					delete_transient( $group_user_id . '_whitelabel_back_page_transient' );
					delete_transient( $group_user_id . '_whitelabel_back_page_advanced_transient' );
				}

				update_user_meta( $user_id, $this->has_group_logo_image_set, true );
			}
		}
	}

	/**
	 * Get Base Image
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return string
	 */
	public function get_base_image( $source = '' ) {
		if ( empty( $source ) ) {
			return '';
		}

		if ( $this->is_base_image( $source ) ) {
			return $source;
		}

		$dots    = explode( '.', $source );
		$type    = $dots[ ( count( $dots ) - 1 ) ];
		$encoded = base64_encode( $source );  // phpcs:ignore

		return "data:image/$type;base64," . $encoded;
	}

	/**
	 * Has logo image saved
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param int $user_id The user ID.
	 *
	 * @return bool
	 */
	public function has_logo_image_saved( $user_id = 0 ) {
		$user_id = ! $user_id ? get_current_user_id() : $user_id;

		if ( ! $user_id ) {
			return;
		}

		$has_logo_image = get_user_meta( $user_id, $this->has_logo_image_set, true );

		return $has_logo_image;
	}

	/**
	 * Has Group logo image saved
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param int $user_id The user ID.
	 *
	 * @return bool
	 */
	public function has_group_logo_image_saved( $user_id = 0 ) {
		$user_id = ! $user_id ? get_current_user_id() : $user_id;

		if ( ! $user_id ) {
			return;
		}

		$has_logo_image = get_user_meta( $user_id, $this->has_group_logo_image_set, true );

		return $has_logo_image;
	}

	/**
	 * Is base image
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param string $image The image string.
	 *
	 * @return bool
	 */
	public function is_base_image( $image = '' ) {
		if ( empty( $image ) ) {
			return;
		}

		$has_data_image = strpos( $image, 'data:image' ) !== false ? true : false;

		return $has_data_image;
	}

	/**
	 * Save Logo Image
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param string $image   The image string.
	 * @param int    $user_id The user ID.
	 * @param string $type    The type of logo.
	 *
	 * @return string
	 */
	public function save_logo_image( $image = '', $user_id = 0, $type = 'individual', $is_ajax = false ) {
		if ( empty( $image ) ) {
			return 0;
		}

		if ( $is_ajax ) {
			return 0;
		}

		$user_id = $user_id ? $user_id : get_current_user_id();

		require_once ABSPATH . 'wp-admin/includes/image.php';

		$upload_dir = wp_upload_dir();

		if ( empty( $upload_dir ) ) {
			return 0;
		}

		$upload_dir_path = ! empty( $upload_dir['path'] ) ? $upload_dir['path'] : '';
		$upload_dir_url  = ! empty( $upload_dir['url'] ) ? $upload_dir['url'] : '';

		if ( empty( $upload_dir_path ) ) {
			return 0;
		}

		$upload_path = str_replace( '/', DIRECTORY_SEPARATOR, $upload_dir_path ) . DIRECTORY_SEPARATOR;
		$image_type  = ( new Image() )->get_image_type( $image );
		$image_ext   = 'jpeg';

		if ( $image_type === 'png' ) {
			$image_ext = 'png';
		}

		$img       = str_replace( "data:image/$image_ext;base64,", '', $image );
		$img       = str_replace( ' ', '+', $img );
		$decoded   = base64_decode( $img ); // phpcs:ignore
		$date      = new \Datetime( 'now' );
		$filename  = $user_id . '-' . $date->format( 'U' ) . '-' . $type . "-logo.$image_ext";
		$file_type = "image/$image_ext";

		file_put_contents( $upload_path . $filename, $decoded ); // phpcs:ignore

		$attachment = array(
			'post_mime_type' => $file_type,
			'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $filename ) ),
			'post_content'   => '',
			'post_status'    => 'inherit',
			'guid'           => $upload_dir_url . '/' . basename( $filename ),
		);

		$attach_id = wp_insert_attachment( $attachment, $upload_dir_path . '/' . $filename );

		$attach_data = wp_generate_attachment_metadata( $attach_id, $upload_dir_path . '/' . $filename );
		wp_update_attachment_metadata( $attach_id, $attach_data );

		return $attach_id;
	}
}
