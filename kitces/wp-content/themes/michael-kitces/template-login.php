<?php

/*
Template Name: Login Page
*/

// full width layout
add_filter ( 'genesis_pre_get_option_site_layout', '__genesis_return_full_width_content' );

add_filter( 'body_class', 'cgd_body_class' );
function cgd_body_class( $classes ) {

	$classes[] = 'login';
	return $classes;

}

genesis();
