<?php

function fp_upsell_modals() {
	// interactive feature modal
	// white label feature modal
	// one click download feature modal
	// upsell_modals
	$modal_keys = array( 'interactive', 'white_label', 'one_click' );

	if ( is_array( $modal_keys ) && ! empty( $modal_keys ) ) {
		foreach ( $modal_keys as $mk ) {
			$title  = get_field( $mk . '_title', 'upsell_modals' );
			$blurb  = get_field( $mk . '_blurb', 'upsell_modals' );
			$image  = get_field( $mk . '_image', 'upsell_modals' );
			$button = get_field( $mk . '_button', 'upsell_modals' );

			if ( ! empty( $title ) && ! empty( $button ) ) {
				?>
					<div class="upsell_modal <?php echo $mk; ?> hidden" id="upsell_modal_<?php echo $mk; ?>">
						<div class="upsell_modal_inner">
							<h3 class=""><?php echo $title; ?></h3>
							<?php if ( ! empty( $blurb ) ) : ?>
								<div class=""><?php echo $blurb; ?></div>
							<?php endif; ?>
							<?php if ( ! empty( $image ) ) : ?>
								<div class="image-wrap">
									<?php echo wp_get_attachment_image( $image['id'], 'large' ); ?>
								</div>
							<?php endif; ?>
							<div class="buttons-wrap">
								<?php echo fp_get_link_button( $button['url'], $button['target'], $button['title'] ); ?>
							</div>
						</div>
					</div>
				<?php
			}
		}
	}
}

add_action( 'genesis_after_footer', 'fp_upsell_modals' );

function fp_get_upsell_modal_link( $key = null ) {
	if ( empty( $key ) ) {
		return false;
	} else {
		return "#upsell_modal_$key";
	}
}
