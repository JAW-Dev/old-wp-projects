<?php

namespace FP_Core\Admin_AJAX\Endpoints;

use FP_Core\Downloads\Bundles\Generator\Bundle_Generator_Controller;

class PDFBundleGenerator implements EndpointInterface {

	/**
	 * Initialize the class
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function __construct() {
		add_action( 'wp_ajax_generate_bundle_test', array( $this, 'handler' ) );
		add_action( 'wp_ajax_nopriv_generate_bundle_test', array( $this, 'handler' ) );
		add_action( 'wp_ajax_generate_bundle_delete', array( $this, 'bundle_delete' ) );
		add_action( 'wp_ajax_nopriv_generate_bundle_delete', array( $this, 'bundle_delete' ) );
	}

	public function get_name(): string {
		return 'generate_bundle';
	}

	public function get_handler(): callable {
		return array( $this, 'handler' );
	}

	public function get_nopriv_handler(): callable {
		return function() {};
	}

	public function handler() {
		$post     = $_POST;
		$data     = $post['data'];
		$defaults = [
			'post_id'         => 0,
			'bundle_dir'      => '',
			'first_run'       => 'false',
			'total_posts'     => 0,
			'remaining_posts' => [],
			'remaining_count' => 0,
			'pdfs'            => [],
			'temp_dir'        => '',
			'user_id'         => 0,
			'user_settings'   => [],
		];

		$data = wp_parse_args( $data, $defaults );

		echo Bundle_Generator_Controller::handle_bundle_generation_request( $data );
		exit;
	}

	/**
	 * Bundle Delete
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function bundle_delete() {
		$bundle_dir = ! empty( $_POST['bundle_dir'] ) ? sanitize_text_field( wp_unslash( $_POST['bundle_dir'] ) ) : '';

		$this->delete_dir( $bundle_dir );

		echo '';
		wp_die();
	}

	/**
	 * Delete Dir
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function delete_dir( $dir ) {
		if ( ! file_exists( $dir ) ) {
			return true;
		}

		if ( ! is_dir( $dir ) ) {
			return unlink( $dir );
		}

		foreach ( scandir( $dir ) as $item ) {
			if ( $item === '.' || $item === '..' ) {
				continue;
			}
			if ( ! $this->delete_dir( $dir . DIRECTORY_SEPARATOR . $item ) ) {
				return false;
			}
		}

		return rmdir( $dir );
	}
}
