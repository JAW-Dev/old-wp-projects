<section class="paragraph-icon-cta-section page-flexible-section <?php echo $padding_classes; ?>">
	<div class="wrap">
		<?php obj_section_header( $title ); ?>

		<div class="paragraph-icon-cta-section-wrap paragraph-icon-cta">

			<?php
			foreach ( $items as $item ) {
				$image = trailingslashit( get_stylesheet_directory_uri() ) . trailingslashit( $path ) . $item['pic_icon'];
				?>
				<div class="paragraph-icon-cta__item">
					<img src="<?php echo esc_url( $image ); ?>" class="paragraph-icon-cta__image" />
					<div class="paragraph-icon-cta__body"><?php echo wp_kses_post( $item['pic_blurb'] ); ?></div>
				</div>
				<?php
			}
			?>

		</div>
	</div>
</section>
