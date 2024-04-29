<?php
add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_full_width_content' );

add_theme_support( 'genesis-structural-wraps', array( 'header', 'nav', 'subnav', 'footer-widgets', 'footer' ) );

remove_action( 'genesis_loop', 'genesis_do_loop' );
add_action( 'genesis_after_header', 'fp_member_section_banner' );
add_action( 'genesis_after_header', 'fp_member_section_search' );

$query_params = array_keys( $_GET );
$is_search    = preg_grep( '/^fwp_/', $query_params );

if ( $is_search ) {
	add_filter( 'genesis_markup_site-inner', '__return_null' );
	add_filter( 'genesis_markup_content-sidebar-wrap', '__return_null' );
	add_filter( 'genesis_markup_content', '__return_null' );
	add_action( 'genesis_loop', 'fp_member_section_search_results' );
} else {
	add_action( 'genesis_after_header', 'fp_member_section_sliders' );
}

genesis();
