<?php

function obj_testimonials_list( $testimonials, $images = false, $style = null, $display_as_slider = false ) {

	if ( $display_as_slider ) {
		echo "<div class='testimonial-list-slider-outer'>";
		echo "<div class='testimonial-list-slider'>";
	}

	foreach ( $testimonials as $testimonial_array ) {
		$testimonial = current( $testimonial_array );
		$id          = $testimonial->ID;
		$name        = $testimonial->post_title;
		$quote       = get_field( 'quote', $id );
		$image_id    = get_post_thumbnail_id( $id );
		$image       = wp_get_attachment_image( $image_id, 'obj_lsquare' );

		?>
		<div class="testimonial-slider-block bbr tal">
			<?php if ( $images ) : ?>
				<div class="headshot-wrap"><?php echo $image; ?></div>
			<?php endif; ?>
			<div class="testimonial-inner">
				<div class="testimonial-block__bg-icon">
					<?php objectiv_testimonial_quote_svg( 'currentColor' ); ?>
				</div>
				<div class="testimonial-block__quote f20">
					&ldquo;<?php echo $quote; ?>&rdquo;
				</div>
				<div class="testimonial-block__attribution f18 fw100">
					&mdash; <?php echo $name; ?>
				</div>
			</div>
		</div>
		<?php
	}

	if ( $display_as_slider ) {
		echo '</div>';
		objectiv_slider_arrows();
		echo '</div>';
	}
}

function obj_simple_testimonial_html( $testimonial = null ) {
	if ( empty( $testimonial ) ) {
		return null;
	}

	$id    = $testimonial->ID;
	$name  = $testimonial->post_title;
	$quote = get_field( 'quote', $id );

	return "<span class='italics'>&ldquo;$quote&rdquo;</span> - <span class='fwb'>$name</span>";

}
