<?php

function obj_testimonial_block( $image = null, $blurb = null, $attribution = null, $attribution_comp = null, $full = false ) {
	if ( ! empty( $blurb ) ) {
		$width_class = 'fifty-width';
		if ( $full ) {
			$width_class = 'full-width';
		}
		?>
		<div class="testimonial-block mt3 <?php echo $width_class; ?>">
			<?php if ( ! empty( $image ) ) : ?>
				<div class="testimonial-block-image-wrap">
					<img src="<?php echo $image; ?>" alt="<?php echo $attribution; ?>" class="br50">
				</div>
			<?php endif; ?>
			<div class="testimonial-block-content">
				<div class="testimonial-block-blurb">
					<?php echo $blurb; ?>
				</div>
				<?php if ( ! empty( $attribution ) ) : ?>
					<div class="testimonial-block-attribution fwb"><?php echo $attribution; ?></div>
					<?php if ( ! empty( $attribution_comp ) ) : ?>
						<div class="testimonial-block-attribution-company"><?php echo $attribution_comp; ?></div>
					<?php endif; ?>
				<?php endif; ?>
			</div>
		</div>
		<?php
	}
}

function objectiv_consult_testimonial_footer( $details = null, $hide_image = false ) {
	$tf_photo     = $details['photo'];
	$tf_attr      = $details['testimonial_attribution'];
	$tf_firm_t    = $details['testimonial_attribution_title_firm'];
	$tf_firm_logo = $details['firm_logo'];
	$tf_firm_link = $details['firm_link'];

	if ( $hide_image ) {
		$tf_photo = null;
	}

	$tf_attr_text = $tf_attr;
	if ( ! empty( $tf_firm_t ) ) {
		$tf_attr_text = $tf_attr . ',&#160<span class="consult-attr-firm">' . $tf_firm_t . '</span>';
	}

	$tf_head_img_class  = null;
	$tf_firm_logo_class = null;

	if ( empty( $tf_photo ) ) {
		$tf_head_img_class = 'no-image';
	}

	if ( empty( $tf_firm_logo ) ) {
		$tf_firm_logo_class = 'no-firm-logo';
	}

	$tf_class_list = $tf_head_img_class . ' ' . $tf_firm_logo_class;

	?>
	<?php if ( ! empty( $tf_attr ) ) : ?>
		<footer class="consult-testimonial-footer <?php echo $tf_class_list; ?>">
			<?php if ( ! empty( $tf_photo ) ) : ?>
				<div class="consult-attr-head">
					<img src="<?php echo $tf_photo['sizes']['thumbnail']; ?>" alt="<?php echo $tf_attr; ?>" class="br50">
				</div>
			<?php endif; ?>
			<div class="consult-attr-footer-right">
				<?php if ( ! empty( $tf_attr_text ) ) : ?>
					<div class="consult-attr-name">
						<?php echo $tf_attr_text; ?>
					</div>
				<?php endif; ?>
				<?php if ( ! empty( $tf_firm_link ) && ! empty( $tf_firm_logo ) ) : ?>
					<a class="consult-attr-firm-logo-wrap" href="<?php echo $tf_firm_link; ?>">
						<img src="<?php echo $tf_firm_logo['sizes']['medium']; ?>" alt="<?php echo $tf_firm_t; ?>">
					</a>
				<?php elseif ( ! empty( $tf_firm_logo ) ) : ?>
					<div class="consult-attr-firm-logo-wrap">
						<img src="<?php echo $tf_firm_logo['sizes']['medium']; ?>" alt="<?php echo $tf_firm_t; ?>">						
					</div>
				<?php endif; ?>
			</div>
		</footer>
	<?php endif; ?>
	<?php
}

function objectiv_do_testimonial_slider( $testimonials = null, $slider_wrap_class = null, $slider_class = null, $type = null ) {

	$hide_footer_image = false;

	if ( empty( $slider_wrap_class ) ) {
		$slider_wrap_class = 'kind-words-slider-wrap';
	}

	if ( empty( $slider_class ) ) {
		$slider_class = 'kind-words-slider';
	}

	$img_l = false;
	if ( ! empty( $type ) && 'img-left-test-right' === $type ) {
		$img_l = true;
		$hide_footer_image = true;
	}

	?>
	<div class="<?php echo $slider_wrap_class; ?>">
		<div class="<?php echo $slider_class; ?>">
			<?php
			foreach ( $testimonials as $t ) :
				$t_content = $t['testimonial_content'];
				$t_image_array = mk_key_value( $t, 'photo' );
				$t_image = mk_key_value( $t_image_array, 'sizes' );
				$t_image_url = mk_key_value( $t_image, 'small-square' );

				?>
				<?php if ( ! empty( $t_content ) ) : ?>
				<div class="kind-words-slide tac <?php echo $type ?>">
					<div class="slide-innards">
						<?php if( $img_l && $t_image_url ) : ?>
							<div class="left-image-wrap">
								<img src="<?php echo $t_image_url ?>" alt="<?php echo $t_image_array['alt'] ?>">
							</div>
						<?php endif; ?>
						<?php if( $img_l ) : ?>
							<div class="right-side">
						<?php endif; ?>
						<div class="kind-words-slide-content">
							<?php echo $t_content; ?>
						</div>
						<div class="kind-words-slide-footer-wrap">
							<?php objectiv_consult_testimonial_footer( $t, $hide_footer_image ); ?>		
						</div>
						<?php if( $img_l ) : ?>
							</div>
						<?php endif; ?>
					</div>
				</div>
			<?php endif; ?>
			<?php endforeach; ?>
		</div>
		<?php if ( count( $testimonials ) > 1 ) : ?>
			<div class="left-arrow">
				<?php slide_arrow(); ?>
			</div>
			<div class="right-arrow">
				<?php slide_arrow(); ?>
			</div>
		<?php endif; ?>
	</div>
	<?php
}
