<?php

if ( ! function_exists( 'objectiv_download_cpt' ) ) {

	// Register Custom Post Type
	function objectiv_download_cpt() {

		$labels = array(
			'name'                  => _x( 'Downloads', 'Post Type General Name', 'text_domain' ),
			'singular_name'         => _x( 'Download', 'Post Type Singular Name', 'text_domain' ),
			'menu_name'             => __( 'Downloads', 'text_domain' ),
			'name_admin_bar'        => __( 'Downloads', 'text_domain' ),
			'archives'              => __( 'Downloads Archives', 'text_domain' ),
			'parent_item_colon'     => __( 'Parent Item:', 'text_domain' ),
			'all_items'             => __( 'All Downloads', 'text_domain' ),
			'add_new_item'          => __( 'Add New Download', 'text_domain' ),
			'add_new'               => __( 'Add New', 'text_domain' ),
			'new_item'              => __( 'New Download', 'text_domain' ),
			'edit_item'             => __( 'Edit Download', 'text_domain' ),
			'update_item'           => __( 'Update Download', 'text_domain' ),
			'view_item'             => __( 'View Download', 'text_domain' ),
			'search_items'          => __( 'Search Downloads', 'text_domain' ),
			'not_found'             => __( 'Not found', 'text_domain' ),
			'not_found_in_trash'    => __( 'Not found in Trash', 'text_domain' ),
			'featured_image'        => __( 'Featured Image', 'text_domain' ),
			'set_featured_image'    => __( 'Set featured image', 'text_domain' ),
			'remove_featured_image' => __( 'Remove featured image', 'text_domain' ),
			'use_featured_image'    => __( 'Use as featured image', 'text_domain' ),
			'insert_into_item'      => __( 'Insert into Download', 'text_domain' ),
			'uploaded_to_this_item' => __( 'Uploaded to this Download', 'text_domain' ),
			'items_list'            => __( 'Downloads list', 'text_domain' ),
			'items_list_navigation' => __( 'Downloads list navigation', 'text_domain' ),
			'filter_items_list'     => __( 'Filter Downloads list', 'text_domain' ),
		);
		$args   = array(
			'label'               => __( 'Download', 'text_domain' ),
			'description'         => __( 'Post Type Description', 'text_domain' ),
			'labels'              => $labels,
			'supports'            => array( 'title', 'revisions' ),
			'taxonomies'          => array(),
			'hierarchical'        => true,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'menu_position'       => 5,
			'menu_icon'           => 'dashicons-upload',
			'show_in_admin_bar'   => true,
			'show_in_nav_menus'   => true,
			'can_export'          => true,
			'has_archive'         => true,
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			'rewrite'             => array(
				'slug' => 'downloads',
			),
		);
		register_post_type( 'download', $args );

	}
	add_action( 'init', 'objectiv_download_cpt', 0 );

}


// Rename the title text on creating a new post
function objectiv_change_download_title( $title ) {
	$screen = get_current_screen();

	if ( 'download' == $screen->post_type ) {
		$title = 'Download Title';
	}

	return $title;
}

add_filter( 'enter_title_here', 'objectiv_change_download_title' );
