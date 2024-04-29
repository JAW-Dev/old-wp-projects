<?php

add_action( 'acf/init', 'mk_register_icon_blurbs_block' );
function mk_register_icon_blurbs_block() {

	// check that the register function exists.
	if ( function_exists( 'acf_register_block_type' ) ) {

		acf_register_block_type(
			array(
				'name'            => 'mk-icon-blurbs',
				'title'           => __( 'MK - Icon Blurbs' ),
				'description'     => __( 'A block that displays icons + blurbs' ),
				'render_callback' => 'mk_icon_blurbs_block_render_callback',
				'keywords'        => array( 'kitces', 'icons', 'icon', 'grid' ),
				'category'        => 'kitces-custom',
				'mode'            => 'preview',
				'align'           => 'full',
				'post_types'      => array( 'page' ),
				'supports'        => array(
					'align'           => false,
					'anchor'          => true,
					'customClassName' => true,
				),
			),
		);

	}
}

function mk_icon_blurbs_block_render_callback( $block, $content = '', $is_preview = false, $post_id = 0 ) {
	// Block Meta Values
	$block_meta      = mk_block_meta( $block );
	$classes         = mk_key_value( $block_meta, 'classes' );
	$id              = mk_key_value( $block_meta, 'id' );
	$name            = mk_key_value( $block_meta, 'name' );
	$ga_event_action = mk_key_value( $block_meta, 'ga_event_action' );

	// Block Data Values
	$grid_items = mk_get_gb_field( 'grid_items' );
	$layout     = mk_get_gb_field( 'icon_blurb_layout' );
	$icon_bg    = mk_get_gb_field( 'icon_bg' );

	$icon_bg_class = null;
	if ( $icon_bg ) {
		$icon_bg_class = 'icon-has-bg';
	}

	$layout_class = null;
	if ( $layout ) {
		$layout_class = 'layout-' . $layout;
	}

	?>
	<section class="mk-block <?php echo $classes; ?>" id="<?php echo $id; ?>" data-mk-block-event-action="<?php echo $ga_event_action; ?>">
		<div class="wrap">
			<div class="<?php echo $name; ?>-inner">
				<?php mk_do_block_intro(); ?>
				<div class="mt4 icon-blurb-grid">
					<?php foreach ( $grid_items as $item ) : ?>
						<?php
						$title             = mk_key_value( $item, 'title' );
						$blurb             = mk_key_value( $item, 'blurb' );
						$icon_type         = mk_key_value( $item, 'icon_type' );
						$font_awesome_icon = mk_key_value( $item, 'font_awesome_icon' );
						$upload_icon       = mk_key_value( $item, 'upload_icon' );
						?>
						<div class="icon-blurb-grid-item <?php echo $layout_class; ?> <?php echo $icon_bg_class; ?>">
							<?php if ( $upload_icon || $font_awesome_icon ) : ?>
								<div class="icon-wrap">
									<?php if ( 'font-awesome' === $icon_type && $font_awesome_icon ) : ?>
										<?php echo $font_awesome_icon; ?>
									<?php endif; ?>
									<?php if ( 'upload' === $icon_type && $upload_icon ) : ?>
										<div class="icon-image-wrap"><?php echo wp_get_attachment_image( $upload_icon['id'], array( '100' ) ); ?></div>
									<?php endif; ?>
								</div>
							<?php endif; ?>
							<div class="details">
								<?php if ( $title ) : ?>
									<h4 class="mb0 f20 fwb"><?php echo $title; ?></h4>
								<?php endif; ?>
								<?php if ( $blurb ) : ?>
									<div class="fmt0 lmb0 small-spaced f16"><?php echo wp_kses_post( wpautop( $blurb ) ); ?></div>
								<?php endif; ?>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
				<?php mk_do_block_footer_buttons(); ?>
			</div>
		</div>
	</section>
	<?php
}
