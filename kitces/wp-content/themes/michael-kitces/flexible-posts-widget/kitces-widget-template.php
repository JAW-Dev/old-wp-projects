<?php
/**
 * Flexible Posts Widget: Kitces Custom Widget Template
 *
 * @package Kitces
 * @since 1.0.0
 *
 * This is the ORIGINAL default template used by the plugin.
 * There is a new default template (default.php) that will be
 * used by default if no template was specified in a widget.
 */

// Block direct requests.
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

echo $before_widget; // phpcs:ignore

if ( ! empty( $title ) ) {
	echo $before_title . $title . $after_title; // phpcs:ignore
}

if ( $flexible_posts->have_posts() ) :
	?>
	<ul class="dpe-flexible-posts widget_recent_entries recent-entries">
	<?php
	while ( $flexible_posts->have_posts() ) :
		$flexible_posts->the_post();
		global $post;
		?>
		<li id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<a href="<?php echo esc_url( the_permalink() ); ?>" class="recent-entries__link">
				<div class="recent-entries__icon">
					<?php
					if ( $thumbnail === true ) {
						// If the post has a feature image, show it.
						if ( has_post_thumbnail() ) {
							the_post_thumbnail( $thumbsize );
							// Else if the post has a mime type that starts with "image/" then show the image directly.
						} elseif ( 'image/' === substr( $post->post_mime_type, 0, 6 ) ) {
							echo wp_get_attachment_image( $post->ID, $thumbsize );
						}
					} else {
						$post_terms = get_the_terms( $post->ID, 'category' );
						$post_term  = ! empty( $post_terms[0] ) ? $post_terms[0] : array();
						$tax_term   = ! empty( $post_term ) ? $post_term->taxonomy . '_' . $post_term->term_id : '';
						$icon_type  = ! empty( $tax_term ) && function_exists( 'get_field' ) ? get_field( 'kitces_category_icon_type', $tax_term ) : '';
						$icon       = '';

						if ( 'attachment' === get_post_type( $post->ID ) ) {
							$icon = '<i class="fas fa-file"></i>';
						} else {
							if ( ! empty( $icon_type ) ) {
								$icon = '';

								if ( 'font' === $icon_type ) {
									$icon = function_exists( 'get_field' ) ? get_field( 'kitces_category_icon_font_icon', $tax_term ) : '';
								} elseif ( 'image' === $icon_type ) {
									$image_id = function_exists( 'get_field' ) ? get_field( 'kitces_category_icon_image_icon', $tax_term ) : '';
									$icon     = wp_get_attachment_image( $image_id, 'full', true );
								}
							}
						}

						echo $icon; // phpcs:ignore
					}
					?>
				</div>
			</a>
			<div>
				<h4 class="title">
					<a href="<?php echo esc_url( the_permalink() ); ?>" class="recent-entries__link">
						<?php the_title(); ?>
					</a>
					<p style="margin-top: 0.25rem"><?php echo get_the_author_meta( 'first_name' ) . ' ' . get_the_author_meta( 'last_name' ) ?></p>
				</h4>
				<div class="entry-meta">
					<span class="entry-time"><?php the_date(); ?></span>
					<span class="entry-comments"><i class="far fa-comment"></i><a href="<?php echo esc_url( get_comments_link() ); ?>"><?php comments_number( '0', '1', '%' ); ?></a></span>
				</div>
			</div>
		</li>
		<?php wp_reset_postdata(); ?>
	<?php endwhile; ?>
	</ul><!-- .dpe-flexible-posts -->
<?php else : // We have no posts. ?>
	<div class="dpe-flexible-posts no-posts">
		<p><?php esc_html_e( 'No post found', 'flexible-posts-widget' ); ?></p>
	</div>
	<?php
endif; // End have_posts().

echo $after_widget; // phpcs:ignore
