<?php

/*
Template Name: Content Builder
*/

// full width layout
add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_full_width_content' );

// Remove 'site-inner' from structural wrap
add_theme_support( 'genesis-structural-wraps', array( 'header', 'nav', 'subnav', 'footer-widgets', 'footer' ) );


add_filter( 'body_class', 'objectiv_body_class' );
function objectiv_body_class( $classes ) {

	$classes[] = 'content-builder';
	return $classes;

}

// Add attributes for site-inner element, since we're removing 'content'.
add_filter( 'genesis_attr_site-inner', 'objectiv_site_inner_attr' );
function objectiv_site_inner_attr( $attributes ) {
	$attributes['role']     = 'main';
	$attributes['itemprop'] = 'mainContentOfPage';
	return $attributes;
}

add_action( 'objectiv_page_content', 'objectiv_flexible_sections' );
function objectiv_flexible_sections() {
	echo '<section id="flexible-section-repeater">';
	$fcs = FlexibleContentSectionFactory::create( 'page_flexible_sections' );
	$fcs->run();
	echo '</section>';
}

add_action( 'wp_footer', 'helpscout_chat' );

function helpscout_chat() {
	global $post;

	if ( ( is_home() || is_front_page() ) || $post->post_name === 'become-a-member' ) {
		?>
<script type="text/javascript">!function(e,t,n){function a(){var e=t.getElementsByTagName("script")[0],n=t.createElement("script");n.type="text/javascript",n.async=!0,n.src="https://beacon-v2.helpscout.net",e.parentNode.insertBefore(n,e)}if(e.Beacon=n=function(t,n,a){e.Beacon.readyQueue.push({method:t,options:n,data:a})},n.readyQueue=[],"complete"===t.readyState)return a();e.attachEvent?e.attachEvent("onload",a):e.addEventListener("load",a,!1)}(window,document,window.Beacon||function(){});</script> <script type="text/javascript">window.Beacon('init', '761b9639-9e31-4a3b-8fd1-12f7f486de17')</script>
		<?php
	}
}

// Build the page
get_header();
do_action( 'objectiv_page_content' );
get_footer();
