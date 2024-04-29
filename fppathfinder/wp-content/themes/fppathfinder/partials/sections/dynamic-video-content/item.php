<section class="dynamic-welcome sectionpb sectionpt <?php echo $bg_color_class; ?>">
	<div class="wrap">

		<?php if ( ! $hide_section_top ) : ?>
			<?php obj_section_header( $section_title, 'max-width-760 mx-auto tac', $section_blurb ); ?>
		<?php endif; ?>

		<?php if ( ! empty( $display_vc_blocks ) ) : ?>
			<div class="<?php echo $mt_class ?>">
				<?php foreach ( $display_vc_blocks as $block ) : ?>
					<?php obj_do_video_content_block( $block, $light_bg ); ?>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>

	</div>
</section>
