<?php

namespace FP_Core\Downloads\Bundles\Generator;

class Process {
	protected $id;
	protected $user_id;
	protected $bundle_id;
	protected $percent = 0;
	protected $file_url;

	public function __construct() {}

	public function get_id() {
		return $this->id;
	}

	public function set_id( int $id ) {
		$this->id = $id;
	}

	public function get_user_id() {
		return $this->user_id;
	}

	public function set_user_id( int $user_id ) {
		$this->user_id = $user_id;
	}

	public function get_bundle_id() {
		return $this->bundle_id;
	}

	public function set_bundle_id( int $bundle_id ) {
		$this->bundle_id = $bundle_id;
	}

	public function get_percent() {
		return $this->percent;
	}

	public function set_percent( int $percent ) {
		$this->percent = $percent;
	}

	public function get_file_url() {
		return $this->file_url;
	}

	public function set_file_url( string $file_url ) {
		$this->file_url = $file_url;
	}

	/**
	 * Save
	 *
	 * Attempt to update an existing entry, if none is found create a new one.
	 */
	public function save() {
		if ( $this->can_update() ) {
			$this->update();
			return;
		}

		$this->save_new();
	}

	/**
	 * Save New
	 *
	 * Create a new record in the datastore for this entry.
	 */
	public function save_new() {
		if ( ! $this->can_save_new() ) {
			return false;
		}

		$this->set_id( Process_Datastore::create( $this->user_id, $this->bundle_id, 0 ) );
	}

	/**
	 * Can Save New
	 *
	 * Check that it meets minimum requirements to be saved in the datastore.
	 */
	private function can_save_new(): bool {
		$user_id   = ! empty( $this->user_id );
		$bundle_id = ! empty( $this->bundle_id );

		return $user_id && $bundle_id;
	}

	private function can_update(): bool {
		$id             = intval( $this->id ) > 0;
		$user_id        = ! empty( $this->user_id );
		$bundle_id      = ! empty( $this->bundle_id );
		$already_exists = $this->get_existing_row();

		return $id && $user_id && $bundle_id && $already_exists;
	}

	/**
	 * Update
	 *
	 * Update this entry's record in the datastore.
	 *
	 * @return bool whether the update could be made or not.
	 */
	public function update(): bool {
		if ( ! $this->can_update() ) {
			return false;
		}

		$updates = array(
			'user_id'   => $this->user_id,
			'bundle_id' => $this->bundle_id,
			'percent'   => $this->percent,
			'file_url'  => $this->file_url,
		);

		Process_Datastore::update( $this->id, $updates );

		return true;
	}

	private function get_existing_row() {
		$row = Process_Datastore::get_by_id( $this->get_id() );

		if ( empty( $row ) ) {
			return false;
		}

		return $row;
	}

	public function load() {
		$row = $this->get_existing_row();

		if ( ! $row ) {
			return false;
		}

		$this->set_user_id( (int) $row['user_id'] );
		$this->set_bundle_id( (int) $row['bundle_id'] );
		$this->set_percent( (int) $row['percent'] );

		if ( $row['file_url'] ) {
			$this->set_file_url( $row['file_url'] );
		}

		return true;
	}

	static public function create( int $user_id, int $bundle_id, int $percent = 0, string $file_url = '' ) {
		$item = new self();

		$item->set_user_id( $user_id );
		$item->set_bundle_id( $bundle_id );
		$item->set_percent( $percent );

		if ( ! empty( $file_url ) ) {
			$item->set_file_url( $file_url );
		}

		$item->save_new();

		return $item;
	}

	static public function get( int $id ) {
		$item = new self();
		$item->set_id( $id );

		if ( $item->load() ) {
			return $item;
		}

		return false;
	}
}
