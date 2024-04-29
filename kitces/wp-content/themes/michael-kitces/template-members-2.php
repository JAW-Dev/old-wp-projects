<?php

/*
Template Name: Members Sales Page 2.0
*/

add_filter( 'body_class', 'cgd_body_class' );
function cgd_body_class( $classes ) {
	$classes[] = 'members2';
	return $classes;
}

// full width layout
add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_full_width_content' );

remove_action( 'genesis_loop', 'genesis_do_loop' );
remove_action( 'genesis_entry_header', 'genesis_do_post_title' );

add_action( 'genesis_after_header', 'objectiv_intro_header' );
add_action( 'genesis_after_header', 'objectiv_first_content_sec' );
add_action( 'genesis_after_header', 'objectiv_second_content_sec' );
add_action( 'genesis_after_header', 'objectiv_members_icon_link_section' );
add_action( 'genesis_after_header', 'objectiv_members_first_icon_content_section' );
add_action( 'genesis_after_header', 'objectiv_members_first_testimonial_section' );
add_action( 'genesis_after_header', 'objectiv_members_second_icon_content_section' );
add_action( 'genesis_after_header', 'objectiv_members_full_cat_cta_section' );
add_action( 'genesis_after_header', 'objectiv_members_second_testimonial_section' );
add_action( 'genesis_after_header', 'objectiv_members_pricing_section' );
add_action( 'genesis_after_header', 'objectiv_members_frequently_asked_section' );
add_action( 'genesis_after_header', 'objectiv_members_third_testimonial_section' );
add_action( 'genesis_after_header', 'objectiv_members_final_cta_section' );

function objectiv_intro_header() { ?>
	<?php
	$image         = get_field( 'mh_image' );
	$display_image = wp_get_attachment_image( $image['ID'], 'medium_large' );
	$title         = get_field( 'mh_title' );
	$sub_title     = get_field( 'mh_sub_title' );
	$left_btn      = get_field( 'mh_left_button_details' );
	$right_btn     = get_field( 'mh_right_button_details' );
	?>
	<section class="page-section mem-head spt spb">
		<div class="wrap">
			<?php if ( ! empty( $image ) ) : ?>
				<div class="mem-head-img">
					<?php echo $display_image; ?>
				</div>
			<?php endif; ?>
			<div class="mem-head-content">
				<?php if ( ! empty( $title ) ) : ?>
					<h1 class="mem-head-title wt fwb f42"><?php echo $title; ?></h1>
				<?php endif; ?>
				<?php if ( ! empty( $sub_title ) ) : ?>
					<p class="mem-head-sub-title wt fwm f26"><?php echo $sub_title; ?></p>
				<?php endif; ?>

				<?php if ( ! empty( $left_btn ) || ! empty( $right_btn ) ) : ?>
					<footer class="mem-head-footer">
						<?php if ( ! empty( $left_btn ) ) : ?>
							<?php echo mk_link_html( $left_btn, 'button larger-button f26' ); ?>
						<?php endif; ?>
						<?php if ( ! empty( $right_btn ) ) : ?>
							&nbsp;
							&nbsp;
							<?php echo mk_link_html( $right_btn, 'button larger-button f26 medium-blue' ); ?>
						<?php endif; ?>
					</footer>
				<?php endif; ?>
			</div>
		</div>
	</section>
<?php
}

function objectiv_first_content_sec() {
?>
	<?php
	$content = get_field( 'first_content_content' );
	?>
	<?php if ( ! empty( $content ) ) : ?>
	<section class="page-section mem-first-content spt spb bg-light-gray" id="learn-more-1">
		<div class="wrap ml0">
			<?php echo $content; ?>
		</div>
	</section>
	<?php endif; ?>
<?php
}

function objectiv_second_content_sec() {
?>
	<?php
	$content       = get_field( 'second_content_content' );
	$call_out_text = get_field( 'second_content_call_out' );
	$second_images = get_field( 'second_content_second_set_of_images_copy', get_the_ID() );
	?>
	<?php if ( ! empty( $content ) ) : ?>
	<section class="page-section mem-second-content spt spb">
		<div class="wrap">
			<?php echo $content; ?>
			<?php if ( ! empty( $second_images ) ) : ?>
			<div class="mem-second-images-wrap">
				<?php
				$count = 0;
				foreach ( $second_images as $i ) {
					$id       = $i['image']['ID'];
					$image    = wp_get_attachment_image_url( $id, 'medium_large' );
					$pop      = $i['pop_up_on_click'];
					$pop_text = $i['pop_up_on_click_text'];
					if ( ! empty( $image ) ) {
						if ( $pop && ! empty( $pop_text ) ) {
						?>
							<a href="#pop-click-<?php echo $count; ?>" id="fancy-inline">
								<img src="<?php echo $image; ?>">
							</a>
							<div id="pop-click-<?php echo $count; ?>" style="display:none;">
								<?php echo $pop_text; ?>
							</div>
							<?php
						} else {
						?>
							<img src="<?php echo $image; ?>">
						<?php
						}
					}
					$count += 1;
				}
				?>
			</div>
			<?php endif; ?>
			<?php if ( ! empty( $call_out_text ) ) : ?>
				<p class="call-out-text"><?php echo $call_out_text; ?></p>
			<?php endif; ?>
		</div>
	</section>
	<?php endif; ?>
<?php
}

function objectiv_members_icon_link_section() {
?>
	<?php
	$title     = get_field( 'il_section_title' );
	$sub_title = get_field( 'il_section_sub_title' );
	$icons     = get_field( 'icon_links' );
	?>
	<?php if ( ! empty( $icons ) ) : ?>
	<section class="page-section mem-icon-links-content spt spb bg-light-gray">
		<div class="wrap">
			<?php if ( ! empty( $title ) ) : ?>
			<header class="page-section-header tac mw-970 mlra">
				<div class="wrap">
					<h2 class="page-section-title f48 border0 ff-mont fwb"><?php echo $title; ?></h2>
					<?php if ( ! empty( $sub_title ) ) : ?>
						<p><?php echo $sub_title; ?></p>
					<?php endif; ?>
				</div>
			</header>
			<?php endif; ?>
			<?php if ( ! empty( $icons ) ) : ?>
				<div class="icons-grid-wrap">
					<?php foreach ( $icons as $i ) : ?>
						<?php
							$icon    = $i['icon']['url'];
							$desc    = $i['icon']['description'];
							$link    = $i['link_details'];
							$l_title = $link['title'];
							$l_url   = $link['url'];
						?>
						<?php if ( ! empty( $link ) ) : ?>
							<a href="<?php echo $l_url; ?>" class="icon-grid-link">
								<?php if ( ! empty( $icon ) ) : ?>
									<div class="icon-wrapper">
										<?php echo obj_svg( $icon , false, $desc ); ?>
									</div>
								<?php endif; ?>
								<?php if ( ! empty( $l_title ) ) : ?>
									<div class="icon-block-title f24"><?php echo $l_title; ?></div>
								<?php endif; ?>
							</a>
						<?php endif; ?>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>
	</section>
	<?php endif; ?>
<?php
}

function objectiv_members_first_icon_content_section() {
?>
	<?php
	$rows = get_field( 'first_icon_content_rows' );
	?>
	<?php if ( ! empty( $rows ) ) : ?>
	<section class="page-section mem-icon-content-rows spt spb">
		<div class="wrap">
			<?php if ( ! empty( $rows ) ) : ?>
				<div class="icon-rows-wrap">
					<?php foreach ( $rows as $r ) : ?>
						<?php
						$sec_id    = $r['section_id'];
						$svg       = $r['icon']['url'];
						$desc      = $r['icon']['description'];
						$content   = $r['content'];
						$green_btn = $r['green_button'];
						$blue_btn  = $r['blue_button'];
						?>
						<?php icon_content_row( $sec_id, $svg, $content, $green_btn, $blue_btn, $desc ); ?>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>
	</section>
	<?php endif; ?>
<?php
}

function objectiv_members_first_testimonial_section() {
?>
	<?php
	$title = get_field( 'first_test_section_title' );
	$rows  = get_field( 'first_testimonials' );

	$spec_class = '';
	if ( empty( $title ) ) {
		$spec_class = 'no-title';
	}

	?>
	<?php if ( ! empty( $rows ) ) : ?>
	<section class="page-section mem-icon-content-rows spt spb bg-light-gray">
		<?php if ( ! empty( $title ) ) : ?>
		<header class="page-section-header tac">
			<div class="wrap">
				<h2 class="page-section-title f36 border0 tc-text-gray fwb"><?php echo $title; ?></h2>
				<?php if ( ! empty( $sub_title ) ) : ?>
					<p><?php echo $sub_title; ?></p>
				<?php endif; ?>
			</div>
		</header>
		<?php endif; ?>

		<div class="wrap">
			<?php if ( ! empty( $rows ) ) : ?>
				<div class="testimonial-rows-wrap <?php echo $spec_class; ?>">
					<?php foreach ( $rows as $r ) : ?>
						<?php
						$image_id         = $r['photo']['ID'];
						$image            = wp_get_attachment_image_src( $image_id, 'large-square' )[0];
						$blurb            = $r['testimonial_content'];
						$attribution      = $r['testimonial_attribution'];
						$attribution_comp = $r['testimonial_attribution_title_firm'];
						?>
						<?php obj_testimonial_block( $image, $blurb, $attribution, $attribution_comp, false ); ?>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>
	</section>
	<?php endif; ?>
<?php
}

function objectiv_members_second_icon_content_section() {
?>
	<?php
	$rows = get_field( 'second_icon_content_rows' );
	?>
	<?php if ( ! empty( $rows ) ) : ?>
	<section class="page-section mem-icon-content-rows spt spb">
		<div class="wrap">
			<?php if ( ! empty( $rows ) ) : ?>
				<div class="icon-rows-wrap">
					<?php foreach ( $rows as $r ) : ?>
						<?php
						$sec_id    = $r['section_id'];
						$svg       = $r['icon']['url'];
						$desc      = $r['icon']['description'];
						$content   = $r['content'];
						$green_btn = $r['green_button'];
						$blue_btn  = $r['blue_button'];
					?>
						<?php icon_content_row( $sec_id, $svg, $content, $green_btn, $blue_btn, $desc ); ?>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>
	</section>
	<?php endif; ?>
<?php
}

function objectiv_members_full_cat_cta_section() {
?>
	<?php
	$title       = get_field( 'fc_section_title' );
	$sub_title   = get_field( 'fc_sub_title' );
	$btn_details = get_field( 'button_details' );
	?>
	<?php if ( ! empty( $title ) || ! empty( $sub_title ) || ! empty( $btn_details ) ) : ?>
	<section class="page-section mem-icon-content-rows spt spb bg-light-blue">
		<?php if ( ! empty( $title ) ) : ?>
		<header class="page-section-header tac">
			<div class="wrap">
				<h2 class="page-section-title border0 tc-white fwb f48 lh1"><?php echo $title; ?></h2>
				<?php if ( ! empty( $sub_title ) ) : ?>
					<p class="page-section-sub-title tc-white mw-760 mlra f20 fwm mt2 mb2"><?php echo $sub_title; ?></p>
				<?php endif; ?>
			</div>
		</header>
		<?php endif; ?>
		<?php if ( ! empty( $btn_details ) ) : ?>
		<footer class="cta-button-wrap">
			<div class="wrap tac">
				<?php echo mk_link_html( $btn_details, 'button larger-button' ); ?>
			</div>
		</footer>
		<?php endif; ?>
	</section>
	<?php endif; ?>
<?php
}

function objectiv_members_second_testimonial_section() {
?>
	<?php
	$title = get_field( 'second_test_section_title' );
	$rows  = get_field( 'second_testimonials' );

	$spec_class = '';
	if ( empty( $title ) ) {
		$spec_class = 'no-title';
	}
	?>
	<?php if ( ! empty( $rows ) ) : ?>
	<section class="page-section mem-icon-content-rows spt spb bg-light-gray">
		<?php if ( ! empty( $title ) ) : ?>
		<header class="page-section-header tac">
			<div class="wrap">
				<h2 class="page-section-title f36 border0 tc-text-gray fwb"><?php echo $title; ?></h2>
				<?php if ( ! empty( $sub_title ) ) : ?>
					<p><?php echo $sub_title; ?></p>
				<?php endif; ?>
			</div>
		</header>
		<?php endif; ?>

		<div class="wrap">
			<?php if ( ! empty( $rows ) ) : ?>
				<div class="testimonial-rows-wrap <?php echo $spec_class; ?>">
					<?php foreach ( $rows as $r ) : ?>
						<?php
						$image_id         = $r['photo']['ID'];
						$image            = wp_get_attachment_image_src( $image_id, 'large-square' )[0];
						$blurb            = $r['testimonial_content'];
						$attribution      = $r['testimonial_attribution'];
						$attribution_comp = $r['testimonial_attribution_title_firm'];
						?>
						<?php obj_testimonial_block( $image, $blurb, $attribution, $attribution_comp, false ); ?>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>
	</section>
	<?php endif; ?>
<?php
}

function objectiv_members_pricing_section() {
?>
	<?php
	$title               = get_field( 'pricing_section_title' );
	$top_content         = get_field( 'pricing_top_content' );
	$top_button          = get_field( 'pricing_top_button' );
	$price_table_title   = get_field( 'pricing_table_title' );
	$price_table_details = get_field( 'pricing_table_details' );
	$price_table_footer  = get_field( 'pricing_footer_text' );
	$top_bot_content     = get_field( 'pricing_after_button_content' );

	?>
	<section class="page-section mem-icon-content-rows spt spb">
		<?php if ( ! empty( $title ) ) : ?>
		<header class="page-section-header tac">
			<div class="wrap">
				<h2 class="page-section-title f48 border0 tc-text-gray fwb"><?php echo $title; ?></h2>
				<?php if ( ! empty( $sub_title ) ) : ?>
					<p><?php echo $sub_title; ?></p>
				<?php endif; ?>
			</div>
		</header>
		<?php endif; ?>
		<div class="pricing-pre-content mw-970 mlra">
			<div class="wrap">
				<?php if ( ! empty( $top_content ) ) : ?>
					<div class="pricing-top-content mt2 tac ml0"><?php echo $top_content; ?></div>
				<?php endif; ?>
				<?php if ( ! empty( $top_button ) ) : ?>
					<div class="pricing-top-button-wrap tac mt2 mb2">
						<?php echo mk_link_html( $top_button, 'button larger-button' ); ?>
					</div>
				<?php endif; ?>
				<?php if ( ! empty( $top_bot_content ) ) : ?>
					<div class="pricing-top-bot-content mt2 tac ml0"><?php echo $top_bot_content; ?></div>
				<?php endif; ?>
			</div>
		</div>
		<?php if ( ! empty( $price_table_details ) ) : ?>
			<div class="pricing-divider-line">
				<div class="wrap">
					<hr class="mt3 mb3 pad0">
				</div>
			</div>

			<div class="pricing-table-wrapper" id="pricing-table-section">
				<div class="wrap">
					<?php if ( ! empty( $price_table_title ) ) : ?>
						<div class="pricing-table-title tac f48 fwb tc-text-med-blue mb1 mw-760 mlra lh1"><?php echo $price_table_title; ?></div>
					<?php endif; ?>
					<?php obj_do_pricing_table( $price_table_details ); ?>
					<?php if ( ! empty( $price_table_footer ) ) : ?>
						<div class="pricing-table-footer tac mw760 mlra1 ml0"><?php echo $price_table_footer; ?></div>
					<?php endif; ?>
				</div>
			</div>

		<?php endif; ?>
	</section>
<?php
}

function objectiv_members_frequently_asked_section() {
	$title = get_field('faq_section_title');
	$rows = get_field('faq_accordions');

	objectiv_do_faq_accordion_section( $title, $rows );
}

function objectiv_members_third_testimonial_section() {
?>
	<?php
	$title = get_field( 'third_test_section_title' );
	$rows  = get_field( 'third_testimonials' );

	$spec_class = '';
	if ( empty( $title ) ) {
		$spec_class = 'no-title';
	}

	?>
	<?php if ( ! empty( $rows ) ) : ?>
	<section class="page-section mem-icon-content-rows spt spb">
		<?php if ( ! empty( $title ) ) : ?>
		<header class="page-section-header tac">
			<div class="wrap">
				<h2 class="page-section-title f36 border0 tc-text-gray fwb"><?php echo $title; ?></h2>
				<?php if ( ! empty( $sub_title ) ) : ?>
					<p><?php echo $sub_title; ?></p>
				<?php endif; ?>
			</div>
		</header>
		<?php endif; ?>

		<div class="wrap">
			<?php if ( ! empty( $rows ) ) : ?>
				<div class="testimonial-rows-wrap <?php echo $spec_class; ?>">
					<?php foreach ( $rows as $r ) : ?>
						<?php
						$image_id         = $r['photo']['ID'];
						$image            = wp_get_attachment_image_src( $image_id, 'large-square' )[0];
						$blurb            = $r['testimonial_content'];
						$attribution      = $r['testimonial_attribution'];
						$attribution_comp = $r['testimonial_attribution_title_firm'];
						?>
						<?php obj_testimonial_block( $image, $blurb, $attribution, $attribution_comp, true ); ?>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>
	</section>
	<?php endif; ?>
<?php
}

function objectiv_members_final_cta_section() {
?>
	<?php
	$title       = get_field( 'footer_cta_title' );
	$btn_details = get_field( 'footer_cta_button_details' );
	?>
	<?php if ( ! empty( $title ) || ! empty( $btn_details ) ) : ?>
	<section class="page-section mem-icon-content-rows spt spb bg-light-blue">
		<?php if ( ! empty( $title ) ) : ?>
		<header class="page-section-header tac">
			<div class="wrap">
				<h2 class="page-section-title border0 tc-white fwb f36 lh1 uppercase mw-760 mlra"><?php echo $title; ?></h2>
			</div>
		</header>
		<?php endif; ?>
		<?php if ( ! empty( $btn_details ) ) : ?>
		<footer class="cta-button-wrap mt1">
			<div class="wrap tac">
				<?php echo mk_link_html( $btn_details, 'button larger-button' ); ?>
			</div>
		</footer>
		<?php endif; ?>
	</section>
	<?php endif; ?>
<?php
}

genesis();
