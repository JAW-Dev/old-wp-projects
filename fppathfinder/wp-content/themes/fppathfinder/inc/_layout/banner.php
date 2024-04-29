<?php
add_action( 'genesis_after_header', 'objectiv_page_banner', 15 );

function objectiv_page_banner() {

	$no_banner_templates   = array(
		'template-log-in.php',
		'template-lead-thanks.php',
		'template-getting-started.php',
	);
	$page_template_file    = get_post_meta( get_the_ID(), '_wp_page_template', true );
	$is_no_banner_template = in_array( $page_template_file, $no_banner_templates );

	$hide_banner_conditions = array(
		$is_no_banner_template,
		is_singular( 'post' ),
		is_front_page(),
		is_post_type_archive( 'resource' ),
	);

	$hide_banner = ! empty( array_filter( $hide_banner_conditions ) );

	if ( ! $hide_banner ) {
		echo get_template_part( 'partials/banner/hero', 'banner' );
	}
}
