<section class="price-block-cta sectionpb sectionpt <?php echo $bg_class; ?>">
	<div class="wrap">
		<div class="price-block-cta__inner pos-rel">
			<?php obj_section_header( $title ); ?>
			<?php if ( ! empty( $price_text ) ) : ?>
				<h3 class="price-block-cta__price-text"><?php echo esc_html( $price_text ); ?></h3>
			<?php endif; ?>
			<?php if ( ! empty( $sub_title ) ) : ?>
				<h6 class="price-block-cta__sub-title"><?php echo esc_html( $sub_title ); ?></h6>
			<?php endif; ?>
			<?php if ( ! empty( $content ) ) : ?>
				<div class="price-block-cta__content">
					<div class="price-block-cta__content-inner">
						<?php echo $content; ?>
					</div>
				</div>
			<?php endif; ?>
			<?php if ( ! empty( $button ) ) : ?>
				<div class="price-block-cta__button-wrap">
					<?php echo objectiv_link_button( $button, 'button red-button large-button' ); ?>
				</div>
			<?php endif; ?>
			<?php if ( ! empty( $footer_content ) ) : ?>
				<div class="price-block-cta__footer lmb0">
					<?php echo $footer_content; ?>
				</div>
			<?php endif; ?>
		</div>
	</div>
</section>
