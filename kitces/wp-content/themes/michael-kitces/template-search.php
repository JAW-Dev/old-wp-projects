<?php

/*
Template Name: Search Page
*/

remove_action( 'genesis_loop', 'genesis_do_loop' );

add_action( 'genesis_loop', 'cgd_search' );
add_action( 'genesis_loop', 'cgd_search_links' );

genesis();
