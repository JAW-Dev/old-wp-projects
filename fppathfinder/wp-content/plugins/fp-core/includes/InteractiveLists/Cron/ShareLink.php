<?php
/**
 * Share Link Cron
 *
 * @package    FP_Core/
 * @subpackage FP_Core/InteractiveLists/Cron
 * @author     Objectiv
 * @copyright  Copyright (c) 2020, Objectiv
 * @license    GPL-2.0
 * @version    1.0.0
 */

namespace FP_Core\InteractiveLists\Cron;

use FP_Core\InteractiveLists\Tables\LinkShare;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

/**
 * Share Link Cron
 *
 * @author Jason Witt
 * @since  1.0.0
 */
class ShareLink {

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
		$this->test_email();
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
		add_action( 'resource_links_update', array( $this, 'expired_cron_job' ) );
		add_action( 'resource_links_mid_update', array( $this, 'mid_cron_job' ) );
	}

	/**
	 * Test Email
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function test_email() {
		global $wpdb;

		$table         = LinkShare::get_resource_share_link_table_name();
		$entries       = $wpdb->get_results( "SELECT * FROM $table", ARRAY_A ); // phpcs:ignore
		$test_email    = isset( $_GET['test-sh-email'] ) ? sanitize_text_field( wp_unslash( $_GET['test-sh-email'] ) ) : '';
		$test_email_id = isset( $_GET['test-sh-email-id'] ) ? sanitize_text_field( wp_unslash( $_GET['test-sh-email-id'] ) ) : '';

		if ( empty( $entries ) || empty( $test_email ) ) {
			return;
		}

		foreach ( $entries as $entry ) {
			$sh_id = $entry['share_key'];

			if ( $sh_id !== $test_email_id ) {
				continue;
			}

			// Email setup.
			$advisor     = get_userdata( $entry['advisor_user_id'] );
			$client_name = $entry['client_name'];
			$resource    = $entry['resource_id'];

			$to      = $advisor->user_email;
			$from    = 'fpPathfinder';
			$headers = array( 'Content-Type: text/html; charset=UTF-8', "From: {$from} <no-reply@fppathfinder.com>" );

			$args = [
				'client_name' => $client_name,
				'resource'    => $resource,
			];

			if ( ! empty( $test_email ) && $test_email === 'expired' ) {
				$this->maybe_send_expired_email( $to, $headers, $args );
			}

			if ( ! empty( $test_email ) && $test_email === 'mid' ) {
				$this->maybe_send_mid_email( $to, $headers, $args );
			}
		}
	}

	/**
	 * Mid Cron Job
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function mid_cron_job() {
		global $wpdb;

		$table   = LinkShare::get_resource_share_link_table_name();
		$entries = $wpdb->get_results( "SELECT * FROM $table", ARRAY_A ); // phpcs:ignore

		if ( empty( $entries ) ) {
			return;
		}

		foreach ( $entries as $entry ) {
			$update     = false;
			$expiration = ! empty( $entry['mid_time'] ) ? strtotime( $entry['mid_time'] ) : '';
			$date       = new \DateTime();
			$match_date = new \DateTime( $entry['mid_time'] );
			$interval   = $date->diff( $match_date );

			if ( $entry['mid_time_notice'] === '0' && $interval->days === 0 ) {
				$update = true;
			}

			// Email setup.
			$advisor     = get_userdata( $entry['advisor_user_id'] );
			$client_name = $entry['client_name'];
			$resource    = $entry['resource_id'];

			if ( empty( $advisor ) ) {
				return;
			}

			$to = ! empty( $advisor ) ? $advisor->user_email : '';

			if ( empty( $to ) ) {
				return;
			}

			$from    = 'fpPathfinder';
			$headers = array( 'Content-Type: text/html; charset=UTF-8', "From: {$from} <no-reply@fppathfinder.com>" );

			$args = [
				'client_name' => $client_name,
				'resource'    => $resource,
			];

			if ( $update === true ) {
				$wpdb->update(
					$table,
					array(
						'mid_time_notice' => 1,
					),
					array( 'id' => $entry['id'] ),
					array(
						'%d',
					),
					array( '%d' )
				);

				$this->maybe_send_mid_email( $to, $headers, $args );
			}
		}
	}

	/**
	 * Cron Job
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function expired_cron_job() {
		global $wpdb;

		$table   = LinkShare::get_resource_share_link_table_name();
		$entries = $wpdb->get_results( "SELECT * FROM $table", ARRAY_A ); // phpcs:ignore

		if ( empty( $entries ) ) {
			return;
		}

		foreach ( $entries as $entry ) {
			$update     = false;
			$expiration = ! empty( $entry['expiration'] ) ? strtotime( $entry['expiration'] ) : '';
			$datetime   = new \DateTime();
			$now        = strtotime( $datetime->format( 'Y-m-d H:i:s' ) );

			if ( $entry['completed'] === '0' && $now > $expiration ) {
				$update = true;
			}

			// Email setup.
			$advisor     = get_userdata( $entry['advisor_user_id'] );
			$client_name = $entry['client_name'];
			$resource    = $entry['resource_id'];

			$to      = ! empty( $advisor ) ? $advisor->user_email : '';
			$from    = 'fpPathfinder';
			$headers = array( 'Content-Type: text/html; charset=UTF-8', "From: {$from} <no-reply@fppathfinder.com>" );

			$args = [
				'client_name' => $client_name,
				'resource'    => $resource,
			];

			if ( $update === true ) {
				$wpdb->update(
					$table,
					array(
						'completed'   => 1,
						'client_name' => '',
					),
					array( 'id' => $entry['id'] ),
					array(
						'%d',
						'%s',
					),
					array( '%d' )
				);

				$this->maybe_send_expired_email( $to, $headers, $args );
			}

			if ( $entry['completed'] === '1' && $now > $expiration ) {
				$wpdb->delete(
					$table,
					array( 'id' => $entry['id'] ),
					array( '%d' )
				);
			}
		}
	}

	/**
	 * Maybe Send Expired Email
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param string $to          Who to send the email to.
	 * @param array  $headers     The email headers.
	 * @param array  $args        The arguments.
	 *
	 * @return void
	 */
	public function maybe_send_expired_email( $to, $headers, $args ) {
		if ( empty( $to ) || empty( $headers) || empty( $args ) ) {
			return;
		}

		$tags = [
			'{client_name}'   => $args['client_name'],
			'{resource_name}' => get_the_title( $args['resource'] ),
		];

		$subject_text = function_exists( 'get_field' ) ? get_field( 'share_link_expired_subject', 'option' ) : '';
		$body_text    = function_exists( 'get_field' ) ? get_field( 'share_link_expired_body', 'option' ) : '';
		$subject      = fp_custom_text_tags( $subject_text, $tags );
		$body         = fp_custom_text_tags( $body_text, $tags );

		if ( strpos( $_SERVER['HTTP_HOST'], 'fppathfinder.com' ) !== false ) {
			add_filter( 'sent_mail_get_setting', array( $this, 'maybe_disable_wpsm' ), 10, 2 );
		}

		$sent_email = wp_mail( $to, $subject, $body, $headers );

		if ( strpos( $_SERVER['HTTP_HOST'], 'fppathfinder.com' ) !== false ) {
			remove_filter( 'sent_mail_get_setting', array( $this, 'maybe_disable_wpsm' ), 10, 2 );
		}

		if ( $sent_email || defined( 'WPSM_AIRPLANE_MODE' ) ) {
			return true;
		}

		return $sent_email;
	}

	/**
	 * Maybe Send Mid Email
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param string $to          Who to send the email to.
	 * @param array  $headers     The email headers.
	 * @param array  $args        The arguments.
	 *
	 * @return void
	 */
	public function maybe_send_mid_email( $to, $headers, $args ) {
		$tags = [
			'{client_name}'   => $args['client_name'],
			'{resource_name}' => get_the_title( $args['resource'] ),
		];

		$subject_text = function_exists( 'get_field' ) ? get_field( 'share_link_fifteen_day_subject', 'option' ) : '';
		$body_text    = function_exists( 'get_field' ) ? get_field( 'share_link_fifteen_day_body', 'option' ) : '';
		$subject      = fp_custom_text_tags( $subject_text, $tags );
		$body         = fp_custom_text_tags( $body_text, $tags );

		if ( strpos( $_SERVER['HTTP_HOST'], 'fppathfinder.com' ) !== false ) {
			add_filter( 'sent_mail_get_setting', array( $this, 'maybe_disable_wpsm' ), 10, 2 );
		}

		$sent_email = wp_mail( $to, $subject, $body, $headers );

		if ( strpos( $_SERVER['HTTP_HOST'], 'fppathfinder.com' ) !== false ) {
			remove_filter( 'sent_mail_get_setting', array( $this, 'maybe_disable_wpsm' ), 10, 2 );
		}

		if ( $sent_email || defined( 'WPSM_AIRPLANE_MODE' ) ) {
			return true;
		}

		return $sent_email;
	}

	/**
	 * Maybe Disable WPSM
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function maybe_disable_wpsm( $value, $setting ) {
		if ( 'enable' === $setting ) {
			return 'no';
		}

		return $value;
	}
}
