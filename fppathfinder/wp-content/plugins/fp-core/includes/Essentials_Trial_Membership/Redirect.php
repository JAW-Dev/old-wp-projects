<?php

namespace FP_Core\Essentials_Trial_Membership;

class Redirect {
	static public function init() {
		add_action( 'wp', __CLASS__ . '::maybe_redirect' );
	}

	static public function maybe_redirect() {
		$registration_page_id = Settings::get_registration_page_id();

		if ( ! $registration_page_id || ! is_page( $registration_page_id ) ) {
			return;
		}

		if ( Settings::get_request_code() ) {
			return;
		}

		wp_safe_redirect( '/', 307 );
	}
}
