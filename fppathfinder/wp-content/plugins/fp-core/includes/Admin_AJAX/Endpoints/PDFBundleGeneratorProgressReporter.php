<?php

namespace FP_Core\Admin_AJAX\Endpoints;

use FP_Core\Downloads\Bundles\Generator\Progress_Reporter;

class PDFBundleGeneratorProgressReporter implements EndpointInterface {
	public function get_name(): string {
		return 'generate_bundle_progress';
	}

	public function get_handler(): callable {
		return array( $this, 'handler' );
	}

	public function get_nopriv_handler(): callable {
		return array( $this, 'handler' );
	}

	public function handler() {
		$process_id = $_REQUEST['id'];

		if ( empty( $process_id ) ) {
			return;
		}

		if ( ! wp_verify_nonce( $_REQUEST['nonce'] ) ) {
			return;
		}

		echo Progress_Reporter::get_progress( $process_id, get_current_user_id() );
		exit;
	}
}
