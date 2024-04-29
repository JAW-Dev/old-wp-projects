<?php 

remove_action( 'genesis_entry_footer', 'genesis_post_meta' );
remove_action('genesis_entry_footer', 'genesis_entry_footer_markup_open', 5);
remove_action('genesis_entry_footer', 'genesis_entry_footer_markup_close', 15);

add_filter('genesis_post_info', 'objectiv_post_info_filter');
function objectiv_post_info_filter($post_info)
{
    $post_info = '[post_date]<br>[post_categories before=""]';
    return $post_info;
}

genesis();