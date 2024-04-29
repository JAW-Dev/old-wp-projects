<?php
/**
 * LinkShare
 *
 * @package    FP_Core/
 * @subpackage FP_Core/InteractiveLists/Tables
 * @author     Objectiv
 * @copyright  Copyright (c) 2020, Objectiv
 * @license    GPL-2.0
 * @version    1.0.0
 */

namespace FP_Core\InteractiveLists\Tables;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

/**
 * LinkShare
 *
 * @author Jason Witt
 * @since  1.0.0
 */
class LinkShare {

	/**
	 * Version
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @var string
	 */
	private static $version = '1.3';

	/**
	 * Tabel Name
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @var string
	 */
	private static $table_name = 'fppathfinder_resource_link_share';

	/**
	 * Initialize the class
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function __construct() {}

	/**
	 * Tabel
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public static function setup_table() {
		global $wpdb;

		$charset_collate = $wpdb->get_charset_collate();
		$table_name      = self::get_resource_share_link_table_name();

		if ( get_option( $table_name . '_table_version' ) === self::$version && self::table_exists() ) {
			return;
		}

		$sql = '';

		switch ( self::$version ) {
			case '1.2':
				$sql = self::table_1_2( $table_name, $charset_collate );
				break;
			case '1.3':
				$sql = self::table_1_3( $table_name, $charset_collate );
				break;
		}

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		\dbDelta( $sql );

		if ( get_option( $table_name . '_table_version' ) !== self::$version ) {
			update_option( $table_name . '_table_versions', self::$version, false );
		}
	}

	/**
	 * Table 1.2
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param string $table_name      The table name.
	 * @param string $charset_collate The Charset Collate.
	 *
	 * @return void
	 */
	public static function table_1_2( $table_name, $charset_collate ) {
		return "
		CREATE TABLE $table_name (
			id int(11) NOT NULL AUTO_INCREMENT,
			share_key varchar(255) NOT NULL,
			resource_id varchar(255) NOT NULL,
			crm_contact_id varchar(255) NOT NULL,
			completed BIT DEFAULT 0 NOT NULL,
			advisor_user_id varchar(255) NOT NULL,
			account_id varchar(255),
			expiration datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
			client_name varchar(255) NOT NULL,
			created datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
			fields longtext,
			PRIMARY KEY  (id),
			UNIQUE KEY id (id)
		) $charset_collate;";
	}

	/**
	 * Table 1.2
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param string $table_name      The table name.
	 * @param string $charset_collate The Charset Collate.
	 *
	 * @return void
	 */
	public static function table_1_3( $table_name, $charset_collate ) {
		return "
		CREATE TABLE $table_name (
			id int(11) NOT NULL AUTO_INCREMENT,
			share_key varchar(255) NOT NULL,
			resource_id varchar(255) NOT NULL,
			crm_contact_id varchar(255) NOT NULL,
			completed BIT DEFAULT 0 NOT NULL,
			advisor_user_id varchar(255) NOT NULL,
			account_id varchar(255),
			expiration datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
			mid_time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
			mid_time_notice BIT DEFAULT 0 NOT NULL,
			client_name varchar(255) NOT NULL,
			created datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
			fields longtext,
			PRIMARY KEY  (id),
			UNIQUE KEY id (id)
		) $charset_collate;";
	}

	/**
	 * Get Resource Shar Link Table
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public static function get_resource_share_link_table_name() {
		global $wpdb;
		return $wpdb->prefix . self::$table_name;
	}

	/**
	 * Share Link Entry
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param array $data The date to inset into the database tabel.
	 *
	 * @return array
	 */
	public static function share_link_entry( $data ) {
		if ( empty( $data ) ) {
			return;
		}

		return array(
			'share_key'       => $data['share_key'],
			'resource_id'     => $data['resource_id'],
			'crm_contact_id'  => $data['contact_id'],
			'completed'       => 0,
			'advisor_user_id' => $data['advisor_id'],
			'client_name'     => $data['client_name'],
			'created'         => $data['created'],
			'expiration'      => $data['expiration'],
			'mid_time'        => $data['mid_time'],
			'mid_time_notice' => 0,
			'fields'          => $data['fields'],
			'account_id'      => $data['account_id'],
		);
	}

	/**
	 * Share Link Format
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public static function share_link_format() {
		return array(
			'%s', // share_key
			'%s', // resource_id
			'%s', // crm_contact_id
			'%d', // completed
			'%s', // advisor_user_id
			'%s', // client_name
			'%s', // created
			'%s', // expiration
			'%s', // mid time
			'%d', // mid time notice
			'%s', // fields
			'%s', // account_id
		);
	}

	/**
	 * Insert
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param array $data The date to inset into the database tabel.
	 *
	 * @return void
	 */
	public static function insert( $data ) {
		if ( empty( $data ) ) {
			return;
		}

		global $wpdb;

		$wpdb->insert( self::get_resource_share_link_table_name(), self::share_link_entry( $data ), self::share_link_format() );
	}

	/**
	 * Update
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param array $data The date to inset into the database tabel.
	 * @param int   $id   The table entry ID.
	 *
	 * @return void
	 */
	public static function update( $data, $id ) {
		if ( empty( $data ) ) {
			return;
		}

		global $wpdb;

		$wpdb->update(
			self::get_resource_share_link_table_name(),
			self::share_link_entry( $data ),
			array( 'id' => $id ),
			self::share_link_format(),
			array( '%d' )
		);
	}

	/**
	 * Table Exists
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return boolean
	 */
	public static function table_exists() {
		$query = self::$wpdb->prepare( 'SHOW TABLES LIKE %s', self::get_resource_share_link_table_name() );

		if ( self::$wpdb->get_var( $query ) === self::get_resource_share_link_table_name() ) {
			return true;
		}

		return false;
	}
}
