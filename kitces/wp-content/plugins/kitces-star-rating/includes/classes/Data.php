<?php
/**
 * Data.
 *
 * @package    Kitces_Star_Rating
 * @subpackage Kitces_Star_Rating/Inlcudes/Classes
 * @author     Objectiv
 * @copyright  Copyright (c) Plugin_Boilerplate_Date, Objectiv
 * @license    GPL-2.0
 * @since      1.0.0
 */

namespace KitcesStarRating\Includes\Classes;

use KitcesStarRating\Includes\Classes\Tables as Tables;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

if ( ! class_exists( __NAMESPACE__ . '\\Data' ) ) {

	/**
	 * Data.
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
		 * Rating Level
		 *
		 * @author Jason Witt
		 * @since  1.0.0
		 *
		 * @param string $rating The user submitted rating.
		 *
		 * @return array
		 */
		public static function get_rating_level( $rating ) {
			$total_one   = $rating === '1' ? (int) $rating : 0;
			$total_two   = $rating === '2' ? (int) $rating : 0;
			$total_three = $rating === '3' ? (int) $rating : 0;
			$total_four  = $rating === '4' ? (int) $rating : 0;
			$total_five  = $rating === '5' ? (int) $rating : 0;

			return array(
				'total_one'   => $total_one,
				'total_two'   => $total_two,
				'total_three' => $total_three,
				'total_four'  => $total_four,
				'total_five'  => $total_five,
			);
		}

		/**
		 * Get Ratings Count
		 *
		 * @author Jason Witt
		 * @since  1.0.0
		 *
		 * @param object $entry  The existing database entry.
		 * @param string $rating The user submitted rating.
		 *
		 * @return array
		 */
		public static function get_ratings_count( $entry, $rating ) {
			$level = self::get_rating_level( $rating );

			return array(
				'total_one'   => $level['total_one'] ? $entry->total_one + 1 : $entry->total_one,
				'total_two'   => $level['total_two'] ? $entry->total_two + 1 : $entry->total_two,
				'total_three' => $level['total_three'] ? $entry->total_three + 1 : $entry->total_three,
				'total_four'  => $level['total_four'] ? $entry->total_four + 1 : $entry->total_four,
				'total_five'  => $level['total_five'] ? $entry->total_five + 1 : $entry->total_five,
			);
		}

		/**
		 * Average
		 *
		 * @author Jason Witt
		 * @since  1.0.0
		 *
		 * @param array $data The user posted data.
		 *
		 * @return int
		 */
		public static function get_average( $data ) {
			$entry  = Tables\Query::get_entry();
			$rating = $data['rating'];

			$levels = array(
				'total_one'   => 1,
				'total_two'   => 2,
				'total_three' => 3,
				'total_four'  => 4,
				'total_five'  => 5,
			);

			if ( $entry ) {
				$counts        = self::get_ratings_count( $entry, $rating );
				$ratings_total = 0;

				foreach ( $levels as $level_key => $level_value ) {
					if ( (int) $rating === (int) $level_value ) {
						$counts[ $level_key ] = $counts[ $level_key ];
						break;
					}
				}

				foreach ( $levels as $level_key => $level_value ) {
					foreach ( $counts as $count_key => $count_value ) {
						if ( $level_key === $count_key ) {
							$ratings_total += (int) $count_value * (int) $level_value;
						}
					}
				}

				return round( (int) $ratings_total / ( (int) $entry->ratings_count + 1 ), 2 );
			}
		}

		/**
		 * Updated Totals
		 *
		 * @author Jason Witt
		 * @since  1.0.0
		 *
		 * @param array $data The user posted data.
		 *
		 * @return array
		 */
		public static function get_updated_totals( $data ) {
			$entry = Tables\Query::get_entry();

			if ( $entry ) {
				$level = self::get_rating_level( $data['rating'] );

				$updated_total_one     = $level['total_one'] ? $entry->total_one + 1 : $entry->total_one;
				$updated_total_two     = $level['total_two'] ? $entry->total_two + 1 : $entry->total_two;
				$updated_total_three   = $level['total_three'] ? $entry->total_three + 1 : $entry->total_three;
				$updated_total_four    = $level['total_four'] ? $entry->total_four + 1 : $entry->total_four;
				$updated_total_five    = $level['total_five'] ? $entry->total_five + 1 : $entry->total_five;
				$updated_ratings_count = $entry->ratings_count + 1;

				return array(
					'total_one'     => (int) $updated_total_one,
					'total_two'     => (int) $updated_total_two,
					'total_three'   => (int) $updated_total_three,
					'total_four'    => (int) $updated_total_four,
					'total_five'    => (int) $updated_total_five,
					'ratings_count' => (int) $updated_ratings_count,
				);
			}
		}
	}
}
