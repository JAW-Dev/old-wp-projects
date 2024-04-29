<?php

namespace FP_Core\Downloads\Bundles\Generator;

use FP_Core\Datastores\Database_Datastore;

class Process_Datastore extends Database_Datastore {
	static public function get_table_name(): string {
		global $wpdb;
		return $wpdb->prefix . 'fppathfinder_bundle_processes';
	}

	static public function get_table_create_statement(): string {
		global $wpdb;

		$charset_collate = $wpdb->get_charset_collate();
		$table_name      = self::get_table_name();

		return "CREATE TABLE $table_name (
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`user_id` int(11) NOT NULL,
			`bundle_id` int(11) NOT NULL,
			`percent` int(11) NOT NULL,
			`file_url` varchar(255),
			`created` datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
			`modified` datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
			PRIMARY KEY  (`id`),
			UNIQUE KEY `id` (`id`)
		) $charset_collate;";
	}

	static public function find( array $args ) {
		global $wpdb;

		$conditions        = array();
		$field_formats_map = array(
			'user_id'   => '%d',
			'bundle_id' => '%d',
			'percent'   => '%d',
			'file_url'  => '%s',
			'created'   => '%s',
			'modified'  => '%s',
		);

		foreach ( $field_formats_map as $field_name => $format ) {
			foreach ( $args as $arg_key => $arg_value ) {
				if ( $field_name !== $arg_key || empty( $arg_value ) ) {
					continue;
				}

				$conditions[ $field_name . '=' . $format ] = $arg_value;

			}
		}

		//phpcs:ignore WordPress.DB.PreparedSQLPlaceholders.UnfinishedPrepare
		$where = empty( $conditions ) ? '' : 'WHERE ' . $wpdb->prepare( join( ' AND ', array_keys( $conditions ) ), $conditions );
		$table = static::get_table_name();

		return $wpdb->get_results( "SELECT * FROM {$table} " . $where );
	}

	static public function create( int $user_id, int $bundle_id, int $percent, string $file_url = null ) {
		global $wpdb;

		$current_time = current_time( 'mysql' );

		$new_record = array(
			'user_id'   => $user_id,
			'bundle_id' => $bundle_id,
			'percent'   => $percent,
			'file_url'  => $file_url,
			'created'   => $current_time,
			'modified'  => $current_time,
		);

		$record_format = array(
			'user_id'   => '%d',
			'bundle_id' => '%d',
			'percent'   => '%d',
			'file_url'  => '%s',
			'created'   => '%s',
			'modified'  => '%s',
		);

		if ( $user_id ) {
			$new_record['user_id'] = $user_id;
			$record_format[]       = '%d';
		}

		$success = $wpdb->insert( static::get_table_name(), $new_record, $record_format );

		if ( ! $success ) {
			return new \WP_Error( '', 'Unsuccessful attempt to create process record', array( 'new_record' => $new_record ) );
		}

		return $wpdb->insert_id;
	}

	static public function update( int $id, array $updates ) {
		global $wpdb;

		$row = static::get_by_id( $id );

		if ( empty( $row ) ) {
			$error_array = array(
				'id'      => $id,
				'updates' => $updates,
			);

			return new \WP_Error( '', 'Unsuccessful attempt to update process record', $error_array );
		}

		foreach ( $row as $column => $value ) {
			if ( array_key_exists( $column, $updates ) ) {
				$row[ $column ] = $updates[ $column ];
			}
		}

		$row['id']       = $id;
		$row['modified'] = current_time( 'mysql' );

		$field_formats_map = array(
			'user_id'   => '%d',
			'bundle_id' => '%d',
			'percent'   => '%d',
			'file_url'  => '%s',
			'created'   => '%s',
			'modified'  => '%s',
		);

		$success = $wpdb->update( static::get_table_name(), $row, array( 'id' => $id ), $field_formats_map, array( '%d' ) );

		if ( ! $success ) {
			return new \WP_Error( '', 'Unsuccessful attempt to create process record', array( 'row' => $row ) );
		}

		return $success;
	}
}
