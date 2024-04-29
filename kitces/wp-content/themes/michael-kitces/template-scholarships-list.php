<?php

/*
Template Name: Scholarships List
*/

add_filter( 'body_class', 'cgd_body_class' );
function cgd_body_class( $classes ) {
	$classes[] = 'scholarships-list';
	return $classes;
}

// full width layout
add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_full_width_content' );

remove_action( 'genesis_loop', 'genesis_do_loop' );
remove_action( 'genesis_entry_header', 'genesis_do_post_title' );

// Add the Page Sections
add_action( 'genesis_after_header', 'objectiv_scholarship_list_content' );

function objectiv_scholarship_list_content() {
	$args = array(
		'numberposts' => -1,
		'post_type'   => 'scholarship',
		'post_status' => 'publish',
		'orderby'     => 'title',
		'order'       => 'ASC',
	);

	$scholarships = get_posts( $args );

	$filters_data = array(
		'scholarship_type'    => array(
			'type'  => 'select',
			'label' => 'Scholarship Type',
			'multi' => true,
		),
		'applicant_type'      => array(
			'type'  => 'select',
			'label' => 'Applicant Type',
			'multi' => true,
		),
		'application_type'      => array(
			'type'  => 'select',
			'label' => 'Application Type',
			'multi' => true,
		),
	);

	objectiv_intro_header();
	objectiv_first_content_sec();
	objectiv_filter_scholarships_sec( $scholarships, $filters_data );
	objectiv_scholarships_list( $scholarships, $filters_data );
}

function objectiv_intro_header() { ?>
	<?php
	$title_overide = mk_get_field( 'page_title_override' );
	$title         = ! empty( $title_overide ) ? $title_overide : get_the_title();
	$sub_title     = mk_get_field( 'page_sub_title' );

	mk_lists_hero( $title, $sub_title );
}

function objectiv_first_content_sec() {
	$content = mk_get_field( 'first_content_content' );

	mk_lists_content( $content );
}

function objectiv_filter_scholarships_sec( $scholarships = null, $filters_data = null ) {
	$display_filter_section = mk_get_field( 'display_filter_section' );

	if ( $display_filter_section && ! empty( $scholarships ) ) {

		$filters_markup = mk_get_list_filters_markup( $filters_data, $scholarships );
		$filter_title   = mk_get_field( 'filter_title' );

		mk_do_lists_filters_section( $filters_markup, $filter_title );
	}
}

function objectiv_scholarships_list( $scholarships = null, $filters_data = null ) {
	$list_block_settings = mk_get_scholarship_list_block_settings();

	mk_lists_list( $scholarships, $list_block_settings, $filters_data );
}

genesis();
