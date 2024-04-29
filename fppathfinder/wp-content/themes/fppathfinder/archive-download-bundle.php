<?php

remove_action( 'genesis_sidebar', 'genesis_do_sidebar' );

$user_id = get_current_user_id();

if ( ! $user_id ) {
	wp_safe_redirect( '/become-a-member', 307 );
}

$member = new \FP_Core\Member( $user_id );

if ( ! rcp_user_has_access( 0, 4 ) && ! $member->is_active_at_level( FP_ENTERPRISE_DELUXE_ID ) && ! $member->is_active_at_level( FP_ENTERPRISE_PREMIER_ID ) && ! current_user_can( 'administrator' ) ) {
	wp_safe_redirect( '/become-a-member', 307 );
}

function bundle_single_item( \WP_Post $bundle ) {
	$url = add_query_arg(
		array(
			'action' => 'generate-test',
			'postid' => $bundle->ID,
		),
		get_permalink( $bundle )
	);
	// $url = add_query_arg( 'action', 'generate', get_permalink( $bundle ) );
	?>
	<div class="single-bundle" style="margin-bottom: 3rem;">
		<h4 class="title"><?php echo $bundle->post_title; ?></h4>
		<?php echo fp_get_link_button( $url, '_blank', 'Download', 'red-button', false, true ); ?>
	</div>
	<?php
}

remove_action( 'genesis_loop', 'genesis_do_loop' );
add_action( 'genesis_loop', 'objectiv_bundle_archive_loop' );
function objectiv_bundle_archive_loop() {
	global $wp_query;

	array_map( 'bundle_single_item', $wp_query->posts );
}

genesis();
