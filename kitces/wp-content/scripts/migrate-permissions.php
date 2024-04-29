<?php
global $wpdb;
set_time_limit(0);
require ( '../../wp/wp-load.php' );

$pages = get_pages();

foreach ( $pages as $page ) {
	$any_membership = get_post_meta( $page->ID, '_is4wp_any_loggedin_user', true );

	echo "Updating any membership: <br/>";

	if ( $any_membership === '0'|| $any_membership === '1' ) {
		update_post_meta( $page->ID, '_memberium_any_membership', $any_membership );
		var_dump($any_membership);
	}

	$membership_levels = get_post_meta( $page->ID, '_is4wp_membership_levels', true );
	$membership_levels = explode(',', $membership_levels);
	$new_membership_levels = array();

	if ( in_array( '338', $membership_levels ) ) {
		$new_membership_levels[] = 5;
	}

	if ( in_array( '104', $membership_levels ) ) {
		$new_membership_levels[] = 8;
	}

	echo "Updating membership levels: <br/>";
	print_r($new_membership_levels);

	update_post_meta( $page->ID, '_memberium_membership_levels', join( ',', $new_membership_levels ) );
}