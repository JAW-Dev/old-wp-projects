<?php

namespace FP_REST_API;

abstract class Custom_Endpoint {

	public function __construct() {

	}

	protected function get_token() {

		/** copied from  /wp-content/plugins/oauth2-provider/library/class-wo-api.php */
		$storage = new \WPOAuth2\Storage\Wordpressdb();
		$config  = array(
			'use_crypto_tokens'                 => false,
			'store_encrypted_token_string'      => false,
			'use_openid_connect'                => wo_setting( 'use_openid_connect' ),
			'issuer'                            => home_url( null, 'https' ),
			'id_lifetime'                       => wo_setting( 'id_token_lifetime' ),
			'access_lifetime'                   => wo_setting( 'access_token_lifetime' ),
			'refresh_token_lifetime'            => wo_setting( 'refresh_token_lifetime' ),
			'www_realm'                         => apply_filters( 'wo_www_realm', 'Service' ),
			'token_param_name'                  => apply_filters( 'wo_token_param_name', 'access_token' ),
			'token_bearer_header_name'          => apply_filters( 'wo_token_bearer_header_name', 'Bearer' ),
			'enforce_state'                     => wo_setting( 'enforce_state' ),
			'require_exact_redirect_uri'        => wo_setting( 'require_exact_redirect_uri' ),
			'allow_implicit'                    => wo_setting( 'implicit_enabled' ),
			'allow_credentials_in_request_body' => apply_filters( 'wo_allow_credentials_in_request_body', true ),
			'allow_public_clients'              => apply_filters( 'wo_allow_public_clients', false ),
			'always_issue_new_refresh_token'    => apply_filters( 'wo_always_issue_new_refresh_token', true ),
			'unset_refresh_token_after_use'     => apply_filters( 'wo_unset_refresh_token_after_use', false ),
			'redirect_status_code'              => apply_filters( 'wo_redirect_status_code', 302 ),
			'use_jwt_access_tokens'             => false,
		);
		/** End copied code */

		$auth_server = new \WPOAuth2\Server( $storage, $config );
		$request     = \WPOAuth2\Request::createFromGlobals();
		return $auth_server->getAccessTokenData( $request );
	}

	public function vet_token( \WP_REST_Request $wp_rest_request ) {
		$token = $this->get_token();

		if ( is_null( $token ) ) {
			return new \WP_Error( 'invalid_token', 'Invalid token', array( 'status' => 400 ) );
		}

		$this->handle_request( $token, $wp_rest_request );
	}

	static function validate_scope( $required_scope, $avaliable_scope ) {
		$scope_checker = new \WPOAuth2\Scope();

		if ( ! $scope_checker->checkScope( $required_scope, $avaliable_scope ) ) {
			$response = new \WPOAuth2\Response();
			$response->setError(
				400,
				'invalid_request',
				'Invalid scope'
			);
			$response->send();
			exit;
		}
	}

	abstract public function handle_request( array $token, \WP_REST_Request $wp_rest_request );
	abstract public function get_endpoint_text();
	abstract public function get_methods();
}
