<?php
/**
 * Flowchart
 *
 * @package    Package_Name
 * @subpackage Package_Name/Subpackage_Name
 * @author     Author_Name
 * @copyright  Copyright (c) Date, Author_Name
 * @license    GPL-2.0
 * @version    1.0.0
 * @since      1.0.0
 */

namespace FP_Core\InteractiveLists\PostTypes;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

/**
 * Flowchart
 *
 * @author Jason Witt
 * @since  1.0.0
 */
class Flowchart {

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
		add_action( 'init', array( $this, 'post_type' ) );
		add_action( 'add_meta_boxes', array( $this, 'remove_metaboxes' ), 9999 );
	}

	/**
	 * Post Type
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function post_type() {
		$labels = array(
			'name'                  => _x( 'Flowcharts', 'Post Type General Name', 'fp_core' ),
			'singular_name'         => _x( 'Flowchart', 'Post Type Singular Name', 'fp_core' ),
			'menu_name'             => __( 'Flowcharts', 'fp_core' ),
			'name_admin_bar'        => __( 'Flowcharts', 'fp_core' ),
			'archives'              => __( 'Flowcharts Archives', 'fp_core' ),
			'parent_item_colon'     => __( 'Parent Item:', 'fp_core' ),
			'all_items'             => __( 'All Flowcharts', 'fp_core' ),
			'add_new_item'          => __( 'Add New Flowchart', 'fp_core' ),
			'add_new'               => __( 'Add New', 'fp_core' ),
			'new_item'              => __( 'New Flowchart', 'fp_core' ),
			'edit_item'             => __( 'Edit Flowchart', 'fp_core' ),
			'update_item'           => __( 'Update Flowchart', 'fp_core' ),
			'view_item'             => __( 'View Flowchart', 'fp_core' ),
			'search_items'          => __( 'Search Flowcharts', 'fp_core' ),
			'not_found'             => __( 'Not found', 'fp_core' ),
			'not_found_in_trash'    => __( 'Not found in Trash', 'fp_core' ),
			'featured_image'        => __( 'Featured Image', 'fp_core' ),
			'set_featured_image'    => __( 'Set featured image', 'fp_core' ),
			'remove_featured_image' => __( 'Remove featured image', 'fp_core' ),
			'use_featured_image'    => __( 'Use as featured image', 'fp_core' ),
			'insert_into_item'      => __( 'Insert into Flowchart', 'fp_core' ),
			'uploaded_to_this_item' => __( 'Uploaded to this Flowchart', 'fp_core' ),
			'items_list'            => __( 'Flowcharts list', 'fp_core' ),
			'items_list_navigation' => __( 'Flowcharts list navigation', 'fp_core' ),
			'filter_items_list'     => __( 'Filter Flowcharts list', 'fp_core' ),
		);
		$args   = array(
			'label'               => __( 'FlowCharts', 'text_domain' ),
			'description'         => __( 'Post Type Description', 'fp_core' ),
			'labels'              => $labels,
			'supports'            => array( 'title', 'revisions' ),
			'taxonomies'          => array( 'resource-cat', 'resource-tag' ),
			'hierarchical'        => true,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'menu_position'       => 6,
			'menu_icon'           => 'dashicons-rest-api',
			'show_in_admin_bar'   => true,
			'show_in_nav_menus'   => true,
			'can_export'          => true,
			'has_archive'         => true,
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			'rewrite'             => array(
				'slug' => 'flowchart',
			),
		);
		register_post_type( 'flowchart', $args );
	}

	/**
	 * Remove Metaboxes
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function remove_metaboxes() {
		$screen = get_current_screen();

		if ( $screen->post_type === 'flowchart' ) {
			remove_meta_box( 'wpseo_meta', $screen->id, 'normal' );
			remove_meta_box( 'pys-head-footer', $screen->id, 'advanced' );
		}
	}
}
