<?php

// Custom taxonomy for Posts
if ( ! function_exists( 'objectiv_custom_tax_resource_cat' ) ) {

	// Register Custom Taxonomy
	function objectiv_custom_tax_resource_cat() {

		$labels = array(
			'name'                       => _x( 'Resource Categories', 'Taxonomy General Name', 'text_domain' ),
			'singular_name'              => _x( 'Resource Category', 'Taxonomy Singular Name', 'text_domain' ),
			'menu_name'                  => __( 'Resource Categories', 'text_domain' ),
			'all_items'                  => __( 'All Resource Categories', 'text_domain' ),
			'parent_item'                => __( 'Parent Resource Category', 'text_domain' ),
			'parent_item_colon'          => __( 'Parent Project Idea:', 'text_domain' ),
			'new_item_name'              => __( 'New Project Idea Name', 'text_domain' ),
			'add_new_item'               => __( 'Add New Resource Category', 'text_domain' ),
			'edit_item'                  => __( 'Edit Resource Category', 'text_domain' ),
			'update_item'                => __( 'Update Resource Category', 'text_domain' ),
			'view_item'                  => __( 'View Resource Category', 'text_domain' ),
			'separate_items_with_commas' => __( 'Separate Resource Categories with commas', 'text_domain' ),
			'add_or_remove_items'        => __( 'Add or remove Resource Categories', 'text_domain' ),
			'choose_from_most_used'      => __( 'Choose from the most used', 'text_domain' ),
			'popular_items'              => __( 'Popular Resource Categories', 'text_domain' ),
			'search_items'               => __( 'Search Resource Categories', 'text_domain' ),
			'not_found'                  => __( 'Not Found', 'text_domain' ),
			'no_terms'                   => __( 'No Resource Categories', 'text_domain' ),
			'items_list'                 => __( 'Resource Categories list', 'text_domain' ),
			'items_list_navigation'      => __( 'Resource Categories list navigation', 'text_domain' ),
		);
		$args   = array(
			'labels'            => $labels,
			'hierarchical'      => true,
			'public'            => true,
			'show_ui'           => true,
			'show_admin_column' => true,
			'show_in_nav_menus' => true,
			'show_tagcloud'     => true,
		);
		register_taxonomy( 'resource-cat', array( 'resource' ), $args );

	}
	add_action( 'init', 'objectiv_custom_tax_resource_cat', 0 );

}
