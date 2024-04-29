<?php

namespace FP_Core\Integrations\ProfitWell;

use FP_Core\Integrations\ProfitWell\Logger;

class ProfitWell_API {

	static public function get_base_url(): string {
		return 'https://api.profitwell.com/v2';
	}

	static public function get_dev_api_key(): string {
		return get_field( 'profitwell_dev_api_key', 'options' ) ?? '';
	}

	static public function get_prod_api_key(): string {
		return get_field( 'profitwell_prod_api_key', 'options' ) ?? '';
	}

	static public function get_api_key(): string {
		$api_key = ProfitWell_Integration::is_dev_mode() ? self::get_dev_api_key() : self::get_prod_api_key();
		return $api_key;
	}

	/**
	 * Check Status
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public static function check_status() {
		$endpoint = self::get_base_url() . '/api-status/';

		$request_args = array(
			'method'  => 'GET',
			'headers' => array(
				'Content-Type'  => 'application/json',
				'Authorization' => self::get_api_key(),
			),
		);

		$response = wp_remote_request( $endpoint, $request_args );

		return $response;
	}

	/**
	 * Logger
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param int    $user_id    The user ID.
	 * @param string $user_email The user email.
	 * @param string $type       The type being logged.
	 * @param string $response   The body of the API response.
	 *
	 * @return void
	 */
	public static function do_log( $user_id, $user_email, $type, $message, $response ) {
		return ( new Logger() )->log( $user_id, $user_email, $type, $message, $response );
	}

	/**
	 * Success Codes
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public static function response_success( $user_id, $user_email, $type, $response ) {
		$codes = [
			200,
			201,
			204,
		];

		if ( is_wp_error( $response ) ) {
			$error_codes   = function_exists( 'array_key_first' ) ? array_key_first( $response->errors ) : self::custom_array_key_first( $response->errors );
			$error_code    = $response->errors[ $error_codes ][0] ?? '';
			$error_message = $response->get_error_message();

			self::do_log( $user_id, $user_email, $type, '[code]: ' . $error_code . ' [message]: ' . $error_message, $response );
			return false;
		}

		if ( ! in_array( $response['response']['code'], $codes, true ) ) {
			self::do_log( $user_id, $user_email, $type, '[code]: ' . $response['response']['code'] . ' [message]: ' . $response['response']['message'], $response );
			return false;
		}

		return true;
	}

	static public function create_subscription(
		string $email,
		string $plan_id,
		int $value_in_cents,
		int $unix_effective,
		string $plan_interval = 'year',
		string $user_alias = '',
		string $user_id = '',
		string $subscription_alias = '',
		string $plan_currency = '',
		string $status = ''
		) {

		$type     = 'create_subscription';
		$endpoint = self::get_base_url() . '/subscriptions';
		$body     = array(
			'email'          => $email,
			'plan_id'        => $plan_id,
			'plan_interval'  => $plan_interval,
			'value'          => $value_in_cents,
			'effective_date' => $unix_effective,
		);

		if ( ! empty( $user_alias ) ) {
			$body['user_alias'] = $user_alias;
		}

		if ( ! empty( $subscription_alias ) ) {
			$body['subscription_alias'] = $subscription_alias;
		}

		if ( ! empty( $plan_currency ) ) {
			$body['plan_currency'] = $plan_currency;
		}

		if ( ! empty( $status ) ) {
			$body['status'] = $status;
		}

		$request_args = array(
			'method'  => 'POST',
			'headers' => array(
				'Content-Type'  => 'application/json',
				'Authorization' => self::get_api_key(),
			),
			'body'    => json_encode( $body ),
		);

		$response = wp_remote_request( $endpoint, $request_args );

		if ( ! empty( $user_id ) && ! empty( $email ) && ! empty( $response ) ) {

			if ( ! self::response_success( $user_id, $email, $type, $response ) ) {
				return;
			}

			self::do_log( $user_id, $email, $type, 'Success', $response );
		}

		return $response;
	}

	static public function update_subscription( $user_id = '', $user_email = '', $subscription_id, $new_plan_id, $value_in_cents, $unix_effective, $plan_interval = 'year', $status = '' ) {
		$type     = 'update_subscription';
		$endpoint = self::get_base_url() . "/subscriptions/$subscription_id";
		$body     = array(
			'plan_id'        => $new_plan_id,
			'plan_interval'  => $plan_interval,
			'value'          => $value_in_cents,
			'effective_date' => $unix_effective,
			'status'         => 'active',
		);

		if ( ! empty( $status ) ) {
			$body['status'] = $status;
		}

		$request_args = array(
			'method'  => 'PUT',
			'headers' => array(
				'Content-Type'  => 'application/json',
				'Authorization' => self::get_api_key(),
			),
			'body'    => json_encode( $body ),
		);

		$response = wp_remote_request( $endpoint, $request_args );

		if ( ! empty( $user_id ) && ! empty( $user_email ) && ! empty( $response ) ) {
			if ( ! self::response_success( $user_id, $user_email, $type, $response ) ) {
				return;
			}

			self::do_log( $user_id, $user_email, $type, 'Success', $response );
		}

		return $response;
	}

	static public function churn_subscription( $user_id = '', $user_email = '', $subscription_id, $unix_effective, $churn_type = 'voluntary' ) {
		$type     = 'churn_subscription';
		$endpoint = self::get_base_url() . "/subscriptions/{$subscription_id}?effective_date={$unix_effective}&churn_type={$churn_type}";

		$request_args = array(
			'method'  => 'DELETE',
			'headers' => array(
				'Content-Type'  => 'application/json',
				'Authorization' => self::get_api_key(),
			),
		);

		$response = wp_remote_request( $endpoint, $request_args );

		if ( ! empty( $user_id ) && ! empty( $user_email ) && ! empty( $response ) ) {
			if ( ! self::response_success( $user_id, $user_email, $type, $response ) ) {
				return;
			}

			self::do_log( $user_id, $user_email, $type, 'Success', $response );
		}

		return $response;
	}

	static public function unchurn_subscription( $user_id = '', $user_email = '', $subscription_id ) {
		$type         = 'unchurn_subscription';
		$endpoint     = self::get_base_url() . "/unchurn/{$subscription_id}";
		$request_args = array(
			'method'  => 'PUT',
			'headers' => array(
				'Content-Type'  => 'application/json',
				'Authorization' => self::get_api_key(),
			),
		);

		$response = wp_remote_request( $endpoint, $request_args );

		if ( ! empty( $user_id ) && ! empty( $user_email ) && ! empty( $response ) ) {
			if ( ! self::response_success( $user_id, $user_email, $type, $response ) ) {
				return;
			}

			self::do_log( $user_id, $user_email, $type, 'Success', $response );
		}

		return $response;
	}

	static public function get_subscription_history_for_user( string $user_id_or_alias ) {
		$endpoint = self::get_base_url() . "/users/{$user_id_or_alias}";

		$request_args = array(
			'method'  => 'GET',
			'headers' => array(
				'Content-Type'  => 'application/json',
				'Authorization' => self::get_api_key(),
			),
		);

		return wp_remote_request( $endpoint, $request_args );
	}

	static public function delete_user( string $user_id_or_alias ) {
		$endpoint = self::get_base_url() . "/users/{$user_id_or_alias}";

		$request_args = array(
			'method'  => 'DELETE',
			'headers' => array(
				'Content-Type'  => 'application/json',
				'Authorization' => self::get_api_key(),
			),
		);

		$response = wp_remote_request( $endpoint, $request_args );

		return $response;
	}

	static public function retrieve_plan( string $plan_id ) {
		$endpoint = self::get_base_url() . "/plans/{$plan_id}";

		$request_args = array(
			'method'  => 'GET',
			'headers' => array(
				'Content-Type'  => 'application/json',
				'Authorization' => self::get_api_key(),
			),
		);

		return wp_remote_request( $endpoint, $request_args );
	}

	static public function get_all_plans() {
		$endpoint = self::get_base_url() . '/plans';

		$request_args = array(
			'method'  => 'GET',
			'headers' => array(
				'Content-Type'  => 'application/json',
				'Authorization' => self::get_api_key(),
			),
		);

		$response = wp_remote_request( $endpoint, $request_args );

		if ( 200 !== $response['response']['code'] ) {
			return false;
		}

		return json_decode( $response['body'] );
	}

	/**
	 * Search User
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param string $email The user email.
	 *
	 * @return object
	 */
	public static function search_for_user( $email ) {
		$endpoint = self::get_base_url() . "/customers/?email={$email}";

		$request_args = array(
			'method'  => 'GET',
			'headers' => array(
				'Content-Type'  => 'application/json',
				'Authorization' => self::get_api_key(),
			),
		);

		$reponse = wp_remote_request( $endpoint, $request_args );

		return $reponse;
	}

	/**
	 * Get User History
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param string $user_id The Profitwell user ID.
	 *
	 * @return void
	 */
	public static function get_user_history( $user_id ) {
		$endpoint = self::get_base_url() . "/users/{$user_id}";

		$request_args = array(
			'method'  => 'GET',
			'headers' => array(
				'Content-Type'  => 'application/json',
				'Authorization' => self::get_api_key(),
			),
		);

		$reponse = wp_remote_request( $endpoint, $request_args );

		return $reponse;
	}
}
