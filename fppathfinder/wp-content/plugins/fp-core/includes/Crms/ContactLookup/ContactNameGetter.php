<?php
/**
 * Contact Name Getter
 *
 * @package    FP_Core/
 * @subpackage FP_Core/Included/Crms
 * @author     Objectiv
 * @copyright  Copyright (c) 2020, Objectiv
 * @license    GPL-2.0
 * @version    1.0.0
 */

namespace FP_Core\Crms\ContactLookup;

use FP_Core\Crms\Utilities;

class ContactNameGetter {

	/**
	 * Initialize the class
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function __construct() {
		add_filter( 'interactive_checklist_client_name', __CLASS__ . '::get_client_name_filter' );
	}

	static public function get_client_name_filter( string $client_name ) {
		if ( $client_name ) {
			return $client_name;
		}

		$user_id = get_current_user_id();

		if ( ! $user_id ) {
			return $client_name;
		}

		$crm = Utilities::get_active_crm();
		$arg = '';

		switch ( $crm ) {
			case 'redtail':
				$arg = 'contact_id';
				break;
			case 'wealthbox':
				$arg = 'wbcid';
				break;
			default:
				$arg = 'crm_contact_id';
		}

		$contact_id = $_GET[ $arg ] ?? false;

		if ( ! $contact_id ) {
			return $client_name;
		}

		return self::get_contact_name( $user_id, $contact_id );
	}

	/**
	 * Get Contact Name
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param int $user_id    The current user ID.
	 * @param int $contact_id The contact ID.
	 *
	 * @return void
	 */
	public static function get_contact_name( $user_id, $contact_id ) {
		$active_crm = get_user_meta( $user_id, 'current_active_crm', true );
		$response   = self::get_crm_contact( $active_crm, $contact_id, $user_id );

		if ( is_wp_error( $response ) ) {
			return '';
		}

		if ( ! $response || 200 !== $response['response']['code'] ) {
			return '';
		}

		$contact_details = json_decode( $response['body'] );

		return self::get_crm_contact_name( Utilities::get_active_crm(), $contact_details );
	}

	/**
	 * Get CRM Contact
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param string $slug       The CRM slug.
	 * @param int    $contact_id The contact ID.
	 * @param int    $user_id    The user ID.
	 *
	 * @return void
	 */
	public static function get_crm_contact( $slug, $contact_id, $user_id ) {
		$crm       = ucfirst( str_replace( '_', '', $slug ) );
		$classname = 'FP_Core\\Crms\\Apis\\' . $crm . 'API';

		return $classname::get_contact( $user_id, $contact_id );
	}

	/**
	 * Get Contact Name
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public static function get_crm_contact_name( $slug, $contact_details ) {
		switch ( $slug ) {
			case 'redtail':
				$first_name = $contact_details->ContactRecord->Firstname; //phpcs:ignore
				$last_name  = $contact_details->ContactRecord->Lastname; //phpcs:ignore

				return join( ' ', array_filter( array( $first_name, $last_name ) ) );
				break;
			case 'wealthbox':
				foreach ( $contact_details as $contact_detail ) {
					foreach ( $contact_detail as $contact ) {
						if ( empty( $contact->name ) ) {
							return '';
						}

						return $contact->name;
					}
				}
				break;
			case 'salesforce':
			case 'xlr8':
				foreach ( $contact_details as $contact_detail ) {
					if ( ! is_array( $contact_detail ) ) {
						continue;
					}

					foreach ( $contact_detail as $contact ) {
						if ( empty( $contact->Name ) ) {
							return '';
						}

						return $contact->Name;
					}
				}
				break;
			default:
				return '';
		}
	}
}
