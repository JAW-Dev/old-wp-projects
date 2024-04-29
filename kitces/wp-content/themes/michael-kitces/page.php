<?php

remove_action( 'genesis_loop', 'genesis_do_loop' );
add_action( 'genesis_loop', 'custom_genesis_do_loop' );
/**
 * Attach a loop to the `genesis_loop` output hook so we can get some front-end output.
 *
 * @since 1.1.0
 */
function custom_genesis_do_loop() {
	global $post;

	$expired = get_post_meta( $post->ID, 'kitces_expired_quiz', true );

	if ( is_singular( 'page' ) && genesis_get_custom_field( 'query_args' ) ) {

		$paged = get_query_var( 'paged' ) ?: 1;

		/*
		 * Convert custom field string to args array.
		 */
		$query_args = wp_parse_args(
			genesis_get_custom_field( 'query_args' ),
			[
				'paged' => $paged,
			]
		);

		genesis_custom_loop( $query_args );
	} elseif ( $expired && kitces_is_quiz_page( $post ) ) {
		$option = get_option( 'cecredits_settings' );

		if ( have_posts() ) {
			do_action( 'genesis_before_while' );
			while ( have_posts() ) {

				the_post();
				do_action( 'genesis_before_entry' );
				genesis_markup(
					[
						'open'    => '<article %s>',
						'context' => 'entry',
					]
				);
				do_action( 'genesis_entry_header' );
				do_action( 'genesis_before_entry_content' );
				genesis_markup(
					[
						'open'    => '<div %s>',
						'context' => 'entry-content',
					]
				);

				echo wp_kses_post( '<p>' . $option['expired_quiz_message'] . '</p>' );

				genesis_markup(
					[
						'close'   => '</div>',
						'context' => 'entry-content',
					]
				);
				do_action( 'genesis_after_entry_content' );
				do_action( 'genesis_entry_footer' );
				genesis_markup(
					[
						'close'   => '</article>',
						'context' => 'entry',
					]
				);
				do_action( 'genesis_after_entry' );

			} // End of one post.

			/**
			 * Fires inside the standard loop, after the while() block.
			 *
			 * @since 1.0.0
			 */
			do_action( 'genesis_after_endwhile' );

		} else { // If no posts exist.

			/**
			 * Fires inside the standard loop when they are no posts to show.
			 *
			 * @since 1.0.0
			 */
			do_action( 'genesis_loop_else' );

		} // End loop.
	} else {
		genesis_standard_loop();
	}

}

genesis();
