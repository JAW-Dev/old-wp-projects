<?php
/**
 * Data
 *
 * @package    Custom_Reports
 * @subpackage Custom_Reports/Includes/Classes
 * @author     Objectiv
 * @copyright  Copyright (c) 2021, Objectiv
 * @license    GPL-2.0
 * @version    1.0.0
 */

namespace CustomReports\QuizTimeAverage;

use KitcesQuizzes\Includes\Classes\Tables\Query;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

/**
 * Data
 *
 * @author Jason Witt
 * @since  1.0.0
 */
class Data {

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
	 * Init
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return array
	 */
	public static function get_data() {
		$data = self::get_entries();

		return $data;
	}

	/**
	 * Get Trial Members
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return array
	 */
	public static function get_entries() {
		$entries = self::get_post_entries( ( new Query() )->get_entries() );

		if ( empty( $entries ) ) {
			return array();
		}

		$array = array();

		foreach ( $entries as $entry ) {
			$count = count( $entry['entries'] );
			$sum   = 0;

			foreach ( $entry['entries'] as $item ) {
				$sum += self::time_to_seconds( $item->time_total );
			}

			$average      = $sum / $count;
			$average_time = gmdate( 'H:i:s', $average );

			$array[] = array(
				'post_id' => $entry['post_id'],
				'title'   => get_the_title( $entry['post_id'] ),
				'average' => $average_time,
			);
		}

		return $array;
	}

	/**
	 * Time to Seconds
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param string $time The time to convert.
	 *
	 * @return int
	 */
	public static function time_to_seconds( $time = '' ) {
		if ( empty( $time ) ) {
			return 0;
		}

		$array = explode( ':', $time );

		if ( count( $array ) === 3 ) {
			return $array[0] * 3600 + $array[1] * 60 + $array[2];
		}

		return $array[0] * 60 + $array[1];
	}

	/**
	 * Get Post Entries
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param array $entries All the quiz timmer entires.
	 *
	 * @return array
	 */
	public static function get_post_entries( $entries ) {
		// Get unique users.
		$unique_posts = array();

		foreach ( $entries as $entry ) {
			$post_id = $entry->post_id;
			if ( ! in_array( $post_id, $unique_posts, true ) ) {
				$unique_posts[] = $post_id;
			}
		}

		// Build tempoary entry array.
		$post_entries_temp = array();

		foreach ( $unique_posts as $unique_post ) {
			$post_entries_temp[] = array(
				'post_id' => $unique_post,
				'entries' => array(),
			);
		}

		// Get each post's entries.
		$post_entries = array();

		foreach ( $post_entries_temp as $post_entry ) {
			$post_entry_id = $post_entry['post_id'];
			$entries_temp  = array();

			foreach ( $entries as $entry ) {
				$entry_post_id = $entry->post_id;

				if ( (int) $post_entry_id === (int) $entry_post_id ) {
					$entries_temp[] = $entry;
				}
			}

			$post_entries[] = array(
				'post_id' => $post_entry_id,
				'entries' => $entries_temp,
			);
		}

		return $post_entries;
	}
}
