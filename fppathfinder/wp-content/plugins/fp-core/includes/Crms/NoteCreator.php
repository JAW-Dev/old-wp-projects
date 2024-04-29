<?php
/**
 * Note Creator
 *
 * @package    FP_Core/
 * @subpackage FP_Core/Includes/Crms
 * @author     Objectiv
 * @copyright  Copyright (c) 2020, Objectiv
 * @license    GPL-2.0
 * @version    1.0.0
 */

namespace FP_Core\Crms;

use FP_Core\InteractiveChecklistsNotification;
use FP_Core\Crms\Utilities as CrmUtilities;
use FP_Core\Utilities as CoreUtilities;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

/**
 * NoteCreator
 *
 * @author Jason Witt
 * @since  1.0.0
 */
class NoteCreator {

	/**
	 * Initialize the class
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function __construct() {
		add_action( 'interactive_resource_integration_note_button', __CLASS__ . '::maybe_output_crm_note_button' );
		add_action( 'before_single_interactive_resource_view', __CLASS__ . '::listen_for_crm_note_submit_button' );
	}

	public static function maybe_output_crm_note_button( $form ) {
		if ( ! ( $_POST['crm_contact_id'] ?? false ) ) {
			return;
		}

		$active_crm = CrmUtilities::get_active_crm();
		$crm        = CrmUtilities::get_crm_info( $active_crm );

		?>
			<div class="send-to-redtail-button">
				<input type="submit" form="<?php echo esc_attr( $form ); ?>" name="redtail_note_button" value="Send to <?php echo esc_attr( $crm['name'] ); ?>" id="submit-button" class="send-to-crm">
			</div>
		<?php
	}

	public static function listen_for_crm_note_submit_button() {
		$is_redtail_button_submission = isset( $_POST['redtail_note_button'] ) ? $_POST['redtail_note_button'] : '';

		if ( ! $is_redtail_button_submission ) {
			return;
		}

		self::submit_handler();
	}

	public static function submit_handler() {
		$user_id       = get_current_user_id();
		$contact_id    = $_POST['crm_contact_id'];
		$account_id    = $_POST['account_id'];
		$note          = self::build_note();
		$tasks         = self::build_tasks();
		$note_reponse  = self::create_note( CrmUtilities::get_active_crm(), $user_id, $contact_id, $note, $account_id );
		$active_crm    = CrmUtilities::get_active_crm();
		$crm           = CrmUtilities::get_crm_info( $active_crm );
		$crm_name      = $crm['name'] ?? '';
		$error_message = 'There was an error processing your request.';

		$success_codes = array(
			200,
			201,
		);

		if ( is_wp_error( $note_reponse ) || empty( $note_reponse ) ) {
			InteractiveChecklistsNotification::add( $error_message, true );
			return;
		}

		if ( ! in_array( $note_reponse['response']['code'], $success_codes, true ) ) {
			InteractiveChecklistsNotification::add( $error_message, true );
			return;
		}

		if ( fp_is_feature_active( 'activities' ) && ! empty( $tasks ) ) {
			if ( CrmUtilities::is_crm_active( 'wealthbox' ) ) {
				$tasks = array_reverse( $tasks, true );
			}

			foreach ( $tasks as $task ) {
				if ( empty( $task ) ) {
					continue;
				}

				$task_reponse = self::create_task( $active_crm, $user_id, $contact_id, $task );

				if ( is_wp_error( $task_reponse ) || empty( $task_reponse ) ) {
					InteractiveChecklistsNotification::add( $error_message, true );
					return;
				}

				if ( ! in_array( $task_reponse['response']['code'], $success_codes, true ) ) {
					InteractiveChecklistsNotification::add( $error_message, true );
					return;
				}
			}
		}

		InteractiveChecklistsNotification::add( 'Note successfully created in ' . $crm_name  . '.' );
	}

	/**
	 * Create Note
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param string $slug       The CRM slug.
	 * @param int    $user_id    The user ID.
	 * @param int    $contact_id The contact ID.
	 * @param string $note       The note.
	 *
	 * @return void
	 */
	public static function create_note( $slug, $user_id, $contact_id, $note, $account_id ) {
		$crm       = ucfirst( str_replace( '_', '', $slug ) );
		$classname = 'FP_Core\\Crms\\Apis\\' . $crm . 'API';

		return $classname::create_note( $user_id, $contact_id, $note, $account_id );
	}

	/**
	 * Create Task
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param string $slug       The CRM slug.
	 * @param int    $user_id    The user ID.
	 * @param int    $contact_id The contact ID.
	 * @param string $note       The note.
	 *
	 * @return void
	 */
	public static function create_task( $slug, $user_id, $contact_id, $task ) {
		if ( ! fp_is_feature_active( 'activities' ) ) {
			return;
		}

		if ( empty( $slug ) ) {
			return;
		}

		$crm       = ucfirst( str_replace( '_', '', $slug ) );
		$classname = 'FP_Core\\Crms\\Apis\\' . $crm . 'API';

		return $classname::create_task( $user_id, $contact_id, $task );
	}

	/**
	 * Build Note
	 *
	 * @return string
	 */
	public static function build_note( $plain = false ) {
		$type = sanitize_text_field( wp_unslash( $_POST['resource_type'] ?? '' ) );
		$note = '';

		switch ( $type ) {
			case 'checklist':
				$note = Notes\Checklist::build_note( $plain );
				break;
			case 'flowchart':
				$note = Notes\Flowchart::build_note();
				break;
		}

		return $note;
	}

	/**
	 * Build Tasks
	 *
	 * @return array
	 */
	public static function build_tasks() {
		if ( ! fp_is_feature_active( 'activities' ) ) {
			return;
		}

		$type = sanitize_text_field( wp_unslash( $_POST['resource_type'] ?? '' ) );

		if ( 'checklist' === $type ) {
			return Notes\Checklist::build_tasks();
		}
	}

	/**
	 * Build Email Note
	 *
	 * @param string $client_name The client name.
	 *
	 * @return string
	 */
	public static function build_email_note( $client_name = null ) {
		$type = sanitize_text_field( wp_unslash( $_POST['resource_type'] ?? '' ) );
		$note = '';

		switch ( $type ) {
			case 'checklist':
				$note = Notes\Checklist::build_email_note( $client_name );
				break;
			case 'flowchart':
				$note = Notes\Flowchart::build_email_note( $client_name );
				break;
		}

		return $note;
	}
}
