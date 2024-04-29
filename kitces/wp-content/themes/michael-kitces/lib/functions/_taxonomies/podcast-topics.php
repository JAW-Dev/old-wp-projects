<?php

// Custom taxonomy for Topics on Podcast Episodes
if ( ! function_exists( 'objectiv_podcast_topics' ) ) {

	// Register Custom Taxonomy
	function objectiv_podcast_topics() {

		$labels = array(
			'name'                       => _x( 'Podcast Topics', 'Taxonomy General Name', 'text_domain' ),
			'singular_name'              => _x( 'Podcast Topic', 'Taxonomy Singular Name', 'text_domain' ),
			'menu_name'                  => __( 'Podcast Topics', 'text_domain' ),
			'all_items'                  => __( 'All Podcast Topics', 'text_domain' ),
			'parent_item'                => __( 'Parent Podcast Topic', 'text_domain' ),
			'parent_item_colon'          => __( 'Parent Podcast Topic:', 'text_domain' ),
			'new_item_name'              => __( 'New Podcast Topic Name', 'text_domain' ),
			'add_new_item'               => __( 'Add New Podcast Topic', 'text_domain' ),
			'edit_item'                  => __( 'Edit Podcast Topic', 'text_domain' ),
			'update_item'                => __( 'Update Podcast Topic', 'text_domain' ),
			'view_item'                  => __( 'View Podcast Topic', 'text_domain' ),
			'separate_items_with_commas' => __( 'Separate states with commas', 'text_domain' ),
			'add_or_remove_items'        => __( 'Add or remove states', 'text_domain' ),
			'choose_from_most_used'      => __( 'Choose from the most used', 'text_domain' ),
			'popular_items'              => __( 'Popular Podcast Topics', 'text_domain' ),
			'search_items'               => __( 'Search Podcast Topics', 'text_domain' ),
			'not_found'                  => __( 'Not Found', 'text_domain' ),
			'no_terms'                   => __( 'No states', 'text_domain' ),
			'items_list'                 => __( 'Podcast Topics list', 'text_domain' ),
			'items_list_navigation'      => __( 'Podcast Topics list navigation', 'text_domain' ),
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
		register_taxonomy( 'podcast-topic', array( 'post' ), $args );

	}
	add_action( 'init', 'objectiv_podcast_topics', 0 );

}
