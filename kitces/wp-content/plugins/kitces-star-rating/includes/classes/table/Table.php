<?php
/**
 * Table.
 *
 * @package    Kitces_Star_Rating
 * @subpackage Kitces_Star_Rating/Inlcudes/Classes/Tables
 * @author     Objectiv
 * @copyright  Copyright (c) Plugin_Boilerplate_Date, Objectiv
 * @license    GPL-2.0
 * @since      1.0.0
 */

namespace KitcesStarRating\Includes\Classes\Tables;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

if ( ! class_exists( __NAMESPACE__ . '\\Table' ) ) {

	/**
	 * Table.
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 */
	class Table {

		/**
		 * Activate
		 *
		 * @author Jason Witt
		 * @since  1.0.0
		 *
		 * @return mixed
		 */
		public static function activate() {
			if ( (int) get_option( KITCES_STAR_RAITING_TABLE_OPTION_NAME ) === (int) KITCES_STAR_RAITING_TABLE_VERSION ) {
				return false;
			}

			self::create();
		}

		/**
		 * Create Table
		 *
		 * @author Jason Witt
		 * @since  1.0.0
		 *
		 * @return void
		 */
		public static function create() {
			global $wpdb;

			$table           = $wpdb->prefix . KITCES_STAR_RAITING_TABLE_NAME;
			$charset_collate = $wpdb->get_charset_collate();

			$sql = "
			CREATE TABLE {$table} (
				rating_id INTEGER NOT NULL AUTO_INCREMENT,
				post_id BIGINT NOT NULL,
				version varchar(200) NOT NULL,
				ratings_count BIGINT NOT NULL DEFAULT 0,
				ratings_average TINYINT NOT NULL DEFAULT 0,
				total_one BIGINT NOT NULL DEFAULT 0,
				total_two BIGINT NOT NULL DEFAULT 0,
				total_three BIGINT NOT NULL DEFAULT 0,
				total_four BIGINT NOT NULL DEFAULT 0,
				total_five BIGINT NOT NULL DEFAULT 0,
				PRIMARY KEY (rating_id)
			) $charset_collate;
			";

			\dbDelta( $sql );

			update_option( KITCES_STAR_RAITING_TABLE_OPTION_NAME, (int) KITCES_STAR_RAITING_TABLE_VERSION, false );
		}
	}
}
