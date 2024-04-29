<?php

namespace FP_Interactive_Checklists;

/**
 * Database
 *
 * Database init and interfacing with tables
 */
class Database {

	public function __construct() {}

	public function setup_tables() {
		$this->setup_checklist_entries_table();
		$this->setup_checklist_entries_questions_table();
	}

	private function setup_checklist_entries_table() {
		global $wpdb;

		$charset_collate = $wpdb->get_charset_collate();
		$table_name      = self::get_checklist_entries_table_name();

		$sql = "CREATE TABLE $table_name (
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`created` datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
			`modified`datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
			`status` varchar(255) NOT NULL DEFAULT 'draft',
			`checklist_id` int(11) NOT NULL,
			`user_id` int(11) NOT NULL,
			`client_name` varchar(255) NOT NULL,
			`external_data` varchar(255),
			`external_provider` varchar(255),
			PRIMARY KEY  (`id`),
			UNIQUE KEY `id` (`id`)
		) $charset_collate;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );
	}

	private function setup_checklist_entries_questions_table() {
		global $wpdb;

		$charset_collate = $wpdb->get_charset_collate();
		$table_name      = self::get_checklist_entries_questions_table_name();

		$sql = "CREATE TABLE $table_name (
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`added` datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
			`entry_id` int(11) NOT NULL,
			`question_id` varchar(255) NOT NULL,
			`value` varchar(255) NOT NULL,
			`note` varchar(255),
			PRIMARY KEY  (`id`),
			UNIQUE KEY `id` (`id`)
		) $charset_collate;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );
	}

	static function get_checklist_entries_table_name() {
		global $wpdb;
		return $wpdb->prefix . 'fp_checklist_entries';
	}

	static function get_checklist_entries_questions_table_name() {
		global $wpdb;
		return $wpdb->prefix . 'fp_checklist_entries_questions';
	}
}
