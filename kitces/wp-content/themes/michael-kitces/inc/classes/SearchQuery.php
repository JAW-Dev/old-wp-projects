<?php
/**
 * Search Query
 *
 * @package    Kitces
 * @subpackage Kitces/Classes
 * @author     Objectiv
 * @copyright  Copyright (c) Date, Objectiv
 * @license    GPL-2.0
 */

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

if ( ! class_exists( 'SearchQuery' ) ) {

	/**
	 * Search Query
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 */
	class SearchQuery {

		/**
		 * Initialize the class
		 *
		 * @author Jason Witt
		 * @since  1.0.0
		 *
		 * @return void
		 */
		public function __construct() {
			$this->hooks();
		}

		/**
		 * Hooks
		 *
		 * @author Jason Witt
		 * @since  1.0.0
		 *
		 * @return void
		 */
		public function hooks() {
			add_filter( 'pre_get_posts', array( $this, 'search_query' ) );
		}

		/**
		 * Search Query
		 *
		 * @author Jason Witt
		 * @since  1.0.0
		 *
		 * @param WP_Query $query The WP Query object.
		 *
		 * @return void
		 */
		public function search_query( $query ) {

			if ( is_admin() || ! $query->is_main_query() || ! $query->is_search() ) {
				return;
			}

			$query->set( 'post_parent__not_in', array( 6719 ) );

			return $query;
		}
	}
}