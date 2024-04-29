<?php
/**
 * Chargebee Credit Card.
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
 * Chargebee Credit Card.
 *
 * @author Objectiv
 * @since  1.0.0
 */
class CreditCard {

	/**
	 * Initialize the class
	 *
	 * @author Objectiv
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function __construct() {
		\ChargeBee_Environment::configure( KITCES_CHARGEBEE_SITE, KITCES_CHARGEBEE_API_KEY );
	}

	/**
	 * Get Member Chargebee ID
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return string
	 */
	public function get_member_chargebee_id() {
		return get_user_meta( get_current_user_id(), 'memb_%CHARGEBEE_ID%', true );
	}

	/**
	 * Is Chargebee Member
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return boolean
	 */
	public function is_chargebee_member() {
		return ! empty( $this->get_member_chargebee_id() ) ? true : false;
	}

	/**
	 * Get Card Info
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return object
	 */
	public function get_card_info() {
		try {
			$tansient_name = '_' . $this->get_member_chargebee_id() . '_chargebee_card_info';
			$transient     = get_transient( $tansient_name );

			if ( ! empty( $transient ) ) {
				return $transient;
			} else {
				$result = ! empty( $this->get_member_chargebee_id() ) ? \ChargeBee_Card::retrieve( $this->get_member_chargebee_id() ) : '';

				if ( ! empty( $result ) && method_exists( $result, 'card' ) ) {
					set_transient( $tansient_name, $result->card(), 3600 );
				}
			}

			return ! empty( $result ) && method_exists( $result, 'card' ) ? $result->card() : '';
		} catch ( \ChargeBee_InvalidRequestException $e ) {}
	}

	/**
	 * Has Card Info
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return boolean
	 */
	public function has_card_info() {
		return ! empty( $this->get_card_info() ) ? true : false;
	}

	/**
	 * Get Card Expiry Date
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return string
	 */
	public function get_card_expiry_date() {
		$card  = ! empty( $this->get_card_info() ) ? $this->get_card_info() : '';
		$month = ! empty( $card ) ? $card->expiryMonth : ''; // phpcs:ignore
		$year  = ! empty( $card ) ? $card->expiryYear : ''; // phpcs:ignore

		return ! empty( $month ) && ! empty( $year ) ? $month . '-' . $year : '';
	}

	/**
	 * Card to Expire
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return boolean
	 */
	public function card_to_expire() {
		if ( ! empty( $this->has_card_info() ) ) {
			$card_expiry = strtotime( '01-' . $this->get_card_expiry_date() );

			return $card_expiry < strtotime( '31 days' );
		}

		return '';
	}

	/**
	 * Is Card to Expire
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return boolean
	 */
	public function is_card_to_expire() {
		return ! empty( $this->card_to_expire() ) ? true : false;
	}

	/**
	 * Get Portal Session
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return object
	 */
	public function get_portal_session() {
		if ( empty( $this->get_member_chargebee_id() ) ) {
			return '';
		}

		$result = \ChargeBee_PortalSession::create(
			array(
				'redirectUrl' => 'https://kitces.com/member',
				'customer'    => array( 'id' => $this->get_member_chargebee_id() ),
			)
		);

		return $result->portalSession();
	}

	/**
	 * Get Portal Link
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return string
	 */
	public function get_portal_access_url() {
		return ! empty( $this->get_portal_session() ) ? $this->get_portal_session()->accessUrl : '';
	}

	/**
	 * Customer Subscriptions
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return boolean
	 */
	public function is_active_subscription() {
		$results = ! empty( $this->get_member_chargebee_id() ) ? \ChargeBee_Subscription::subscriptionsForCustomer( $this->get_member_chargebee_id() ) : '';
		$active  = false;

		if ( ! empty( $results ) ) {
			foreach ( $results as $entry ) {
				$subscription = $entry->subscription();
				$active       = isset( $subscription->status ) && $subscription->status === 'active' ? true : false;
			}
		}

		return $active;
	}
}
