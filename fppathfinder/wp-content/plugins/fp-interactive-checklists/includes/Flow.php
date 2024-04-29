<?php

namespace FP_Interactive_Checklists;

/**
 * Form Processor
 *
 * Form Processor handles interactive checklist form submits.
 */
class Flow {

	public function __construct() {
		$this->add_action_listeners();
	}

	private function add_action_listeners() {
		add_action( 'wp', array( $this, 'check_for_actions' ), 100 );
	}

	public function check_for_actions() {
		$actions = array(
			// 'checklist_form' => array( $this, 'checklist_form_listener' ),
			// 'edit_button'    => array( $this, 'edit_button_listener' ),
		);

		foreach ( $actions as $action => $handler ) {
			if ( self::verify_nonce( $action ) ) {
				call_user_func( $handler );
			}
		}
	}

	public function edit_button_listener() {
		$entry_id = $_REQUEST['entry'] ?? false;

		if ( ! $entry_id ) {
			throw new \Exception( 'No entry specified.' );
		}

		$entry = new \FP_Interactive_Checklists\Checklist_Entry( (int) $entry_id );

		if ( ! $entry->current_user_is_owner() ) {
			throw new \Exception( 'Not authorized to edit this Checklist_Entry' );
		}

		$entry->mark_draft();
		$entry->save();

		wp_safe_redirect( remove_query_arg( self::get_nonce_name() ), 303 ); // 303 to ensure GET request
	}

	public function checklist_form_listener() {
		$is_save     = 'save' === $_POST['submit-button'];
		$is_complete = 'complete' === $_POST['submit-button'];

		if ( $is_save ) {
			$this->process_form_save();
		} elseif ( $is_complete ) {
			$this->process_form_completion();
		}
	}

	private function parse_out_questions() {
		$questions = array();

		foreach ( $_POST as $key => $value ) {
			if ( preg_match( '/^question_(?<question_id>[0-9a-fA-F]+)_(?<value_type>value|note)/', $key, $match ) ) {
				$questions[ $match['question_id'] ][ $match['value_type'] ] = $value;
			}
		}

		return $questions;
	}

	private function process_form_save() {
		$entry_id    = $_POST['entry'] ?? false;
		$client_name = $_POST['client_name'];
		$questions   = $this->parse_out_questions();

		if ( $entry_id ) {
			$entry = new \FP_Interactive_Checklists\Checklist_Entry( (int) $entry_id );

			if ( ! $entry->current_user_is_owner() ) {
				throw new \Exception( 'Not authorized to edit this Checklist_Entry' );
			}

			$entry->set_client_name( $client_name );
			$entry->set_questions( $questions );
			$entry->save();

		} else {
			$checklist_id = get_the_ID();
			$user_id      = get_current_user_id();

			if ( ! $user_id ) {
				throw new \Exception( 'No user id present for new Checklist_Entry' );
			}

			$entry = \FP_Interactive_Checklists\Checklist_Entry::create( $user_id, $checklist_id, $client_name, $questions );

			wp_safe_redirect( add_query_arg( 'entry', $entry->get_entry_id() ), 303 ); // 303 to ensure GET request
		}
	}

	private function process_form_completion() {
		$user_id  = get_current_user_id();
		$entry_id = $_POST['entry'] ?? false;

		if ( ! $user_id ) {
			throw new \Exception( 'No user for this operation' );
		}

		$questions   = $this->parse_out_questions();
		$client_name = $_POST['client_name'];

		if ( $entry_id ) {
			$entry = new \FP_Interactive_Checklists\Checklist_Entry( (int) $entry_id );

			if ( ! $entry->current_user_is_owner() ) {
				throw new \Exception( 'Not authorized to edit this Checklist_Entry' );
			}

			$entry->set_client_name( $client_name );
			$entry->set_questions( $questions );
			$entry->mark_complete();
			$entry->save();
		} else {
			$entry = \FP_Interactive_Checklists\Checklist_Entry::create( $user_id, get_the_ID(), $client_name, $questions, true );
		}

		wp_safe_redirect( add_query_arg( 'entry', $entry->get_entry_id() ), 303 ); // 303 to ensure GET request
	}

	static function get_nonce_name() {
		return 'fp_interactive_checklist_action';
	}

	static function output_nonce_field( string $action ) {
		wp_nonce_field( $action, self::get_nonce_name() );
	}

	static function verify_nonce( string $action ) {
		return isset( $_REQUEST[ self::get_nonce_name() ] ) && wp_verify_nonce( $_REQUEST[ self::get_nonce_name() ], $action );
	}

	static function output_checklist_form_nonce() {
		self::output_nonce_field( 'checklist_form' );
	}

	static function get_edit_button_nonced_url( string $url ) {
		return wp_nonce_url( $url, 'edit_button', self::get_nonce_name() );
	}
}
