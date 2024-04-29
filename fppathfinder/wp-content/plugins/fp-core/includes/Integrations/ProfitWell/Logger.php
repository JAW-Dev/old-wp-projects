<?php
/**
 * Logger
 *
 * @package    FP_Core
 * @subpackage FP_Core/Integrations/ProfitWell
 * @author     Objectiv
 * @copyright  Copyright (c) 2021, Objectiv
 * @license    GPL-2.0
 * @version    1.0.0
 */

namespace FP_Core\Integrations\ProfitWell;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

/**
 * Logger
 *
 * @author Jason Witt
 * @since  1.0.0
 */
class Logger {

	/**
	 * Table Name
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @var string
	 */
	protected $table_name;

	/**
	 * Initialize the class
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function __construct() {
		$this->table_name = 'fp_profitwell_logger';
	}

	/**
	 * Maybe Create Table
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function maybe_create_table() {
		global $wpdb;

		$version = '1.1';
		$table   = $wpdb->prefix . $this->table_name;

		if ( get_option( $this->table_name . '_table_version' ) === $version && $this->table_exists( $wpdb, $table ) ) {
			return;
		}

		$charset_collate = $wpdb->get_charset_collate();
		$sql             = "
		CREATE TABLE {$table} (
			id INTEGER NOT NULL AUTO_INCREMENT,
			user_id INTEGER NOT NULL,
			user_email varchar(100) NOT NULL,
			type varchar(100) NOT NULL,
			message LONGTEXT NOT NULL,
			response LONGTEXT NOT NULL,
			created DATETIME NOT NULL,
			PRIMARY KEY (id)
		) $charset_collate;
		";

		\dbDelta( $sql );

		if ( get_option( $this->table_name . '_table_version' ) !== $version ) {
			update_option( $this->table_name . '_table_versions', $version, false );
		}
	}

	/**
	 * Log
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param int    $user_id    The user ID.
	 * @param string $user_email The user email.
	 * @param string $type       The type being logged.
	 * @param string $response   The body of the API response.
	 *
	 * @return boolean|int
	 */
	public function log( $user_id, $user_email, $type, $message, $response ) {
		global $wpdb;

		$insert = $wpdb->insert(
			$wpdb->prefix . $this->table_name,
			array(
				'user_id'    => $user_id,
				'user_email' => $user_email,
				'type'       => $type,
				'message'    => maybe_serialize( $message ),
				'response'   => maybe_serialize( $response ),
				'created'    => gmdate( 'Y-m-d H:i:s' ),
			),
			array(
				'%d',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
			)
		);

		return $insert;
	}

	/**
	 * Table Exists
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param object $wpdb The WordPress database class global object.
	 *
	 * @return boolean
	 */
	public function table_exists( $wpdb, $table ) {
		$query = $wpdb->prepare( 'SHOW TABLES LIKE %s', $table );

		if ( $wpdb->get_var( $query ) === $table ) {
			return true;
		}

		return false;
	}
}
