<?php

namespace FP_Core\Admin_AJAX\Endpoints;

use FP_Core\Downloads\Bundles\Generator\Process_Creation_Controller;

class PDFBundleProcessCreator implements EndpointInterface {
	public function get_name(): string {
		return 'create_generate_bundle_process';
	}

	public function get_handler(): callable {
		return array( $this, 'handler' );
	}

	public function get_nopriv_handler(): callable {
		return function() {};
	}

	public function handler() {
		$bundle_post_id = intval( $_REQUEST['id'] );

		if ( ! $bundle_post_id ) {
			return;
		}

		if ( ! wp_verify_nonce( $_REQUEST['nonce'] ) ) {
			return;
		}

		echo Process_Creation_Controller::handle_process_creation_request( $bundle_post_id, get_current_user_id() );
		exit;
	}
}
