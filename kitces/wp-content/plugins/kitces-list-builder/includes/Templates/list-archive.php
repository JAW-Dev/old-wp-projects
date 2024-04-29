<?php

add_filter( 'body_class', 'cgd_body_class' );
function cgd_body_class( $classes ) {
	$classes[] = 'list-archive';
	return $classes;
}

// full width layout
add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_full_width_content' );

remove_action( 'genesis_loop', 'genesis_do_loop' );
remove_action( 'genesis_entry_header', 'genesis_do_post_title' );

// Add the Page Sections
add_action( 'genesis_after_header', 'objectiv_scholarship_list_content' );

function objectiv_scholarship_list_content() {
	$post_type  = get_post_type();
	$list_posts = mkl_get_all_posts_for_list( $post_type );

	if ( ! empty( $list_posts ) && is_array( $list_posts ) ) {
		$list_id         = mkl_list_post_id( $post_type );
		$list_details    = mkl_get_list_details( $list_id );
		$page_title      = mk_key_value( $list_details, 'list_view_page_title' );
		$page_sub_title  = mk_key_value( $list_details, 'list_view_sub_title' );
		$intro_blurb     = mk_key_value( $list_details, 'list_view_intro_blurb' );
		$disable_filters = mk_key_value( $list_details, 'disable_filters' );

		mkl_archive_hero( $page_title, $page_sub_title );
		mkl_archive_content( $intro_blurb );

		if ( ! $disable_filters ) {
			$filter_title   = mk_key_value( $list_details, 'filters_title' );
			$list_filter    = new MKLB\ListFilter( $list_details, $list_posts );
			$filters_markup = $list_filter->get_markup();

			mkl_archive_filters_section( $filters_markup, $filter_title );
		}

		mkl_lists_list( $list_posts, $list_details );

	}

}

genesis();
