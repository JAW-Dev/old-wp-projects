<?php
/**
 * Send From Client
 *
 * @package    FP_Core/
 * @subpackage Fp_Core/InteractiveLists/Actions
 * @author     Objectiv
 * @copyright  Copyright (c) 2020, Objectiv
 * @license    GPL-2.0
 * @version    1.0.0
 */

namespace FP_Core\InteractiveLists\Actions;

use FP_Core\Crms\Notes\Checklist;
use FP_Core\Utilities\Checklist\ChecklistNotification;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

/**
 * Send From Client
 *
 * @author Jason Witt
 * @since  1.0.0
 */
class SendFromClient extends SendAbstract {

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
			$this->instance = new SendFromClient();
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

		if ( empty( $share_type ) ) {
			return;
		}

		if ( ! $no_contact ) {
			if ( empty( $this->is_email_button_no_crm_submission ) ) {
				return;
			}

			if ( ! empty( $this->is_email_button_no_crm_submission ) ) {
				unset( $_POST['email_no_crm_note_button'] );
			}
		}

		$share_link_entry = function_exists( 'fp_get_share_link_db_entry_array' ) ? fp_get_share_link_db_entry_array( $this->db ) : [];

		if ( empty( $share_link_entry ) ) {
			return;
		}

		$entry = $share_link_entry['entry'];

		if ( fp_is_feature_active( 'checklists_v_two' ) && fp_is_share_link() ) {
			$advisor_id = isset( $_REQUEST['a'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['a'] ) ) : 0;
		}

		// The advisor info
		$advisor_id = $entry['advisor_user_id'] ?? '';

		if ( fp_is_feature_active( 'checklists_v_two' ) && fp_is_share_link() ) {
			$advisor_id = isset( $_REQUEST['a'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['a'] ) ) : 0;
		}

		$advisor = get_userdata( $advisor_id );

		// The client Info
		$client_name  = $share_type === 'group' ? $_POST['share-link-client-name'] : $entry['client_name'];
		$client_email = $share_type === 'group' ? $_POST['share-link-client-email'] : '';

		if ( fp_is_feature_active( 'checklists_v_two' ) && fp_is_share_link() && $share_type === 'group' ) {
			$client_name = isset( $_POST['share-link-client-name'] ) ? sanitize_text_field( wp_unslash( $_POST['share-link-client-name'] ) ) : '';
		}

		// Email setup.
		$from    = $client_name;
		$to      = $advisor->user_email;
		$subject = 'Your fpPathfinder Resource from ' . $client_name;
		$headers = array( 'Content-Type: text/html; charset=UTF-8', "From: {$from} <no-reply@fppathfinder.com>" );
		$body    = $this->build_html_note( $client_name, $client_email );
		$tasks   = Checklist::build_tasks( true );

		if ( ! empty( $tasks ) ) {
			$body .= '<br>';
			foreach ( $tasks as $task ) {
				$body .= '<h4>' . $task['title'] . '</h4>';
				$body .= $task['lines'];
			}
		}

		$sent_email = $this->maybe_send_email( $to, $subject, $body, $headers, $advisor, $body );

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

			return;
		}
	}
}
