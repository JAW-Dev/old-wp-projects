<?php

/*
Template Name: Registration Welcome
*/

// full width layout
add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_full_width_content' );
remove_action( 'genesis_entry_header', 'genesis_do_post_title' );

add_filter( 'body_class', 'objectiv_body_class' );
function objectiv_body_class( $classes ) {

	$classes[] = 'registration-welcome';
	return $classes;

}

// Remove 'site-inner' from structural wrap
add_theme_support( 'genesis-structural-wraps', array( 'header', 'nav', 'subnav', 'footer-widgets', 'footer' ) );

add_action( 'objectiv_page_content', 'objectiv_welcome_page_content' );
function objectiv_welcome_page_content() { ?>
	<div class="template-registration-welcome-content-outer">
		<?php
		objectiv_top_section();
	?>
	</div>
<?php

}

function objectiv_top_section() {
	$intro_blurb        = get_field( 'intro_blurb' );
	$intro_left_button  = get_field( 'intro_left_button' );
	$intro_right_button = get_field( 'intro_right_button' );
	$ubb_intro_blurb    = get_field( 'ubb_intro_blurb' );
	$ubb_icons          = get_field( 'ubb_icons' );
	$ubb_price_text     = get_field( 'ubb_price_text' );
	$ubb_button         = get_field( 'ubb_button' );
	$ubb_footer_text    = get_field( 'ubb_footer_text' );
	$display_ubb        = ! empty( $ubb_intro_blurb ) || ! empty( $ubb_button );

	?>
	<section class="registration-welcome-top sectionmt sectionmb">
		<div class="wrap">
			<div class="registration-welcome-top__inner">
				<?php if ( ! empty( $intro_blurb ) ) : ?>
					<div class="registration-welcome-top__intro lmb0 tac max-width-600 mlra f20"><?php echo $intro_blurb; ?></div>
				<?php endif; ?>
				<?php if ( ! empty( $intro_left_button ) || ! empty( $intro_right_button ) ) : ?>
					<div class="registration-welcome-top__intro-button-wrap tac basemt2">
						<?php if ( is_array( $intro_left_button ) && ! empty( $intro_left_button ) ) : ?>
								<?php echo objectiv_link_button( $intro_left_button, 'button blue-button large-button' ); ?>
						<?php endif; ?>
						<?php if ( is_array( $intro_right_button ) && ! empty( $intro_right_button ) ) : ?>
							<?php echo objectiv_link_button( $intro_right_button, 'button red-button large-button' ); ?>
						<?php endif; ?>
					</div>
				<?php endif; ?>

				<?php if ( $display_ubb ) : ?>
					<div class="registration-welcome-upgrade-block call-out-block basemt4 bg-light-gray">
						<?php if ( ! empty( $ubb_intro_blurb ) ) : ?>
							<div class="registration-welcome-upgrade-block__intro-blurb lmb0 tac f20"><?php echo $ubb_intro_blurb; ?></div>
						<?php endif; ?>
						<?php if ( is_array( $ubb_icons ) && ! empty( $ubb_icons ) ) : ?>
							<div class="white-label-cta__icons-wrap">
								<?php foreach ( $ubb_icons['icons'] as $icon ) : ?>
									<?php obj_do_regular_image_icon_item( $icon ); ?>
								<?php endforeach; ?>
							</div>
						<?php endif; ?>
						<?php if ( ! empty( $ubb_price_text ) ) : ?>
							<div class="registration-welcome-upgrade-block__price tac price-text"><?php echo $ubb_price_text; ?></div>
						<?php endif; ?>
						<?php if ( ! empty( $ubb_button ) ) : ?>
							<div class="registration-welcome-upgrade-block__button-wrap tac">
								<?php echo objectiv_link_button( $ubb_button, 'button red-button large-button' ); ?>
							</div>
						<?php endif; ?>
						<?php if ( ! empty( $ubb_footer_text ) ) : ?>
							<div class="registration-welcome-upgrade-block__footer-text tac basemt2 f16"><?php echo $ubb_footer_text; ?></div>
						<?php endif; ?>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</section>
	<?php
}

get_header();
do_action( 'objectiv_page_content' );
get_footer();
