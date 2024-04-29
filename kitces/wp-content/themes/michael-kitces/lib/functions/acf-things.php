<?php

/**
 * Add Theme options
 *
 * @author Wesley Cole
 * @link http://objectiv.co/
 */

if ( function_exists( 'acf_add_options_page' ) ) {

	acf_add_options_page(
		array(
			'page_title' => 'Theme Settings',
			'menu_title' => 'Theme Settings',
			'menu_slug'  => 'theme-general-settings',
			'icon_url'   => 'dashicons-art',
			'capability' => 'edit_posts',
			'position'   => 59.5,
			'redirect'   => false,
		)
	);

	acf_add_options_page(
		array(
			'page_title'  => 'Announcements Settings',
			'menu_title'  => 'Announcements',
			'menu_slug'   => 'announcements-settings',
			'parent_slug' => 'theme-general-settings',
			'capability'  => 'edit_posts',
			'post_id'     => 'announcements-settings',
			'redirect'    => false,
		)
	);

	acf_add_options_page(
		array(
			'page_title'  => 'Conferences Settings',
			'menu_title'  => 'Conferences Settings',
			'menu_slug'   => 'conferences-settings',
			'parent_slug' => 'edit.php?post_type=conference',
			'capability'  => 'edit_posts',
			'redirect'    => false,
		)
	);

	acf_add_options_page(
		array(
			'page_title'  => 'Course Catalog Settings',
			'menu_title'  => 'CC Settings',
			'menu_slug'   => 'course-cat-settings',
			'parent_slug' => 'edit.php?post_type=cc-item',
			'capability'  => 'edit_posts',
			'redirect'    => false,
		)
	);

	acf_add_options_page(
		array(
			'page_title'  => 'Mobile Menu Settings',
			'menu_title'  => 'Mobile Menu',
			'menu_slug'   => 'mobile-menu-settings',
			'parent_slug' => 'theme-general-settings',
			'capability'  => 'edit_posts',
			'redirect'    => false,
		)
	);

	acf_add_options_page(
		array(
			'page_title'  => 'Podcast Settings',
			'menu_title'  => 'Podcast',
			'menu_slug'   => 'podcast-settings',
			'parent_slug' => 'theme-general-settings',
			'capability'  => 'edit_posts',
			'redirect'    => false,
		)
	);

	acf_add_options_page(
		array(
			'page_title'  => 'Quiz Feedback Surveys',
			'menu_title'  => 'Quiz Surveys',
			'menu_slug'   => 'quiz-feedback-surveys',
			'parent_slug' => 'theme-general-settings',
			'capability'  => 'edit_posts',
			'redirect'    => false,
		)
	);

	acf_add_options_page(
		array(
			'page_title'  => 'Scholarships Settings',
			'menu_title'  => 'Scholarships',
			'menu_slug'   => 'scholarships-settings',
			'parent_slug' => 'edit.php?post_type=scholarship',
			'capability'  => 'edit_posts',
			'post_id'     => 'scholarships-settings',
			'redirect'    => false,
		)
	);

	acf_add_options_page(
		array(
			'page_title'  => 'Speaker Settings',
			'menu_title'  => 'Speakers',
			'menu_slug'   => 'kitces-speaker-availability',
			'parent_slug' => 'theme-general-settings',
			'capability'  => 'edit_posts',
			'redirect'    => false,
		)
	);

	acf_add_options_page(
		array(
			'page_title'  => 'Topic Tags Settings',
			'menu_title'  => 'Topic Tags',
			'menu_slug'   => 'kitces-topic-tags',
			'parent_slug' => 'theme-general-settings',
			'capability'  => 'edit_posts',
			'redirect'    => false,
		)
	);
}

if ( strpos( get_site_url(), 'kitces.com' ) && function_exists( 'acf_add_options_page' ) ) {
	add_filter( 'acf/settings/show_admin', '__return_false' );
}

// Dynamic tags for topics on speaking page.
function obj_load_topic_tags_select( $field ) {

	// reset choices
	$field['choices'] = array();

	// get the textarea value from options page without any formatting
	$choices = get_field( 'topic_tags', 'option', false );

	// explode the value so that each line is a new array piece
	$choices = explode( "\n", $choices );

	// remove any unwanted white space
	$choices = array_map( 'trim', $choices );

	// loop through array and add to field 'topic_tags_select'
	if ( is_array( $choices ) ) {
		$count = 1;

		foreach ( $choices as $choice ) {

			$field['choices'][ $count ] = $choice;
			$count ++;
		}
	}

	// return the field
	return $field;

}
add_filter( 'acf/load_field/key=field_5b9141a36dbab', 'obj_load_topic_tags_select' );
