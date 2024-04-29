<?php

function register_testimonial_post_type() {

	$labels = array(
		'name'                  => _x( 'Testimonials', 'Post Type General Name', 'kitces' ),
		'singular_name'         => _x( 'Testimonial', 'Post Type Singular Name', 'kitces' ),
		'menu_name'             => __( 'Testimonials', 'kitces' ),
		'name_admin_bar'        => __( 'Testimonials', 'kitces' ),
		'parent_item_colon'     => __( 'Testimonial:', 'kitces' ),
		'all_items'             => __( 'All Testimonials', 'kitces' ),
		'add_new_item'          => __( 'Add New Testimonial', 'kitces' ),
		'add_new'               => __( 'Add New', 'kitces' ),
		'new_item'              => __( 'New Testimonial', 'kitces' ),
		'edit_item'             => __( 'Edit Testimonial', 'kitces' ),
		'update_item'           => __( 'Update Testimonial', 'kitces' ),
		'view_item'             => __( 'View Testimonial', 'kitces' ),
		'search_items'          => __( 'Search Testimonial', 'kitces' ),
		'not_found'             => __( 'Not found', 'kitces' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'kitces' ),
		'items_list'            => __( 'Testimonials List', 'kitces' ),
		'items_list_navigation' => __( 'Testimonials List Navigation', 'kitces' ),
		'filter_items_list'     => __( 'Filter Testimonials List', 'kitces' ),
	);
	$args   = array(
		'label'               => __( 'Testimonial', 'kitces' ),
		'description'         => __( 'Add testimonials to the site.', 'kitces' ),
		'labels'              => $labels,
		'supports'            => array( 'title', 'thumbnail' ),
		'hierarchical'        => false,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'menu_position'       => 5,
		'menu_icon'           => 'dashicons-testimonial',
		'show_in_admin_bar'   => true,
		'show_in_nav_menus'   => true,
		'can_export'          => false,
		'has_archive'         => false,
		'exclude_from_search' => true,
		'publicly_queryable'  => true,
		'capability_type'     => 'post',
	);
	register_post_type( 'testimonials', $args );

}
add_action( 'init', 'register_testimonial_post_type', 0 );
