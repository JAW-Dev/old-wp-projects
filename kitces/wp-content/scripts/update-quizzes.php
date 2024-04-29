<?php
require ( '../../wp/wp-load.php' );

/**
 * Switch quiz pages to publish
 */
function switch_quiz_drafts() {
	global $CGD_CECredits;

	$args = array(
		'post_type'       => 'page',
		'post_status'     => 'draft',
		'posts_per_page'  => -1,
		'post_parent__in' => array(
			761,  // CE Quizzes.
			2735, // Webinars.
			6719, // Nerd's Eye View Blog CE Quizzes.
		),
	);

	$the_posts = get_posts( $args );

	foreach ( $the_posts as $the_post ) {

		$matches = false;

		preg_match( '@id="(\d+)"@', $the_post->post_content, $matches );

		if ( ! empty( $matches ) && $CGD_CECredits->form_is_quiz( array( 'id' => $matches[1] ) ) ) {
			update_field( 'kitces_expired_quiz', 1, $the_post->ID );
			wp_update_post(
				array(
					'ID'          => $the_post->ID,
					'post_status' => 'publish',
				)
			);
		}
	}
}

switch_quiz_drafts();
