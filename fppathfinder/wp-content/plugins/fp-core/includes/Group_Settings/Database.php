<?php

namespace FP_Core\Group_Settings;

class Database {
	public function __construct() {}

	static public function init() {
		self::setup_table();
	}

	static public function get_table_name(): string {
		global $wpdb;

		return $wpdb->prefix . 'fppathfinder_group_settings';
	}

	static public function setup_table() {
		global $wpdb;

		$charset_collate = $wpdb->get_charset_collate();
		$table_name      = self::get_table_name();

		$sql = "CREATE TABLE $table_name (
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`group_id` int(11) NOT NULL,
			`setting_key` varchar(255) NOT NULL,
			`value` varchar(255) NOT NULL,
			PRIMARY KEY  (`id`),
			UNIQUE KEY `id` (`id`)
		) $charset_collate;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		$result = dbDelta( $sql );
		return;
	}

	static public function add_group_setting( int $group_id, string $key, string $value ) {
		global $wpdb;

		$new_record = array(
			'group_id'    => $group_id,
			'setting_key' => $key,
			'value'       => $value,
		);

		$format = array(
			'%d', // group_id
			'%s', // key
			'%s', // value
		);

		$success = $wpdb->insert( self::get_table_name(), $new_record, $format );

		if ( ! $success ) {
			// throw new \Exception( 'Error writing new Active Campaign Contact Update to database' );
		}

		return $success;
	}

	static public function set_group_setting( int $group_id, string $key, string $value ) {
		$existing_settings = self::get_group_settings_rows( $group_id, $key );

		foreach ( $existing_settings as $setting_row ) {
			self::delete_group_setting( $setting_row->id );
		}

		self::add_group_setting( $group_id, $key, $value );
	}

	static public function get_group_settings_rows( $group_id = 0, $key = '', $value = '' ) {
		global $wpdb;

		$conditions = array_filter(
			array(
				'group_id=%d'    => $group_id,
				'setting_key=%s' => $key,
				'value=%s'       => $value,
			)
		);

		//phpcs:ignore WordPress.DB.PreparedSQLPlaceholders.UnfinishedPrepare
		$where    = empty( $conditions ) ? '' : 'WHERE ' . $wpdb->prepare( join( ' AND ', array_keys( $conditions ) ), $conditions );
		$table    = self::get_table_name();
		$settings = $wpdb->get_results( "SELECT * FROM {$table} " . $where );

		return empty( $settings ) ? array() : $settings;
	}

	static public function get_group_setting( $group_id, $key ) {
		$settings = self::get_group_settings_rows( $group_id, $key );

		if ( empty( $settings ) ) {
			return '';
		}

		return current( $settings )->value;
	}

	static public function delete_group_setting( int $row_id ) {
		global $wpdb;

		$wpdb->delete( self::get_table_name(), array( 'id' => $row_id ), array( '%d' ) );
	}
}
