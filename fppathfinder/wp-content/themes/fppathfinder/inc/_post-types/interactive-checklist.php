<?php

if ( ! function_exists( 'objectiv_interactive_checklist_cpt' ) ) {

	// Register Custom Post Type
	function objectiv_interactive_checklist_cpt() {

		$labels = array(
			'name'                  => _x( 'Interactive Checklists', 'Post Type General Name', 'text_domain' ),
			'singular_name'         => _x( 'Interactive Checklist', 'Post Type Singular Name', 'text_domain' ),
			'menu_name'             => __( 'Interactive Checklists', 'text_domain' ),
			'name_admin_bar'        => __( 'Interactive Checklists', 'text_domain' ),
			'archives'              => __( 'Interactive Checklists Archives', 'text_domain' ),
			'parent_item_colon'     => __( 'Parent Item:', 'text_domain' ),
			'all_items'             => __( 'All Interactive Checklists', 'text_domain' ),
			'add_new_item'          => __( 'Add New Interactive Checklist', 'text_domain' ),
			'add_new'               => __( 'Add New', 'text_domain' ),
			'new_item'              => __( 'New Interactive Checklist', 'text_domain' ),
			'edit_item'             => __( 'Edit Interactive Checklist', 'text_domain' ),
			'update_item'           => __( 'Update Interactive Checklist', 'text_domain' ),
			'view_item'             => __( 'View Interactive Checklist', 'text_domain' ),
			'search_items'          => __( 'Search Interactive Checklists', 'text_domain' ),
			'not_found'             => __( 'Not found', 'text_domain' ),
			'not_found_in_trash'    => __( 'Not found in Trash', 'text_domain' ),
			'featured_image'        => __( 'Featured Image', 'text_domain' ),
			'set_featured_image'    => __( 'Set featured image', 'text_domain' ),
			'remove_featured_image' => __( 'Remove featured image', 'text_domain' ),
			'use_featured_image'    => __( 'Use as featured image', 'text_domain' ),
			'insert_into_item'      => __( 'Insert into Interactive Checklist', 'text_domain' ),
			'uploaded_to_this_item' => __( 'Uploaded to this Interactive Checklist', 'text_domain' ),
			'items_list'            => __( 'Interactive Checklists list', 'text_domain' ),
			'items_list_navigation' => __( 'Interactive Checklists list navigation', 'text_domain' ),
			'filter_items_list'     => __( 'Filter Interactive Checklists list', 'text_domain' ),
		);
		$args   = array(
			'label'               => __( 'Interactive Checklist', 'text_domain' ),
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
			'has_archive'         => false,
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			'rewrite'             => array(
				'slug' => 'checklist',
			),
		);
		register_post_type( 'checklist', $args );
	}
	add_action( 'init', 'objectiv_interactive_checklist_cpt', 0 );

}


// Rename the title text on creating a new post
function objectiv_change_interactive_checklist_title( $title ) {
	$screen = get_current_screen();

	if ( 'checklist' == $screen->post_type ) {
		$title = 'Interactive Checklist Title';
	}

	return $title;
}

add_filter( 'enter_title_here', 'objectiv_change_interactive_checklist_title' );
