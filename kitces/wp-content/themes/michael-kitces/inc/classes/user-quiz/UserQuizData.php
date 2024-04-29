<?php
/**
 * User Quiz Data
 *
 * @package    Package_Name
 * @subpackage Package_Name/Subpackage_Name
 * @author     Author_Name
 * @copyright  Copyright (c) Date, Author_Name
 * @license    GPL-2.0
 * @version    1.0.0
 * @since      1.0.0
 */

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

if ( ! class_exists( 'UserQuizData' ) ) {

	/**
	 * User Quiz Data
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 */
	class UserQuizData {

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
		 * Get Quiz Data
		 *
		 * @author Jason Witt
		 * @since  1.0.0
		 *
		 * @return array
		 */
		public static function get_quiz_data() {
			$entries = self::get_entries_data();
			$forms   = self::get_form_data();
			$data    = array();

			foreach ( $entries as $entry ) {
				foreach ( $forms as $form ) {
					if ( $entry['form_id'] === $form['form_id'] ) {
						$data[] = array_merge( $entry, $form );
					}
				}
			}

			return self::array_merge_recursive_distinct( $entries, $forms );
		}

		/**
		 * Get Entries Data
		 *
		 * @author Jason Witt
		 * @since  1.0.0
		 *
		 * @return array
		 */
		private static function get_entries_data() {
			$data = array();

			$search_criteria['field_filters'][] = array(
				'key'   => 'created_by',
				'value' => get_current_user_id(),
			);

			$paging = $paging = array(
				'offset'    => 0,
				'page_size' => 999,
			);

			$entries = GFAPI::get_entries( 0, $search_criteria, null, $paging );

			$temp = array();

			if ( ! empty( $entries ) ) {

				foreach ( $entries as $entry ) {
					if ( isset( $entry['gquiz_is_pass'] ) ) {
						$datetime = self::get_datetime( $entry['date_updated'] ? $entry['date_updated'] : $entry['date_created'] );

						// TODO: REMOVE!
						// error_log( ': ' . print_r( $entry, true ) ); // phpcs:ignore

						if ( ! in_array( $entry['form_id'], $temp, true ) ) {

							$temp[] = $entry['form_id'];

							$data[] = array(
								'form_id'     => $entry['form_id'],
								'date'        => $datetime['date'],
								'time'        => $datetime['time'],
								'year'        => $datetime['year'],
								'score'       => $entry['gquiz_score'],
								'passed'      => $entry['gquiz_is_pass'],
								'passPercent' => $entry['gquiz_percent'],
								'has-entries' => true,
							);
						}

					}
				}
			} else {
				$data[] = array( 'has-entries' => false );
			}

			return $data;
		}

		/**
		 * Get Form Data
		 *
		 * @author Jason Witt
		 * @since  1.0.0
		 *
		 * @return array
		 */
		private static function get_form_data() {
			$data         = array();
			$entries_data = self::get_entries_data();
			foreach ( $entries_data as $entry_data ) {
				$form = GFAPI::get_form( $entry_data['form_id'] );

				$data[] = array(
					'form_id'         => $entry_data['form_id'],
					'title'           => rgar( $form, 'title' ),
					'cfp_program_id'  => rgar( $form, 'cfp_program_id' ),
					'imca_program_id' => rgar( $form, 'imca_program_id' ),
					'iar_program_id'  => rgar( $form, 'iar_program_id' ),
					'cfp_hours'       => rgar( $form, 'hours' ),
					'nasba_hours'     => rgar( $form, 'nasba_hours' ),
					'iar_epr'         => rgar( $form, 'iar_epr' ),
					'iar_pp'          => rgar( $form, 'iar_pp' ),
				);
			}

			return $data;
		}

		/**
		 * Get Datetime
		 *
		 * @author Jason Witt
		 * @since  1.0.0
		 *
		 * @param int $timestamp The Quiz timestamp.
		 *
		 * @return array
		 */
		private static function get_datetime( $timestamp ) {
			$date = new \DateTime( $timestamp, new \DateTimeZone( 'UTC' ) );
			$date->setTimezone( new \DateTimeZone( 'America/New_York' ) );

			$array = array(
				'date' => $date->format( 'n/d/Y' ),
				'time' => $date->format( 'g:i A' ),
				'year' => $date->format( 'Y' ),
			);

			return $array;
		}

		/**
		 * Array Merge Recursize Distinct
		 *
		 * @author Jason Witt
		 * @since  1.0.0
		 *
		 * @param array $array1 The first array.
		 * @param array $array2 The second array.
		 *
		 * @return array
		 */
		private static function array_merge_recursive_distinct( $array1, $array2 ) {
			$merged = $array1;

			foreach ( $array2 as $key => &$value ) {
				if ( is_array( $value ) && isset( $merged[ $key ] ) && is_array( $merged[ $key ] ) ) {
					$merged[ $key ] = self::array_merge_recursive_distinct( $merged[ $key ], $value );
				} else {
					$merged[ $key ] = $value;
				}
			}

			return $merged;
		}
	}
}
