<?php

// Add a Custom Post Type for Kitces Events
if ( ! function_exists( 'mk_register_kitces_event_post_type' ) ) {

	// Register Custom Post Type
	function mk_register_kitces_event_post_type() {
		$labels = array(
			'name'                  => _x( 'Kitces Events', 'Post Type General Name', 'mk' ),
			'singular_name'         => _x( 'Kitces Event', 'Post Type Singular Name', 'mk' ),
			'menu_name'             => __( 'Kitces Events', 'mk' ),
			'name_admin_bar'        => __( 'Kitces Events', 'mk' ),
			'archives'              => __( 'Kitces Events Archives', 'mk' ),
			'parent_item_colon'     => __( 'Parent Item:', 'mk' ),
			'all_items'             => __( 'All Kitces Events', 'mk' ),
			'add_new_item'          => __( 'Add New Kitces Event', 'mk' ),
			'add_new'               => __( 'Add New', 'mk' ),
			'new_item'              => __( 'New Kitces Event', 'mk' ),
			'edit_item'             => __( 'Edit Kitces Event', 'mk' ),
			'update_item'           => __( 'Update Kitces Event', 'mk' ),
			'view_item'             => __( 'View Kitces Event', 'mk' ),
			'search_items'          => __( 'Search Kitces Events', 'mk' ),
			'not_found'             => __( 'Not found', 'mk' ),
			'not_found_in_trash'    => __( 'Not found in Trash', 'mk' ),
			'featured_image'        => __( 'Featured Image', 'mk' ),
			'set_featured_image'    => __( 'Set featured image', 'mk' ),
			'remove_featured_image' => __( 'Remove featured image', 'mk' ),
			'use_featured_image'    => __( 'Use as featured image', 'mk' ),
			'insert_into_item'      => __( 'Insert into Kitces Event', 'mk' ),
			'uploaded_to_this_item' => __( 'Uploaded to this Kitces Event', 'mk' ),
			'items_list'            => __( 'Kitces Events list', 'mk' ),
			'items_list_navigation' => __( 'Kitces Events list navigation', 'mk' ),
			'filter_items_list'     => __( 'Filter Kitces Events list', 'mk' ),
		);
		$args   = array(
			'label'               => __( 'Kitces Event', 'mk' ),
			'labels'              => $labels,
			'supports'            => array( 'title', 'editor', 'thumbnail', 'revisions' ),
			'taxonomies'          => array(),
			'hierarchical'        => false,
			'public'              => false,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'menu_position'       => 5,
			'menu_icon'           => 'dashicons-tickets-alt',
			'show_in_admin_bar'   => true,
			'show_in_nav_menus'   => true,
			'can_export'          => true,
			'has_archive'         => false,
			'exclude_from_search' => true,
			'publicly_queryable'  => true,
			'capability_type'     => 'page',
			'rewrite'             => array( 'with_front' => false ),
		);
		register_post_type( 'kitces-event', $args );

	}

	add_action( 'init', 'mk_register_kitces_event_post_type', 0 );
}

// Rename the title text on creating a new post
function cgd_change_kitces_event_title_placeholder_text( $title ) {
	 $screen = get_current_screen();

	if ( 'kitces_event' == $screen->post_type ) {
		 $title = 'Enter Event Title';
	}

	 return $title;
}
add_filter( 'enter_title_here', 'cgd_change_kitces_event_title_placeholder_text' );


// Allow sorting by ACF Date Meta
function mk_set_custom_edit_kitces_event_columns( $columns ) {

	$columns['kitces-event-date'] = __( 'Event Date', 'mg-mkitces' );

	return $columns;
}
add_filter( 'manage_kitces-event_posts_columns', 'mk_set_custom_edit_kitces_event_columns' );


function mk_custom_kitces_events_column( $column, $post_id ) {
	switch ( $column ) {

		case 'kitces-event-date':
			$start_date = mk_get_field( 'date_time', $post_id );

			if ( $start_date ) {
				$start_date = date( 'Y/m/d g:i a', strtotime( $start_date ) );
				echo $start_date;
			} else {
				echo 'Date Not Set';
			}

			break;
	}
}
add_action( 'manage_kitces-event_posts_custom_column', 'mk_custom_kitces_events_column', 10, 2 );


function mk_set_custom_kitces_event_sortable_columns( $columns ) {
	$columns['kitces-event-date'] = 'kitces-event-date';

	return $columns;
}
add_filter( 'manage_edit-kitces-event_sortable_columns', 'mk_set_custom_kitces_event_sortable_columns' );


function kitces_event_custom_orderby( $query ) {
	if ( ! is_admin() ) {
		return;
	}

	$orderby = $query->get( 'orderby' );

	if ( 'kitces-event-date' === $orderby ) {
		$query->set( 'orderby', 'meta_value' );
		$query->set( 'meta_key', 'date_time' );
	}
}
add_action( 'pre_get_posts', 'kitces_event_custom_orderby' );
