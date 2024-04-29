<?php

namespace FP_Core\Datastores;

abstract class Database_Datastore {
	abstract static public function get_table_name(): string;
	abstract static public function get_table_create_statement(): string;
	abstract static public function find( array $args );

	static public function setup_table(): void {
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

		$result = dbDelta( static::get_table_create_statement() );

		return;
	}

	static public function get_by_id( int $id ) {
		global $wpdb;

		$table = static::get_table_name();

		return $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$table} WHERE id = %d", $id ), 'ARRAY_A' );
	}

	static public function delete( int $id ) {
		global $wpdb;

		$wpdb->delete( static::get_table_name(), array( 'id' => $id ), array( '%d' ) );
	}
}
