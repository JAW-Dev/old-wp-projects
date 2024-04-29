<?php
/**
 * User Meta
 *
 * @package    Kitces_Members
 * @subpackage Kitces_Members/Inlcudes/Classes/MemberActions/Merge
 * @author     Objectiv
 * @copyright  Copyright (c) 2021, Objectiv
 * @license    GNU General Public License v2 or later
 * @since      1.0.0
 */

namespace Kitces_Members\Includes\Classes\MemberActions\Merge;

/**
 * User Meta
 *
 * @author Jason Witt
 * @since  1.0.0
 */
class UserMeta extends Base {

	/**
	 * Do Merge
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return boolean
	 */
	public function do_merge() {
		$source_user_meta      = $this->get_all_user_meta( $this->get_source_wp_user()->ID );
		$destination_user_meta = $this->get_all_user_meta( $this->get_destination_wp_user()->ID );
		$merged_user_meta      = $this->array_merge_recursive_numeric( $source_user_meta, $destination_user_meta );
		$count                 = 0;

		foreach ( $merged_user_meta as $key => $value ) {
			if ( update_user_meta( $this->get_destination_wp_user()->ID, $key, $value ) ) {
				$count++;
			}
		}

		return $count > 0 ? $count : false;
	}

	/**
	 * Get All User Meta
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param string $user_id The user ID.
	 *
	 * @return array
	 */
	public function get_all_user_meta( $user_id ) {
		return array_map( array( $this, 'clean_meta' ), get_user_meta( $user_id ) );
	}

	/**
	 * Clean Meta
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param array $a The array.
	 *
	 * @return array
	 */
	public function clean_meta( $a ) {
		return $a[0];
	}

	/**
	 * Array Merge Recursive Numeric
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return array
	 */
	public function array_merge_recursive_numeric() {

		// Gather all arrays.
		$arrays = func_get_args();

		// If there's only one array, it's already merged.
		if ( count( $arrays ) === 1 ) {
			return $arrays[0];
		}

		// Remove any items in $arrays that are NOT arrays.
		foreach ( $arrays as $key => $array ) {
			if ( ! is_array( $array ) ) {
				unset( $arrays[ $key ] );
			}
		}

		// We start by setting the first array as our final array.
		// We will merge all other arrays with this one.
		$final = array_shift( $arrays );

		foreach ( $arrays as $b ) {

			foreach ( $final as $key => $value ) {

				// If $key does not exist in $b, then it is unique and can be safely merged.
				if ( ! isset( $b[ $key ] ) ) {

					$final[ $key ] = $value;

				} else {

					// If $key is present in $b, then we need to merge and sum numeric values in both.
					if ( is_numeric( $value ) && is_numeric( $b[ $key ] ) ) {
						// If both values for these keys are numeric, we sum them.
						$final[ $key ] = $value + $b[ $key ];
					} elseif ( is_array( $value ) && is_array( $b[ $key ] ) ) {
						// If both values are arrays, we recursively call yourself.
						$final[ $key ] = $this->array_merge_recursive_numeric( $value, $b[ $key ] );
					} else {
						// If both keys exist but differ in type, then we cannot merge them.
						// In this scenario, we will $b's value for $key is used.
						$final[ $key ] = $b[ $key ];
					}
				}
			}

			// Finally, we need to merge any keys that exist only in $b.
			foreach ( $b as $key => $value ) {
				if ( ! isset( $final[ $key ] ) ) {
					$final[ $key ] = $value;
				}
			}
		}

		return $final;
	}
}
