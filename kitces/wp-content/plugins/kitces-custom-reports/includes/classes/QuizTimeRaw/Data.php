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

namespace CustomReports\QuizTimeRaw;

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
		$entries = ( new Query() )->get_entries();

		foreach ( $entries as $entry ) {
			if ( ! empty( $entry->user_id ) ) {
				$user  = get_user_by( 'ID', $entry->user_id );
				$email = ! empty( $user->user_email ) ? $user->user_email : '';
				$name  = ! empty( $user->display_name ) ? $user->display_name : '';

				$entry->user_email = '';
				$entry->user_name  = '';

				if ( ! empty( $email ) ) {
					$entry->user_email = $email;
				}

				if ( ! empty( $name ) ) {
					$entry->user_name  = $name;
				}
			}
		}

		return $entries;
	}
}
