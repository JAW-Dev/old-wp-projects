<?php

/**
 * FP Member Section Search
 *
 * Output the search results
 *
 * @return void
 */
function fp_member_section_search_results() {

	?>
	<div class="member-section-resources facetwp-template">
		<div class="wrap">
			<div class="search-results">
				<?php
				// Use old loop hook structure if not supporting HTML5.
				if ( ! genesis_html5() ) {
					genesis_legacy_loop();
					return;
				}

				if ( have_posts() ) {

					/**
					 * Fires inside the standard loop, before the while() block.
					 *
					 * @since 2.1.0
					 */
					do_action( 'genesis_before_while' );

					while ( have_posts() ) {

						the_post();
						fp_resource_card( get_post() );
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
				?>
			</div>
		</div>
	</div>
	<?php

}
