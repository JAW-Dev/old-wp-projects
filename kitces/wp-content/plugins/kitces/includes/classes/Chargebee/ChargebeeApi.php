<?php
/**
 * Chargebee API
 *
 * @package    Kitces
 * @subpackage Kitces/Includes/Classes/Chargebee
 * @author     Objectiv
 * @copyright  Copyright (c) 2020, Objectiv
 * @license    GPL-2.0
 * @since      1.0.0
 */

namespace Kitces\Includes\Classes\Chargebee;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

/**
 * Class_Name
 *
 * @author Jason Witt
 * @since  1.0.0
 */
class ChargebeeApi {

	/**
	 * Initialize the class
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function __construct() {
		\ChargeBee_Environment::configure( KITCES_CHARGEBEE_SITE, KITCES_CHARGEBEE_API_KEY );
	}

	/**
	 * Get User By Email
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param string $email The email of the user to check.
	 *
	 * @return mixed
	 */
	public function get_user_by_email( $email ) {
		$response = '';
		$request  = \ChargeBee_Customer::all(
			array(
				'email[is]' => $email,
			)
		);

		foreach ( $request as $entry ) {
			$response = $entry->customer();
		}

		return $response;
	}

	/**
	 * Get User By ID
	 *
	 * @author Eldon
	 * @since  1.0.0
	 *
	 * @param string $id The id of the user to check.
	 *
	 * @return mixed
	 */
	public function get_user_by_id( $id ) {
		$response = '';
		$request  = \ChargeBee_Customer::all(
			array(
				'id[is]' => $id,
			)
		);

		foreach ( $request as $entry ) {
			$response = $entry->customer();
		}

		return $response;
	}

	/**
	 * Get Credit Card
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param string $id The customer Chargebee ID.
	 *
	 * @return object
	 */
	public function get_credit_card( $id ) {
		$response = \ChargeBee_Card::retrieve( $id );
		$card     = ! empty( $response ) && method_exists( $response, 'card' ) ? $response->card() : '';

		return $card;
	}

	/**
	 * Create Portal
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param array $params The param for the portal.
	 *
	 * @return object
	 */
	public function create_protal( $params ) {
		$response = \ChargeBee_PortalSession::create( $params );

		return $response;
	}

	/**
	 * Get customer subscription
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param string $id The customer ID.
	 *
	 * @return object
	 */
	public function get_customer_subscription( $id ) {
		$response = \ChargeBee_Subscription::subscriptionsForCustomer( $id );

		return $response;
	}

	/**
	 * Cancel Subscription
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return json
	 */
	public function cancel_subscription( $id ) {
		$response = ChargeBee_Subscription::cancel(
			$id,
			array( 'endOfTerm' => true )
		);

		return $response;
	}

	/**
	 * Get Invoice Via ID
	 *
	 * @author Eldon Yoder
	 * @since  1.0.0
	 */

	public function retrieve_invoice( $id ) {
		$response = \ChargeBee_Invoice::retrieve( $id );
		$invoice  = $response->invoice();

		return $invoice;
	}
}
