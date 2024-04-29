<?php

function register_link_list_post_type() {

	$labels = array(
		'name'                  => _x( 'Link Lists', 'Post Type General Name', 'kitces' ),
		'singular_name'         => _x( 'Link List', 'Post Type Singular Name', 'kitces' ),
		'menu_name'             => __( 'Link Lists', 'kitces' ),
		'name_admin_bar'        => __( 'Link Lists', 'kitces' ),
		'parent_item_colon'     => __( 'Link List:', 'kitces' ),
		'all_items'             => __( 'All Link Lists', 'kitces' ),
		'add_new_item'          => __( 'Add New Link List', 'kitces' ),
		'add_new'               => __( 'Add New', 'kitces' ),
		'new_item'              => __( 'New Link List', 'kitces' ),
		'edit_item'             => __( 'Edit Link List', 'kitces' ),
		'update_item'           => __( 'Update Link List', 'kitces' ),
		'view_item'             => __( 'View Link List', 'kitces' ),
		'search_items'          => __( 'Search Link List', 'kitces' ),
		'not_found'             => __( 'Not found', 'kitces' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'kitces' ),
		'items_list'            => __( 'Link Lists List', 'kitces' ),
		'items_list_navigation' => __( 'Link Lists List Navigation', 'kitces' ),
		'filter_items_list'     => __( 'Filter Link Lists List', 'kitces' ),
	);
	$args   = array(
		'label'               => __( 'Link List', 'kitces' ),
		'description'         => __( 'Add Link Lists to the site.', 'kitces' ),
		'labels'              => $labels,
		'supports'            => array( 'title' ),
		'hierarchical'        => false,
		'public'              => false,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'menu_position'       => 5,
		'menu_icon'           => 'dashicons-editor-ol',
		'show_in_admin_bar'   => true,
		'show_in_nav_menus'   => false,
		'can_export'          => false,
		'has_archive'         => false,
		'exclude_from_search' => true,
		'publicly_queryable'  => false,
		'capability_type'     => 'post',
	);
	register_post_type( 'link-list', $args );

}
add_action( 'init', 'register_link_list_post_type', 0 );
