<?php if ( ! empty( $rows ) ) : ?>
	<section class="fifty-image-content sectionpb sectionpt <?php echo $bg_class; ?>">
		<div class="wrap">
			<div class="fifty-image-content__inner">
				<?php array_map( 'objectiv_fifty_image_content', $rows ); ?>
			</div>
		</div>
	</section>
<?php endif; ?>
