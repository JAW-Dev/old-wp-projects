<?php

// Custom taxonomy for Industry Channel
if ( ! function_exists( 'objectiv_industry_channel' ) ) {

	// Register Custom Taxonomy
	function objectiv_industry_channel() {

		$labels = array(
			'name'                       => _x( 'Industry Channel', 'Taxonomy General Name', 'text_domain' ),
			'singular_name'              => _x( 'Industry Channel', 'Taxonomy Singular Name', 'text_domain' ),
			'menu_name'                  => __( 'Industry Channel', 'text_domain' ),
			'all_items'                  => __( 'All Industry Channels', 'text_domain' ),
			'parent_item'                => __( 'Parent Industry Channel', 'text_domain' ),
			'parent_item_colon'          => __( 'Parent Industry Channel:', 'text_domain' ),
			'new_item_name'              => __( 'New Industry Channel Name', 'text_domain' ),
			'add_new_item'               => __( 'Add New Industry Channel', 'text_domain' ),
			'edit_item'                  => __( 'Edit Industry Channel', 'text_domain' ),
			'update_item'                => __( 'Update Industry Channel', 'text_domain' ),
			'view_item'                  => __( 'View Industry Channel', 'text_domain' ),
			'separate_items_with_commas' => __( 'Separate Industry Channels with commas', 'text_domain' ),
			'add_or_remove_items'        => __( 'Add or remove Industry Channels', 'text_domain' ),
			'choose_from_most_used'      => __( 'Choose from the most used', 'text_domain' ),
			'popular_items'              => __( 'Popular Industry Channel', 'text_domain' ),
			'search_items'               => __( 'Search Industry Channel', 'text_domain' ),
			'not_found'                  => __( 'Not Found', 'text_domain' ),
			'no_terms'                   => __( 'No Industry Channels', 'text_domain' ),
			'items_list'                 => __( 'Industry Channels list', 'text_domain' ),
			'items_list_navigation'      => __( 'Industry Channels list navigation', 'text_domain' ),
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
		register_taxonomy( 'industry-channel', array( 'conference' ), $args );

	}
	add_action( 'init', 'objectiv_industry_channel', 0 );

}
