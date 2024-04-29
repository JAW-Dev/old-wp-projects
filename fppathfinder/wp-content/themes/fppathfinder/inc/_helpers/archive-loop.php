<?php

function objectiv_custom_archive_loop() {

	if ( have_posts() ) :

		do_action( 'genesis_before_while' );
		while ( have_posts() ) :
			the_post();

			$current_id = get_the_ID();

			objectiv_custom_blog_block( $current_id );

		endwhile; // End of one post.
		do_action( 'genesis_after_endwhile' );

	else : // If no posts exist.
		do_action( 'genesis_loop_else' );
	endif; // End loop.
}

function objectiv_custom_blog_block( $post_id = null ) {
	if ( ! empty( $post_id ) ) {
		$permalink    = get_permalink( $post_id );
		$post_title   = get_the_title( $post_id );
		$post_excerpt = objectiv_get_short_description( $post_id, 40 );
		$thumbnail_id = get_post_thumbnail_id( $post_id );
		$thumbnail    = wp_get_attachment_image( $thumbnail_id, 'obj_post_thumb' );

		?>
		<a href="<?php echo $permalink; ?>">
			<div class="archive-blog-block">
				<?php if ( ! empty( $thumbnail ) ) : ?>
					<div class="left-archive-blog-block">
						<?php echo $thumbnail; ?>
					</div>
				<?php endif; ?>
				<div class="right-archive-blog-block">
					<h2 class="archive-blog-block__title"><?php echo $post_title; ?></h2>
					<div class="archive-blog-block__excerpt"><?php echo $post_excerpt; ?></div>
					<div class="read-more-link">Read More</div>
				</div>
			</div>
		</a>
		<?php
	}
}
