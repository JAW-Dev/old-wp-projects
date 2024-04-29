<?php

namespace FP_Core\Crms\Apis;

use FP_Core\Crms\XLR8\OAuthController;
use FP_Core\Utilities as CoreUtilities;
use FP_Core\InteractiveLists\Tables\LinkShare;

class Xlr8API {
	/**
	 * Subset
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @var string|boolean
	 */
	protected $subset = false;

	public function __construct() {}

	static public function get_base_url(): string {
		global $wpdb;

		$share_key  = sanitize_text_field( wp_unslash( $_GET['sh'] ?? '' ) );
		$table      = LinkShare::get_resource_share_link_table_name();
		$entry      = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $table WHERE share_key = %s", $share_key ), ARRAY_A ); // phpcs:ignore
		$share_link = $entry['advisor_user_id'] ?? '';
		$advisor_id = ! empty( $share_link ) ? $share_link : get_current_user_id();
		$tokens     = get_user_meta( $advisor_id, 'xlr8_tokens', true );

		if ( gettype( $tokens ) === 'string' ) {
			$tokens = json_decode( $tokens );
		}

		$instance = $tokens->instance_url;

		return "$instance/services/data/v50.0/";
	}

	static public function get_headers( int $user_id ): array {
		$bearer_token = ( new OAuthController() )->get_user_token( $user_id );

		return array(
			'Content-Type'  => 'application/json',
			'AUTHORIZATION' => 'Bearer ' . $bearer_token,
		);
	}

	static public function create_note( $user_id, $contact_id, $note, $account_id = '' ) {
		$body   = [
			'XLR8CS__Note__c'              => $note,
			'XLR8CS__Contact__c'           => $contact_id,
			'XLR8CS__Short_Description__c' => get_the_title(),
			'XLR8CS__Entity__c'            => $account_id,
		];

		try {
			$endpoint     = self::get_base_url() . 'sobjects/XLR8CS__Critical_Note__c';
			$request_args = array(
				'method'  => 'POST',
				'headers' => self::get_headers( $user_id ),
				'body'    => json_encode( $body ),
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
			$endpoint = self::get_base_url() . 'sobjects/Task';

			$date     = new \DateTime();
			$now      = $date->format( 'Y-m-d' );
			$interval = new \DateInterval( 'P1M' );
			$date->add( $interval );
			$month = $date->format( 'Y-m-d' );

			$request_args = array(
				'method'  => 'POST',
				'headers' => self::get_headers( $user_id ),
				'body'    => json_encode(
					array(
						'WhoID'       => $contact_id,
						'Subject'     => $note['title'] ?? '',
						'Description' => $note['lines'] ?? '',
						'Status'      => 'Not Started',
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

	static public function get_contact( $user_id, $contact_id ) {
		try {
			$endpoint     = self::get_base_url() . "query/?q=SELECT+name+from+Contact+where+id+%3d+'{$contact_id}'";
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
				$endpoint     = self::get_base_url() . "parameterizedSearch/?q=${query}&sobject=Contact&Contact.fields=Id,Name,Accountid";
				$request_args = array(
					'method'  => 'GET',
					'headers' => self::get_headers( $user_id ),
				);

				$response = wp_remote_request( $endpoint, $request_args );

				CoreUtilities::response_errors( $response );

				$body = json_decode( $response['body'] );

				if ( ! property_exists( $body, 'searchRecords' ) || empty( $body->searchRecords ) ) {
					return array();
				}

				$body_contacts = $body->searchRecords;

				foreach ( $body_contacts as $body_contact ) {
					array_push( $contacts, $body_contact );
				}

				return $body->searchRecords;
			}

			return $contacts;
		} catch ( \Exception $e ) {
			error_log( __CLASS__ . '::' . __FUNCTION__ . '() ' . $e->getMessage() );
			return array();
		}
	}
}
