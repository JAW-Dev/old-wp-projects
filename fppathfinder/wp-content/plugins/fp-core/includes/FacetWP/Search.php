<?php
/**
 * Search
 *
 * @package    FP_Core
 * @subpackage FP_Core/FacetWP
 * @author     Objectiv
 * @copyright  Copyright (c) 2020, Objectiv
 * @license    GPL-2.0
 * @version    1.0.0
 * @since      1.0.0
 */

namespace  FP_Core\FacetWP;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

if ( ! class_exists( __NAMESPACE__ . '\Search' ) ) {

	/**
	 * Search
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 */
	class Search {

		/**
		 * Is Search
		 *
		 * @author Jason Witt
		 * @since  1.0.0
		 *
		 * @var boolean
		 */
		protected $is_search = false;

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
			$this->is_search = isset( $_GET['fwp_search'] ) ? true : false;
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
			add_filter( 'posts_join', array( $this, 'search_join' ) );
			add_filter( 'posts_where', array( $this, 'search_where' ) );
			add_filter( 'posts_distinct',  array( $this, 'search_distinct' ) );
		}

		/**
		 * Search Join
		 *
		 * @author Jason Witt
		 * @since  1.0.0
		 *
		 * @return void
		 */
		public function search_join( $join ) {
			global $wpdb;

			if ( $this->is_search ) {
				$join .= ' LEFT JOIN ' . $wpdb->postmeta . ' ON ' . $wpdb->posts . '.ID = ' . $wpdb->postmeta . '.post_id ';
			}

			return $join;
		}

		/**
		 * Search Where
		 *
		 * @author Jason Witt
		 * @since  1.0.0
		 *
		 * @return void
		 */
		public function search_where( $where ) {
			global $pagenow, $wpdb;

			if ( $this->is_search ) {
				$where = preg_replace( "/\(\s*$wpdb->posts.post_title\s+LIKE\s*(\'[^\']+\')\s*\)/", "($wpdb->posts.post_title LIKE $1) OR ($wpdb->postmeta.meta_value LIKE $1)", $where );
			}

			return $where;
		}

		/**
		 * Search Distinct
		 *
		 * @author Jason Witt
		 * @since  1.0.0
		 *
		 * @return void
		 */
		public function search_distinct( $where ) {
			global $wpdb;

			if ( $this->is_search ) {
				return 'DISTINCT';
			}

			return $where;
		}
	}
}
