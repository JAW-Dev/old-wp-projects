<?php

function objectiv_do_gravity_form_section( $sec_title = null, $content = null, $form_title = null, $form = null, $section_id = null, $bg_class = 'bg-light-gray' ) {
	if ( is_array( $form ) && array_key_exists( 'id', $form ) ) {
		$form = mk_key_value( $form, 'id' );
	}
	?>
		<section class="page-section spt spb <?php echo $bg_class; ?>" id="<?php echo $section_id; ?>">
			<div class="wrap">
				<?php if ( ! empty( $sec_title ) ) : ?>
					<h2 class="section-title tac"><?php echo $sec_title; ?></h2>
				<?php endif; ?>
				<?php if ( ! empty( $content ) ) : ?>
					<div class="page-section__content norm-list last-child-margin-bottom-0 mw-950 mlra"><?php echo $content; ?></div>
				<?php endif; ?>
				<?php if ( ! empty( $form ) ) : ?>
					<div class="mw-600 mlra mt2 bg-light-blue bbr">
						<?php if ( ! empty( $form_title ) ) : ?>
							<h4 class="tac pt2 text-white f24"><?php echo $form_title; ?></h4>
						<?php endif; ?>
						<?php gravity_form( $form, false, false, false, '', true, 1 ); ?>
					</div>
				<?php endif; ?>
			</div>
		</section>
	<?php
}
