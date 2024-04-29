<?php

namespace FP_PDF_Generator;

use FpAccountSettings\Includes\Classes\Conditionals;
use FpAccountSettings\Includes\Utilites\Media\Image;

class Main {
	public function __construct() {
		$this->create_listeners();
	}

	private function create_listeners() {
		add_action( 'wp_ajax_generate_pdf', array( $this, 'generate_pdf_listener' ) );
		add_action( 'wp_ajax_nopriv_generate_pdf', array( $this, 'generate_pdf_listener' ) );
		add_action( 'wp_ajax_generate_pdf_preview', array( $this, 'generate_preview' ) );
		add_action( 'wp_ajax_nopriv_generate_pdf_preview', array( $this, 'generate_preview' ) );
	}

	public function generate_pdf_listener() {
		$download_post_id = $_REQUEST['id'] ?? false;
		$mode             = $_REQUEST['mode'] ?? false;

		if ( $mode && $download_post_id && $this->user_can_download( $download_post_id ) ) {
			$download = new Download( $download_post_id );

			if ( 'download' === $mode ) {
				$download->generate_download();
			} elseif ( 'inline' === $mode ) {
				$download->generate_inline();
			}
		}
	}

	public function generate_bundle_listener() {
		$bundle_post_id = intval( $_REQUEST['id'] ?? false );

		if ( ! $bundle_post_id || ! $this->user_can_download( $bundle_post_id ) ) {
			return;
		}

		do_action( 'download_bundle_pre_generate', $bundle_post_id, get_current_user_id() );

		$bundle = new Download_Bundle( $bundle_post_id );
		$bundle->generate();
	}

	public function generate_preview() {
		$preview_svg_path = trailingslashit( dirname( __DIR__ ) ) . 'assets/svgs/whitelabeling-flowchart.svg';
		$svgs             = array( $preview_svg_path );
		$pdf_settings     = fp_get_pdf_settings();
		$permissions      = fp_get_group_permissions();
		$no_advisor_name  = fp_use_advisor_name();

		$licensee = join( ' of ', array_filter( array( $pdf_settings['advisor_name'], $pdf_settings['business_display_name'] ) ) );

		if ( $no_advisor_name ) {
			$licensee = $pdf_settings['business_display_name'];
		}

		$pdf = new PDF( $svgs, $licensee, 'Can-I-Make-A-Deductible-IRA-Contribution-2019.pdf' );

		if ( ! empty( $pdf_settings['color_set'] ) ) {
			$pdf->set_colors( $pdf_settings['color_set'] );
		}

		if ( ! empty( $pdf_settings['logo'] ) ) {
			$pdf->set_logo( $pdf_settings['logo'] );
		}

		$check_backpage = [
			'address'                => ! empty( $pdf_settings['address'] ) ? trim( $pdf_settings['address'] ) : '',
			'job_title'              => ! empty( $pdf_settings['job_title'] ) ? trim( $pdf_settings['job_title'] ) : '',
			'second_page_body_copy'  => ! empty( $pdf_settings['second_page_body_copy'] ) ? trim( $pdf_settings['second_page_body_copy'] ) : '',
			'second_page_body_title' => ! empty( $pdf_settings['second_page_body_title'] ) ? trim( $pdf_settings['second_page_body_title'] ) : '',
			'second_page_title'      => ! empty( $pdf_settings['second_page_title'] ) ? trim( $pdf_settings['second_page_title'] ) : '',
			'email'                  => ! empty( $pdf_settings['email'] ) ? trim( $pdf_settings['email'] ) : '',
			'phone'                  => ! empty( $pdf_settings['phone'] ) ? trim( $pdf_settings['phone'] ) : '',
			'website'                => ! empty( $pdf_settings['website'] ) ? trim( $pdf_settings['website'] ) : '',
			'advanced_body'          => ! empty( $pdf_settings['advanced_body'] ) ? trim( $pdf_settings['advanced_body'] ) : '',
		];

		$filled_out_back_page_fields = array_filter( $pdf_settings );

		foreach ( $check_backpage as $key => $value ) {
			if ( empty( $value ) ) {
				unset( $filled_out_back_page_fields[ $key ] );
			}
		}

		if ( $permissions['advanced_body_permission'] === 'off' ) {
			$pdf_settings['use_advanced'] = 'false';
		}

		if ( $pdf_settings['use_advanced'] === 'true' && ! array_key_exists( 'advanced_body', $filled_out_back_page_fields ) ) {
			$filled_out_back_page_fields = array();
		}

		if ( $pdf_settings['use_advanced'] === 'false' && array_key_exists( 'advanced_body', $filled_out_back_page_fields ) ) {
			unset( $filled_out_back_page_fields['advanced_body'] );
		}

		if ( fp_do_generate_back_page( $pdf_settings ) ) {
			$pdf->set_back_page_settings( $pdf_settings );
		}

		$pdf->generate_inline();
	}

	private function user_can_download( int $post_id, int $user_id = 0 ) {
		$user_id             = $user_id ? $user_id : get_current_user_id();
		$sample_download_ids = get_field( 'sample_download_ids', 'option' );
		$is_sample_download  = $sample_download_ids && in_array( $post_id, $sample_download_ids, true );

		return $is_sample_download || rcp_user_can_access( $user_id, $post_id ) || current_user_can( 'administrator' );
	}
}
