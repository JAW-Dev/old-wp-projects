<?php

namespace FP_Interactive_Checklists;

/**
 * Checklist Entry
 *
 * Represents an entry for an interactive checklist
 */
class Checklist_Entry {
	private $entry_id;
	private $status;
	private $checklist_id;
	private $user_id;
	private $client_name;
	private $external_id;
	private $external_provider;
	private $questions;
	private $entries_table_name;
	private $questions_table_name;
	private $created_datetime;
	private $modified_datetime;

	/**
	 * Construct
	 *
	 */
	public function __construct( int $entry_id ) {
		$this->entry_id             = $entry_id;
		$this->entries_table_name   = \FP_Interactive_Checklists\Database::get_checklist_entries_table_name();
		$this->questions_table_name = \FP_Interactive_Checklists\Database::get_checklist_entries_questions_table_name();
		$this->load();
	}

	public function load() {
		global $wpdb;

		$row = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$this->entries_table_name} WHERE id = %d", $this->entry_id ) );

		if ( ! $row ) {
			throw new \Exception( 'No such Checklist Entry exists for id: ' . $this->entry_id );
		}

		$this->status            = $row->status;
		$this->checklist_id      = (int) $row->checklist_id;
		$this->user_id           = (int) $row->user_id;
		$this->client_name       = $row->client_name;
		$this->external_id       = $row->external_id;
		$this->external_provider = $row->external_provider;
		$this->created_datetime  = $row->created;
		$this->modified_datetime = $row->modified;

		$this->load_questions();
	}

	static function create( int $user_id, int $checklist_id, string $client_name, array $questions, bool $is_complete = false, string $external_provider = '', string $external_id = '' ) {
		global $wpdb;

		$new_record = array(
			'user_id'           => $user_id,
			'client_name'       => $client_name,
			'status'            => $is_complete ? 'complete' : 'draft',
			'checklist_id'      => $checklist_id,
			'external_provider' => $external_provider,
			'external_id'       => $external_id,
			'created'           => current_time( 'mysql' ),
			'modified'          => current_time( 'mysql' ),
		);

		$format = array(
			'%d', // user_id
			'%s', // client_name
			'%s', // status
			'%d', // checklist_id
			'%s', // external_provider
			'%s', // external_id
			'%s', // created
			'%s', // modified
		);

		$success = $wpdb->insert( \FP_Interactive_Checklists\Database::get_checklist_entries_table_name(), $new_record, $format );

		if ( ! $success ) {
			throw new \Exception( 'Error writing new Checklist_Entry to database' );
		}

		$new_entry = new \FP_Interactive_Checklists\Checklist_Entry( $wpdb->insert_id );
		$new_entry->set_questions( $questions );
		$new_entry->save();

		return $new_entry;
	}

	public function save() {
		global $wpdb;

		$record = array(
			'user_id'           => $this->user_id,
			'client_name'       => $this->client_name,
			'status'            => $this->status,
			'checklist_id'      => $this->checklist_id,
			'external_id'       => $this->external_id,
			'external_provider' => $this->external_provider,
			'modified'          => current_time( 'mysql' ),
		);

		$record_format = array(
			'%d', // user_id
			'%s', // client_name
			'%s', // status
			'%d', // checklist_id
			'%s', // external_id
			'%s', // external_provider
			'%s', // modified
		);

		$where        = array( 'id' => $this->entry_id );
		$where_format = array( '%d' );

		$success = $wpdb->update( $this->entries_table_name, $record, $where, $record_format, $where_format );

		if ( false === $success ) {
			throw new \Exception( 'Error saving Interactive Checklist Entry' );
		}

		$this->delete_questions();
		$this->write_questions( $this->questions );
	}

	public function current_user_is_owner() {
		return get_current_user_id() === $this->user_id;
	}

	public function set_questions( array $questions ) {
		if ( ! $this->validate_questions( $questions ) ) {
			throw new \Exception( 'Invalid questions array passed to \FP_Interactive_Checklists\Checklist_Entry::set_questions' );
		}

		$this->questions = $questions;
	}

	public function is_complete() {
		return 'complete' === $this->status;
	}

	public function mark_complete() {
		$this->set_status( 'complete' );
	}

	public function is_draft() {
		return 'draft' === $this->status;
	}

	public function mark_draft() {
		$this->set_status( 'draft' );
	}

	private function set_status( string $status ) {
		$this->status = $status;
	}

	public function get_status() {
		return $this->status;
	}

	public function get_questions() {
		return $this->questions;
	}

	private function validate_questions( array $questions ) {
		$valid      = true;
		$unique_ids = array();

		foreach ( $questions as $id => $question ) {
			$id_is_unique  = ! in_array( $id, $unique_ids, true );
			$unique_ids[]  = $id;
			$only_two_keys = 2 === count( $question );
			$valid_value   = ( $question['value'] ?? false ) && in_array( $question['value'], array( 'yes', 'no', 'unset' ), true );
			$has_note_key  = key_exists( 'note', $question );
			$valid         = $valid && $id_is_unique && $only_two_keys && $valid_value && $has_note_key;
		}

		return $valid;
	}

	private function delete_questions() {
		global $wpdb;
		$wpdb->delete( $this->questions_table_name, array( 'entry_id' => $this->entry_id ), array( '%d' ) );
	}

	private function write_questions( array $questions ) {
		foreach ( $questions as $id => $fields ) {
			$this->write_question( $id, $fields );
		}
	}

	private function write_question( string $id, array $fields ) {
		global $wpdb;

		$new_record = array(
			'entry_id'    => $this->entry_id,
			'question_id' => $id,
			'value'       => $fields['value'],
			'note'        => $fields['note'],
			'added'       => current_time( 'mysql' ),
		);

		$format = array(
			'%d', // entry_id
			'%s', // question_id
			'%s', // value
			'%s', // note
			'%s', // added
		);

		$wpdb->insert( $this->questions_table_name, $new_record, $format );
	}

	private function load_questions() {
		global $wpdb;

		$questions_rows = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$this->questions_table_name} WHERE entry_id = %d", $this->entry_id ) );

		$questions = array();

		foreach ( $questions_rows as $row ) {
			$questions[ $row->question_id ]['value'] = $row->value;
			$questions[ $row->question_id ]['note']  = $row->note;
		}

		$this->questions = $questions;
	}

	public function set_client_name( string $client_name ) {
		$this->client_name = $client_name;
	}

	public function get_client_name() {
		return $this->client_name;
	}

	public function get_entry_id() {
		return $this->entry_id;
	}

	public function get_user_id() {
		return $this->user_id;
	}

	public function get_checklist_id() {
		return $this->checklist_id;
	}

	public function get_created_datetime() {
		return $this->created_datetime;
	}

	public function get_modified_datetime() {
		return $this->modified_datetime;
	}
}
