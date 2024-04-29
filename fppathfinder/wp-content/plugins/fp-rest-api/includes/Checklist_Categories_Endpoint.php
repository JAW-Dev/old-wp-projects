<?php

namespace FP_REST_API;

class Checklist_Categories_Endpoint extends Custom_Endpoint {

	public function __construct() {}

	public function get_endpoint_text() {
		return 'checklist-categories';
	}

	public function get_methods() {
		return array( 'GET' );
	}

	public function handle_request( array $token, \WP_REST_Request $wp_rest_request ) {
		parent::validate_scope( 'read_checklists', $token['scope'] );

		$data     = $this->get_response_data( $token, $wp_rest_request );
		$response = new \WPOAuth2\Response( $data );
		$response->send();
		exit;
	}

	private function get_response_data( array $token, \WP_REST_Request $wp_rest_request ) {
		$categories = get_terms( array( 'taxonomy' => 'checklist-cat' ) );

		if ( ! $categories ) {
			return 'no categories found';
		}

		$prepare_category = function( \WP_Term $term ) {
			return array(
				'name' => $term->name,
				'slug' => $term->slug,
			);
		};

		return array( 'checklist-categories' => array_map( $prepare_category, $categories ) );
	}
}
