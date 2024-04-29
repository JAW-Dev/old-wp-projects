<?php

// Custom taxonomy for Focus
if ( ! function_exists( 'objectiv_content_focus' ) ) {

	// Register Custom Taxonomy
	function objectiv_content_focus() {

		$labels = array(
			'name'                       => _x( 'Content Focus', 'Taxonomy General Name', 'text_domain' ),
			'singular_name'              => _x( 'Content Focus', 'Taxonomy Singular Name', 'text_domain' ),
			'menu_name'                  => __( 'Content Focus', 'text_domain' ),
			'all_items'                  => __( 'All Content Focus', 'text_domain' ),
			'parent_item'                => __( 'Parent Content Focus', 'text_domain' ),
			'parent_item_colon'          => __( 'Parent Content Focus:', 'text_domain' ),
			'new_item_name'              => __( 'New Content Focus Name', 'text_domain' ),
			'add_new_item'               => __( 'Add New Content Focus', 'text_domain' ),
			'edit_item'                  => __( 'Edit Content Focus', 'text_domain' ),
			'update_item'                => __( 'Update Content Focus', 'text_domain' ),
			'view_item'                  => __( 'View Content Focus', 'text_domain' ),
			'separate_items_with_commas' => __( 'Separate states with commas', 'text_domain' ),
			'add_or_remove_items'        => __( 'Add or remove states', 'text_domain' ),
			'choose_from_most_used'      => __( 'Choose from the most used', 'text_domain' ),
			'popular_items'              => __( 'Popular Content Focus', 'text_domain' ),
			'search_items'               => __( 'Search Content Focus', 'text_domain' ),
			'not_found'                  => __( 'Not Found', 'text_domain' ),
			'no_terms'                   => __( 'No states', 'text_domain' ),
			'items_list'                 => __( 'Content Focus list', 'text_domain' ),
			'items_list_navigation'      => __( 'Content Focus list navigation', 'text_domain' ),
		);
		$args   = array(
			'labels'            => $labels,
			'hierarchical'      => true,
			'public'            => false,
			'show_ui'           => true,
			'show_admin_column' => true,
			'show_in_nav_menus' => true,
			'show_tagcloud'     => true,
		);
		register_taxonomy( 'focus', array( 'conference' ), $args );

	}
	add_action( 'init', 'objectiv_content_focus', 0 );

}
