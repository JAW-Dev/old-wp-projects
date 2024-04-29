<?php

/*
Template Name: Blank Page
*/

add_filter( 'body_class', 'mk_body_class' );
function mk_body_class( $classes ) {
	$classes[] = 'blank-page';
	return $classes;
}

// full width layout
add_filter ( 'genesis_pre_get_option_site_layout', '__genesis_return_full_width_content' );

genesis();
