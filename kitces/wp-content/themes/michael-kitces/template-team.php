<?php

/*
Template Name: Team
*/

add_filter( 'body_class', 'cgd_team_body_class' );
function cgd_team_body_class( $classes ) {
	$classes[] = 'team-page';
	return $classes;
}

// full width layout
add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_full_width_content' );

// Remove the loop
remove_action( 'genesis_loop', 'genesis_do_loop' );

// Remove the page title
remove_action( 'genesis_entry_header', 'genesis_do_post_title' );

// Page Sections
add_action( 'genesis_after_header', 'objectiv_intro_header' );
add_action( 'genesis_after_header', 'objectiv_what_we_do' );
add_action( 'genesis_after_header', 'objectiv_who_we_are' );
add_action( 'genesis_after_header', 'objectiv_testimonial' );
add_action( 'genesis_after_header', 'objectiv_what_we_value' );
add_action( 'genesis_after_header', 'objectiv_see_yourself_here' );


function objectiv_intro_header() {
	$head_details = get_field( 'hero_section_details' );
	hero_head( $head_details );
}

function objectiv_what_we_do() {
	$what_we_do_title = mk_get_field( 'what_we_do_title' );
	$what_we_do_text  = mk_get_field( 'what_we_do_text' );

	if ( $what_we_do_title || $what_we_do_text ) {
		?>
		<section class="page-section spt spb">
			<div class="wrap mw-970">
				<?php if ( ! empty( $what_we_do_title ) ) : ?>
					<h2 class="section-title tac"><?php echo $what_we_do_title; ?></h2>
				<?php endif; ?>
				<?php if ( ! empty( $what_we_do_text ) ) : ?>
					<div class="last-child-margin-bottom-0">
						<?php echo wpautop( $what_we_do_text ); ?>
					</div>
				<?php endif; ?>
			</div>
		</section>
		<?php
	}

}

function objectiv_who_we_are() {
	$who_we_are_title              = mk_get_field( 'who_we_are_title' );
	$who_we_are_blurb              = mk_get_field( 'who_we_are_blurb' );
	$who_we_are_team_member_blocks = mk_get_field( 'who_we_are_team_member_blocks', get_the_ID(), true, true );

	if ( $who_we_are_title || $who_we_are_blurb || $who_we_are_team_member_blocks ) {
		?>
		<section class="page-section spt spb bg-light-gray">
			<div class="wrap">
				<?php if ( ! empty( $who_we_are_title ) ) : ?>
					<h2 class="section-title tac"><?php echo $who_we_are_title; ?></h2>
				<?php endif; ?>
				<?php if ( ! empty( $who_we_are_blurb ) ) : ?>
					<div class="last-child-margin-bottom-0 tac mw-600 mlra">
						<?php echo $who_we_are_blurb; ?>
					</div>
				<?php endif; ?>
				<?php if ( ! empty( $who_we_are_team_member_blocks ) ) : ?>
					<div class="team-member-block-grid one23grid mt2">
						<?php foreach ( $who_we_are_team_member_blocks as $tmb ) : ?>
							<?php mk_team_member_block( $tmb ); ?>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
			</div>
		</section>
		<?php
	}
}

function objectiv_testimonial() {
	$testimonials = mk_get_field( 'testimonials', get_the_ID(), true, true );

	if ( is_array( $testimonials ) && ! empty( $testimonials ) ) {
		?>
		<section class="page-section spt spb-l">
			<div class="wrap">
				<?php objectiv_do_testimonial_slider( $testimonials, null, null, 'img-left-test-right' ); ?>
			</div>
		</section>
		<?php
	}
}

function objectiv_what_we_value() {
	$what_we_value_title  = mk_get_field( 'what_we_value_title' );
	$what_we_value_blurb  = mk_get_field( 'what_we_value_blurb' );
	$what_we_value_blocks = mk_get_field( 'what_we_value_blocks', get_the_ID(), true, true );

	if ( $what_we_value_title || $what_we_value_blurb ) {
		?>
		<section class="page-section spt spb bg-light-gray">
			<div class="wrap">
				<?php if ( ! empty( $what_we_value_title ) ) : ?>
					<h2 class="section-title tac"><?php echo $what_we_value_title; ?></h2>
				<?php endif; ?>
				<?php if ( ! empty( $what_we_value_blurb ) ) : ?>
					<div class="last-child-margin-bottom-0 tac mw-600 mlra">
						<?php echo $what_we_value_blurb; ?>
					</div>
				<?php endif; ?>
				<?php if ( ! empty( $what_we_value_blocks ) ) : ?>
					<div class="value-blocks-wrap">
						<?php foreach ( $what_we_value_blocks as $block ) : ?>
							<?php
								$icon  = mk_key_value( $block, 'icon' );
								$desc  = ! empty( $icon['description'] ) ? $icon['description'] : '';
								$title = mk_key_value( $block, 'title' );
								$blurb = mk_key_value( $block, 'blurb' );
							if ( $icon ) {
								$icon = mk_key_value( $icon, 'sizes' );
								$icon = mk_key_value( $icon, 'large-square' );
							}
							?>
							<div class="value-block">
								<?php if ( $icon ) : ?>
									<div class="left-image-wrap">
										<img src="<?php echo esc_url( $icon ); ?>" alt="<?php echo  esc_attr( $title ); ?>" data-description="<?php echo esc_attr( $desc ); ?>">
									</div>
								<?php endif; ?>
								<div class="right-content">
									<?php if ( $title ) : ?>
										<h3 class="tc-text-gray f28 no-transform fwb"><?php echo $title; ?></h3>
									<?php endif; ?>
									<?php if ( $blurb ) : ?>
										<div class=""><?php echo $blurb; ?></div>
									<?php endif; ?>
								</div>
							</div>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
			</div>
		</section>
		<?php
	}
}

function objectiv_see_yourself_here() {
	$work_here_title  = mk_get_field( 'work_here_title' );
	$work_here_blurb  = mk_get_field( 'work_here_blurb' );
	$gravity_form     = mk_get_field( 'work_here_gravity_form' );
	$work_here_button = mk_get_field( 'work_here_button' );

	if ( $work_here_title || $work_here_blurb ) {
		?>
		<section class="page-section spt spb">
			<div class="wrap">
				<?php if ( ! empty( $work_here_title ) ) : ?>
					<h2 class="section-title tac"><?php echo $work_here_title; ?></h2>
				<?php endif; ?>
				<?php if ( ! empty( $work_here_blurb ) ) : ?>
					<div class="last-child-margin-bottom-0 tac mw-800 mlra f20">
						<?php echo $work_here_blurb; ?>
					</div>
				<?php endif; ?>
				<?php if ( ! empty( $gravity_form ) ) : ?>
					<div class="mw-600 mlra mt2">
						<?php gravity_form( $gravity_form, false, false, false, '', true, 1 ); ?>
					</div>
				<?php endif; ?>
				<?php if ( $work_here_button ) : ?>
					<div class="mt2 tac">
						<?php echo mk_link_html( $work_here_button, 'button light-blue large-button' ); ?>
					</div>
				<?php endif; ?>
			</div>
		</section>
		<?php
	}
}


genesis();
