<?php
/**
 * Taxonomies
 *
 * @package    Kitces
 * @subpackage Kitces/Classes
 * @author     Objectiv
 * @copyright  2020 (c) Date, Objectiv
 * @license    GPL-2.0
 * @version    1.0.0
 * @since      1.0.0
 */

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

if ( ! class_exists( 'Taxonomies' ) ) {

	/**
	 * Taxonomies
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 */
	class Taxonomies {

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
			add_action( 'init', array( $this, 'page_tags' ) );
		}

		/**
		 * Page Tags
		 *
		 * @author Jason Witt
		 * @since  1.0.0
		 *
		 * @return void
		 */
		public function page_tags() {
			$labels = array(
				'name'              => _x( 'Page Tags', 'taxonomy general name' ),
				'singular_name'     => _x( 'Page Tag', 'taxonomy singular name' ),
				'search_items'      => __( 'Search Page Tag' ),
				'all_items'         => __( 'All Page Tags' ),
				'parent_item'       => __( 'Parent Page Tag' ),
				'parent_item_colon' => __( 'Parent Page Tag:' ),
				'edit_item'         => __( 'Edit Page Tag' ),
				'update_item'       => __( 'Update Page Tag' ),
				'add_new_item'      => __( 'Add New Page Tag' ),
				'new_item_name'     => __( 'New Page Tag Name' ),
				'menu_name'         => __( 'Page Tags' ),
			);

			register_taxonomy(
				'page_tags',
				array( 'page' ),
				array(
					'hierarchical'      => false,
					'labels'            => $labels,
					'show_ui'           => true,
					'show_admin_column' => true,
					'query_var'         => true,
					'_builtin'          => true,
					'rewrite'           => array( 'slug' => 'page_tags' ),
				)
			);
		}
	}
}