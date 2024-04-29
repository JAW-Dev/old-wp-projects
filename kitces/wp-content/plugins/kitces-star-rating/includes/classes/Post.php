<?php
/**
 * Post.
 *
 * @package    Kitces_Star_Rating
 * @subpackage Kitces_Star_Rating/Inlcudes/Classes
 * @author     Objectiv
 * @copyright  Copyright (c) Plugin_Boilerplate_Date, Objectiv
 * @license    GPL-2.0
 * @since      1.0.0
 */

namespace KitcesStarRating\Includes\Classes;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

if ( ! class_exists( __NAMESPACE__ . '\\Post' ) ) {

	/**
	 * Post.
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 */
	class Post {

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
		 * Get Post Request.
		 *
		 * @author Jason Witt
		 * @since  1.0.0
		 *
		 * @param array $post The post request.
		 *
		 * @return array
		 */
		public static function request( $post = null ) {

			if ( null === $post ) {
				$post = $_POST; // phpcs:ignore
			}

			// Bail and return an empty array if there is no post data.
			if ( ! $_POST || empty( $_POST ) ) { // phpcs:ignore
				return array();
			}

			// Ge the sanitized $_POST data.
			$sanitized = self::sanitize_array( $post );

			// If there is sanitized data return it else return false.
			return ! empty( $sanitized ) ? $sanitized : false;
		}

		/**
		 * Sanitize the $_POST array values.
		 *
		 * @author Jason Witt
		 * @since  1.0.0
		 *
		 * @param array $array The $post array.
		 *
		 * @return array
		 */
		public static function sanitize_array( $array ) {

			// If the $array is an array.
			if ( is_array( $array ) ) {

				// Loop through the array.
				foreach ( $array as $entry ) {

					// If entry is not an array.
					if ( ! is_array( $entry ) ) {

						// Sanitize the data.
						$entry = sanitize_text_field( $entry );
					} else { // Else if entry is an array.

						// Run through the loop again.
						self::sanitize_array( $entry );
					}
				}

				// Return the sanitized array.
				return $array;
			}
			return array();
		}

		/**
		 * Data
		 *
		 * @author Jason Witt
		 * @since  1.0.0
		 *
		 * @return array
		 */
		public static function data() {
			$post = self::request();

			if ( ! empty( $post ) ) {
				$nonce = $post['nonce'] ? wp_unslash( sanitize_text_field( $post['nonce'] ) ) : '';

				if ( ! wp_verify_nonce( $nonce, 'user_star_rating' ) ) {
					return;
				}

				return $post;
			}
		}
	}
}
