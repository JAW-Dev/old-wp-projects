<?php

function hero_head( $hd = null ) {

	if ( is_array( $hd ) && array_key_exists( 'hero_title', $hd ) && ! empty( $hd['hero_title'] ) ) {
		$title         = $hd['hero_title'];
		$sub_title     = $hd['hero_sub_title'];
		$img           = $hd['hero_bg_image'];
		$img_url       = null;
		$overlay_class = '';
		$img_class     = null;
		$pad_classes   = 'spt spb';

		if ( is_array( $img ) && array_key_exists( 'sizes', $img ) && ! empty( $img['sizes']['large'] ) ) {
			$img_url       = $img['sizes']['large'];
			$overlay_class = 'bg-o-50';
			$img_class     = 'has-image';
			$pad_classes   = 'spt-xl spb-xl';

		}

		?>
		<section 
			class="page-section hero-head bg-light-blue bg-cover <?php echo $pad_classes; ?> <?php echo $overlay_class; ?> <?php echo $img_class; ?>" 
			style="background-image: url('<?php echo $img_url; ?>');"
		>
			<div class="wrap prel">
				<?php if ( ! empty( $title ) ) : ?>
					<h1 class="hero-head-title wt fwb f42 tac"><?php echo $title; ?></h1>
				<?php endif; ?>
				<?php if ( ! empty( $sub_title ) ) : ?>
					<p class="hero-head-sub-title wt fwm f26 tac mb0"><?php echo $sub_title; ?></p>
				<?php endif; ?>
			</div>
		</section>
		<?php
	}
}
