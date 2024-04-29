<?php

add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_content_sidebar' );
remove_action( 'genesis_entry_footer', 'genesis_post_meta' );
remove_action( 'genesis_entry_footer', 'genesis_post_meta' );

remove_action( 'genesis_loop', 'genesis_do_loop' );
add_action( 'genesis_loop', 'objectiv_custom_archive_loop' );

genesis();
