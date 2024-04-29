<?php
/**
 * OAuth Controller
 *
 * @package    FP_Core/
 * @subpackage FP_Core/Includes/Crms
 * @author     Objectiv
 * @copyright  Copyright (c) 2020, Objectiv
 * @license    GPL-2.0
 * @version    1.0.0
 */

namespace FP_Core\Crms\Wealthbox;

class OAuthController {

	protected static $app_id                 = '01be210478a269307f80f650e609733d107e95c4c9fa4d6d03cb720c9f1322dc';
	protected static $app_secret             = '9536bf12064ca5ed901d24baa2e09d744243a20fde13272fb4164e2aa8dc6013';
	protected static $token_user_meta_key    = 'wealthbox_tokens';
	protected static $token_endpoint         = 'https://api.crmworkspace.com/oauth/token';
	protected static $refresh_token_endpoint = 'https://app.crmworkspace.com/oauth/token';

	static public function init() {
		add_action( 'wp', __CLASS__ . '::listen' );
	}

	static public function get_redirect_uri(): string {
		return home_url( '/your-membership/integrations' );
	}

	static public function has_sufficient_credentials(): bool {
		return self::get_client_id() && self::get_client_secret();
	}

	static private function get_client_id() {
		if ( ! function_exists( 'get_field' ) ) {
			return false;
		}

		return get_field( 'wealthbox_oauth_client_id', 'options', true );
	}

	static private function get_client_secret() {
		if ( ! function_exists( 'get_field' ) ) {
			return false;
		}

		return get_field( 'wealthbox_oauth_client_secret', 'options', true );
	}

	static public function listen() {
		$user_id = get_current_user_id();

		if ( ! $user_id ) {
			return;
		}

		if ( empty( $_GET['state'] ?? false ) ) {
			return;
		}

		if ( ! wp_verify_nonce( $_GET['state'], get_current_user_id() . '_wealthbox_oauth' ) ) {
			return;
		}

		$code = $_GET['code'] ?? false;

		if ( ! $code ) {
			return;
		}

		self::get_tokens( $user_id, $code );

		wp_safe_redirect( '/your-membership/integrations?settings=wealthbox' );
	}

	/**
	 * Get Tokens
	 *
	 * Use the authorization code given by wealthbox in the OAuth 2.0 handshake to get tokens for the user.
	 *
	 * @param string $authorization_code
	 * @return void
	 */
	static public function get_tokens( int $user_id, string $authorization_code ) {
		$reqeust_body = array(
			'client_id'     => self::$app_id,
			'client_secret' => self::$app_secret,
			'grant_type'    => 'authorization_code',
			'code'          => $authorization_code,
			'redirect_uri'  => self::get_redirect_uri(),
			'scopes'        => 'login data',
		);

		try {
			$response = wp_remote_post( self::$token_endpoint, array( 'body' => $reqeust_body ) );
		} catch ( \Exception $e ) {
			error_log( "failed wealthbox token exchange\n\n" . $e->getMessage() );
			return;
		}

		$code = $response['response']['code'];

		if ( is_wp_error( $code ) ) {
			return;
		}

		if ( 200 !== $code ) {
			error_log( "failed wealthbox token exchange\n\n" . var_export( $response, true ) );
			return;
		}

		$tokens = json_decode( $response['body'] );

		update_user_meta( $user_id, self::$token_user_meta_key, $tokens );
	}

	static public function get_user_token( int $user_id ) {
		$tokens = get_user_meta( $user_id, self::$token_user_meta_key, true );

		if ( gettype( $tokens ) === 'string' ) {
			$tokens = json_decode( $tokens );
		}

		if ( empty( $tokens ) ) {
			return false;
		}

		if ( time() < strtotime( '+90 minutes', $tokens->created_at ) ) {
			return $tokens->access_token;
		}

		$refresh_reqeust_body = array(
			'client_id'     => self::$app_id,
			'client_secret' => self::$app_secret,
			'grant_type'    => 'refresh_token',
			'refresh_token' => $tokens->refresh_token,
		);

		try {
			$response = wp_remote_post( self::$token_endpoint, array( 'body' => $refresh_reqeust_body ) );
		} catch ( \Exception $e ) {
			error_log( "failed wealthbox token refresh exchange\n\n" . $e->getMessage() );
			return;
		}

		$code = $response['response']['code'];
		$body = json_decode( $response['body'] );

		if ( is_wp_error( $code ) ) {
			return;
		}

		if ( 200 !== $code ) {
			error_log( 'Refresh Oauth Token Error [code]: ' . $response['response']['code'] . ' [message]: ' . print_r( $response['response']['message'], true ) );
			$error       = $body->error ?? '';
			$description = $body->error_description ?? '';
			error_log( $error . ': ' . print_r( $description, true ) );
			return;
		}

		$refreshed_tokens = json_decode( $response['body'] );

		if ( ! isset( $refreshed_tokens->refresh_token ) ) {
			$refreshed_tokens->refresh_token = $tokens->refresh_token;
		}

		update_user_meta( $user_id, self::$token_user_meta_key, $refreshed_tokens );

		return $refreshed_tokens->access_token;
	}

	static public function get_authorize_url(): string {
		$nonce    = wp_create_nonce( get_current_user_id() . '_wealthbox_oauth' );
		$redirect = self::get_redirect_uri();

		return "https://app.crmworkspace.com/oauth/authorize?client_id=01be210478a269307f80f650e609733d107e95c4c9fa4d6d03cb720c9f1322dc&redirect_uri=$redirect&scope=login data&response_type=code&response_mode=form_post&state=$nonce";
	}
}
