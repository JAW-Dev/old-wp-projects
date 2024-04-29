<?php

if ( ! function_exists( 'mk_register_ot_event_post_type' ) ) {

	function mk_register_ot_event_post_type() {
		// Adding Custom Post Type called "Events"
		$args = array(
			'label'               => 'Events',
			'description'         => '',
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'capability_type'     => 'post',
			'menu_icon'           => 'dashicons-tickets-alt',
			'hierarchical'        => false,
			'rewrite'             => array( 'slug' => '' ),
			'query_var'           => true,
			'menu_position'       => 5,
			'supports'            => array( 'title', 'editor', 'excerpt', 'custom-fields' ),
			'labels'              => array(
				'name'               => 'Events',
				'singular_name'      => 'Event',
				'menu_name'          => 'Events',
				'add_new'            => 'Add Event',
				'add_new_item'       => 'Add New Event',
				'edit'               => 'Edit',
				'edit_item'          => 'Edit Event',
				'new_item'           => 'New Event',
				'view'               => 'View Event',
				'view_item'          => 'View Event',
				'search_items'       => 'Search Events',
				'not_found'          => 'No Events Found',
				'not_found_in_trash' => 'No Events Found in Trash',
				'parent'             => 'Parent Event',
			),
			'exclude_from_search' => true,
		);
		register_post_type( 'event', $args ); // end register post type
    }

    add_action( 'init', 'mk_register_ot_event_post_type', 0 );
}


// Allow sorting by ACF Date Meta
function mk_set_custom_edit_event_columns( $columns ) {

	$columns['event-date']    = __( 'Event Date', 'mg-mkitces' );
	$columns['event-speaker'] = __( 'Event Speaker', 'mg-mkitces' );

	return $columns;
}
add_filter( 'manage_event_posts_columns', 'mk_set_custom_edit_event_columns' );


function mk_custom_events_column( $column, $post_id ) {
	switch ( $column ) {

		case 'event-date':
			$start_date = mk_get_field( 'obj_event_start_date', $post_id );

			if ( $start_date ) {
				$start_date = date( 'Y/m/d', strtotime( $start_date ) );
				echo $start_date;
			} else {
				echo 'Date Not Set';
			}

			break;

		case 'event-speaker':
			$speaker = obj_get_event_speaker( $post_id );

			if ( $speaker ) {
				echo $speaker['label'];
			} else {
				echo 'Speaker Not Set';
			}

			break;
	}
}
add_action( 'manage_event_posts_custom_column', 'mk_custom_events_column', 10, 2 );


function mk_set_custom_event_sortable_columns( $columns ) {
	$columns['event-date']    = 'event-date';
	$columns['event-speaker'] = 'event-speaker';

	return $columns;
}
add_filter( 'manage_edit-event_sortable_columns', 'mk_set_custom_event_sortable_columns' );


function event_custom_orderby( $query ) {
	if ( ! is_admin() ) {
		return;
	}

	$orderby = $query->get( 'orderby' );

	if ( 'event-date' === $orderby ) {
		$query->set( 'meta_key', 'obj_event_start_date' );
		$query->set( 'orderby', 'meta_value_num' );
	}

	if ( 'event-speaker' === $orderby ) {
		$query->set( 'meta_key', 'speaker' );
		$query->set( 'orderby', 'meta_value' );
	}
}
add_action( 'pre_get_posts', 'event_custom_orderby' );
