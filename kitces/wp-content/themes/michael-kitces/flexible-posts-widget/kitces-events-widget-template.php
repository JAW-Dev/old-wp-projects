<?php
/**
 * Flexible Posts Widget: Kitces Custom Events Widget Template
 *
 * @since 1.0.0
 *
 * This is the ORIGINAL default template used by the plugin.
 * There is a new default template (default.php) that will be
 * used by default if no template was specified in a widget.
 */

// Block direct requests
if ( !defined('ABSPATH') )
	die('-1');

echo $before_widget;

if ( !empty($title) )
	echo $before_title . $title . $after_title;

if( $flexible_posts->have_posts() ):
?>
	<ul class="dpe-flexible-posts listed_posts events">
	<?php while( $flexible_posts->have_posts() ) : $flexible_posts->the_post(); global $post; ?>

		<?php
			$event_timestamp = date(get_post_custom_values('ot_e_date')[0]);
			$location = get_post_custom_values('ot_e_location')[0];
			$label = get_post_custom_values('ot_e_label')[0];
			// $link = get_permalink( $post->ID );
			$link = get_post_meta( $post->ID, 'ot_e_link', true );
		?>

		<li id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<div class="entry-meta">
				<span class="entry-date"><?php echo gmdate('M\<\b\r\/\>d', $event_timestamp); ?></span>
			</div>
			<div class="event-content">
				<div class="">
					<h4 class="title"><?php the_title(); ?></h4>
				</div>

				<div class="event-location">
					<?php
						if (!empty($location) ) {
							echo '<i class="fas fa-map-marker"></i> ' . $location;
						}
					?>
				</div>
				<div class="event-label">
					<?php if ( ! empty( $link ) ): ?>
						<a href="<?php echo $link ?>"><i class="fas fa-file-text-o"></i> Event Details</a>
					<?php endif; ?>
				</div>
			</div>
		</li>
		<?php wp_reset_postdata(); ?>
	<?php endwhile; ?>
	</ul><!-- .dpe-flexible-posts -->
	<div style="margin-top: 20px;">
		<a href="/schedule/" class="more-link">View Full Schedule</a>
	</div>
<?php else: // We have no posts ?>
	<div class="dpe-flexible-posts no-posts">
		<p><?php _e( 'No post found', 'flexible-posts-widget' ); ?></p>
	</div>
<?php
endif; // End have_posts()

echo $after_widget;
