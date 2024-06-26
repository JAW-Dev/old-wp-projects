<?php

// Remove 'site-inner' from structural wrap
add_theme_support( 'genesis-structural-wraps', array( 'header', 'footer-widgets', 'footer' ) );
add_filter ( 'genesis_pre_get_option_site_layout', '__genesis_return_full_width_content' );

//* Remove default loop
remove_action('genesis_loop', 'genesis_do_loop');

// Swap out the placeholder text for the search box
function objectiv_search_placeholder_404( $placeholder ) {

	$place_text = 'What were you looking for?';

	return $place_text;

}
add_filter('genesis_search_text', 'objectiv_search_placeholder_404', 10, 1);

// Add the 404 page markup
add_action('genesis_loop', 'objectiv_404');
function objectiv_404() {
	$body_text = get_field( '404_body_text', 'option' );

	if ( empty( $body_text ) ) {
		// $url = htmlspecialchars($_SERVER['HTTP_REFERER']);
		$body_text = 'Sorry about that! <a href="javascript:history.go(-1)">Click here to go back</a> or give a search a whirl.';
	}

	?>

	<section class="section-404">
		<div class="content-404 wrap inside-content-wrap">
			<p><?php echo $body_text ?></p>
			<?php get_search_form(); ?>
		</div>
	</section>

	<?php
}


genesis();
