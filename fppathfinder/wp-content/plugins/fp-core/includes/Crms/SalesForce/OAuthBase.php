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

namespace FP_Core\Crms\SalesForce;

abstract class OAuthBase {

	/**
	 * CRM Slug
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @var string
	 */
	protected $crm_slug = '';

	/**
	 * Authorize Endpoint
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @var string
	 */
	protected $authorize_endpoint = 'https://login.salesforce.com/services/oauth2/authorize';

	/**
	 * Token Endpoint
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @var string
	 */
	protected $token_endpoint = 'https://login.salesforce.com/services/oauth2/token';

	/**
	 * OAuth Key
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @var string
	 */
	protected $oauth_key;

	/**
	 * OAuth Secret
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @var string
	 */
	protected $oauth_secret;

	/**
	 * Initialize the class
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function __construct() {}

	/**
	 * Init
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function init() {
		add_action( 'wp', [ $this, 'listen' ] );
	}

	/**
	 * Get Redirect URL
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return string
	 */
	public function get_redirect_uri(): string {
		return home_url( '/your-membership/integrations/' );
	}

	/**
	 * Listener
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function listen() {
		$user_id = get_current_user_id();

		if ( ! $user_id ) {
			return;
		}

		if ( empty( $_GET['state'] ?? false ) ) {
			return;
		}

		if ( ! wp_verify_nonce( $_GET['state'], get_current_user_id() . '_' . $this->crm_slug . '_oauth' ) ) {
			return;
		}

		$code = $_GET['code'] ?? false;

		if ( ! $code ) {
			return;
		}

		$token = $this->crm_slug;

		$this->get_tokens( $user_id, $code, $token );

		wp_safe_redirect( '/your-membership/integrations?settings=' . $this->crm_slug );
	}

	/**
	 * Get Tokens
	 *
	 * Use the authorization code given by salesforce in the OAuth 2.0 handshake to get tokens for the user.
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param int    $user_is            The user ID.
	 * @param string $authorization_code The authorization code.
	 *
	 * @return void
	 */
	public function get_tokens( int $user_id, string $authorization_code ) {
		$reqeust_body = array(
			'client_id'     => FP_SALSFORCE_PROD_KEY,
			'client_secret' => FP_SALESFORCE_PROD_SECRET,
			'grant_type'    => 'authorization_code',
			'code'          => $authorization_code,
			'redirect_uri'  => $this->get_redirect_uri(),
		);

		try {
			$response = wp_remote_post( $this->token_endpoint, array( 'body' => $reqeust_body ) );
		} catch ( \Exception $e ) {
			error_log( 'failed ' . $this->crm_slug . ' token exchange' . "\n\n" . $e->getMessage() );
			return;
		}

		$code = $response['response']['code'];

		if ( is_wp_error( $code ) ) {
			return;
		}

		if ( 200 !== $code ) {
			error_log( 'failed ' . $this->crm_slug . ' token exchange' . "\n\n" . var_export( $response, true ) );
			return;
		}

		$tokens = json_decode( $response['body'] );

		update_user_meta( $user_id, $this->crm_slug . '_tokens', $tokens );
	}

	/**
	 * Get User Tokens
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param int $user_id The user ID.
	 *
	 * @return string
	 */
	public function get_user_token( int $user_id ) {
		$tokens = get_user_meta( $user_id, $this->crm_slug . '_tokens', true );

		if ( empty( $tokens ) ) {
			return '';
		}

		if ( gettype( $tokens ) === 'string' ) {
			$tokens = json_decode( $tokens );
		}

		$issued       = $tokens->issued_at;
		$refresh_time = strtotime( '+90 minutes', $issued );

		if ( strlen( $issued ) > 10 ) {
			$seconds      = ceil( $issued / 1000 );
			$refresh_time = strtotime( '+90 minutes', $seconds );
		}

		if ( time() < $refresh_time ) {
			return $tokens->access_token;
		}

		$refresh_reqeust_body = array(
			'client_id'     => FP_SALSFORCE_PROD_KEY,
			'client_secret' => FP_SALESFORCE_PROD_SECRET,
			'grant_type'    => 'refresh_token',
			'refresh_token' => $tokens->refresh_token,
		);

		try {
			$response = wp_remote_post( $this->token_endpoint, array( 'body' => $refresh_reqeust_body ) );
		} catch ( \Exception $e ) {
			error_log( 'failed ' . $this->crm_slug . ' token exchange' . "\n\n" . $e->getMessage() );
			return;
		}

		$code = $response['response']['code'];

		if ( is_wp_error( $code ) ) {
			return;
		}

		if ( 200 !== $code ) {
			error_log( 'failed ' . $this->crm_slug . ' token exchange' . "\n\n" . var_export( $response, true ) );
			return;
		}

		$refreshed_tokens = json_decode( $response['body'] );

		if ( ! isset( $refreshed_tokens->refresh_token ) ) {
			$refreshed_tokens->refresh_token = $tokens->refresh_token;
		}

		update_user_meta( $user_id, $this->crm_slug . '_tokens', $refreshed_tokens );

		return $refreshed_tokens->access_token;
	}

	/**
	 * Get Authorize URL
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return string
	 */
	public function get_authorize_url(): string {
		$endpoint = $this->authorize_endpoint;
		$id       = FP_SALSFORCE_PROD_KEY;
		$nonce    = wp_create_nonce( get_current_user_id() . '_' . $this->crm_slug . '_oauth' );
		$redirect = $this->get_redirect_uri();
		$scope    = 'api refresh_token';
		$type     = 'code';
		$url      = "$endpoint?client_id=$id&redirect_uri=$redirect&response_type=$type&redirect_uri=$redirect&scope=$scope&state=$nonce";

		return $url;
	}
}
