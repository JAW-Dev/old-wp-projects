<?php

use Kitces\Includes\Classes\User\AcSync;
use Kitces\Includes\Classes\Chargebee\ChargebeeApi;
use Kitces\Includes\Classes\Facebook\ConversionAPI;

/**
 * No Access
 *
 * @package    Kitces
 * @author     Objectiv
 * @copyright  Copyright (c) 2020, Objectiv
 * @license    GPL-2.0
 * @version    1.0.0
 * @since      1.0.0
 */

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

if ( ! function_exists( 'mk_get_chargebee_user' ) ) {
	function mk_get_chargebee_user( $email = null ) {

		$customer = false;

		// Default to current user email
		if ( empty( $email ) && is_user_logged_in() ) {
			$user  = wp_get_current_user();
			$email = $user->user_email;
		}

		if ( empty( $email ) ) {
			return $customer;
		}

		$chargebee = new ChargebeeApi();
		$customer  = $chargebee->get_user_by_email( $email );

		return $customer;
	}
}

if ( ! function_exists( 'mk_get_chargebee_user_email_from_invoice_id' ) ) {
	function mk_get_chargebee_user_email_from_invoice_id( $invoice_id = null ) {
		$email = '';
		if ( ! empty( $invoice_id ) ) {
			$chargebee = new ChargebeeApi();
			$order     = $chargebee->retrieve_invoice( $invoice_id );

			if ( ! empty( $order ) && is_object( $order ) ) {
				$customer_id = $order->customerId;
				$customer    = $chargebee->get_user_by_id( $customer_id );

				if ( ! empty( $customer ) && is_object( $customer ) ) {
					$email = $customer->email;
				}
			}
		}

		return $email;
	}
}

if ( ! function_exists( 'mk_get_chargebee_customer_subscription' ) ) {
	function mk_get_chargebee_customer_subscription( $email = null ) {

		$customer     = mk_get_chargebee_user( $email );
		$subscription = null;

		if ( $customer ) {
			$chargebee = new ChargebeeApi();
			$response  = $chargebee->get_customer_subscription( $customer->id );

			if ( ! empty( $response ) ) {
				// For the life of me I can't figure out how to get this without doing this sillyness
				$jsonified = $response->toJson();
				$back      = json_decode( $jsonified );

				if ( is_array( $back ) && array_key_exists( 0, $back ) && is_object( $back[0] ) ) {
					$response_back = $back[0];
					$subscription  = $response_back->subscription;
				}
			}
		}

		return $subscription;
	}
}

if ( ! function_exists( 'mk_get_customer_subscription_expiration' ) ) {
	function mk_get_customer_subscription_expiration( $date_format = 'Y-m-d' ) {
		$initial_user_expiration = kitces_members_get_meta( 'EXPIRATION_DATE', get_current_user_id() );
		$raw_user_exp_date       = trim( $initial_user_expiration );
		$expiry_date             = strtotime( $raw_user_exp_date );
		$current_date            = strtotime( 'now' );
		$user_expiration         = date( $date_format, $expiry_date );
		$user_expiration_check   = date( 'Y-m-d', $expiry_date );
		$expire_date_title       = 'Renewal:';
		$expire_date_title_class = '';

		// $user_outdated = false;
		if ( $user_expiration_check !== '1970-01-01' && $expiry_date < $current_date ) {
			$expire_date_title       = 'Expired:';
			$expire_date_title_class = 'expired';
		}

		if ( $user_expiration_check === '1970-01-01' ) {
			$user_expiration = 'No Expiration';
		}

		if ( kitces_is_valid_reader_member() ) {
			$user_expiration         = null;
			$expire_date_title       = null;
			$expire_date_title_class = null;
		}

		return array(
			'user_expiration'         => $user_expiration,
			'expire_date_title'       => $expire_date_title,
			'expire_date_title_class' => $expire_date_title_class,
		);
	}
}

if ( ! function_exists( 'mk_fb_track_conversion' ) ) {
	function mk_fb_track_conversion( $email = null, $product_id = null, $price = '0.00', $quantity = 1, $conversion_url = '' ) {
		$api = new ConversionAPI();
		$api->track_conversion( $email, $product_id, $price, $quantity, $conversion_url );
	}
}

if ( ! function_exists( 'mk_get_url_param' ) ) {
	function mk_get_url_param( $param = null ) {
		if ( isset( $_GET[ $param ] ) && ! empty( $_GET[ $param ] ) ) {
			return $_GET[ $param ];
		}

		return false;
	}
}


if ( ! function_exists( 'mk_ac_sync_member_non_ajax' ) ) {
	function mk_ac_sync_member_non_ajax( $user_id = null ) {
		$acsync = new AcSync();
		$acsync->sync_member_non_ajax( $user_id );
	}
}

if ( ! function_exists( 'mk_key_value' ) ) {
	function mk_key_value( $incoming_array = null, $key = null ) {
		if ( is_array( $incoming_array ) && ! empty( $key ) ) {
			if ( array_key_exists( $key, $incoming_array ) && ! empty( $incoming_array[ $key ] ) ) {
				return $incoming_array[ $key ];
			}
		}
		return false;
	}
}
