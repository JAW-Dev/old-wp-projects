<?php

function register_person_post_type() {

	$labels = array(
		'name'                  => _x( 'People', 'Post Type General Name', 'kitces' ),
		'singular_name'         => _x( 'Person', 'Post Type Singular Name', 'kitces' ),
		'menu_name'             => __( 'People', 'kitces' ),
		'name_admin_bar'        => __( 'People', 'kitces' ),
		'parent_item_colon'     => __( 'Person:', 'kitces' ),
		'all_items'             => __( 'All People', 'kitces' ),
		'add_new_item'          => __( 'Add New Person', 'kitces' ),
		'add_new'               => __( 'Add New', 'kitces' ),
		'new_item'              => __( 'New Person', 'kitces' ),
		'edit_item'             => __( 'Edit Person', 'kitces' ),
		'update_item'           => __( 'Update Person', 'kitces' ),
		'view_item'             => __( 'View Person', 'kitces' ),
		'search_items'          => __( 'Search Person', 'kitces' ),
		'not_found'             => __( 'Not found', 'kitces' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'kitces' ),
		'items_list'            => __( 'People List', 'kitces' ),
		'items_list_navigation' => __( 'People List Navigation', 'kitces' ),
		'filter_items_list'     => __( 'Filter People List', 'kitces' ),
		'featured_image'        => __( 'Headshot', 'mk' ),
		'set_featured_image'    => __( 'Set headshot image', 'mk' ),
		'remove_featured_image' => __( 'Remove headshot image', 'mk' ),
		'use_featured_image'    => __( 'Use as headshot image', 'mk' ),
	);
	$args   = array(
		'label'               => __( 'Person', 'kitces' ),
		'description'         => __( 'Add People to the site.', 'kitces' ),
		'labels'              => $labels,
		'supports'            => array( 'title', 'thumbnail' ),
		'hierarchical'        => false,
		'public'              => false,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'menu_position'       => 5,
		'menu_icon'           => 'dashicons-universal-access-alt',
		'show_in_admin_bar'   => true,
		'show_in_nav_menus'   => false,
		'can_export'          => false,
		'has_archive'         => false,
		'exclude_from_search' => true,
		'publicly_queryable'  => false,
		'capability_type'     => 'post',
	);
	register_post_type( 'person', $args );

}
add_action( 'init', 'register_person_post_type', 0 );

// Rename the title text on creating a new post
function cgd_change_person_title_placeholder_text( $title ) {
	$screen = get_current_screen();

	if ( 'person' == $screen->post_type ) {
		$title = 'Full Name';
	}

	return $title;
}
add_filter( 'enter_title_here', 'cgd_change_person_title_placeholder_text' );

function mk_get_person_details( $id = null ) {

	if ( empty( $id ) ) {
		return false;
	}

	return array(
		'person_id'   => $id,
		'headshot_id' => get_post_thumbnail_id( $id ),
		'name'        => get_the_title( $id ),
		'credentials' => mk_get_field( 'credentials', $id ),
		'job_title'   => mk_get_field( 'job_title', $id ),
		'company'     => mk_get_field( 'company', $id ),
		'short_bio'   => mk_get_field( 'short_bio', $id ),
		'full_bio'    => mk_get_field( 'full_bio', $id ),
	);
}
