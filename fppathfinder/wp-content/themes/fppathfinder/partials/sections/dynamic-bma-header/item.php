<section id="<?php echo esc_attr( $section_id ); ?>" class="content-section page-flexible-section <?php echo $padding_classes; ?> <?php echo $custom_classes; ?>">
	<div class="wrap">
		<div class="content-section-blocks-wrap">
			<div class="content-section-content">

				<?php if ( ! empty( $section_title ) ) : ?>
					<h1><?php echo do_shortcode( $section_title ); ?></h1>
				<?php endif; ?>

				<?php if ( ! empty( $section_blurb ) ) : ?>
					<?php echo wp_kses_post( $section_blurb ); ?>
				<?php endif; ?>

				<?php if ( ! empty( $button ) ) : ?>
					<p>&nbsp;</p>
					<p class="text-center">
						<span class="red-button">
							<a href="<?php echo esc_url( $button_url ); ?>" target=""><?php echo esc_html( $button_text ); ?></a>
						</span>
						<span class="transparent-button">
							<a href="#learn-more" target="">Learn More</a>
						</span>
					</p>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>
