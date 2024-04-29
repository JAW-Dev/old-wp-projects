<?php

/**
 * Template Name: PDF Generator
 */

// NOTE: This file cannot be renamed unless you also modify the check in plugins/fppathfinder-logo-editing/includes/classes/EnqueueScripts.php.

// full width layout.
add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_full_width_content' );
remove_action( 'genesis_entry_header', 'genesis_do_post_title' );

add_filter( 'body_class', 'objectiv_body_class' );
function objectiv_body_class( $classes ) {

	$classes[] = 'template-lead-pdf-generator';
	return $classes;

}

// Remove 'site-inner' from structural wrap.
add_theme_support( 'genesis-structural-wraps', array( 'header', 'nav', 'subnav', 'footer-widgets', 'footer' ) );

add_action( 'objectiv_page_content', \FP_PDF_Generator\Customization_Controller::user_can_view_white_label_settings( get_current_user_id() ) ? 'objectiv_pdf_generator_page_content' : 'objectiv_settings_are_managed_by_group_owner_markup' );
function objectiv_pdf_generator_page_content() {
	// FP_PDF_Generator\Whitelabeling\Form::whitelabeling_form();
}

function objectiv_settings_are_managed_by_group_owner_markup() {
	?>
		<h3 style="text-align: center; margin: 10rem 2rem 25rem 2rem;">Your group's settings are managed by your group admin account.</h3>
	<?php
}


get_header();
do_action( 'objectiv_page_content' );
get_footer();
