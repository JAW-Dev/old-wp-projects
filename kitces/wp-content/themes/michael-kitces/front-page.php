<?php
/**
 * Template Name: Custom Home Page
 * Description: Custom homepage template
 */

// Only use pagination on custom loop
// remove_action( 'genesis_after_endwhile', 'genesis_posts_nav' );

// remove_action( 'genesis_sidebar', 'genesis_do_sidebar' );
remove_action( 'genesis_entry_header', 'single_post_featured_image', 0 );
// remove_action( 'genesis_header', 'genesis_do_header' );
remove_action( 'genesis_entry_header', 'genesis_do_post_title' );
remove_action( 'genesis_entry_header', 'genesis_post_info', 12 );
add_action( 'genesis_entry_header', 'kitces_entry_header' );

// Hero Image
add_action( 'genesis_after_header', 'cgd_front_hero', 10 );
function cgd_front_hero() { ?>
	<?php
	$prefix     = '_cgd_';
	$h_title    = get_post_meta( get_the_ID(), $prefix . 'hero_title', true );
	$h_subtitle = get_post_meta( get_the_ID(), $prefix . 'hero_subtitle', true );
	$h_optin    = get_post_meta( get_the_ID(), $prefix . 'hero_optin', true );
	$h_button   = get_post_meta( get_the_ID(), $prefix . 'hero_button_text', true );
	$thrive_id  = get_post_meta( get_the_ID(), $prefix . 'hero_thrive_id', true );
	$oim_id     = get_post_meta( get_the_ID(), $prefix . 'hero_oim_id', true );

	?>
	<?php
	$home_hero_cookie = isset( $_COOKIE['home-hero'] ) ? $_COOKIE['home-hero'] : '';

	if ( ! $home_hero_cookie == 'hidden' && ( ! empty( $thrive_id ) || ! empty( $oim_id ) || ! empty( $h_button ) ) ) :
		?>
		<section class="hero">
			<div class="hero-wrap">
				<div class="hero-content">
					<h1 class="hero-title"><?php echo $h_title; ?></h1>
					<h2 class="hero-subtitle"><?php echo $h_subtitle; ?></h2>
					<p class="hero-tagline"><?php echo do_shortcode( $h_optin ); ?></p>

					<?php if ( ! empty( $thrive_id ) ) : ?>
						<?php echo do_shortcode( '[thrive_2step id="' . $thrive_id . '"]<span class="button">' . $h_button . '</span>[/thrive_2step]' ); ?>
					<?php elseif ( ! empty( $oim_id ) ) : ?>
						<a href="https://app.monstercampaigns.com/c/<?php echo $oim_id; ?>/" class="button manual-optin-trigger" data-optin-slug="<?php echo $oim_id; ?>" target="_blank"><?php echo $h_button; ?></a>
					<?php else : ?>
						<span class="button homepage-above-fold-optin"><?php echo $h_button; ?></span>
					<?php endif; ?>

					<span class="close"><i class="fas fa-close"></i> Close</span>
				</div>
			</div>
		</section>
	<?php endif; ?>
	<?php
}

add_action( 'genesis_after_header', 'cgd_front_page_testimonials' );
function cgd_front_page_testimonials() {
	?>
	<?php
	$args = array(
		'post_type'      => 'testimonials',
		'posts_per_page' => '6',
	);
	?>
	<?php
	$home_hero_cookie = isset( $_COOKIE['home-hero'] ) ? $_COOKIE['home-hero'] : '';

	if ( ! $home_hero_cookie == 'hidden' ) :
		?>
		<?php $testimonials = new WP_Query( $args ); ?>
		<?php if ( $testimonials->have_posts() ) : ?>
			<section class="testimonials">
				<div class="wrap">
					<?php
					while ( $testimonials->have_posts() ) :
						$testimonials->the_post();
						?>
						<?php
						$prefix           = '_cgd_';
						$testimonial_text = get_post_meta( get_the_ID(), $prefix . 'testmionial_posttype_text', true );
						$testimonial_link = get_post_meta( get_the_ID(), $prefix . 'testimonial_posttype_link', true );
						?>
						<div class="testimonial">
							<?php if ( ! empty( $testimonial_link ) ) : ?>
								<a href="<?php echo $testimonial_link; ?>" target="_blank">
									<?php if ( has_post_thumbnail() ) : ?>
										<div class="testimonial-image">
											<?php the_post_thumbnail(); ?>
										</div>
									<?php endif; ?>
									<p class="testimonial-text">&ldquo;<?php echo $testimonial_text; ?>&rdquo;</p>
								</a>
							<?php else : ?>
								<?php if ( has_post_thumbnail() ) : ?>
									<div class="testimonial-image">
										<?php the_post_thumbnail(); ?>
									</div>
								<?php endif; ?>
								<p class="testimonial-text">&ldquo;<?php echo $testimonial_text; ?>&rdquo;</p>
							<?php endif; ?>
						</div>
						<?php wp_reset_postdata(); ?>
					<?php endwhile; // * end of one post ?>
				</div>
			</section>
		<?php else : // * if no posts exist ?>
			<?php echo 'No Testimonials Found'; ?>
		<?php endif; // * end loop ?>
	<?php endif; ?>
	<?php
}

/**
 * Homepage loop
 */
remove_action( 'genesis_loop', 'genesis_do_loop' );
add_action( 'genesis_loop', 'homepage_post_podcast_loop' );

function homepage_post_podcast_loop() {
	global $wp_query;
	global $paged;

	$paged = get_query_var( 'page' ); // phpcs:ignore
	$args  = array(
		'posts_per_page' => 6,
		'paged'          => $paged,
	);

	$wp_query = new WP_Query( $args ); // phpcs:ignore

	if ( $wp_query->have_posts() ) :
		if ( is_active_sidebar( 'announcements_sidebar' ) ) {
			echo "<div class='announcements-sidebar-wrap'>";
			dynamic_sidebar( 'announcements_sidebar' );
			echo '</div>';
		}

		do_action( 'genesis_before_while' );

		while ( $wp_query->have_posts() ) :
			$wp_query->the_post();
			global $post;

			$thumb_id        = get_post_thumbnail_id();
			$thumb_url_array = wp_get_attachment_image_src( $thumb_id, 'thumbnail', true );
			$thumb_url       = $thumb_url_array[0];
			$alt             = get_post_meta( get_post_thumbnail_id(), '_wp_attachment_image_alt', true );

			printf( '<article %s>', genesis_attr( 'entry' ) );

			do_action( 'genesis_entry_header' );
			do_action( 'genesis_before_entry_content' );

			if ( has_post_thumbnail() ) {
				echo '<a href="' . esc_url( get_permalink() ) . '" class="post-thumbnail"><img src="' . esc_attr( $thumb_url ) . '" alt="' . esc_attr( $alt ) . '"></a>';
			}
			printf( '<div %s>', genesis_attr( 'entry-content' ) );
			do_action( 'genesis_entry_content' );
			echo '</div>';

			do_action( 'genesis_after_entry_content' );

			do_action( 'genesis_entry_footer' );

			echo '</article>';

		endwhile;
		do_action( 'genesis_after_while' );

		genesis_posts_nav();
	endif;
	wp_reset_query();
}


// add_action( 'genesis_after_content', 'home_sidebar' );
function home_sidebar() {
	?>
	<aside class="sidebar sidebar-primary widget-area" role="complementary" aria-label="Primary Sidebar" itemscope="" itemtype="http://schema.org/WPSideBar">
		<?php dynamic_sidebar( 'primary-sidebar' ); ?>
	</aside>

	<?php
}

genesis();
