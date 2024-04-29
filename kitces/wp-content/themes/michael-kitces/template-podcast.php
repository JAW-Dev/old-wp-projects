<?php

/*
Template Name: Podcast
*/

add_filter( 'body_class', 'cgd_podcast_body_class' );
function cgd_podcast_body_class( $classes ) {
	$classes[] = 'podcast-page';
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
add_action( 'genesis_after_header', 'objectiv_iceberg_section' );
add_action( 'genesis_after_header', 'objectiv_listen_learn_section' );
add_action( 'genesis_after_header', 'objectiv_reviews_section' );
add_action( 'genesis_after_header', 'objectiv_subscribe_section' );
add_action( 'genesis_after_header', 'objectiv_listen_now_section' );
add_action( 'genesis_after_header', 'objectiv_find_episode_section' );
add_action( 'genesis_after_header', 'objectiv_reviews_section_2' );
add_action( 'genesis_after_header', 'objectiv_apply_recommend_section' );
add_action( 'genesis_after_header', 'objectiv_faq_section' );


function objectiv_intro_header() {
	$head_deets     = mk_get_field( 'hero_section_details', get_the_ID(), true, true );
	$hero_title     = mk_key_value( $head_deets, 'hero_title' );
	$hero_sub_title = mk_key_value( $head_deets, 'hero_sub_title' );
	$hero_bg_image  = mk_key_value( $head_deets, 'hero_bg_image' );
	$green_button   = mk_get_field( 'hero_green_button', get_the_ID(), true, true );
	$blue_button    = mk_get_field( 'hero_blue_button', get_the_ID(), true, true );

	// Decide whether to use featured or hero set image
	if ( ! $hero_bg_image ) {
		$bg_img_id = get_post_thumbnail_id();
	} else {
		$bg_img_id = $hero_bg_image['ID'];
	}

	// Get the BG image url
	$bg_img_url = wp_get_attachment_image_url( $bg_img_id, 'full' );

	?>
	<section class="page-section pod-head spt-l spb-l bg-cover light-blue-overlay" style="background-image: url(<?php echo $bg_img_url; ?>);">
		<div class="wrap prel">
			<div class="pod-head-content max-800 mlra">
				<?php if ( ! empty( $hero_title ) ) : ?>
					<h1 class="pod-head-title wt fwb f42 tac"><?php echo $hero_title; ?></h1>
				<?php endif; ?>
				<?php if ( ! empty( $hero_sub_title ) ) : ?>
					<p class="pod-head-sub-title wt fwm f26 italic tac mb0 mt1"><?php echo $hero_sub_title; ?></p>
				<?php endif; ?>

				<?php if ( ! empty( $green_button ) || ! empty( $blue_button ) ) : ?>
					<footer class="pod-head-footer tac mt1">
						<?php if ( ! empty( $green_button ) ) : ?>
							<?php echo mk_link_html( $green_button, 'button f18' ); ?>
						<?php endif; ?>
						<?php if ( ! empty( $blue_button ) ) : ?>
							<?php echo mk_link_html( $blue_button, 'button light-blue f18' ); ?>
						<?php endif; ?>
					</footer>
				<?php endif; ?>
			</div>
		</div>
	</section>
	<?php
}

function objectiv_iceberg_section() {
	$title   = mk_get_field( 'iceberg_section_title', get_the_ID(), true, true );
	$content = mk_get_field( 'iceberg_content', get_the_ID(), true, true );
	$image   = mk_get_field( 'iceberg_image', get_the_ID(), true, true );

	?>
	<section class="page-section iceberg-section spt spb" id="iceberg">
		<div class="wrap">
			<div class="iceberg-section-inner">
				<?php if ( ! empty( $image ) ) : ?>
					<div class="iceberg-image-wrap mw-500 image-modaal" data-modaal-content-source="<?php echo $image['url']; ?>"">
						<img class="soft-shadow" src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>">
						<div class="iceberg-image-larger">Click to View Image Larger</div>
					</div>
				<?php endif; ?>
				<?php if ( ! empty( $title ) ) : ?>
					<h2 class="iceberg-title section-title"><?php echo $title; ?></h2>
				<?php endif; ?>
				<?php if ( ! empty( $content ) ) : ?>
					<div class="iceberg-content last-child-margin-bottom-0">
						<?php echo $content; ?>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</section>
	<?php
}

function objectiv_listen_learn_section() {
	$title   = mk_get_field( 'listen_learn_section_title', get_the_ID(), true, true );
	$content = mk_get_field( 'listen_learn_content', get_the_ID(), true, true );
	$image   = mk_get_field( 'listen_learn_bg_image', get_the_ID(), true, true );

	?>
	<section class="page-section listen-learn-section spt spb bg-cover" style="background-image: url(<?php echo $image['url']; ?>)">
		<div class="wrap prel">
			<div class="listen-learn-section-inner">
				<?php if ( ! empty( $title ) ) : ?>
					<h2 class="section-title tac text-white"><?php echo $title; ?></h2>
				<?php endif; ?>
				<?php if ( ! empty( $content ) ) : ?>
					<div class="text-white mw-970 mlra last-child-margin-bottom-0">
						<?php echo $content; ?>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</section>
	<?php
}

function objectiv_reviews_section() {
	$title   = mk_get_field( 'reviews_section_title', get_the_ID(), true, true );
	$reviews = mk_get_field( 'reviews', get_the_ID(), true, true );

	objectiv_do_podcast_reviews_section( $title, $reviews );
}

function objectiv_subscribe_section() {
	$image           = mk_get_field( 'subscribe_image', get_the_ID(), true, true );
	$blurb           = mk_get_field( 'subscribe_blurb', get_the_ID(), true, true );
	$display_buttons = mk_get_field( 'subscribe_buttons', get_the_ID(), true, true );
	$sub_buttons     = mk_get_field( 'podcast_subscribe_links', 'options', true, true );
	$email_form      = mk_get_field( 'subscribe_email_form', get_the_ID(), true, true );

	?>
	<section class="page-section subscribe-section spt spb bg-light-gray">
		<div class="wrap ">
			<div class="subscribe-section-inner">
				<?php if ( ! empty( $image ) ) : ?>
					<div class="left-image-wrap mw-300">
						<?php echo wp_get_attachment_image( $image['ID'], 'large-square' ); ?>
					</div>
				<?php endif; ?>
				<div class="right-subscribe-details">
					<?php if ( ! empty( $blurb ) ) : ?>
						<div class="subscribe-blurb"><?php echo $blurb; ?></div>
					<?php endif; ?>
					<?php if ( $display_buttons && ! empty( $sub_buttons ) ) : ?>
						<?php objectiv_do_podcast_image_buttons( $sub_buttons ); ?>
						<?php if ( ! empty( $email_form ) ) : ?>
							<div class="subscribe-or">or...</div>
							<div class="subscribe-email-form-wrap">
								<?php echo do_shortcode( $email_form ); ?>
							</div>
						<?php endif; ?>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</section>
	<?php
}

function objectiv_listen_now_section() {
	$title   = mk_get_field( 'listen_now_section_title', get_the_ID(), true, true );
	$content = mk_get_field( 'listen_now_section_blurb', get_the_ID(), true, true );

	?>
	<section class="page-section listen-now-section spt spb">
		<div class="wrap ">
			<div class="listen-now-section-inner">
				<?php if ( ! empty( $title ) ) : ?>
					<h2 class="section-title tac"><?php echo $title; ?></h2>
				<?php endif; ?>
				<?php if ( ! empty( $content ) ) : ?>
					<div class="mw-970 mlra last-child-margin-bottom-0">
						<?php echo $content; ?>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</section>
	<?php
}

function objectiv_find_episode_section() {
	$title   = mk_get_field( 'find_ep_section_title', get_the_ID(), true, true );
	$content = mk_get_field( 'find_ep_section_blurb', get_the_ID(), true, true );

	?>
	<section class="page-section listen-now-section spt spb bg-light-gray">
		<div class="wrap ">
			<div class="listen-now-section-inner">
				<?php if ( ! empty( $title ) ) : ?>
					<h2 class="section-title tac"><?php echo $title; ?></h2>
				<?php endif; ?>
				<?php if ( ! empty( $content ) ) : ?>
					<div class="mw-970 mlra last-child-margin-bottom-0">
						<?php echo $content; ?>
					</div>
				<?php endif; ?>
				<?php objectiv_do_tabbed_podcast_topics(); ?>
			</div>
		</div>
	</section>
	<?php
}

function objectiv_reviews_section_2() {
	$title   = mk_get_field( 'reviews_2_section_title', get_the_ID(), true, true );
	$reviews = mk_get_field( 'reviews_2', get_the_ID(), true, true );

	objectiv_do_podcast_reviews_section( $title, $reviews );
}

function objectiv_apply_recommend_section() {
	$sec_title  = mk_get_field( 'apply_section_title', get_the_ID(), true, true );
	$content    = mk_get_field( 'apply_top_blurb', get_the_ID(), true, true );
	$form_title = mk_get_field( 'apply_form_title', get_the_ID(), true, true );
	$form       = mk_get_field( 'apply_form_to_display', get_the_ID(), true, true );

	objectiv_do_gravity_form_section( $sec_title, $content, $form_title, $form, 'apply-recommend' );
}

function objectiv_faq_section() {
	$title = get_field( 'faq_section_title' );
	$rows  = get_field( 'faq_accordions' );

	objectiv_do_faq_accordion_section( $title, $rows, 'bg-white' );
}

genesis();
