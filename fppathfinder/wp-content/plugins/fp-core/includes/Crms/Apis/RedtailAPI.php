<?php

namespace FP_Core\Crms\Apis;

use FP_Core\Crms\Redtail\RedtailUserIntegration;
use FP_Core\Utilities as CoreUtilities;
use FP_Core\Crms\Utilities;

class RedtailAPI {
	public function __construct() {}

	static public function get_base_url(): string {
		$dev  = 'https://dev.api2.redtailtechnology.com/crm/v1/rest/';
		$prod = 'https://api2.redtailtechnology.com/crm/v1/rest/';

		return RedtailUserIntegration::is_dev_mode() ? $dev : $prod;
	}

	static public function get_authorization_header( int $user_id ) {
		if ( empty( $user_id ) ) {
			$user_id = get_current_user_id();
		}

		$redtail_userkey = Utilities::get_crm_token( $user_id );
		$api_key         = ! empty( $redtail_userkey ) ? self::get_api_key() : 0;

		if ( $api_key || ! empty( $redtail_userkey ) ) {
			return 'Userkeyauth ' . base64_encode( "{$api_key}:{$redtail_userkey}" );
		}

		return '';
	}

	static public function get_api_key() {
		$prod_api_key = function_exists( 'get_field' ) ? get_field( 'redtail_api_key', 'options' ) : '';
		$dev_api_key  = function_exists( 'get_field' ) ? get_field( 'redtail_dev_api_key', 'options' ) : '';

		return RedtailUserIntegration::is_dev_mode() ? $dev_api_key : $prod_api_key;
	}

	static public function create_note( $user_id, $contact_id, $note, $count = 1 ) {
		try {
			$endpoint     = self::get_base_url() . "contacts/{$contact_id}/notes/0";
			$request_args = array(
				'method'  => 'PUT',
				'headers' => array(
					'Content-Type'  => 'application/json',
					'Authorization' => self::get_authorization_header( $user_id ),
				),
				'body'    => json_encode( array( 'Note' => $note ) ),
			);

			$response = wp_remote_request( $endpoint, $request_args );

			if ( ( ! $response || 200 !== $response['response']['code'] ) && $count <= 3 ) {
				self::create_note( $user_id, $contact_id, $note, $count++ );
			}

			if ( $count > 3) {
				CoreUtilities::response_errors( $response );
			}

			return $response;
		} catch ( \Exception $e ) {
			error_log( __CLASS__ . '::' . __FUNCTION__ . '() ' . $e->getMessage() );
			return array();
		}
	}

	static public function get_contact( int $user_id, int $contact_id, $count = 1 ) {
		try {
			$endpoint     = self::get_base_url() . "contacts/{$contact_id}/master";
			$request_args = array(
				'method'  => 'GET',
				'headers' => array(
					'Content-Type'  => 'application/json',
					'Authorization' => self::get_authorization_header( $user_id ),
				),
			);

			$response = wp_remote_request( $endpoint, $request_args );

			if ( ( ! $response || 200 !== $response['response']['code'] ) && $count <= 3 ) {
				self::get_contact( $user_id, $contact_id, $count++ );
			}

			if ( $count > 3) {
				CoreUtilities::response_errors( $response );
			}

			return $response;
		} catch ( \Exception $e ) {
			error_log( __CLASS__ . '::' . __FUNCTION__ . '() ' . $e->getMessage() );
			return array();
		}
	}

	/**
	 * Send Activity
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public static function create_task( $user_id, $contact_id, $note, $count = 1 ) {
		try {
			$endpoint = self::get_base_url() . 'calendar/activities/0';

			$date     = new \DateTime();
			$interval = new \DateInterval( 'P1M' );
			$now      = strtotime( $date->format( 'Y-m-d H:i:s' ) ) . '000';

			$body = json_encode(
				array(
					'ClientID'   => $contact_id,
					'StartDate'  => '/Date(' . strval( $now ) . ')/',
					'EndDate'    => '/Date(' . strval( $now ) . ')/',
					'Subject'    => $note['title'] ?? '',
					"Note"       => $note['lines'] ?? '',
					'TypeID'     => 1,
					'CategoryID' => 2,
				)
			);

			$request_args = array(
				'method'  => 'PUT',
				'body'    => $body,
				'headers' => array(
					'Content-Type'  => 'application/json',
					'Authorization' => self::get_authorization_header( $user_id ),
				),
			);

			$response = wp_remote_request( $endpoint, $request_args );

			if ( is_wp_error( $response ) ) {
				return [];
			}

			if ( ( ! $response || 200 !== $response['response']['code'] ) && $count <= 3 ) {
				self::create_task( $user_id, $contact_id, $note, $count++ );
			}

			if ( $count > 3) {
				CoreUtilities::response_errors( $response );
			}

			return $response;
		} catch ( \Exception $e ) {
			error_log( __CLASS__ . '::' . __FUNCTION__ . '() ' . $e->getMessage() );
			return [];
		}
	}

	static public function search_contacts( $user_id, $query, $count = 1 ): array {
		try {
			if ( empty( $user_id ) || ! is_int( $user_id ) ) {
				$user_id = get_current_user_id();
			}

			$advisor_id = isset( $_REQUEST['a'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['a'] ) ) : 0;

			if ( $advisor_id ) {
				$user_id = $advisor_id;
			}

			$words    = explode( ' ', $query );
			$contacts = [];

			foreach ( $words as $word ) {
				$endpoint     = self::get_base_url() . "contacts/search/beginswith?value={$word}";
				$request_args = array(
					'method'  => 'GET',
					'headers' => array(
						'Content-Type'  => 'application/json',
						'Authorization' => self::get_authorization_header( $user_id ),
					),
				);

				$response = wp_remote_request( $endpoint, $request_args );

				if ( ( ! $response || 200 !== $response['response']['code'] ) && $count <= 3 ) {
					self::create_task( $user_id, $query, $count++ );
				}

				if ( $count > 3 ) {
					CoreUtilities::response_errors( $response );
				}

				$body = json_decode( $response['body'] );

				if ( ! property_exists( $body, 'Contacts' ) || empty( $body->Contacts ) ) { // phpcs:ignore
					return array();
				}

				$body_contacts = $body->Contacts;

				foreach ( $body_contacts as $body_contact ) {
					array_push( $contacts, $body_contact );
				}
			}

			return $contacts;

		} catch ( \Exception $e ) {
			error_log( __CLASS__ . '::' . __FUNCTION__ . '() ' . $e->getMessage() );
			return array();
		}
	}
}
