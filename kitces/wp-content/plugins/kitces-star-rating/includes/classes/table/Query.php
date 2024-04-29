<?php
/**
 * Query.
 *
 * @package    Kitces_Star_Rating
 * @subpackage Kitces_Star_Rating/Inlcudes/Classes
 * @author     Objectiv
 * @copyright  Copyright (c) Plugin_Boilerplate_Date, Objectiv
 * @license    GPL-2.0
 * @since      1.0.0
 */

namespace KitcesStarRating\Includes\Classes\Tables;

use KitcesStarRating\Includes\Classes as Classes;
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
		protected static $db;

		/**
		 * Table
		 *
		 * @author Jason Witt
		 * @since  1.0.0
		 *
		 * @var string
		 */
		protected static $table;

		/**
		 * Data Format
		 *
		 * @author Jason Witt
		 * @since  1.0.0
		 *
		 * @var array
		 */
		protected static $data_format = array(
			'%d',
			'%s',
			'%d',
			'%d',
			'%d',
			'%d',
			'%d',
			'%d',
			'%d',
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

			self::$db    = $wpdb;
			self::$table = $wpdb->prefix . KITCES_STAR_RAITING_TABLE_NAME;
		}

		/**
		 * Get Entry
		 *
		 * @author Jason Witt
		 * @since  1.0.0
		 *
		 * @return WP_Query
		 */
		public static function get_entry() {
			$data    = Classes\Post::data();
			$post_id = $data['post_id'];
			$version = $data['version'];
			$table   = self::$table;

			$query = self::$db->get_row( "SELECT * FROM $table WHERE post_id = $post_id AND version = '$version'" ); // phpcs:ignore

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
		public static function get_entries() {
			$table = self::$table;

			return self::$db->get_results( "SELECT * FROM $table" );
		}

		/**
		 * Get Post Entries
		 *
		 * @author Jason Witt
		 * @since  1.0.0
		 *
		 * @param int $post_id The post ID.
		 *
		 * @return object
		 */
		public static function get_post_entries( $post_id = 0 ) {
			if ( ! $post_id ) {
				return new stdClass();
			}

			$table = self::$table;

			return self::$db->get_results( "SELECT * FROM $table" );
		}

		/**
		 * Add Row
		 *
		 * @author Jason Witt
		 * @since  1.0.0
		 *
		 * @return void
		 */
		public static function add() {
			$data         = Classes\Post::data();
			$post_id      = (int) $data['post_id'];
			$version      = $data['version'];
			$average      = $data['rating'];
			$rating_level = Classes\Data::get_rating_level( $data['rating'] );

			self::$db->insert(
				self::$table,
				array(
					'post_id'         => $post_id,
					'version'         => $version,
					'ratings_count'   => 1,
					'ratings_average' => $average,
					'total_one'       => $rating_level['total_one'] ? 1 : 0,
					'total_two'       => $rating_level['total_two'] ? 1 : 0,
					'total_three'     => $rating_level['total_three'] ? 1 : 0,
					'total_four'      => $rating_level['total_four'] ? 1 : 0,
					'total_five'      => $rating_level['total_five'] ? 1 : 0,
				),
				self::$data_format
			);
		}

		/**
		 * Update
		 *
		 * @author Jason Witt
		 * @since  1.0.0
		 *
		 * @return void
		 */
		public static function update() {
			$data    = Classes\Post::data();
			$post_id = (int) $data['post_id'];
			$version = $data['version'];
			$average = Classes\Data::get_average( $data );
			$totals  = Classes\Data::get_updated_totals( $data );
			self::$db->update(
				self::$table,
				array(
					'post_id'         => $post_id,
					'version'         => $version,
					'ratings_count'   => $totals['ratings_count'],
					'ratings_average' => $average,
					'total_one'       => $totals['total_one'],
					'total_two'       => $totals['total_two'],
					'total_three'     => $totals['total_three'],
					'total_four'      => $totals['total_four'],
					'total_five'      => $totals['total_five'],
				),
				array(
					'post_id' => $post_id,
					'version' => $version,
				),
				self::$data_format,
				array(
					'%d',
					'%s',
				)
			);
		}
	}
}
