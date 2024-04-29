<?php
/**
 * New Registration
 *
 * @package    FP_Core/
 * @subpackage FP_Core/Includes/RCP
 * @author     Objectiv
 * @copyright  Copyright (c) 2020, Objectiv
 * @license    GPL-2.0
 * @version    1.0.0
 */

namespace FP_Core\RCP;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

/**
 * New Registration
 *
 * @author Jason Witt
 * @since  1.0.0
 */
class NewRegistration {

	/**
	 * Initialize the class
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function __construct() {
		$this->hooks();
	}

	/**
	 * Hooks
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function hooks() {
		add_action( 'rcp_form_errors', [ $this, 'check_required_fields' ], 10 );
		add_action( 'rcp_form_processing', [ $this, 'email_referred' ], 10, 2 );
	}

	/**
	 * Check Required Fields
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param array $posted The posted fields data.
	 *
	 * @return void
	 */
	public function check_required_fields( array $posted ) {
		if ( is_user_logged_in() ) {
			return;
		}

		if ( empty( $posted['rcp_user_first'] ) ) {
			rcp_errors()->add( 'invalid_first_name', __( 'Please enter your first name', 'rcp' ), 'register' );
		}

		if ( empty( $posted['rcp_user_last'] ) ) {
			rcp_errors()->add( 'invalid_last_name', __( 'Please enter your last name', 'rcp' ), 'register' );
		}
	}

	/**
	 * Email Referred
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param array $posted  The posted fields data.
	 * @param int   $user_id The user ID.
	 *
	 * @return void
	 */
	public function email_referred( array $posted, int $user_id ) {
		$member_first_name = '';
		$member_last_name  = '';
		$member_email      = '';
		$referred_name     = '';
		$referred_email    = '';

		if ( isset( $posted['rcp_user_first'] ) ) {
			$member_first_name = sanitize_text_field( wp_unslash( $posted['rcp_user_first'] ) );
		}

		if ( isset( $posted['rcp_user_last'] ) ) {
			$member_last_name = sanitize_text_field( wp_unslash( $posted['rcp_user_last'] ) );
		}

		if ( isset( $posted['rcp_user_email'] ) ) {
			$member_email = sanitize_text_field( wp_unslash( $posted['rcp_user_email'] ) );
		}

		$member_name = $member_first_name . ' ' . $member_last_name;

		if ( isset( $posted['referred_name'] ) ) {
			$referred_name = sanitize_text_field( wp_unslash( $posted['referred_name'] ) );
		}

		if ( isset( $posted['referred_email'] ) ) {
			$referred_email = sanitize_text_field( wp_unslash( $posted['referred_email'] ) );
		}

		if ( isset( $posted['company_name'] ) ) {
			update_user_meta( $user_id, 'company_name', sanitize_text_field( $posted['company_name'] ) );
		}

		if ( empty( $referred_name ) && empty( $referred_email ) ) {
			return;
		}

		// Email setup.
		$to      = 'support@fppathfinder.com';
		$from    = 'fpPathfinder';
		$headers = array( 'Content-Type: text/html; charset=UTF-8', "From: {$from} <no-reply@fppathfinder.com>" );
		$body    = "<p>New member $member_name: $member_email was referred by $referred_name: $referred_email</p>";

		wp_mail( $to, 'New Member With Referral', $body, $headers );
	}
}
