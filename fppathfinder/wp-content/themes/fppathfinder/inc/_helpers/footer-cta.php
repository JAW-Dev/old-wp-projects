<?php

function do_footer_cta( $options = null ) {

	// General Details
	$cta_type           = obj_key_value( $options, 'cta_type' );
	$background         = obj_key_value( $options, 'background' );
	$bg_img             = obj_key_value( $options, 'bg_img' );
	$title              = obj_key_value( $options, 'title' );
	$blurb              = obj_key_value( $options, 'blurb' );
	$button             = obj_key_value( $options, 'button' );
	$show_sample_button = obj_key_value( $options, 'show_sample_button' );

	// Testimonial Details
	$display_testimonials              = obj_key_value( $options, 'display_testimonials' );
	$testimonials_to_display           = obj_key_value( $options, 'testimonials_to_display' );
	$number_of_testimonials_to_display = obj_key_value( $options, 'number_of_testimonials_to_display' );
	$specific_testimonials             = obj_key_value( $options, 'specific_testimonials' );

	if ( $display_testimonials && 'testimonials' === $cta_type ) {

		if ( $testimonials_to_display === 'random' ) {
			$args = array(
				'posts_per_page' => $number_of_testimonials_to_display,
				'orderby'        => 'rand',
				'post_type'      => 'testimonial',
				'post_status'    => 'publish',
			);
		}

		if ( $testimonials_to_display === 'latest' ) {
			$args = array(
				'posts_per_page' => $number_of_testimonials_to_display,
				'orderby'        => 'post_date',
				'post_type'      => 'testimonial',
				'post_status'    => 'publish',
			);
		}
		$testimonials = get_posts( $args );

		// Over ride if we have specifics
		if ( $testimonials_to_display === 'specific' ) {
			$testimonials = $specific_testimonials;
		}
	} else {
		$testimonials = null;
	}

	$display_testimonials = is_array( $testimonials ) && ! empty( $testimonials ) && $display_testimonials;

	// Image Details
	$image_url = null;
	if ( 'solid' !== $background && obj_key_value( $bg_img, 'url' ) ) {
		$image_url = obj_key_value( $bg_img, 'url' );
	}

	if ( $show_sample_button ) {
		$sample_page   = get_field( 'view_sample_page', 'option' );
		$sample_button = array();
		$current_id    = get_the_ID();
		$is_restricted = rcp_is_restricted_content( $current_id );

		if ( ! empty( $sample_page ) && $is_restricted ) {
			$sample_button = array(
				'title'  => 'See a Sample',
				'url'    => get_permalink( $sample_page ),
				'target' => '',
			);
		}
	}

	if ( ! empty( $options ) ) { ?>
		<section class="footer-cta bg-img" style="background-image: url(<?php echo $image_url; ?>)">
			<div class="bg-overlay"></div>
			<div class="wrap">
				<div class="footer-cta__inner sectionpt sectionpb tac">
					<?php if ( ! empty( $title ) ) : ?>
						<h2 class="footer-cta__title"><?php echo esc_html( $title ); ?></h2>
					<?php endif; ?>
					<?php if ( ! empty( $blurb ) ) : ?>
						<div class="footer-cta__blurb"><?php echo esc_html( $blurb ); ?></div>
					<?php endif; ?>
					<?php if ( $display_testimonials ) : ?>
						<div class="footer-cta__outer-testimonial-slider">
							<div class="footer-cta__testimonial-slider">
								<?php foreach ( $testimonials as $t ) : ?>
									<?php
										$id    = $t->ID;
										$name  = $t->post_title;
										$quote = get_field( 'quote', $id );
									?>
									<div class="testimonial-slider-block bbr bg-white tal">
										<div class="testimonial-block__bg-icon">
											<?php objectiv_testimonial_quote_svg(); ?>
										</div>
										<div class="testimonial-block__quote f20">
											&ldquo;<?php echo $quote; ?>&rdquo;
										</div>
										<div class="testimonial-block__attribution f18 fw100">
											&mdash; <?php echo $name; ?>
										</div>
									</div>
								<?php endforeach; ?>
							</div>
							<?php objectiv_slider_arrows(); ?>
						</div>
					<?php endif; ?>
					<?php if ( ! empty( $button ) || ! empty( $sample_button ) ) : ?>
						<div class="footer-cta__button-wrap">
							<?php if ( ! empty( $button ) ) : ?>
								<?php echo objectiv_link_button( $button, 'red-button large-button' ); ?>
							<?php endif; ?>
							<?php if ( ! empty( $sample_button ) && $show_sample_button ) : ?>
								<?php echo objectiv_link_button( $sample_button, 'red-button large-button' ); ?>
							<?php endif; ?>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</section>
		<?php
	}
}

function objectiv_slider_arrows() {
	?>
	<div class="left-arrow">
		<svg height="512px" id="Layer_1" style="enable-background:new 0 0 512 512;" version="1.1" viewBox="0 0 512 512" width="512px" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
		<polygon fill="#fff" points="160,115.4 180.7,96 352,256 180.7,416 160,396.7 310.5,256 "/>
		</svg>
	</div>
	<div class="right-arrow">
		<svg height="512px" id="Layer_1" style="enable-background:new 0 0 512 512;" version="1.1" viewBox="0 0 512 512" width="512px" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
		<polygon fill="#fff" points="160,115.4 180.7,96 352,256 180.7,416 160,396.7 310.5,256 "/>
		</svg>
	</div>
	<?php
}

function objectiv_testimonial_quote_svg( $color = '#293D52' ) {
	?>
		<svg width="144px" height="125px" viewBox="0 0 144 125" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
			<g id="Symbols" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd" opacity="0.102725074">
				<g id="Footer-CTA-Section-With-Testimonials" transform="translate(-147.000000, -406.000000)" fill="<?php echo $color; ?>" fill-rule="nonzero">
					<g id="CTA-Content">
						<g transform="translate(68.000000, 95.228029)">
							<g id="Testimonials" transform="translate(0.000000, 282.771971)">
								<g id="Testimonial-Left-Copy-2" transform="translate(52.000000, 0.771971)">
									<g id="quote" transform="translate(27.000000, 27.000000)">
										<path d="M28.078125,0.759375 C12.984375,0.759375 0.740625,13.35 0.740625,28.884375 C0.740625,44.409375 12.984375,57.009375 28.078125,57.009375 C55.40625,57.009375 37.190625,111.375 0.740625,111.375 L0.740625,124.5 C65.79375,124.509375 91.284375,0.759375 28.078125,0.759375 Z M106.828125,0.759375 C91.74375,0.759375 79.5,13.35 79.5,28.884375 C79.5,44.409375 91.74375,57.009375 106.828125,57.009375 C134.165625,57.009375 115.95,111.375 79.5,111.375 L79.5,124.5 C144.54375,124.509375 170.034375,0.759375 106.828125,0.759375 Z" id="Shape"></path>
									</g>
								</g>
							</g>
						</g>
					</g>
				</g>
			</g>
		</svg>
	<?php
}
