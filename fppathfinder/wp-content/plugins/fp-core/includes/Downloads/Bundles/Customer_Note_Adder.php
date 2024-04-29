<?php

namespace FP_Core\Downloads\Bundles;

class Customer_Note_Adder {
	static public function init() {
		add_action( 'download_bundle_pre_generate', __CLASS__ . '::add_note', 10, 2 );
	}

	static public function add_note( int $bundle_post_id, int $user_id ) {
		$customer = rcp_get_customer_by_user_id( $user_id );

		if ( ! $customer ) {
			return;
		}

		$post = get_post( $bundle_post_id );

		if ( ! $post ) {
			return;
		}

		$bundle_name = $post->post_title;

		$customer->add_note( "Downloaded Bundle: $bundle_name" );
	}
}
