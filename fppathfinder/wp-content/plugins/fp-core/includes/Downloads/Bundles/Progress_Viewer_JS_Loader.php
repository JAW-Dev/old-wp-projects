<?php

namespace FP_Core\Downloads\Bundles;

class Progress_Viewer_JS_Loader {
	static public function init() {
		add_action( 'wp_enqueue_scripts', __CLASS__ . '::maybe_enqueue' );
	}

	static public function maybe_enqueue() {
		if ( ! is_singular( 'download-bundle' ) || 'generate-test' !== $_REQUEST['action'] ) {
			return;
		}

		$file_url = FP_CORE_DIR_URL . 'src/js/download-bundle-progress-viewer.js';
		$array    = array(
			'ajax_url'      => admin_url( 'admin-ajax.php' ),
			'nonce'         => wp_create_nonce(),
			'id'            => get_the_ID(),
			'user_id'       => get_current_user_id(),
			'user_settings' => fp_get_user_settings( get_current_user_id() ),
		);

		wp_enqueue_script( 'download-bundle-progress-viewer', $file_url, array( 'jquery' ), '1.0.0' );
		wp_localize_script( 'download-bundle-progress-viewer', 'download_bundle_progress_viewer_object', $array );
	}
}
