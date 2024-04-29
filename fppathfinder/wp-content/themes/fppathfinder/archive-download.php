<?php

remove_action( 'genesis_sidebar', 'genesis_do_sidebar' );
add_action( 'genesis_sidebar', 'objectiv_do_single_post_sidebar' );
function objectiv_do_single_post_sidebar() {
	dynamic_sidebar( 'members-area-sidebar' );
}

remove_action( 'genesis_loop', 'genesis_do_loop' );
add_action( 'genesis_loop', 'objectiv_archive_loop' );
function objectiv_archive_loop() {
	download_archive_loop();
}

genesis();
