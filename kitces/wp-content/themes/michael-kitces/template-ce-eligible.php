<?php

/*
Template Name: CE Eligible
*/

remove_action( 'genesis_loop', 'genesis_do_loop' );
add_action( 'genesis_loop', 'ce_eligible_custom_loop' );

function ce_eligible_custom_loop() {
	/** Here, we have to alter the query parameters which are sent to the loop, so as to display just the posts under category ‘Genesis Tutorials’. You can filter posts by category id (to display posts from a particular category and sub categories), or category_slug (for posts only from the particular category) **/

	global $paged;
	global $query_args;

	$args = array(
		'posts_per_page' => -1,
		'post_type'      => 'post',
		'status'         => 'publish',
		'meta_query'     => array(
			'relation' => 'AND',
			array(
				'key'     => 'ce_quiz_page',
				'value'   => '',
				'compare' => '!=',
			),
			array(
				'key'     => '_kitces_show_ce_banner',
				'value'   => '',
				'compare' => '!=',
			),
			array(
				'relation' => 'OR',
				array(
					'key'     => '_kitces_show_ce_old_banner',
					'value'   => '',
					'compare' => 'NOT EXISTS',
				),
				array(
					'key'     => '_kitces_show_ce_old_banner',
					'value'   => 'on',
					'compare' => '!=',
				),
			)
		),
	);

	genesis_custom_loop( wp_parse_args( $query_args, $args ) );
}

remove_action( 'genesis_entry_header', 'genesis_do_post_title' );
remove_action( 'genesis_entry_header', 'genesis_post_info', 12 );
add_action( 'genesis_entry_header', 'kitces_entry_header' );

genesis();
