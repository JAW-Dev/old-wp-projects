<?php

// Add a Custom Post Type for Conferences
if ( ! function_exists( 'cgd_conference' ) ) {

	// Register Custom Post Type
	function cgd_conference() {
		$labels = array(
			'name'                  => _x( 'Conferences', 'Post Type General Name', 'text_domain' ),
			'singular_name'         => _x( 'Conference', 'Post Type Singular Name', 'text_domain' ),
			'menu_name'             => __( 'Conferences', 'text_domain' ),
			'name_admin_bar'        => __( 'Conferences', 'text_domain' ),
			'archives'              => __( 'Conferences Archives', 'text_domain' ),
			'parent_item_colon'     => __( 'Parent Item:', 'text_domain' ),
			'all_items'             => __( 'All Conferences', 'text_domain' ),
			'add_new_item'          => __( 'Add New Conference', 'text_domain' ),
			'add_new'               => __( 'Add New', 'text_domain' ),
			'new_item'              => __( 'New Conference', 'text_domain' ),
			'edit_item'             => __( 'Edit Conference', 'text_domain' ),
			'update_item'           => __( 'Update Conference', 'text_domain' ),
			'view_item'             => __( 'View Conference', 'text_domain' ),
			'search_items'          => __( 'Search Conferences', 'text_domain' ),
			'not_found'             => __( 'Not found', 'text_domain' ),
			'not_found_in_trash'    => __( 'Not found in Trash', 'text_domain' ),
			'featured_image'        => __( 'Featured Image', 'text_domain' ),
			'set_featured_image'    => __( 'Set featured image', 'text_domain' ),
			'remove_featured_image' => __( 'Remove featured image', 'text_domain' ),
			'use_featured_image'    => __( 'Use as featured image', 'text_domain' ),
			'insert_into_item'      => __( 'Insert into Conference', 'text_domain' ),
			'uploaded_to_this_item' => __( 'Uploaded to this Conference', 'text_domain' ),
			'items_list'            => __( 'Conferences list', 'text_domain' ),
			'items_list_navigation' => __( 'Conferences list navigation', 'text_domain' ),
			'filter_items_list'     => __( 'Filter Conferences list', 'text_domain' ),
		);
		$args   = array(
			'label'               => __( 'Conference', 'text_domain' ),
			'description'         => __( 'Post Type Description', 'text_domain' ),
			'labels'              => $labels,
			'supports'            => array( 'title' ),
			'taxonomies'          => array(),
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'menu_position'       => 5,
			'menu_icon'           => 'dashicons-yes-alt',
			'show_in_admin_bar'   => true,
			'show_in_nav_menus'   => true,
			'can_export'          => true,
			'has_archive'         => false,
			'exclude_from_search' => true,
			'publicly_queryable'  => true,
			'capability_type'     => 'page',
			'rewrite'             => array( 'with_front' => false ),
		);
		register_post_type( 'conference', $args );

	}

	add_action( 'init', 'cgd_conference', 0 );
}

// Rename the title text on creating a new post
function cgd_change_conference_title_placeholder_text( $title ) {
	 $screen = get_current_screen();

	if ( 'conference' == $screen->post_type ) {
		 $title = 'Enter Conference Title';
	}

	 return $title;
}
add_filter( 'enter_title_here', 'cgd_change_conference_title_placeholder_text' );


function cgd_change_returned_result_select_post( $title, $post, $field, $post_id ) {
	if ( $post->post_type === 'event' ) {
		$event_start_date        = obj_get_event_start_date( $post->ID );
		$event_start_date_string = null;

		if ( empty( $event_start_date ) ) {
			$event_start_date_string = ' | Date: Not Set';
		} else {
			$event_start_date        = date_i18n( 'Y-m-d', $event_start_date );
			$event_start_date_string = ' | Date: ' . $event_start_date;
		}

		if ( $event_start_date_string && ! empty( $event_start_date_string ) ) {
			$title .= $event_start_date_string;
		}
	}

	$title .= ' | ID: ' . $post->ID;

	  return $title;
}

add_filter( 'acf/fields/post_object/result', 'cgd_change_returned_result_select_post', 10, 4 );
