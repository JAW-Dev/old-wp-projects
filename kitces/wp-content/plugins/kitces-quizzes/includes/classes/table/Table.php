<?php
/**
 * Table.
 *
 * @package    Kitces_Quizzes
 * @subpackage Kitces_Quizzes/Inlcudes/Classes/Tables
 * @author     Objectiv
 * @copyright  Copyright (c) Plugin_Boilerplate_Date, Objectiv
 * @license    GPL-2.0
 * @since      1.0.0
 */

namespace KitcesQuizzes\Includes\Classes\Tables;

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
			if ( (int) get_option( MK_QUIZ_TIMES_TABLE_OPTION_NAME ) === (int) MK_QUIZ_TIMES_TABLE_VERSION ) {
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

			$table           = $wpdb->prefix . MK_QUIZ_TIMES_TABLE_NAME;
			$charset_collate = $wpdb->get_charset_collate();

			$sql = "
			CREATE TABLE {$table} (
				id INTEGER NOT NULL AUTO_INCREMENT,
				post_id BIGINT NOT NULL,
				quiz_id BIGINT NOT NULL,
				user_id BIGINT NOT NULL,
				time_started DATETIME,
				time_ended DATETIME,
				time_total TEXT NOT NULL,
				created DATETIME NOT NULL,
				PRIMARY KEY (id)
			) $charset_collate;
			";

			\dbDelta( $sql );

			update_option( MK_QUIZ_TIMES_TABLE_OPTION_NAME, (int) MK_QUIZ_TIMES_TABLE_VERSION, false );
		}
	}
}
