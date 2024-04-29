<?php

namespace FP_Core\Downloads\Bundles;

class User_Meta_Adder {
	static public function init() {
		add_action( 'download_bundle_pre_generate', __CLASS__ . '::log', 10, 2 );
	}

	static public function log( int $bundle_post_id, int $user_id ) {
		$data = array(
			'bundle_post_id' => $bundle_post_id,
			'unix'           => time(),
		);

		add_user_meta( $user_id, 'bundle_downloaded', $data );
	}
}
