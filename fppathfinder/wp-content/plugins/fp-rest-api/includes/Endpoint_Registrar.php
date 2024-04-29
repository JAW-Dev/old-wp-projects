<?php

namespace FP_REST_API;

/**
 * Endpoint Registrar
 *
 * Endpoint Registrar functionality customizations
 */
class Endpoint_Registrar {

	public function __construct() {}

	static function get_endpoint_base() {
		return 'api/v1';
	}

	static function register_endpoint( Custom_Endpoint $endpoint ) {
		$register_function = function () use ( $endpoint ) {
			register_rest_route(
				self::get_endpoint_base(), $endpoint->get_endpoint_text(), array(
					'methods'  => $endpoint->get_methods(),
					'callback' => array( $endpoint, 'vet_token' ),
				)
			);
		};

		add_action( 'rest_api_init', $register_function );
	}

	static function register_endpoints( array $endpoints ) {
		foreach ( $endpoints as $endpoint ) {
			self::register_endpoint( $endpoint );
		}
	}
}
