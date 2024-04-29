<?php

/*
Template Name: Log In
*/

// full width layout
add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_full_width_content' );
remove_action( 'genesis_entry_header', 'genesis_do_post_title' );

add_filter( 'body_class', 'objectiv_body_class' );
function objectiv_body_class( $classes ) {

	$classes[] = 'login-template';
	return $classes;

}

add_action( 'objectiv_page_content', 'objectiv_login_page_content' );
function objectiv_login_page_content() {

	$c_title   = get_field( 'cta_title' );
	$c_content = get_field( 'cta_content' );
	$c_button  = get_field( 'cta_button' );

	?>
	<section class="login-page-content-outer">
		<div class="login-page-content">
			<?php if ( ! is_user_logged_in() ) : ?>
				<div class="left-side">
						<h1 class="login-title">Log In</h1>
						<?php if ( function_exists( 'rcp_get_content_subscription_levels' ) ) : ?>
							<?php echo do_shortcode( '[login_form]' ); ?>
						<?php endif; ?>
				</div>
				<?php if ( ! empty( $c_title ) || ! empty( $c_content ) || ! empty( $c_button ) ) : ?>
				<div class="right-side">
					<div class="login-cta">
						<?php if ( ! empty( $c_title ) ) : ?>
							<h3 class="login-cta__title fw400"><?php echo $c_title; ?></h3>
						<?php endif; ?>
						<?php if ( ! empty( $c_content ) ) : ?>
							<div class="login-cta__content"><?php echo $c_content; ?></div>
						<?php endif; ?>
						<?php if ( ! empty( $c_button ) ) : ?>
							<?php echo objectiv_link_button( $c_button ); ?>
						<?php endif; ?>
					</div>
				</div>
				<?php endif; ?>
			<?php else : ?>
				<div class="logout-content">
					<h2 class="logout-title">Looks like you're already logged in!</h2>
					<span class="button">
						<a href="<?php echo wp_logout_url(); ?>" title="Logout">Logout</a>
					</span>
				</div>
			<?php endif; ?>
		</div>
	</section>
<?php
}

get_header();
do_action( 'objectiv_page_content' );
get_footer();
