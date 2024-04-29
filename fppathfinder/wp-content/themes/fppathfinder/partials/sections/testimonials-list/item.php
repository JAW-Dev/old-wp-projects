<section class="testimonial-section testimonial-list-section page-flexible-section <?php echo $padding_classes; ?> <?php echo $class_string; ?>">
	<div class="wrap">
		<?php obj_section_header( $title ); ?>

		<?php if ( ! empty( $intro_text ) ) : ?>
			<div class="testimonial-intro">
				<?php echo $intro_text; ?>
			</div>
		<?php endif; ?>

		<?php if ( ! empty( $testimonials ) ) : ?>
			<?php if ( $two_columns ) : ?>
				<div class="testimonials-columns-wrap one2gridlarge">
					<div class="testimonials-wrap-left">
						<?php obj_testimonials_list( $testimonials_left, $images, $style, $display_as_slider ); ?>
					</div>
					<div class="testimonials-wrap-right">
						<?php obj_testimonials_list( $testimonials_right, $images, $style, $display_as_slider ); ?>
					</div>
				</div>
			<?php else : ?>
				<div class="testimonials-wrap">
					<?php obj_testimonials_list( $testimonials, $images, $style, $display_as_slider ); ?>
				</div>
			<?php endif; ?>
		<?php endif; ?>
	</div>
</section>
