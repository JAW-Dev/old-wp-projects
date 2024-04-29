<?php

// Add a Custom Post Type for Course Catalog Items
if ( ! function_exists( 'cgd_course_catalog_item' ) ) {

	// Register Custom Post Type
	function cgd_course_catalog_item() {
		$labels = array(
			'name'                  => _x( 'Course Catalog Items', 'Post Type General Name', 'text_domain' ),
			'singular_name'         => _x( 'CC Item', 'Post Type Singular Name', 'text_domain' ),
			'menu_name'             => __( 'Course Catalog', 'text_domain' ),
			'name_admin_bar'        => __( 'CC Items', 'text_domain' ),
			'archives'              => __( 'CC Items Archives', 'text_domain' ),
			'parent_item_colon'     => __( 'Parent Item:', 'text_domain' ),
			'all_items'             => __( 'All CC Items', 'text_domain' ),
			'add_new_item'          => __( 'Add New CC Item', 'text_domain' ),
			'add_new'               => __( 'Add New', 'text_domain' ),
			'new_item'              => __( 'New CC Item', 'text_domain' ),
			'edit_item'             => __( 'Edit CC Item', 'text_domain' ),
			'update_item'           => __( 'Update CC Item', 'text_domain' ),
			'view_item'             => __( 'View CC Item', 'text_domain' ),
			'search_items'          => __( 'Search CC Items', 'text_domain' ),
			'not_found'             => __( 'Not found', 'text_domain' ),
			'not_found_in_trash'    => __( 'Not found in Trash', 'text_domain' ),
			'featured_image'        => __( 'Featured Image', 'text_domain' ),
			'set_featured_image'    => __( 'Set featured image', 'text_domain' ),
			'remove_featured_image' => __( 'Remove featured image', 'text_domain' ),
			'use_featured_image'    => __( 'Use as featured image', 'text_domain' ),
			'insert_into_item'      => __( 'Insert into CC Item', 'text_domain' ),
			'uploaded_to_this_item' => __( 'Uploaded to this CC Item', 'text_domain' ),
			'items_list'            => __( 'CC Items list', 'text_domain' ),
			'items_list_navigation' => __( 'CC Items list navigation', 'text_domain' ),
			'filter_items_list'     => __( 'Filter CC Items list', 'text_domain' ),
		);
		$args   = array(
			'label'               => __( 'CC Item', 'text_domain' ),
			'description'         => __( 'Post Type Description', 'text_domain' ),
			'labels'              => $labels,
			'supports'            => array( 'title' ),
			'taxonomies'          => array(),
			'hierarchical'        => true,
			'public'              => false,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'menu_position'       => 5,
			'menu_icon'           => 'dashicons-editor-ul',
			'show_in_admin_bar'   => true,
			'show_in_nav_menus'   => true,
			'can_export'          => true,
			'has_archive'         => false,
			'exclude_from_search' => true,
			'publicly_queryable'  => false,
			'capability_type'     => 'page',
		);
		register_post_type( 'cc-item', $args );

	}

	add_action( 'init', 'cgd_course_catalog_item', 0 );
}

// Rename the title text on creating a new post
function cgd_change_cc_title_placeholder_text( $title ) {
	 $screen = get_current_screen();

	if ( 'cc-item' == $screen->post_type ) {
		 $title = 'Enter CC Item Title';
	}

	 return $title;
}
add_filter( 'enter_title_here', 'cgd_change_cc_title_placeholder_text' );
