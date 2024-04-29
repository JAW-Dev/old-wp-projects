<?php

/*
Template Name: Kitces Events
*/

add_filter( 'body_class', 'mk_kitces_events_body_class' );
function mk_kitces_events_body_class( $classes ) {
	$classes[] = 'kitces-events';
	return $classes;
}

// full width layout
add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_full_width_content' );

remove_action( 'genesis_loop', 'genesis_do_loop' );
remove_action( 'genesis_entry_header', 'genesis_do_post_title' );

add_action( 'genesis_after_header', 'mk_kitces_events_page_content' );

function mk_kitces_events_page_content() {
	mk_events_page_header();
	mk_events_jump_nav();
	mk_events_event_cat_sections();
	mk_events_general_updates();
	mk_events_icymi();
	mk_events_final_cta();
}

function mk_events_page_header() {
	$title_override = mk_get_field( 'hero_title_override' );
	$sub_title      = mk_get_field( 'hero_sub_title' );
	$image_override = mk_get_field( 'hero_image_override' );
	$title          = $title_override ? $title_override : get_the_title();
	$image_id       = $image_override ? $image_override : get_post_thumbnail_id();

	if ( ! empty( $title ) ) { ?>
		<section class="kitces-events-hero spt-l spb-l text-white bg-light-blue">
			<div class="wrap">
				<div class="kitces-events-hero-inner">
					<div class="left-content">
						<h1 class="f48 mb0"><?php echo $title; ?></h1>
						<?php if ( ! empty( $sub_title ) ) : ?>
							<div class="sub-title mthalf"><?php echo $sub_title; ?></div>
						<?php endif; ?>
					</div>
					<?php if ( ! empty( $image_id ) ) : ?>
						<div class="right-content">
							<?php echo wp_get_attachment_image( $image_id, 'large' ); ?>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</section>
		<?php
	}
}

function mk_events_jump_nav() {
	$nav_items = mk_get_field( 'jump_nav_items', get_the_ID(), true, true );

	if ( $nav_items ) {
		mk_do_in_page_nav_section( $nav_items );
	}
}

function mk_events_event_cat_sections() {
	$category_sections = mk_get_field( 'category_sections', get_the_ID(), true, true );
	$count             = 1;

	if ( ! empty( $category_sections ) ) {
		foreach ( $category_sections as $cat ) {
			$category = $cat['category'];
			$args     = array(
				'posts_per_page' => -1,
				'post_type'      => 'kitces-event',
				'post_status'    => 'publish',
				'meta_key'       => 'date_time',
				'orderby'        => 'meta_value',
				'order'          => 'ASC',
				'tax_query'      => array(
					array(
						'taxonomy' => 'ke-cat',
						'field'    => 'term_id',
						'terms'    => $category,
					),
				),
			);
			$events   = get_posts( $args );
			$events   = mk_ke_past_future_events( $events );

			if ( ! empty( $category ) && ! empty( $events ) ) {
				$section_id          = $cat['section_id'];
				$section_title       = $cat['section_title'];
				$section_blurb       = $cat['section_blurb'];
				$next_title          = $cat['next_title'];
				$upcoming_title      = $cat['upcoming_title'];
				$member_only_details = $cat['show_member_only_details'];
				$bg_class            = 'bg-white';
				$next_event          = array_shift( $events );

				if ( $count % 2 == 0 ) {
					$bg_class = 'bg-gray-100';
				}
				?>
				<section class="kitces-events-cat-sec spt-l spb-l <?php echo $bg_class; ?>" id="<?php echo $section_id; ?>">
					<div class="wrap">
						<div class="kitces-events-cat-sec-inner">
							<div class="mw-800 tac mlra">
								<?php if ( ! empty( $section_title ) ) : ?>
									<h2 class="border0 f36 fwb"><?php echo $section_title; ?></h2>
								<?php endif; ?>
								<?php if ( ! empty( $section_blurb ) ) : ?>
									<div class="last-child-margin-bottom-0 f20">
										<?php echo $section_blurb; ?>
									</div>
								<?php endif; ?>
							</div>
							<?php if ( ! empty( $next_event ) ) : ?>
								<div class="next-event mw-970 mlra spt">
									<?php if ( ! empty( $next_title ) ) : ?>
										<h3 class="f24 mb1half no-transform tc-text-gray fwb ff-base"><?php echo $next_title; ?></h3>
									<?php endif; ?>
									<?php mk_kitces_event_block( $next_event, true, false, $member_only_details ); ?>
								</div>
							<?php endif; ?>
							<?php if ( ! empty( $events ) ) : ?>
								<div class="upcoming-events mw-970 mlra spt">
									<?php if ( ! empty( $upcoming_title ) ) : ?>
										<h3 class="f24 mb1half no-transform tc-text-gray fwb ff-base"><?php echo $upcoming_title; ?></h3>
									<?php endif; ?>
									<div class="upcoming-events-grid one2grid flex-start">
										<?php foreach ( $events as $event ) : ?>
											<?php mk_kitces_event_block( $event, false ); ?>
										<?php endforeach; ?>
									</div>
								</div>
							<?php endif; ?>
						</div>
					</div>
				</section>
				<?php
				$count++;
			}
		}
	}
}

function mk_events_general_updates() {
	$section_id              = mk_get_field( 'gu_section_id' );
	$section_title           = mk_get_field( 'gu_section_title' );
	$section_blurb           = mk_get_field( 'gu_section_blurb' );
	$section_content         = mk_get_field( 'gu_content' );
	$after_content_shortcode = mk_get_field( 'gu_after_content_shortcode' );

	if ( ! empty( $section_content ) ) {
		?>
		<section class="kitces-events-general-updates spt-l spb-l bg-white" id="<?php echo $section_id; ?>">
			<div class="wrap">
				<div class="kitces-events-general-updates-inner">
					<div class="mw-800 tac mlra">
						<?php if ( ! empty( $section_title ) ) : ?>
							<h2 class="border0 f36 fwb"><?php echo $section_title; ?></h2>
						<?php endif; ?>
						<?php if ( ! empty( $section_blurb ) ) : ?>
							<div class="last-child-margin-bottom-0 f20">
								<?php echo $section_blurb; ?>
							</div>
						<?php endif; ?>
						<div class="mt1">
						<?php if ( ! empty( $section_content ) ) : ?>
							<div class="last-child-margin-bottom-0 tal norm-list"><?php echo wpautop( $section_content ); ?></div>
						<?php endif; ?>
						<?php if ( ! empty( $after_content_shortcode ) ) : ?>
							<div class="last-child-margin-bottom-0 tal"><?php echo do_shortcode( $after_content_shortcode ); ?></div>
						<?php endif; ?>
						</div>
					</div>
				</div>
			</div>
		</section>
		<?php
	}
}

function mk_events_icymi() {
	$section_id        = mk_get_field( 'icymi_section_id' );
	$section_title     = mk_get_field( 'icymi_section_title' );
	$section_blurb     = mk_get_field( 'icymi_section_blurb' );
	$past_events_title = mk_get_field( 'icymi_past_webinars_title' );
	$past_events       = mk_ke_page_template_events( null, false );

	if ( ! empty( $past_events ) ) {
		?>
		<section class="kitces-events-general-updates spt-l spb-l bg-gray-100" id="<?php echo $section_id; ?>">
			<div class="wrap">
				<div class="kitces-events-general-updates-inner">
					<div class="mw-800 tac mlra">
						<?php if ( ! empty( $section_title ) ) : ?>
							<h2 class="border0 f36 fwb"><?php echo $section_title; ?></h2>
						<?php endif; ?>
						<?php if ( ! empty( $section_blurb ) ) : ?>
							<div class="last-child-margin-bottom-0 f20">
								<?php echo $section_blurb; ?>
							</div>
						<?php endif; ?>
					</div>
					<?php if ( ! empty( $past_events ) ) : ?>
						<div class="icymi-events mw-970 mlra spt">
							<?php if ( ! empty( $past_events_title ) ) : ?>
								<h3 class="f24 mb1half no-transform tc-text-gray fwb ff-base"><?php echo $past_events_title; ?></h3>
							<?php endif; ?>
							<div class="one2grid flex-start">
								<?php foreach ( $past_events as $event ) : ?>
									<?php mk_kitces_event_block( $event, false, true ); ?>
								<?php endforeach; ?>
							</div>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</section>
		<?php
	}
}

function mk_events_final_cta() {
	$title   = mk_get_field( 'final_cta_title' );
	$content = mk_get_field( 'final_cta_content' );
	$button  = mk_get_field( 'final_cta_button' );
	?>
	<?php if ( ! empty( $title ) || ! empty( $content ) || ! empty( $button ) ) : ?>
		<section class="kitces-events-final-cta spt-l spb-l bg-white">
			<div class="wrap">
				<div class="kitces-events-final-cta-inner tac mw-950 mx-auto">
					<?php if ( ! empty( $title ) ) : ?>
						<h2 class="section-title"><?php echo $title; ?></h2>
					<?php endif; ?>
					<?php if ( ! empty( $content ) ) : ?>
						<div class="last-child-margin-bottom-0 f20">
							<?php echo wpautop( $content ); ?>
						</div>
					<?php endif; ?>
					<?php if ( ! empty( $button ) ) : ?>
						<div class="mt2">
							<?php echo mk_link_html( $button, 'button' ); ?>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</section>
	<?php endif; ?>
	<?php
}

genesis();
