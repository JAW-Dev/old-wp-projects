<?php

function mk_filter_content_attributes( $attributes ) {
	if ( mk_user_primary_sidebar_closed() ) {
		$attributes['class'] = $attributes['class'] . ' closed-sidebar';
	}

	return $attributes;
}
add_filter( 'genesis_attr_content-sidebar-wrap', 'mk_filter_content_attributes' );

function mk_add_sidebar_widget_area_toggle() {
	$class = '';
	if ( mk_user_primary_sidebar_closed() ) {
		$class = 'closed-sidebar';
	}
	?>
	<?php if ( is_user_logged_in() ) : ?>
		<button class="member-primary-sidebar-toggle <?php echo $class; ?>" title="Toggle Sidebar">
			<svg xmlns="http://www.w3.org/2000/svg" width="24px" fill="none" viewBox="0 0 24 24" stroke="currentColor">
				<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
			</svg>
			<div class="text"><span class="redundant">Close the</span> Sidebar</div>
		</button>
	<?php endif; ?>
	<?php
}
add_filter( 'genesis_before_sidebar_widget_area', 'mk_add_sidebar_widget_area_toggle' );

function mk_user_primary_sidebar_closed() {

	if ( is_user_logged_in() && isset( $_COOKIE['member-closed-primary-sidebar'] ) ) {
		return true;
	}

	return false;
}
