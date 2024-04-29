<?php 


/*======================================================
Podcast CPT
======================================================*/
//* Add custom post type "Podcast"
function eof_custom_post_podcast() {
	$labels = array(
		'name'               => _x( 'Podcasts', 'post type general name' ),
		'singular_name'      => _x( 'Podcast', 'post type singular name' ),
		'add_new'            => _x( 'Add New', 'podcast' ),
		'add_new_item'       => __( 'Add New podcast' ),
		'edit_item'          => __( 'Edit Podcast' ),
		'new_item'           => __( 'New Podcast' ),
		'all_items'          => __( 'All Podcasts' ),
		'view_item'          => __( 'View Podcast' ),
		'search_items'       => __( 'Search Podcasts' ),
		'not_found'          => __( 'No podcast found' ),
		'not_found_in_trash' => __( 'No podcasts found in the Trash' ), 
		'parent_item_colon'  => '',
		'menu_name'          => 'Podcasts'
	);
	$args = array(
		'labels'        => $labels,
		'description'   => 'Holds all Podcasts for the site.',
		'public'        => true,
		'menu_position' => 5,
		//'rewrite'       => array( 'slug' => 'podcasts' ),
		'supports'      => array( 'title', 'editor', 'thumbnail', 'excerpt', 'comments' ),
		'has_archive'   => true,
	);
	register_post_type( 'podcast', $args );
}
add_action( 'init', 'eof_custom_post_podcast' );

//* Add custom post type messages for "Podcast"

function eof_updated_messages( $messages ) {
	global $post, $post_ID;
	$messages['podcast'] = array(
		0 => '', 
		1 => sprintf( __('Podcast updated. <a href="%s">View your podcast</a>'), esc_url( get_permalink($post_ID) ) ),
		2 => __('Custom field updated.'),
		3 => __('Custom field deleted.'),
		4 => __('Podcast updated.'),
		5 => isset($_GET['revision']) ? sprintf( __('Podcast restored to revision from %s'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
		6 => sprintf( __('Podcast published. <a href="%s">View your podcast</a>'), esc_url( get_permalink($post_ID) ) ),
		7 => __('Podcast saved.'),
		8 => sprintf( __('Podcast submitted. <a target="_blank" href="%s">Preview your podcast</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
		9 => sprintf( __('Podcast scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview your podcast</a>'), date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( get_permalink($post_ID) ) ),
		10 => sprintf( __('Podcast draft updated. <a target="_blank" href="%s">Preview your podcast</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
	);
	return $messages;
}
add_filter( 'post_updated_messages', 'eof_updated_messages' );

//* Add custom post type help for "Podcast"

function eof_contextual_help( $contextual_help, $screen_id, $screen ) { 
	if ( 'podcast' == $screen->id ) {

		$contextual_help = "<h2>Podcasts</h2>
		<p>Podcasts show all the interviews that we are running on Entrepreneur on Fire. Believe me it's an awesome way to keep everything in order.</p> 
		<p>Stay Inspired!</p>";

	} elseif ( 'edit-podcast' == $screen->id ) {

		$contextual_help = '<h2>Editing podcasts</h2>
		<p>This page allows you to view/modify podcast details. Make sure everything is in order so we can make it work lik a charm.</p>';

	}
	return $contextual_help;
}
//add_action( 'contextual_help', 'eof_contextual_help', 10, 3 );

//* Add custom post type categories and tags for "Podcast"

function eof_podcast_categories() {
	$labels = array(
		'name'              => _x( 'Podcast Categories', 'taxonomy general name' ),
		'singular_name'     => _x( 'Podcast Category', 'taxonomy singular name' ),
		'search_items'      => __( 'Search Podcast Categories' ),
		'all_items'         => __( 'All Podcast Categories' ),
		'parent_item'       => __( 'Parent Podcast Category' ),
		'parent_item_colon' => __( 'Parent Podcast Category:' ),
		'edit_item'         => __( 'Edit Podcast Category' ), 
		'update_item'       => __( 'Update Podcast Category' ),
		'add_new_item'      => __( 'Add New Podcast Category' ),
		'new_item_name'     => __( 'New Podcast Category' ),
		'menu_name'         => __( 'Podcast Categories' ),
	);
	$args = array(
		'labels' => $labels,
		'hierarchical' => true,
	);
	register_taxonomy( 'podcast_category', 'podcast', $args );
}
add_action( 'init', 'eof_podcast_categories', 0 );

function eof_podcast_tags() {
	$labels = array(
		'name'              => _x( 'Podcast Tags', 'taxonomy general name' ),
		'singular_name'     => _x( 'Podcast Tag', 'taxonomy singular name' ),
		'search_items'      => __( 'Search Podcast Tags' ),
		'all_items'         => __( 'All Podcast Tags' ),
		'parent_item'       => __( 'Parent Podcast Tag' ),
		'parent_item_colon' => __( 'Parent Podcast Tag:' ),
		'edit_item'         => __( 'Edit Podcast Tag' ), 
		'update_item'       => __( 'Update Podcast Tag' ),
		'add_new_item'      => __( 'Add New Podcast Tag' ),
		'new_item_name'     => __( 'New Podcast Tag' ),
		'menu_name'         => __( 'Podcast Tags' ),
	);
	$args = array(
		'labels' => $labels,
		'hierarchical' => false,
	);
	register_taxonomy( 'podcast_tag', 'podcast', $args );
}
add_action( 'init', 'eof_podcast_tags', 0 );

