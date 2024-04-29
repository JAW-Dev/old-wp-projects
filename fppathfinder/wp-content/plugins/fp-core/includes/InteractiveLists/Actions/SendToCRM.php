<?php
/**
 * Send To CRM
 *
 * @package    Fp_Core/
 * @subpackage Fp_Core/InteractiveLists/Actions
 * @author     Objectiv
 * @copyright  Copyright (c) 2020, Objectiv
 * @license    GPL-2.0
 * @version    1.0.0
 */

namespace FP_Core\InteractiveLists\Actions;

use FP_Core\Utilities\Checklist\ChecklistNotification;
use FP_Core\Crms\Notes\Checklist;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

/**
 * Send To CRM
 *
 * @author Jason Witt
 * @since  1.0.0
 */
class SendToCRM extends SendAbstract {

	/**
	 * Instance
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @var object
	 */
	protected $instance = null;

	/**
	 * Initialize the class
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function __construct() {
		parent::__construct();
	}

	/**
	 * Get Instance
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function get_instance() {
		if ( $this->instance === null ) {
			$this->instance = new SendToCRM();
		}

		return $this->instance;
	}

	/**
	 * Submit Handler
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param bool $no_contact If there is no contact found.
	 *
	 * @return void
	 */
	public function submit_handler( $no_contact = false ) {

		$share_type = sanitize_text_field( wp_unslash( $_GET['ty'] ?? '' ) );

		if ( ! empty( $share_type ) && $share_type !== 'single' ) {
			return;
		}

		if ( empty( $this->is_email_button_submission ) ) {
			return;
		}

		if ( ! empty( $this->is_email_button_submission ) ) {
			unset( $_POST['email_note_button'] );
		}

		$share_link_entry = function_exists( 'fp_get_share_link_db_entry_array' ) ? fp_get_share_link_db_entry_array( $this->db ) : [];

		if ( empty( $share_link_entry ) ) {
			return;
		}

		$entry = $share_link_entry['entry'];

		// The advisor info
		$advisor_id = $entry['advisor_user_id'] ?? '';

		if ( fp_is_feature_active( 'checklists_v_two' ) && fp_is_share_link() ) {
			$advisor_id = isset( $_REQUEST['a'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['a'] ) ) : 0;
		}

		$advisor = get_userdata( $advisor_id );

		// The client info
		$client_name = $entry['client_name'] ?? '';
		$client_id   = $entry['crm_contact_id'] ?? '';
		$account_id  = $entry['account_id'] ?? '';

		// Email setup.
		$from         = $client_name;
		$to           = $advisor->user_email;
		$subject      = 'Completed Client fpPathfinder Resource';
		$headers      = array( 'Content-Type: text/html; charset=UTF-8', "From: {$from} <no-reply@fppathfinder.com>" );
		$body         = $client_name . ' has completed the resource you recently sent to them. Please log into your CRM to view their responses.';
		$tasks        = Checklist::build_tasks( true );
		$attatchement = $this->build_html_note( $client_name );

		if ( ! empty( $tasks ) ) {
			$attatchement .= '<br>';
			foreach ( $tasks as $task ) {
				$attatchement .= '<h4>' . $task['title']. '</h4>';
				$attatchement .= $task['lines'];
			}
		}

		// Send Note
		$send_note = $this->send_note( $entry, $advisor_id, $client_id, $client_name, $to, $headers, $account_id );

		if ( ! $send_note ) {
			ChecklistNotification::add( 'We were unable to send to you advisor\'s notes!', true );
		}

		// Send Tasks
		$send_tasks = $this->send_tasks( $advisor_id, $client_id, $client_name, $to, $headers );

		if ( ! $send_tasks ) {
			ChecklistNotification::add( 'We were unable to send to you advisor\'s tasks!', true );
		}

		// Send Email.
		$sent_email = $this->maybe_send_email( $to, $subject, $body, $headers, $advisor, $attatchement );

		if ( $sent_email ) {
			ChecklistNotification::add( 'The resource was sent to your advisor!', false );

			if ( $share_type === 'single' ) {
				$this->db->update(
					$share_link_entry['table'],
					array(
						'completed' => 1,
					),
					array( 'share_key' => ! empty( $share_link_entry['share_key'] ) ? $share_link_entry['share_key'] : '' ),
					array(
						'%d',
					),
					array( '%s' )
				);
			}
		}
	}
}
