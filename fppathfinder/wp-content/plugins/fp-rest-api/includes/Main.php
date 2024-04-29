<?php

namespace FP_REST_API;

class Main {
	public function __construct() {
		$this->setup_endpoints();
		$this->setup_oauth();
		add_filter( 'wo_use_grant_request', '__return_true' );
	}

	private function setup_endpoints() {
		$endpoints = array(
			new Checklists_Endpoint(),
			new RedTail_User_Key_Endpoint(),
			new Checklist_Categories_Endpoint(),
		);

		Endpoint_Registrar::register_endpoints( $endpoints );
	}
	private function setup_oauth() {
		add_action(
			'wo_before_authorize_method', function () {
				if ( ! is_user_logged_in() ) {
					wp_redirect( site_url() . '/login?redirect_to=' . urlencode( site_url() . $_SERVER['REQUEST_URI'] ) );
					exit;
				}
			}
		);
	}
}
