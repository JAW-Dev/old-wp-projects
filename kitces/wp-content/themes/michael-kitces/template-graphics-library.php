<?php
/**
Template Name: Graphics Library Page
 */

add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_full_width_content' );
remove_action( 'genesis_after_endwhile', 'genesis_posts_nav' );
remove_action( 'genesis_loop', 'genesis_do_loop' );
add_action( 'genesis_loop', 'objectiv_get_graphics_library' );
add_filter( 'wp_featherlight_load_js', '__return_false' );

function objectiv_get_graphics_library() {
	global $wpdb;
	global $query_string;

	wp_parse_str( $query_string, $search_query );

	$posts_with_attachments = $wpdb->get_col( "SELECT DISTINCT post_parent FROM kitces_posts WHERE ID IN( SELECT post_id FROM kitces_postmeta WHERE meta_key = 'graphics_library' AND meta_value = '1' ) AND post_parent > 0;" );

	$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;

	$args = array(
		's'              => $search_query['search'],
		'post_type'      => 'post',
		'orderby'        => 'post_date',
		'order'          => 'desc',
		'posts_per_page' => '5',
		'post__in'       => $posts_with_attachments,
		'paged'          => $paged,
	);

	global $wp_query;

	$wp_query = new WP_Query( $args );

	echo '<div class="graphics-library-posts">';

	?>
	<div class="graphics-library-search">
		<h3>Search the Graphics Library by Blog Post</h3>
		<form role="search" method="get" class="search-form" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
			<input type="search" class="search-field" placeholder="Enter Search Term" value="<?php echo $search_query['search']; ?>" name="search" title="<?php echo esc_attr_x( 'Search for:', 'label' ); ?>" />
			<input type="submit" value="Submit" />
		</form>
	</div>
	<?php

	while ( $wp_query->have_posts() ) :
		$wp_query->the_post();

		$post_graphics = get_posts(
			array(
				'post_type'      => 'attachment',
				'post_mime_type' => 'image',
				'numberposts'    => -1,
				'orderby'        => 'post_date',
				'order'          => 'desc',
				'post_status'    => 'inherit',
				'post_parent'    => get_the_ID(),
				'meta_query'     => array(
					array(
						'key'   => 'graphics_library',
						'value' => '1',
					),
				),
			)
		);

		if ( ! empty( $post_graphics ) ) {
			?>
			<div id="graphics-library-post-<?php echo get_the_ID(); ?>" class="entry graphics-library-post">
				<h2 class="entry-title graphics-library-post__title">
					<a href="<?php the_permalink(); ?>" class="entry-title-link">
						<?php the_title(); ?>
					</a>
				</h2>

				<p class="entry-meta graphics-library-post__date">
					<time class="entry-time"><?php the_date(); ?></time>
				</p>

				<div class="graphics-library-post-images">
					<?php foreach ( $post_graphics as $graphic ) : ?>
						<?php
							$graphic_title = get_the_title( $graphic->ID );
							$graphic_src   = wp_get_attachment_image_src( $graphic->ID, 'full' );
						?>
						<div class="graphics-library-post-image-container">
							<a href="#graphics-library-post-image-<?php echo $graphic->ID; ?>" class="graphics-library-post-image">
								<?php echo wp_get_attachment_image( $graphic->ID, 'thumbnail' ); ?>
								<span class="graphics-library-post-image__title"><?php echo $graphic_title; ?></span>
							</a>
							<div id="graphics-library-post-image-<?php echo $graphic->ID; ?>" style="display: none;" class="graphics-library-post-image-modal">
								<h3><?php echo $graphic_title; ?></h3>
								<?php echo wp_get_attachment_image( $graphic->ID, 'large-rectangular', '', array( 'class' => 'graphics-library-post-image-modal-image' ) ); ?>
								<div class="graphics-library-post-image-meta">
									<span><strong>From:</strong> <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></span>
									<span><strong>On:</strong> <time><?php the_time( get_option( 'date_format' ) ); ?></time></span>
									<p style="text-align: center">
										<a class="button" href="<?php echo $graphic_src[0]; ?>" download="<?php echo $graphic_title; ?>">Download</a>
									</p>
								</div>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
			<?php
			do_action( 'genesis_after_endwhile' );
		}

	endwhile;

	echo '</div>';

	genesis_posts_nav();
	wp_reset_postdata();
}

genesis();
