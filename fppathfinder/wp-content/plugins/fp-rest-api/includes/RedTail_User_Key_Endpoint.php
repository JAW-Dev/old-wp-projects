<?php

namespace FP_REST_API;

class RedTail_User_Key_Endpoint extends Custom_Endpoint {

	public function __construct() {}

	public function get_endpoint_text() {
		return 'redtail-user-key';
	}

	public function get_methods() {
		return array( 'POST' );
	}

	public function handle_request( array $token, \WP_REST_Request $wp_rest_request ) {
		parent::validate_scope( 'set_redtail_user_key', $token['scope'] );

		$success = $this->set_redtail_user_key( $token, $wp_rest_request );

		if ( false === $success ) {
			$response = new \WPOAuth2\Response();
			$response->setError( 400, 'invalid_request', 'Could not update UserKey' );
			$response->send();
			exit;
		}

		$response = new \WPOAuth2\Response( array( 'status' => 'UserKey successfully set' ) );
		$response->send();
		exit;
	}

	private function set_redtail_user_key( array $token, \WP_REST_Request $wp_rest_request ) {
		$user_id          = $token['user_id'];
		$redtail_user_key = $wp_rest_request->get_json_params()['redtail-user-key'] ?? false;

		if ( ! $redtail_user_key ) {
			$response = new \WPOAuth2\Response();
			$response->setError( 400, 'invalid_request', 'No RedTail UserKey specified' );
			$response->send();
			exit;
		}

		// update_user_meta() below will return false if the prev value and new value are the same.
		// Don't ask me why.
		$tokens = get_user_meta( $user_id, 'redtail_tokens', true );

		if ( ! empty( $tokens ) ) {
			delete_user_meta( $user_id, 'redtail_user_key' );
			delete_user_meta( $user_id, 'redtail_tokens' );
			return update_user_meta( $user_id, 'redtail_tokens', $redtail_user_key );
		} else {
			delete_user_meta( $user_id, 'redtail_user_key' );
			delete_user_meta( $user_id, 'redtail_tokens' );
			return update_user_meta( $user_id, 'redtail_tokens', $redtail_user_key );
		}
	}
}
