<?php
/**
 * Chargebee Customer.
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
 * Chargebee Customer.
 *
 * @author Objectiv
 * @since  1.0.0
 */
class Customer {

	/**
	 * Initialize the class
	 *
	 * @author Objectiv
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function __construct() {
		add_action( 'wp_ajax_email_lookup', array( $this, 'email_lookup' ) );
		add_action( 'wp_ajax_nopriv_email_lookup', array( $this, 'email_lookup' ) );
	}

	/**
	 * Email Lookup
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function email_lookup() {
		$nonce = ! empty( $_POST['nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';

		if ( ! isset( $nonce ) || ! wp_verify_nonce( $nonce, 'chargebee_email_lookup' ) ) {
			exit;
		}

		$email = ! empty( $_POST['email'] ) ? sanitize_text_field( wp_unslash( $_POST['email'] ) ) : '';
		$plan  = ! empty( $_POST['plan'] ) ? sanitize_text_field( wp_unslash( $_POST['plan'] ) ) : '';
		$modal = ! empty( $_POST['modal'] ) ? sanitize_text_field( wp_unslash( $_POST['modal'] ) ) : '';
		$iic   = ! empty( $_POST['iic'] ) ? sanitize_text_field( wp_unslash( $_POST['iic'] ) ) : '';

		// Bail if empty.
		if ( empty( $email ) ) {
			return;
		}

		$chargebee   = new ChargebeeApi();
		$is_customer = $chargebee->get_user_by_email( $email );

		if ( ! empty( $is_customer ) ) {
			echo 'true';
			wp_die();
		}

		echo wp_json_encode(
			array(
				'email' => $email,
				'modal' => $modal,
				'plan'  => $plan,
				'iic'   => $iic,
			)
		);
		wp_die();
	}
}
