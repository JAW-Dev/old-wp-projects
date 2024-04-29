<?php

require( '../../wp/wp-load.php' );

function update_acf_fields() {
	global $wpdb;

	$results = $wpdb->get_results(
		"
		SELECT *
		FROM {$wpdb->prefix}postmeta
		WHERE meta_key
		LIKE 'catalog_flexible_sections_%_title'
		",
		ARRAY_N
	);

	if ( empty( $results ) ) {
		return;
	}

	$temp = array();

	foreach ( $results as $result ) {
		if ( in_array( 'Recommended CPE', $result, true ) ) {
			$temp[] = $result;
		}
	}

	if ( empty( $temp ) ) {
		return;
	}

	foreach ( $temp as $item ) {
		$post_id = ! empty( $item[1] ) ? $item[1] : 0;

		if ( $post_id === 0 ) {
			continue;
		}

		$field = ! empty( $item[2] ) ? $item[2] : '';

		if ( empty( $field ) ) {
			continue;
		}

		update_post_meta( $post_id, $field, 'Recommended CE Hours' );
	}

}

update_acf_fields();
