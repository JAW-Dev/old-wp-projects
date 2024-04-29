<?php

// Custom taxonomy for Organization
if ( ! function_exists( 'objectiv_organization' ) ) {

	// Register Custom Taxonomy
	function objectiv_organization() {

		$labels = array(
			'name'                       => _x( 'Organization', 'Taxonomy General Name', 'text_domain' ),
			'singular_name'              => _x( 'Organization', 'Taxonomy Singular Name', 'text_domain' ),
			'menu_name'                  => __( 'Organization', 'text_domain' ),
			'all_items'                  => __( 'All Organizations', 'text_domain' ),
			'parent_item'                => __( 'Parent Organization', 'text_domain' ),
			'parent_item_colon'          => __( 'Parent Organization:', 'text_domain' ),
			'new_item_name'              => __( 'New Organization Name', 'text_domain' ),
			'add_new_item'               => __( 'Add New Organization', 'text_domain' ),
			'edit_item'                  => __( 'Edit Organization', 'text_domain' ),
			'update_item'                => __( 'Update Organization', 'text_domain' ),
			'view_item'                  => __( 'View Organization', 'text_domain' ),
			'separate_items_with_commas' => __( 'Separate organizations with commas', 'text_domain' ),
			'add_or_remove_items'        => __( 'Add or remove organizations', 'text_domain' ),
			'choose_from_most_used'      => __( 'Choose from the most used', 'text_domain' ),
			'popular_items'              => __( 'Popular Organizations', 'text_domain' ),
			'search_items'               => __( 'Search Organizations', 'text_domain' ),
			'not_found'                  => __( 'Not Found', 'text_domain' ),
			'no_terms'                   => __( 'No Organizations', 'text_domain' ),
			'items_list'                 => __( 'Organizations list', 'text_domain' ),
			'items_list_navigation'      => __( 'Organizations list navigation', 'text_domain' ),
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
		register_taxonomy( 'organization', array( 'conference' ), $args );

	}
	add_action( 'init', 'objectiv_organization', 0 );

}
