<?php
/**
 * Query.
 *
 * @package    Kitces_Quizzes
 * @subpackage Kitces_Quizzes/Inlcudes/Classes
 * @author     Objectiv
 * @copyright  Copyright (c) Plugin_Boilerplate_Date, Objectiv
 * @license    GPL-2.0
 * @since      1.0.0
 */

namespace KitcesQuizzes\Includes\Classes\Tables;

use Kitces_Quizzes\Includes\Classes as Classes;
use stdClass;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

if ( ! class_exists( __NAMESPACE__ . '\\Query' ) ) {

	/**
	 * Query.
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 */
	class Query {

		/**
		 * WPDB
		 *
		 * @author Jason Witt
		 * @since  1.0.0
		 *
		 * @var object
		 */
		protected $db;

		/**
		 * Table
		 *
		 * @author Jason Witt
		 * @since  1.0.0
		 *
		 * @var string
		 */
		protected $table;

		/**
		 * Data Format
		 *
		 * @author Jason Witt
		 * @since  1.0.0
		 *
		 * @var array
		 */
		protected $data_format = array(
			'%d',
			'%d',
			'%d',
			'%s',
			'%s',
			'%s',
			'%s',
		);

		/**
		 * Initialize the class
		 *
		 * @author Jason Witt
		 * @since  1.0.0
		 *
		 * @return void
		 */
		public function __construct() {
			global $wpdb;

			$this->db    = $wpdb;
			$this->table = $wpdb->prefix . MK_QUIZ_TIMES_TABLE_NAME;
		}

		/**
		 * Get Entry
		 *
		 * @author Jason Witt
		 * @since  1.0.0
		 *
		 * @return WP_Query
		 */
		public function get_entry() {
			$data    = Classes\Post::data();
			$post_id = $data['post_id'];
			$version = $data['version'];
			$table   = $this->table;

			$query = $this->db->get_row( "SELECT * FROM $table WHERE post_id = $post_id AND version = '$version'" ); // phpcs:ignore

			return $query !== null ? $query : false;
		}

		/**
		 * Get Entries
		 *
		 * @author Jason Witt
		 * @since  1.0.0
		 *
		 * @return object
		 */
		public function get_entries() {
			$table = $this->table;

			return $this->db->get_results( "SELECT * FROM $table" );
		}

		/**
		 * Get Total Average
		 *
		 * @author Jason Witt
		 * @since  1.0.0
		 *
		 * @return object
		 */
		public function get_total_average() {
			$table = $this->table;

			return $this->db->get_results( "SELECT SEC_TO_TIME(AVG(TIME_TO_SEC(time_total))) AS average FROM $table" );
		}

		/**
		 * Add Row
		 *
		 * @author Jason Witt
		 * @since  1.0.0
		 *
		 * @return void
		 */
		public function add( $data = [] ) {
			if ( empty( $data ) ) {
				return;
			}

			if ( empty( $this->db ) ) {
				global $wpdb;

				$this->db = $wpdb;
			}

			$now = new \DateTime();

			$this->db->insert(
				$this->table,
				array(
					'post_id'      => $data['post_id'],
					'quiz_id'      => $data['quiz_id'],
					'user_id'      => $data['user_id'],
					'time_started' => $data['time_started'],
					'time_ended'   => $data['time_ended'],
					'time_total'   => $data['time_total'],
					'created'      => current_time( 'mysql' ),
				),
				$this->data_format
			);
		}
	}
}
