<?php
/**
 * Data
 *
 * @package    Custom_Reports
 * @subpackage Custom_Reports/Includes/Classes/Favorites
 * @author     Objectiv
 * @copyright  Copyright (c) 2021, Objectiv
 * @license    GPL-2.0
 * @version    1.0.0
 */

namespace CustomReports\Favorites;

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
		$users     = get_users();
		$favorites = [];
		$data      = [];

		foreach ( $users as $user ) {
			$user_id = $user->ID;
			$faves   = get_user_meta( $user_id, 'favorited_items', true );

			if ( empty( $faves ) ) {
				continue;
			}

			$favorites[] = $faves;
		}

		foreach ( $favorites as $favorite ) {
			foreach ( $favorite as $key => $value ) {
				$title = get_the_title( $value );

				if ( empty( $title ) ) {
					continue;
				}

				if ( ! array_key_exists( $key, $data ) ) {
					$data[ $key ]['ID']     = $value;
					$data[ $key ]['title']  = get_the_title( $value );
					$data[ $key ]['amount'] = 0;
				}

				$data[ $key ]['amount']++;
			}
		}

		$data = array_values( $data );

		return $data;
	}
}
