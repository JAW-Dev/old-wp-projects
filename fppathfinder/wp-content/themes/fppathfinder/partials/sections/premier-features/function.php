<?php

return (object) array(
	'acf_name' => 'premier_features',
	'options'  => (object) array(
		'func'        => function ( $padding_classes = '' ) {
			$p_loc = FlexibleContentSectionUtility::getSectionsDirectory();
			$fcta_loc = "$p_loc/premier-features";
			$item = "$fcta_loc/item.php";

			$title          = get_sub_field( 'section_title' );
			$icons          = get_sub_field( 'icons' );
			$section_blurb  = get_sub_field( 'section_blurb' );
			$section_button = get_sub_field( 'section_button' );
			$background     = get_sub_field( 'background' );
			$bg_class       = 'bg-' . $background;

			?>
			<section class="white-label-cta premier-features-section sectionpb sectionpt <?php echo $bg_class; ?> pos-rel">
				<div class="wrap">
					<div class="white-label-cta__inner pos-rel">
						<?php obj_section_header( $title, 'max-width-500 tac mlra' ); ?>
						<?php if ( ! empty( $icons ) ) : ?>
							<div class="white-label-cta__icons-wrap">
								<?php foreach ( $icons as $icon ) : ?>
									<?php obj_do_regular_image_icon_item( $icon ); ?>
								<?php endforeach; ?>
							</div>
						<?php endif; ?>
						<?php if ( ! empty( $section_blurb ) ) : ?>
							<div class="white-label-cta__section-blurb tac mlra max-width-760 f22"><?php echo $section_blurb; ?></div>
						<?php endif; ?>
						<?php if ( ! empty( $section_button ) ) : ?>
							<div class="price-block-cta__button-wrap">
								<?php echo objectiv_link_button( $section_button, 'button blue-button' ); ?>
							</div>
						<?php endif; ?>
					</div>
				</div>
			</section>
			<?php
		},
		'has_padding' => false,
	),
);
