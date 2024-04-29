<?php

namespace FP_PDF_Generator;

use FP_Core\Group_Settings\Database;
use FpAccountSettings\Includes\Utilites\Media\Image;
use FpAccountSettings\Includes\Classes\Conditionals;

class Download extends PDF {

	public $post_id = null;

	/**
	 * Construct
	 *
	 * @param int $post_id The post id for the Download.
	 * @param int $user_id The user id.
	 *
	 * @see /wp-content/themes/fppathfinder/inc/_post-types/download.php
	 *
	 * @return \FP_PDF_Generator\Download
	 */
	public function __construct( int $post_id, int $user_id = 0, bool $is_bundle = false, array $user_settings = [] ) {
		$this->post_id   = $post_id;
		$user_id         = $user_id ? $user_id : get_current_user_id();
		$svg_paths       = $this->get_svg_paths_by_post_id( $post_id );
		$licensee        = $this->get_licensee( $user_id, $user_settings );
		$output_filename = sanitize_file_name( get_the_title( $post_id ) ) . '.pdf';

		parent::__construct( $svg_paths, $licensee, $output_filename );

		if ( $this->should_customize( $user_id, $user_settings ) ) {
			if ( $is_bundle ) {
				$this->customize( $user_id, $user_settings );
			} else {
				$this->customize( $user_id );
			}
		}
	}

	/**
	 * Should Customize
	 *
	 * Determine if the user should get a customized Download.
	 *
	 * @param int $user_id
	 *
	 * @return bool
	 */
	private function should_customize( int $user_id = 0, array $user_settings = [] ) {
		$user_id  = $user_id ? $user_id : get_current_user_id();
		$settings = ! empty( $user_settings ) ? $user_settings : fp_get_pdf_settings( fp_get_user_settings( $user_id ) );

		return $user_id && \FP_PDF_Generator\Customization_Controller::user_can_customize_pdf( $user_id ) && ! empty( $settings );
	}

	/**
	 * Customize
	 *
	 * Pull saved settings and customize the Download.
	 *
	 * @param int $user_id
	 *
	 * @return void
	 */
	private function customize( int $user_id = 0, array $user_settings = [] ) {
		$user_id = $user_id ? $user_id : get_current_user_id();
		$logo    = $this->get_saved_logo( $user_id, $user_settings );
		$colors  = $this->get_saved_colors( $user_id, $user_settings );

		$this->set_logo( $logo );
		$this->set_colors( $colors );

		$back_page_settings = ! empty( $user_setting ) ? $user_setting : $this->get_saved_back_page_settings();

		if ( fp_do_generate_back_page( $back_page_settings ) ) {
			$this->set_back_page_settings( $back_page_settings );
		}

		$this->set_post_id( $this->post_id );
		$this->set_user_id( $user_id );
	}

	/**
	 * Get Saved Back Page Settings
	 *
	 * Given a user id, retrieve the relevant settings for the back page.
	 *
	 * @return array the settings
	 */
	private function get_saved_back_page_settings() {
		$settings                 = fp_get_pdf_settings();
		$group_settings           = fp_get_group_whitelabel_settings();
		$advanced_body_permission = ! empty( $group_settings['advanced_body_permission'] ) ? $group_settings['advanced_body_permission'] : 'off';
		$advanced_body            = ! empty( $group_settings['advanced_body'] ) ? $group_settings['advanced_body'] : '';

		if ( $advanced_body_permission === 'off' && ! empty( $advanced_body ) ) {
			$settings['use_advanced']  = 'true';
			$settings['advanced_body'] = $advanced_body;
		}

		return $settings;
	}

	/**
	 * Get Account Settings Field
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function get_account_settings_field( $field, $user_id, $saved_setting, $group_setting = '' ) {
		if ( ! $user_id ) {
			return $saved_setting;
		}

		$member                       = new \FP_Core\Member( $user_id );
		$is_enterprise_deluxe_member  = $member->is_active_at_level( FP_ENTERPRISE_DELUXE_ID );
		$is_enterprise_premier_member = $member->is_active_at_level( FP_ENTERPRISE_PREMIER_ID );
		$is_admin                     = current_user_can( 'administrator' );
		$allowed_user                 = $is_enterprise_deluxe_member || $is_enterprise_premier_member || $is_admin;
		$setting                      = get_user_meta( $user_id, $field, true );
		$enabled_settings_email       = '';

		if ( $field === 'email' ) {
			if ( $member->get_group() && $member->get_group()->get_group_id() ) {
				$enabled_settings_email = Database::get_group_setting( $member->get_group()->get_group_id(), $group_setting );
			}

			$setting = $saved_setting;

			if ( $enabled_settings_email === 'checked' ) {
				$user    = get_userdata( $user_id );
				$setting = $user->user_email;
			}
		}

		return $allowed_user ? $setting : $saved_setting;
	}

	private function get_person_name( int $user_id ) {
		if ( ! $user_id ) {
			return '';
		}

		$settings     = fp_get_pdf_settings();
		$advisor_name = $settings['advisor_name'];

		return apply_filters( 'fppathfinder_pdf_generator_person_name', $advisor_name, $user_id );
	}

	/**
	 * Get Licensee
	 *
	 * Get the relevant licensee to be used in the copyright statement on the Download.
	 *
	 * @param int $user_id
	 *
	 * @return string
	 */
	private function get_licensee( int $user_id = 0, array $user_settings = [] ) {
		$user_id = $user_id ? $user_id : get_current_user_id();

		if ( ! $user_id ) {
			return '';
		}

		$member               = new \FP_Core\Member( $user_id );
		$settings             = fp_get_pdf_settings( $user_settings );
		$is_essentials_member = $member->is_active_at_level( FP_ESSENTIALS_ID ) || Conditionals::is_essentials_group_member();
		$company              = get_user_meta( $user_id, 'company_name', true );
		$business_name        = ! empty( $settings['business_display_name'] ) ? $settings['business_display_name'] : '';
		$org                  = $is_essentials_member ? $company : $business_name;
		$advisor_name         = ! empty( $settings['advisor_name'] ) ? $settings['advisor_name'] : '';
		$pieces               = array( $advisor_name, $org );
		$no_advisor_name      = fp_use_advisor_name( $user_id );

		if ( $no_advisor_name ) {
			$pieces = array( $org );
		}

		return join( ' of ', array_filter( $pieces ) );
	}

	/**
	 * Get Download URL
	 *
	 * Get a link that will download the file in the browser.
	 *
	 * @return string
	 */
	public function get_download_url() {
		return "/wp-admin/admin-ajax.php?action=generate_pdf&mode=download&id=$this->post_id";
	}

	/**
	 * Get Inline URL
	 *
	 * Get a link that will open the file in a new tab.
	 *
	 * @return string
	 */
	public function get_inline_url() {
		return "/wp-admin/admin-ajax.php?action=generate_pdf&mode=inline&id=$this->post_id";
	}

	/**
	 * Get SVG Paths By Post ID
	 *
	 * Get the saved SVGs for this download and return the paths to the files.
	 *
	 * @param int $post_id The post id of the download
	 *
	 * @return array
	 */
	private function get_svg_paths_by_post_id( int $post_id ) {
		$svg_arrays = get_field( 'svg_files', $post_id );

		$get_svg_path = function ( $svg_array ) {
			return get_attached_file( $svg_array['svg_file']['id'] );
		};

		return array_map( $get_svg_path, $svg_arrays );
	}

	/**
	 * Get Saved Logo
	 *
	 * Given a user id, get the saved logo from their settings.
	 *
	 * @param int $user_id
	 *
	 * @return string
	 */
	private function get_saved_logo( int $user_id = 0, array $user_settings = [] ) {
		if ( isset( $user_settings['whitelabel']['logo'] ) ) {
			return $user_settings['whitelabel']['logo'];
		}

		$user_id  = $user_id ? $user_id : get_current_user_id();
		$settings = ! empty( $user_settings ) ? $user_settings : fp_get_pdf_settings( $user_settings );
		$logo     = ! empty( $settings['logo'] ) ? $settings['logo'] : '';

		return $logo;
	}

	/**
	 * Get Saved Colors
	 *
	 * Given a user id, get the saved colors from their settings.
	 *
	 * @param int $user_id
	 *
	 * @return array
	 */
	private function get_saved_colors( int $user_id = 0, array $user_settings = [] ) {
		if ( isset( $user_settings['whitelabel']['color_set'] ) ) {
			return $user_settings['whitelabel']['color_set'];
		}

		$user_id   = $user_id ? $user_id : get_current_user_id();
		$settings  = ! empty( $user_settings ) ? $user_settings : fp_get_pdf_settings( $user_settings );
		$color_set = ! empty( $settings['color_set'] ) ? $settings['color_set'] : array();

		return $color_set;
	}
}
