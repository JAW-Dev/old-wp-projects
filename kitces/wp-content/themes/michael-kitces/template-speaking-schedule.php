<?php

/*
Template Name: Speaking Schedule
*/

add_filter( 'body_class', 'obj_speaking_schedule_body_class' );
function obj_speaking_schedule_body_class( $classes ) {
	$classes[] = 'speaking-schedule';
	return $classes;
}

// full width layout
add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_full_width_content' );

remove_action( 'genesis_loop', 'genesis_do_loop' );
remove_action( 'genesis_entry_header', 'genesis_do_post_title' );

add_action( 'genesis_after_header', 'objectiv_speaking_schedule_page_content' );

function objectiv_speaking_schedule_page_content() {
	// Output the header
	objectiv_speaking_intro_header();
	objectiv_speaking_remaining_events();
	objectiv_speaking_events_years();
}

function objectiv_speaking_intro_header() {
	$title = get_field( 'page_title_override' );

	if ( ! empty( $title ) ) {
		$hd['hero_title'] = $title;
	} else {
		$hd['hero_title'] = get_the_title( get_the_ID() );
	}

	hero_head( $hd );
}

function objectiv_speaking_remaining_events() {
	$intro_text = get_field( 'remain_intro_text' );
	$end_text   = get_field( 'remain_end_text' );

	if ( ! empty( $intro_text ) || ! empty( $end_text ) ) {
		?>
			<section class="remaining-events">
				<div class="wrap">
					<div class="remaining-events__inner bg-white">
						<div class="remaining-events__content-wrap bg-light-gray">
							<?php if ( ! empty( $intro_text ) ) : ?>
								<div class="remaining-events__intro-text lmb0 tac"><?php echo $intro_text; ?></div>
							<?php endif; ?>
							<?php objectiv_speaking_availability_table(); ?>
							<?php if ( ! empty( $end_text ) ) : ?>
								<div class="remaining-events__end-text lmb0 tac"><?php echo $end_text; ?></div>
							<?php endif; ?>
						</div>
					</div>
				</div>
			</section>
		<?php
	}
}

function objectiv_speaking_events_years() {
	$years_to_display = get_field( 'years_to_display' );

	if ( is_array( $years_to_display ) && ! empty( $years_to_display ) ) {
		?>
			<section class="event-years">
				<div class="wrap">
					<div class="event-years__inner bg-white">
						<div class="event-years__inner-wrap">
							<?php obj_do_yearly_events( $years_to_display ); ?>
						</div>
					</div>
				</div>
			</section>
		<?php
	}
}

genesis();
