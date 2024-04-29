<?php
/**
 * GravityFroms Shortcode Finder
 *
 * @package    Kitces
 * @subpackage Kitces/classes
 * @author     Objectiv
 * @copyright  Copyright (c) Date, Objectiv
 * @license    GPL-2.0
 */

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

if ( ! class_exists( 'GFShortcodeFinder' ) ) {

	/**
	 * User Quiz Table
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 */
	class GFShortcodeFinder {

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
		 * Find the Forms.
		 *
		 * @author Jason Witt
		 * @since  1.0.0
		 *
		 * @param int $form_id ID of the Gravity Form to search for.
		 *
		 * @return array Pages that contain the form. Array is in this format: $post_id => $post_title
		 */
		public function find( $form_id ) {
			return array_reduce(
				$this->get_all_page_ids(),
				function( $pages, $page_id ) use ( $form_id ) {
					if ( $form_id ) {
						if ( in_array( $form_id, $this->get_form_ids_in_post_content( $page_id ), true ) ) {
							$pages[ $page_id ] = get_the_title( $page_id );
						}
					}
					return $pages;
				},
				array()
			);
		}

		/**
		 * Get page IDs.
		 *
		 * @author Jason Witt
		 * @since  1.0.0
		 *
		 * @return array Post IDs for all pages.
		 */
		private function get_all_page_ids() {
			return get_posts(
				array(
					'post_type'      => 'page',
					'posts_per_page' => -1,
					'fields'         => 'ids',
				)
			);
		}

		/**
		 * Get the form ID's
		 *
		 * @author Jason Witt
		 * @since  1.0.0
		 *
		 * @param  int $page_id Page ID.
		 *
		 * @return array Gravity Form IDs.
		 */
		private function get_form_ids_in_post_content( $page_id ) {
			$content = get_post_field( 'post_content', $page_id );
			return $this->get_shortcode_ids( $this->get_shortcodes_from_content( $content, 'gravityform' ) );
		}

		/**
		 * Get the shortcodes
		 *
		 * @author Jason Witt
		 * @since  1.0.0
		 *
		 * @param  string $content   Post content.
		 * @param  string $shortcode The shortcode to search for.
		 *
		 * @return array  The shortcodes found or empty array if none.
		 */
		private function get_shortcodes_from_content( $content, $shortcode ) {
			$matches_found = preg_match_all( '/' . get_shortcode_regex() . '/s', $content, $matches );
			if ( ! $matches_found || empty( $matches[2] ) || ! in_array( $shortcode, $matches[2], true ) ) {
				return array();
			}
			return $matches[0];
		}

		/**
		 * Extracts IDs from shortcodes.
		 *
		 * @author Jason Witt
		 * @since  1.0.0
		 *
		 * @param string $shortcodes The shortcodes to get the IDs from.
		 *
		 * @return array $ids The shortcode IDs.
		 */
		private function get_shortcode_ids( $shortcodes ) {
			return array_reduce(
				$shortcodes,
				function( $ids, $shortcode ) {
					$id = $this->get_shortcode_id( $shortcode );
					if ( $id ) {
						$ids[] = $id;
					}
					return $ids;
				},
				array()
			);
		}

		/**
		 * Extract the 'id' parameter from a shortcode.
		 *
		 * @author Jason Witt
		 * @since  1.0.0
		 *
		 * @param  string $shortcode The shortcode.
		 *
		 * @return int The ID, or 0 if none found.
		 */
		private function get_shortcode_id( $shortcode ) {
			$match_found = preg_match( '~id=[\"\']?([^\"\'\s]+)[\"\']?~i', $shortcode, $form_id );
			return $match_found ? (int) $form_id[1] : 0;
		}

	}
}