<?php

namespace FP_Core\Integrations\Active_Campaign;

class Cron_Contact_Update_Queue {
	public function __construct() {}

	static public function init() {
		self::setup_db_table();
	}

	static public function get_queue_table_name(): string {
		global $wpdb;
		return $wpdb->prefix . 'active_campaign_integration_contact_updates_queue';
	}

	static public function setup_db_table() {
		global $wpdb;

		$charset_collate = $wpdb->get_charset_collate();
		$table_name      = self::get_queue_table_name();

		$sql = "CREATE TABLE $table_name (
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`added` datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
			`email` varchar(255) NOT NULL,
			`field_key` varchar(255) NOT NULL,
			`field_value` varchar(255) NOT NULL,
			PRIMARY KEY  (`id`),
			UNIQUE KEY `id` (`id`)
		) $charset_collate;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		$result = dbDelta( $sql );
		return;
	}

	static public function enqueue_update( string $email, string $field_key, string $field_value ) {
		global $wpdb;

		$new_record = array(
			'added'       => current_time( 'mysql' ),
			'email'       => $email,
			'field_key'   => $field_key,
			'field_value' => $field_value,
		);

		$format = array(
			'%s', // added
			'%s', // email
			'%s', // field_key
			'%s', // field_value
		);

		$success = $wpdb->insert( self::get_queue_table_name(), $new_record, $format );

		return $success;
	}

	static public function dequeue_update( int $update_id ) {
		global $wpdb;

		$wpdb->delete( self::get_queue_table_name(), array( 'id' => $update_id ), array( '%d' ) );
	}

	static public function get_updates(): array {
		global $wpdb;

		$updates = $wpdb->get_results( 'SELECT * FROM ' . self::get_queue_table_name() );

		return ! empty( $updates ) ? $updates : array();
	}
}
