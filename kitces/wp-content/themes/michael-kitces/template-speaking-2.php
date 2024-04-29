<?php

/*
Template Name: Speaking 2.0
 */

add_filter( 'body_class', 'speaking_d_body_class' );
function speaking_d_body_class( $classes ) {
	$classes[] = 'speaking-2';
	return $classes;
}

// full width layout
add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_full_width_content' );

remove_action( 'genesis_loop', 'genesis_do_loop' );
remove_action( 'genesis_entry_header', 'genesis_do_post_title' );

add_action( 'genesis_after_header', 'objectiv_speaking_2_page_content' );

function objectiv_speaking_2_page_content() {
	objectiv_speaking_hero();
	objectiv_alert_bar();
	objectiv_as_seen_at();
	objectiv_first_content_section();
	objectiv_first_testimonials();
	objectiv_icon_circle_nav();
	objectiv_kitces_speakers();
	objectiv_speaking_topics();
	objectiv_speaking_pricing();
	objectiv_speaking_presentation_requirements();
	objectiv_second_testimonials();
	objectiv_speaking_availability_capacity();
	objectiv_speaking_contact_form();
	objectiv_speaking_videos();
	objectiv_speaking_faqs();
	objectiv_third_testimonials();
	objectiv_speaking_footer_cta();
}

function objectiv_alert_bar() {
	$message = mk_acf_get_field( 'speaking_alert_message' );
	if ( $message ) {
		?>
			<div class="alert-bar alert-bar__speaking">
				<div class="alert-bar__content">
					<?php echo wp_kses_post( $message ); ?>
				</div>
			</div>
		<?php
	}
}

function objectiv_speaking_hero() {
	 $head_details = get_field( 'hero_section_details' );

	if ( is_array( $head_details ) && ! empty( $head_details ) ) {
		$title      = $head_details['hero_title'];
		$sub_title  = $head_details['hero_sub_title'];
		$banner_img = $head_details['hero_bg_image'];

		if ( ! empty( $banner_img ) ) {
			$banner_img = $banner_img['sizes']['large'];
		}
		?>
		<section class="page-section bg-widget-blue">
			<div class="wrap">
				<div class="speaking-banner-outer bg-light-blue">
					<div class="speaking-banner__image" style="background-image:url(<?php echo $banner_img; ?>)"></div>
					<div class="speaking-banner__content spt spb">
						<h1 class="speaking-banner_page-title tc-white"><?php echo $title; ?></h1>
						<div class="speaking-banner__page-sub-title tc-white"><?php echo $sub_title; ?></div>
					</div>
				</div>
			</div>
		</section>
		<?php

	}

}

function objectiv_as_seen_at() {
	$sec_title = get_field( 'as_seen_at_section_title' );
	$logos     = get_field( 'logos' );

	if ( array_key_exists( 'linked_images', $logos ) && ! empty( $logos['linked_images'] ) ) {
		?>
		<section class="page-section spt spb bg-light-gray">
			<div class="wrap">
				<div class="as-seen-logos">
					<?php if ( ! empty( $sec_title ) ) : ?>
						<h2 class="as-seen-logo"><?php echo $sec_title; ?></h2>
					<?php endif; ?>
					<?php foreach ( $logos['linked_images'] as $logo ) : ?>
						<?php objectiv_output_link_image( $logo, 'as-seen-logo' ); ?>
					<?php endforeach; ?>
				</div>
			</div>
		</section>
		<?php

	}
}

function objectiv_icon_circle_nav() {
	$icon_links = get_field( 'icon_links' );

	if ( is_array( $icon_links ) && ! empty( $icon_links ) ) {
		?>
		<section class="page-section spt spb">
			<div class="wrap">
				<?php objectiv_ouput_icons_grid( $icon_links['linked_images'] ); ?>
			</div>
		</section>
		<?php

	}
}

function objectiv_first_content_section() {
	 $first_content = get_field( 'first_content' );

	if ( is_array( $first_content ) && ! empty( $first_content ) ) {
		$sec_title = $first_content['section_title'];
		$content   = $first_content['content'];
		?>
		<section class="page-section spt spb" id="overview">
			<div class="wrap">
				<?php if ( ! empty( $sec_title ) ) : ?>
					<h2 class="section-title"><?php echo $sec_title; ?></h2>
				<?php endif; ?>
				<?php if ( ! empty( $content ) ) : ?>
					<div class="page-section__content norm-list lmb0"><?php echo $content; ?></div>
				<?php endif; ?>
			</div>
		</section>
		<?php

	}
}

function objectiv_first_testimonials() {
	$sec_title    = get_field( 'first_testimonial_section_title' );
	$testimonials = get_field( 'first_testimonials' );

	if ( is_array( $testimonials ) && ! empty( $testimonials ) ) {
		?>
		<section class="page-section spt spb-l bg-light-gray">
			<div class="wrap">
				<?php if ( ! empty( $sec_title ) ) : ?>
					<h2 class="section-title"><?php echo $sec_title; ?></h2>
				<?php endif; ?>
				<?php objectiv_do_testimonial_slider( $testimonials ); ?>
			</div>
		</section>
		<?php

	}
}

function objectiv_kitces_speakers() {
	$top_content = get_field( 'speakers_top_content' );
	$speakers    = get_field( 'speakers_table' );

	if ( is_array( $top_content ) && ! empty( $top_content ) ) {
		$sec_title = $top_content['section_title'];
		$content   = $top_content['content'];
	} else {
		$sec_title = null;
		$content   = null;
	}

	if ( ! empty( $top_content ) || ! empty( $speakers ) ) {
		?>
		<section class="page-section spt spb bg-light-gray" id="speakers">
			<div class="wrap">
				<?php if ( ! empty( $sec_title ) ) : ?>
					<h2 class="section-title"><?php echo $sec_title; ?></h2>
				<?php endif; ?>
				<?php if ( ! empty( $content ) ) : ?>
					<div class="page-section__content norm-list lmb0"><?php echo $content; ?></div>
				<?php endif; ?>
			</div>
			<?php objectiv_do_new_speakers_table( $speakers ); ?>
		</section>
		<?php

	}
}

function objectiv_speaking_topics() {
	$top_content = get_field( 'topics_top_content' );
	$topic_tabs  = get_field( 'topic_tabs' );

	if ( is_array( $top_content ) && ! empty( $top_content ) ) {
		$sec_title = $top_content['section_title'];
		$content   = $top_content['content'];
	} else {
		$sec_title = null;
		$content   = null;
	}

	if ( ! empty( $top_content ) ) {
		?>
		<section class="page-section spt spb" id="content">
			<div class="wrap">
				<?php if ( ! empty( $sec_title ) ) : ?>
					<h2 class="section-title"><?php echo $sec_title; ?></h2>
				<?php endif; ?>
				<?php if ( ! empty( $content ) ) : ?>
					<div class="page-section__content norm-list lmb0"><?php echo $content; ?></div>
				<?php endif; ?>
				<?php objectiv_do_speaking_topics( $topic_tabs ); ?>
			</div>
		</section>
		<?php

	}
}

function objectiv_speaking_pricing() {
	$top_content         = mk_acf_get_field( 'pricing_top_content' );
	$pricing_table_deets = mk_acf_get_field( 'pricing_table' );

	$bottom_content = mk_acf_get_field( 'pricing_bottom_content' );

	$left_bottom_content_title  = mk_acf_get_field( 'pricing_left_bottom_content_title' );
	$left_bottom_content        = mk_acf_get_field( 'pricing_left_bottom_content' );
	$right_bottom_content_title = mk_acf_get_field( 'pricing_right_bottom_content_title' );
	$right_bottom_content       = mk_acf_get_field( 'pricing_right_bottom_content' );

	$bottom_link = mk_acf_get_field( 'pricing_bottom_button' );

	if ( is_array( $top_content ) && ! empty( $top_content ) ) {
		$sec_title = $top_content['section_title'];
		$content   = $top_content['content'];
	} else {
		$sec_title = null;
		$content   = null;
	}

	if ( ! empty( $pricing_table_deets ) || ! empty( $top_content ) ) {
		?>
		<section class="page-section spt spb bg-light-gray" id="cost">
			<div class="wrap">
				<?php if ( ! empty( $sec_title ) ) : ?>
					<h2 class="section-title"><?php echo wp_kses_post( $sec_title ); ?></h2>
				<?php endif; ?>
				<?php if ( ! empty( $content ) ) : ?>
					<div class="page-section__content norm-list lmb0"><?php echo wp_kses_post( $content ); ?></div>
				<?php endif; ?>
			</div>

			<?php objectiv_do_speaking_pricing_table( $pricing_table_deets ); ?>

			<div class="wrap">
				<?php if ( ! empty( $left_bottom_content ) && ! empty( $right_bottom_content ) ) : ?>
					<div class="speaker-pricing-bottom-content two-column-content norm-list lmb0">
						<div class="two-column-content__content">
							<div class="two-column-content__content-left two-column-content__wrap">
								<h3><?php echo wp_kses_post( $left_bottom_content_title ); ?></h3>
								<?php echo wp_kses_post( $left_bottom_content ); ?>
							</div>
							<div class="two-column-content__content-right two-column-content__wrap">
								<h3><?php echo wp_kses_post( $right_bottom_content_title ); ?></h3>
								<?php echo wp_kses_post( $right_bottom_content ); ?>
							</div>
						</div>
					</div>
				<?php endif; ?>

				<?php if ( ! empty( $bottom_content ) ) : ?>
					<div class="speaker-pricing-bottom-content norm-list lmb0"><?php echo wp_kses_post( $bottom_content ); ?></div>
				<?php endif; ?>

				<?php if ( ! empty( $bottom_link ) ) : ?>
					<div class="speaker-pricing-bottom_button-wrap tac">
						<?php echo wp_kses_post( mk_link_html( $bottom_link, 'button light-blue large-button' ) ); ?>
					</div>
				<?php endif; ?>
			</div>
		</section>
		<?php

	}
}

function objectiv_speaking_presentation_requirements() {
	$top_content = mk_acf_get_field( 'presentation_reqs_content' );

	$left_content_title  = mk_acf_get_field( 'presentation_left_content_title' );
	$left_content        = mk_acf_get_field( 'presentation_left_content' );
	$right_content_title = mk_acf_get_field( 'presentation_right_content_title' );
	$right_content       = mk_acf_get_field( 'presentation_right_content' );

	if ( is_array( $top_content ) && ! empty( $top_content ) ) {
		$sec_title = $top_content['section_title'];
		$content   = $top_content['content'];
	} else {
		$sec_title = null;
		$content   = null;
	}

	if ( ! empty( $top_content ) ) {
		?>
		<section class="page-section spt spb">
			<div class="wrap">
				<?php if ( ! empty( $sec_title ) ) : ?>
					<h2 class="section-title"><?php echo $sec_title; ?></h2>
				<?php endif; ?>
				<?php if ( ! empty( $content ) ) : ?>
					<div class="page-section__content norm-list lmb0"><?php echo $content; ?></div>
				<?php endif; ?>

				<?php if ( ! empty( $left_content ) && ! empty( $right_content ) ) : ?>
					<div class="page-section__content norm-list lmb0">
					<div class="two-column-content__content">
						<div class="two-column-content__content-left two-column-content__wrap">
							<h3><?php echo wp_kses_post( $left_content_title ); ?></h3>
							<?php echo wp_kses_post( $left_content ); ?>
						</div>
						<div class="two-column-content__content-right two-column-content__wrap">
							<h3><?php echo wp_kses_post( $right_content_title ); ?></h3>
							<?php echo wp_kses_post( $right_content ); ?>
						</div>
					</div>
					</div>
				<?php endif; ?>
			</div>
		</section>
		<?php
	}
}

function objectiv_second_testimonials() {
	$sec_title    = get_field( 'second_testimonials_section_title' );
	$testimonials = get_field( 'second_testimonials' );

	if ( is_array( $testimonials ) && ! empty( $testimonials ) ) {
		?>
		<section class="page-section spt spb-l bg-light-gray">
			<div class="wrap">
				<?php if ( ! empty( $sec_title ) ) : ?>
					<h2 class="section-title"><?php echo $sec_title; ?></h2>
				<?php endif; ?>
				<?php objectiv_do_testimonial_slider( $testimonials ); ?>
			</div>
		</section>
		<?php

	}
}

function objectiv_speaking_availability_capacity() {
	$availability_deets = mk_acf_get_field( 'availability_&_capacity_details' );

	if ( is_array( $availability_deets ) && ! empty( $availability_deets ) ) {
		$sec_title    = $availability_deets['section_title'];
		$top_text     = $availability_deets['top_text'];
		$bottom_text  = $availability_deets['bottom_text'];
		$left_button  = $availability_deets['left_button'];
		$right_button = $availability_deets['right_button'];
	} else {
		$sec_title    = null;
		$top_text     = null;
		$bottom_text  = null;
		$left_button  = null;
		$right_button = null;
	}

	if ( ! empty( $sec_title ) || ! empty( $top_text ) ) {
		?>
		<section class="page-section spt spb" id="availability">
			<div class="wrap">
				<?php if ( ! empty( $sec_title ) ) : ?>
					<h2 class="section-title"><?php echo wp_kses_post( $sec_title ); ?></h2>
				<?php endif; ?>
				<?php if ( ! empty( $top_text ) ) : ?>
					<div class="page-section__content norm-list lmb0"><?php echo wp_kses_post( $top_text ); ?></div>
				<?php endif; ?>
			</div>
			<?php objectiv_speaking_availability_table(); ?>
			<div class="wrap">
				<?php if ( ! empty( $bottom_text ) ) : ?>
					<div class="page-section__content norm-list lmb0"><?php echo wp_kses_post( $bottom_text ); ?></div>
				<?php endif; ?>
				<?php if ( ! empty( $left_button ) || ! empty( $right_button ) ) : ?>
					<footer class="availability-footer tac">
						<?php echo wp_kses_post( mk_link_html( $left_button, 'button large-button' ) ); ?>
						<?php echo wp_kses_post( mk_link_html( $right_button, 'button light-blue large-button' ) ); ?>
					</footer>
				<?php endif; ?>
			</div>
		</section>
		<?php

	}
}

function objectiv_speaking_contact_form() {
	 $top_content = get_field( 'req_form_top_content' );
	$form_title   = get_field( 'req_form_title' );
	$form         = get_field( 'req_form_to_display' );

	if ( is_array( $top_content ) && ! empty( $top_content ) ) {
		$sec_title = $top_content['section_title'];
		$content   = $top_content['content'];
	} else {
		$sec_title = null;
		$content   = null;
	}

	objectiv_do_gravity_form_section( $sec_title, $content, $form_title, $form, 'book-now' );
}

function objectiv_speaking_videos() {
	$top_content = get_field( 'videos_top_content' );
	$videos      = get_field( 'videos' );

	if ( is_array( $top_content ) && ! empty( $top_content ) ) {
		$sec_title = $top_content['section_title'];
		$content   = $top_content['content'];
	} else {
		$sec_title = null;
		$content   = null;
	}
	if ( ! empty( $top_content ) ) {
		?>
		<section class="page-section spt spb" id="videos">
			<div class="wrap">
				<?php if ( ! empty( $sec_title ) ) : ?>
					<h2 class="section-title"><?php echo $sec_title; ?></h2>
				<?php endif; ?>
				<?php if ( ! empty( $content ) ) : ?>
					<div class="page-section__content norm-list lmb0 tac"><?php echo $content; ?></div>
				<?php endif; ?>
				<?php objective_videos_grid( $videos['video_rows'] ); ?>
			</div>
		</section>
		<?php

	}
}

function objectiv_speaking_faqs() {
	 $sec_title = get_field( 'faq_section_title' );
	$rows       = get_field( 'frequently_asked_questions' );

	if ( ! empty( $sec_title ) ) {
		?>
		<section class="page-section spt spb bg-light-gray" id="faq">
			<div class="wrap">
				<?php if ( ! empty( $sec_title ) ) : ?>
					<h2 class="section-title"><?php echo $sec_title; ?></h2>
				<?php endif; ?>
				<?php if ( ! empty( $rows ) ) : ?>
					<div class="accordion-rows-wrap accordion-block">
						<?php foreach ( $rows as $r ) : ?>
							<?php
							$title = $r['accordion_title'];
							$icon  = $r['accordion_icon'];
							if ( ! empty( $icon ) ) {
								$icon_url = $icon['url'];
							} else {
								$icon_url = null;
							}
							$content = $r['accordion_content'];
							?>
							<?php obj_accordion_row( $title, $icon_url, $content ); ?>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
			</div>
		</section>
		<?php

	}
}

function objectiv_third_testimonials() {
	$sec_title    = get_field( 'third_testimonials_section_title' );
	$testimonials = get_field( 'third_testimonials' );

	if ( is_array( $testimonials ) && ! empty( $testimonials ) ) {
		?>
		<section class="page-section spt spb-l bg-light-gray">
			<div class="wrap">
				<?php if ( ! empty( $sec_title ) ) : ?>
					<h2 class="section-title"><?php echo $sec_title; ?></h2>
				<?php endif; ?>
				<?php objectiv_do_testimonial_slider( $testimonials ); ?>
			</div>
		</section>
		<?php

	}
}

function objectiv_speaking_footer_cta() {
	$button = get_field( 'footer_cta_button' );

	if ( ! empty( $button ) ) {
		?>
		<section class="page-section spt spb">
			<div class="wrap tac">
				<?php echo mk_link_html( $button, 'button light-blue large-button' ); ?>
			</div>
		</section>
		<?php

	}
}

genesis();
