<?php
/**
 * Parse.
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

if ( ! class_exists( __NAMESPACE__ . '\\Parse' ) ) {

	/**
	 * Parse.
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 */
	class Parse {

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
		 * Args
		 *
		 * @author Jason Witt
		 * @since  1.0.0
		 *
		 * @param mixed $args     The arguments.
		 * @param mixed $defaults The default arguments.
		 *
		 * @return array
		 */
		public static function args( $args, $defaults = '' ) {
			if ( is_object( $args ) ) {
				$parsed_args = get_object_vars( $args );
			} elseif ( is_array( $args ) ) {
				$parsed_args =& $args;
			} else {
				parse_str( $args, $parsed_args );
			}

			if ( is_array( $defaults ) ) {
				return array_merge( $defaults, $parsed_args );
			}

			return $parsed_args;
		}

		/**
		 * Array to Object
		 *
		 * @author Jason Witt
		 * @since  1.0.0
		 *
		 * @param mixed $args     The arguments.
		 * @param mixed $defaults The default arguments.
		 *
		 * @return object
		 */
		public static function array_to_object( $args, $defaults ) {
			// Return orginial arguments if there are no defaults set.
			if ( empty( $defaults ) ) {
				return $args;
			}

			foreach ( $args as $arg_key => $arg_arrays ) {

				// Skip if is not an array.
				if ( ! is_array( $arg_arrays ) || empty( $arg_arrays ) ) {
					continue;
				}

				$args[ $arg_key ] = (object) $arg_arrays;
			}

			return (object) self::args( $args, $defaults );
		}
	}
}
