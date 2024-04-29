<?php

namespace FP_Core\Crms\Apis;

use FP_Core\Crms\Wealthbox\OAuthController;
use FP_Core\Utilities as CoreUtilities;

class WealthboxAPI {
	public function __construct() {}

	static public function get_base_url(): string {
		return 'https://api.crmworkspace.com';
	}

	static public function get_headers( int $user_id ): array {
		$bearer_token = OAuthController::get_user_token( $user_id );

		return array(
			'Content-Type'  => 'application/json',
			'AUTHORIZATION' => 'Bearer ' . $bearer_token,
		);
	}

	static public function create_note( int $user_id, int $contact_id, string $note ) {
		try {
			$endpoint     = self::get_base_url() . '/v1/notes';
			$request_args = array(
				'method'  => 'POST',
				'headers' => self::get_headers( $user_id ),
				'body'    => json_encode(
					array(
						'content'   => $note,
						'linked_to' => array(
							array(
								'id'   => $contact_id,
								'type' => 'Contact',
							),
						),
					)
				),
			);

			$response = wp_remote_request( $endpoint, $request_args );

			CoreUtilities::response_errors( $response );

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
	public static function create_task( $user_id, $contact_id, $note ) {
		try {
			$endpoint = self::get_base_url() . '/v1/tasks';

			$date     = new \DateTime();
			$interval = new \DateInterval( 'P1M' );
			$date->add( $interval );
			$month = $date->format( 'Y-m-d H:i:s' );

			$request_args = array(
				'method'  => 'POST',
				'headers' => self::get_headers( $user_id ),
				'body'    => json_encode(
					array(
						'name'        => $note['title'] ?? '',
						'due_date'    => $month,
						'description' => $note['lines'] ?? '',
						'linked_to'   => array(
							array(
								'id'   => $contact_id,
								'type' => 'Contact',
							),
						),
					)
				),
			);

			$response = wp_remote_request( $endpoint, $request_args );

			CoreUtilities::response_errors( $response );

			return $response;
		} catch ( \Exception $e ) {
			error_log( __CLASS__ . '::' . __FUNCTION__ . '() ' . $e->getMessage() );
			return array();
		}
	}

	static public function get_contact( int $user_id, int $contact_id ) {
		try {
			$endpoint     = self::get_base_url() . "/v1/contacts?id={$contact_id}";
			$request_args = array(
				'method'  => 'GET',
				'headers' => self::get_headers( $user_id ),
			);

			$response = wp_remote_request( $endpoint, $request_args );

			CoreUtilities::response_errors( $response );

			return $response;
		} catch ( \Exception $e ) {
			error_log( __CLASS__ . '::' . __FUNCTION__ . '() ' . $e->getMessage() );
			return array();
		}
	}

	static public function search_contacts( int $user_id, string $query ): array {
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
				$endpoint     = self::get_base_url() . "/v1/contacts?name={$query}";
				$request_args = array(
					'method'  => 'GET',
					'headers' => self::get_headers( $user_id ),
				);

				$response = wp_remote_request( $endpoint, $request_args );

				CoreUtilities::response_errors( $response );

				$body = json_decode( $response['body'] );

				if ( ! property_exists( $body, 'contacts' ) || empty( $body->contacts ) ) {
					return array();
				}

				$body_contacts = $body->contacts;

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
