<?php

/*
Template Name: Thanks For Registering (Webinar)
*/

add_filter( 'body_class', 'cgd_body_class' );
function cgd_body_class( $classes ) {

	$classes[] = 'thanks-purchase';
	return $classes;

}

// full width layout
add_filter ( 'genesis_pre_get_option_site_layout', '__genesis_return_full_width_content' );

remove_action( 'genesis_loop', 'genesis_do_loop' );

add_action( 'genesis_loop', 'cgd_thanks_content' );
function cgd_thanks_content() { ?>
	<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><g stroke="#000" stroke-linejoin="round" stroke-miterlimit="10" fill="none"><circle stroke="#D4AF37" cx="11.542" cy="16" r="7.5"/><path stoke="#005392" d="M11.542 11.5l1.5 3h3l-2.5 2 1 3.5-3-1.876-3 1.876 1-3.5-2.5-2h3z"/><g stroke-linecap="round"><path d="M16.74 7.47l3.302-6.97h-2.5l-2.862 6.01M18.53 8.855l4.012-8.355h-2.5l-3.302 6.97"/></g><g stroke-linecap="round"><path d="M6.301 7.47l-3.301-6.97h2.5l2.862 6.01M4.51 8.855l-4.01-8.355h2.5l3.301 6.97"/></g></g></svg>
    <h1>Thanks for Registering!</h1>
	<h4>You will receive further login details for this upcoming webinar within the hour. <br>Please contact webinars@kitces.com with any questions.</h4>
	<a href="/" class="button">In The Meantime, Check Out Our Latest Nerd's Eye View Blog Content!</a>
<?php }

genesis();
