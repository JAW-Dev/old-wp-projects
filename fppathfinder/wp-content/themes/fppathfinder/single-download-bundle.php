<?php

use FP_Core\Downloads\Bundles\Progress_View;

if ( ! get_current_user_id() && ! rcp_user_can_access() ) {
	wp_safe_redirect( '/login?redirect=' . home_url() . $_SERVER['REQUEST_URI'], 307 );
};

if ( ! rcp_user_can_access() ) {
	wp_safe_redirect( '/ohh-no-you-need-premier-for-interactive-checklists', 307 );
}

do_action( 'before_single_interactive_resource_view' );

// full width layout
add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_full_width_content' );
remove_action( 'genesis_entry_header', 'genesis_do_post_title' );

// Remove 'site-inner' from structural wrap
add_theme_support( 'genesis-structural-wraps', array( 'header', 'nav', 'subnav', 'footer-widgets', 'footer' ) );

if ( 'generate' === $_REQUEST['action'] ) {
	add_action( 'objectiv_page_content', 'output_progress_viewer' );
}

if ( 'generate-test' === $_REQUEST['action'] ) {
	add_action( 'objectiv_page_content', 'output_progress_viewer' );
}

function output_progress_viewer() {
	?>
	<div class="bundle-progress-viewer" style="display: flex; flex-direction: column; align-items: center; margin: 3rem;">
		<h2>Generating Download</h2>
		<p>Your file will start downloading once it's generated.</p>
		<h3 id="bundle-download-percentage" style="margin-top: 2rem;">0%</h3>
		<div class="status-bar-container" style="max-width: 800px; display: flex; height: 2rem; width: 100%; margin: 2rem;">
			<div id="bundle-download-status-bar" style="background: #293D52; width: 0%;"></div>
			<div id="undone" style="background: #ECF1F6; flex-grow: 1;"></div>
		</div>
	</div>
	<?php
}

get_header();
do_action( 'objectiv_page_content' );
get_footer();
