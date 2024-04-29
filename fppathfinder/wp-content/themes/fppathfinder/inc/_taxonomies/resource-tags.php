<?php

// Custom taxonomy for Posts
if ( ! function_exists( 'objectiv_custom_tax_resource_tag' ) ) {

	// Register Custom Taxonomy
	function objectiv_custom_tax_resource_tag() {

		$labels = array(
			'name'                       => _x( 'Resource Tags', 'Taxonomy General Name', 'text_domain' ),
			'singular_name'              => _x( 'Resource Tag', 'Taxonomy Singular Name', 'text_domain' ),
			'menu_name'                  => __( 'Resource Tags', 'text_domain' ),
			'all_items'                  => __( 'All Resource Tags', 'text_domain' ),
			'parent_item'                => __( 'Parent Resource Tag', 'text_domain' ),
			'parent_item_colon'          => __( 'Parent Project Idea:', 'text_domain' ),
			'new_item_name'              => __( 'New Project Idea Name', 'text_domain' ),
			'add_new_item'               => __( 'Add New Resource Tag', 'text_domain' ),
			'edit_item'                  => __( 'Edit Resource Tag', 'text_domain' ),
			'update_item'                => __( 'Update Resource Tag', 'text_domain' ),
			'view_item'                  => __( 'View Resource Tag', 'text_domain' ),
			'separate_items_with_commas' => __( 'Separate Resource Tags with commas', 'text_domain' ),
			'add_or_remove_items'        => __( 'Add or remove Resource Tags', 'text_domain' ),
			'choose_from_most_used'      => __( 'Choose from the most used', 'text_domain' ),
			'popular_items'              => __( 'Popular Resource Tags', 'text_domain' ),
			'search_items'               => __( 'Search Resource Tags', 'text_domain' ),
			'not_found'                  => __( 'Not Found', 'text_domain' ),
			'no_terms'                   => __( 'No Resource Tags', 'text_domain' ),
			'items_list'                 => __( 'Resource Tags list', 'text_domain' ),
			'items_list_navigation'      => __( 'Resource Tags list navigation', 'text_domain' ),
		);
		$args   = array(
			'labels'            => $labels,
			'hierarchical'      => false,
			'public'            => true,
			'show_ui'           => true,
			'show_admin_column' => true,
			'show_in_nav_menus' => true,
			'show_tagcloud'     => true,
		);
		register_taxonomy( 'resource-tag', array( 'resource' ), $args );

	}
	add_action( 'init', 'objectiv_custom_tax_resource_tag', 0 );

}
