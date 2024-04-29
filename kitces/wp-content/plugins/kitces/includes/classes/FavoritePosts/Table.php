<?php
/**
 * Table.
 *
 * @package    Kitces
 * @subpackage Kitces/Includes/Classes/FavoritePosts
 * @author     Objectiv
 * @copyright  Copyright (c) 2020, Objectiv
 * @license    GNU General Public License v2 or later
 * @since      1.0.0
 */

namespace Kitces\Includes\Classes\FavoritePosts;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

/**
 * Table.
 *
 * @author Objectiv
 * @since  1.0.0
 */
class Table {

	/**
	 * Table Name
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @var string
	 */
	protected $table_name = 'mk_favorite_posts';

	/**
	 * Version
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @var string
	 */
	protected $version = '1.0';

	/**
	 * Initialize the class
	 *
	 * @author Objectiv
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function __construct() {}

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

		require_once ABSPATH . 'wp-admin/includes/upgrade.php';

		$table = $wpdb->prefix . $this->table_name;

		if ( get_option( $this->table_name . '_table_version' ) === $this->version && $this->table_exists( $wpdb, $table ) ) {
			return;
		}

		$charset_collate = $wpdb->get_charset_collate();
		$sql             = "
		CREATE TABLE {$table} (
			id INTEGER NOT NULL AUTO_INCREMENT,
			user_id INTEGER NOT NULL,
			post_id INTEGER NOT NULL,
			action varchar(100) NOT NULL,
			category varchar(100) NOT NULL,
			label varchar(200) NOT NULL,
			created DATETIME NOT NULL,
			PRIMARY KEY (id)
		) $charset_collate;
		";

		\dbDelta( $sql );

		if ( get_option( $this->table_name . '_table_version' ) !== $this->version ) {
			update_option( $this->table_name . '_table_versions', $this->version, false );
		}
	}

	/**
	 * Insert
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param int    $user_id  The user ID.
	 * @param int    $post_id  The post ID.
	 * @param string $action   The action.
	 * @param string $category The category.
	 * @param string $label    The post title.
	 *
	 * @return boolean|int
	 */
	public function insert( $user_id, $post_id, $action, $category, $label ) {
		global $wpdb;

		$insert = $wpdb->insert( // phpcs:ignore
			$wpdb->prefix . $this->table_name,
			array(
				'user_id'  => $user_id,
				'post_id'  => $post_id,
				'action'   => $action,
				'category' => $category,
				'label'    => $label,
				'created'  => gmdate( 'Y-m-d H:i:s' ),
			),
			array(
				'%d',
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
	 * @param object $wpdb  The WordPress database class global object.
	 * @param string $table The tabel name.
	 *
	 * @return boolean
	 */
	public function table_exists( $wpdb, $table ) {
		$query = $wpdb->prepare( 'SHOW TABLES LIKE %s', $table );

		if ( $wpdb->get_var( $query ) === $table ) { // phpcs:ignore
			return true;
		}

		return false;
	}
}
