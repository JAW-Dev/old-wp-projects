<?php
require '../../wp/wp-load.php';

function remove_slashes() {
	global $wpdb;

	$terms_table = $wpdb->prefix . 'aioseo_terms';
	$terms_sql     = "SELECT * FROM $terms_table";
	$terms_results = $wpdb->get_results( $terms_sql, ARRAY_A );
	$terms_fields  = array(
		'id',
		'title',
		'description',
		'keywords',
		'keyphrases',
		'page_analysis',
		'og_title',
		'og_description',
		'tabs',
		'images',
		'local_seo',
	);

	cleanup( $wpdb, $terms_table, $terms_results, $terms_fields );

	$posts_table   = $wpdb->prefix . 'aioseo_posts';
	$posts_sql     = "SELECT * FROM $posts_table";
	$posts_results = $wpdb->get_results( $posts_sql, ARRAY_A );
	$posts_fields  = array(
		'id',
		'title',
		'description',
		'keywords',
		'keyphrases',
		'page_analysis',
		'og_title',
		'og_description',
		'schema_type_options',
		'tabs',
		'images',
		'local_seo',
	);

	cleanup( $wpdb, $posts_table, $posts_results, $posts_fields );
}

function cleanup( $wpdb, $posts_table, $posts_results, $posts_fields ) {
	foreach ( $posts_results as $posts_result ) {
		foreach ( $posts_fields as $posts_field ) {
			if ( strpos( $posts_result[ $posts_field ], "\'" ) !== false || strpos( $posts_result[ $posts_field ], '\"' ) !== false ) {
				// TODO: REMOVE!
				error_log( $posts_result['id'] . ' ' . $posts_field . ' : ' . print_r( $posts_result[ $posts_field ], true ) ); // phpcs:ignore
				$new = str_replace( '\\', '', $posts_result[ $posts_field ] );

				$wpdb->update(
					$posts_table,
					array( $posts_field => $new ),
					array( 'id' => $posts_result['id'] ),
					array( '%s' ),
					array( '%d' )
				);
			}
		}
	}
}

remove_slashes();
