<?php

require '../../wp/wp-load.php';

// This script will get all quiz pages and then download a CSV file with them.

if ( is_user_logged_in() && current_user_can( 'manage_options' ) ) {

	$webinar_args = array(
		'posts_per_page' => -1,
		'post_type'      => 'page',
		'status'         => array( 'publish', 'private' ),
		'post_parent'    => 2735,
	);

	$webinar_quiz_pages = get_posts( $webinar_args );

	$webinar_quiz_pages_clean = array();

	if ( ! empty( $webinar_quiz_pages ) ) {
		foreach ( $webinar_quiz_pages as $post ) {
			$webinar_quiz_pages_clean[ $post->ID ]['ID']                  = $post->ID;
			$webinar_quiz_pages_clean[ $post->ID ]['post_date']           = $post->post_date;
			$webinar_quiz_pages_clean[ $post->ID ]['post_title']          = $post->post_title;
			$webinar_quiz_pages_clean[ $post->ID ]['post_url']            = get_permalink( $post->ID );
			$webinar_quiz_pages_clean[ $post->ID ]['post_status']         = $post->post_status;
			$webinar_quiz_pages_clean[ $post->ID ]['kitces_expired_quiz'] = get_post_meta( $post->ID, 'kitces_expired_quiz', true );
		}

		create_csv_download_from_array( 'webinar-quiz-pages', $webinar_quiz_pages_clean );
	}
} else {
	echo 'There once was a chicken nugget.';
}

function create_csv_download_from_array( $file_name_root = null, $pages_array = null ) {
	if ( ! empty( $pages_array ) && is_array( $pages_array ) ) {
		ob_start();

		$current_time = current_time( 'timestamp' );
		$filename     = $file_name_root . '-' . $current_time . '.csv';

		$header_row = array(
			'ID',
			'Post Date',
			'Post Title',
			'Post URL',
			'Post Status',
			'Expired Checkbox',
		);

		$fh = fopen( 'php://output', 'w' );

		header( 'Content-type: text/csv' );
		header( "Content-Disposition: attachment; filename={$filename}" );

		fputcsv( $fh, $header_row );

		foreach ( $pages_array as $data_row ) {
			fputcsv( $fh, $data_row, ',' );
		}

		fclose( $fh );

		ob_end_flush();
	}
}
