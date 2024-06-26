<?php

add_action( 'genesis_after_header', 'cgd_page_header' );
function cgd_page_header() {
	$prefix        = '_cgd_';
	$about_tagline = get_post_meta( get_the_ID(), $prefix . 'about_header_tagline', true );

	// Get the name of the Page Template file.
	$template_file = get_post_meta( get_the_ID(), '_wp_page_template', true );

	// List of templates not to run the general header on
	$template_file_array = array(
		'template-members-2.php',
		'template-member-page.php',
		'template-consulting-2.0.php',
		'template-speaking-2.php',
		'template-speaking-schedule.php',
		'template-product-landing.php',
		'template-conferences-list.php',
		'template-scholarships-list.php',
		'template-podcast.php',
		'template-team.php',
		'template-kitces-events.php',
	);

	$display_title_section = is_page() && ! is_front_page() && ! in_array( $template_file, $template_file_array );

	$remove_title_section = mk_get_field( 'remove_title_section' );
	if ( $remove_title_section ) {
		$display_title_section = false;
	}

	if ( $display_title_section ) {
		echo '<section class="page-hero">';
		if ( is_page_template( 'template-ce-credit.php' ) ) {
			?>
			<svg width="24px" height="24px" viewBox="0 0 24 24" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:sketch="http://www.bohemiancoding.com/sketch/ns">
				<!-- Generator: Sketch 3.5.2 (25235) - http://www.bohemiancoding.com/sketch -->
				<title>Stroke 767 + Stroke 768</title>
				<desc>Created with Sketch.</desc>
				<defs></defs>
				<g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd" sketch:type="MSPage">
					<g id="Stroke-767-+-Stroke-768" sketch:type="MSLayerGroup" stroke="#000000" stroke-linejoin="round">
						<path class="check" d="M17,8.5 L9.5,15.5 L7,13" id="Stroke-767" stroke-linecap="round" sketch:type="MSShapeGroup"></path>
						<path d="M23.5,12 C23.5,11.021 22.795,10.21 21.866,10.038 C22.657,9.523 22.997,8.504 22.625,7.6 C22.25,6.694 21.288,6.215 20.363,6.411 C20.898,5.632 20.822,4.56 20.13,3.868 C19.438,3.177 18.366,3.102 17.588,3.635 C17.784,2.711 17.304,1.751 16.398,1.376 C15.494,1.001 14.476,1.341 13.961,2.133 C13.788,1.204 12.979,0.5 12,0.5 C11.019,0.5 10.209,1.204 10.035,2.133 C9.521,1.341 8.502,1.001 7.597,1.376 C6.692,1.75 6.213,2.711 6.408,3.635 C5.63,3.102 4.559,3.177 3.866,3.869 C3.174,4.56 3.099,5.632 3.633,6.411 C2.709,6.215 1.751,6.695 1.376,7.6 C1.001,8.504 1.339,9.523 2.13,10.037 C1.201,10.21 0.5,11.021 0.5,12 C0.5,12.979 1.202,13.79 2.13,13.963 C1.339,14.479 1.001,15.498 1.375,16.402 C1.75,17.306 2.709,17.785 3.633,17.59 C3.099,18.369 3.175,19.44 3.866,20.132 C4.559,20.824 5.629,20.9 6.408,20.367 C6.213,21.29 6.693,22.25 7.598,22.625 C8.502,23 9.521,22.66 10.035,21.868 C10.209,22.797 11.021,23.5 12,23.5 C12.979,23.5 13.788,22.797 13.961,21.868 C14.476,22.66 15.494,22.998 16.399,22.625 C17.304,22.25 17.783,21.29 17.588,20.365 C18.366,20.9 19.438,20.824 20.13,20.132 C20.822,19.44 20.898,18.369 20.363,17.59 C21.287,17.786 22.25,17.306 22.625,16.402 C23,15.498 22.658,14.479 21.866,13.963 C22.794,13.791 23.5,12.979 23.5,12 L23.5,12 Z" id="Stroke-768" sketch:type="MSShapeGroup"></path>
					</g>
				</g>
			</svg>
			<?php
		}
		if ( is_page( 'member' ) && is_user_logged_in() ) {
			$current_user = wp_get_current_user();
			echo wp_kses_post( '<h1 class="page-hero-title">Welcome ' . $current_user->display_name . '</h1>' );
			echo '<meta name="customer-email" content="' . esc_attr( $current_user->user_email ) . '" />';
		} else {
			echo '<h1 class="page-hero-title">' . get_the_title() . '</h1>';
		}
		if ( ! empty( $about_tagline ) ) {
			echo '<p class="page-hero-desc">' . $about_tagline . '</p>';
		}
		if ( $template_file === 'template-member-dashboard.php' ) {
			echo "<div class='member-area-announcements-wrap mt1 max-500 mlra text-center'>";
			dynamic_sidebar( 'member_announcements_sidebar' );
			echo '</div>';
		}
		echo '</section>';
	}
}
