<?php

/*
Template Name: About
*/

// full width layout
add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_full_width_content' );
remove_action( 'genesis_entry_header', 'genesis_do_post_title' );

add_filter( 'body_class', 'objectiv_body_class' );
function objectiv_body_class( $classes ) {

	$classes[] = 'template-about';
	return $classes;

}

// Remove 'site-inner' from structural wrap
add_theme_support( 'genesis-structural-wraps', array( 'header', 'nav', 'subnav', 'footer-widgets', 'footer' ) );

add_action( 'objectiv_page_content', 'objectiv_login_page_content' );
function objectiv_login_page_content() {

	$bios = get_field( 'bios' );

	?>
	<section class="about-page-content-outer">
		<div class="wrap">
			<div class="about-page-content">
				<?php
				foreach ( $bios as $bio ) :
					$img      = $bio['headshot']['sizes']['obj_lsquare'];
					$img_alt  = $bio['headshot']['alt'];
					$name     = $bio['name'];
					$position = $bio['position'];
					$bio      = $bio['bio'];
				?>
					<div class="about-bio">
						<div class="about-bio__first">
							<?php if ( ! empty( $img ) ) : ?>
								<img src="<?php echo $img; ?>" alt="<?php echo $img_alt; ?>">
							<?php endif; ?>
						</div>
						<div class="about-bio__second">
							<div class="about-bio__header">
								<?php if ( ! empty( $name ) ) : ?>
									<h3 class="about-bio__name"><?php echo $name; ?></h3>
								<?php endif; ?>
								<?php if ( ! empty( $position ) ) : ?>
									<div class="about-bio_position"><?php echo $position; ?></div>
								<?php endif; ?>
							</div>
							<div class="about-bio__bio">
								<?php echo $bio; ?>
							</div>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
<?php
}

get_header();
do_action( 'objectiv_page_content' );
get_footer();
