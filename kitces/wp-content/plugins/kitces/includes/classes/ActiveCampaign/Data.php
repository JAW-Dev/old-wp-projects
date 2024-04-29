<?php
/**
 * Data.
 *
 * @package    Kitces
 * @subpackage Kitces/Includes/Classes/ActiveCampaign
 * @author     Objectiv
 * @copyright  Copyright (c) 2020, Objectiv
 * @license    GNU General Public License v2 or later
 * @since      1.0.0
 */

namespace Kitces\Includes\Classes\ActiveCampaign;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

/**
 * Data.
 *
 * @author Objectiv
 * @since  1.0.0
 */
class Data {

	/**
	 * Initialize the class
	 *
	 * @author Objectiv
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function __construct() {
	}

	/**
	 * Admin Entry Map
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return array
	 */
	public function admin_entry_map() {
		return array(
			'2' => 'first_name',
			'3' => 'last_name',
			'4' => 'email',
		);
	}

	/**
	 * Get Admin Data
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param array $entries The form entries.
	 *
	 * @return array
	 */
	public function get_admin_data( $entries = array() ) {
		$array = array();
		$map   = $this->admin_entry_map();

		if ( ! empty( $entries ) ) {
			$entry_data = array();

			foreach ( $entries as $key => $value ) {
				$entry_key   = '';

				foreach ( $map as $map_key => $map_value ) {
					if ( (string) $key === (string) $map_key ) {
						$entry_key = $map_value;
						break;
					}
				}

				if ( ! empty( $entry_key ) ) {
					$entry_data[ $entry_key ] = $value;
				}
			}

			$array[] = array_filter( $entry_data );
		}

		return $array;
	}

	/**
	 * Member Entry Map
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return array
	 */
	public function member_entry_map() {
		return array(
			'1' => 'first_name',
			'2' => 'last_name',
			'3' => 'email',
			'5' => 'is_current_member',
		);
	}

	/**
	 * Get Members Data
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param array $entries The array of group members entry IDs.
	 *
	 * @return array
	 */
	public function get_members_data( $entries = array() ) {
		global $wpdb;

		$array = array();

		if ( ! empty( $entries ) ) {
			foreach ( $entries as $entry ) {
				$table   = $wpdb->prefix . 'gf_entry_meta';
				$results = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $table WHERE %d = entry_id", $entry ) );

				if ( ! empty( $results ) ) {
					$array[] = $results;
				}
			}
		}

		$formated_meta = $this->format_members_meta( $array, $this->member_entry_map() );

		return $formated_meta;
	}

	/**
	 * Format Members Meta
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param array $entries The array of entry.
	 * @param array $map     Array of items to map entries to.
	 *
	 * @return array
	 */
	public function format_members_meta( $entries = array(), $map ) {
		$array = array();

		if ( ! empty( $entries ) ) {
			foreach ( $entries as $entry ) {
				$entry_data = array();

				foreach ( $entry as $data ) {
					$key   = '';
					$value = '';

					foreach ( $map as $map_key => $map_value ) {
						if ( (string) $data->meta_key === (string) $map_key ) {
							$key = $map_value;
							break;
						}
					}

					if ( ! empty( $key ) ) {
						$entry_data[ $key ] = $data->meta_value;
					}
				}

				$array[] = array_filter( $entry_data );
			}
		}

		return $array;
	}
}
