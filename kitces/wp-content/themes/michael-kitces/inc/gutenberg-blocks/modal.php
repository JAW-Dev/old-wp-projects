<?php

add_action( 'acf/init', 'mk_register_block' );
function mk_register_block() {

	// check that the register function exists.
	if ( function_exists( 'acf_register_block_type' ) ) {

		acf_register_block_type(
			array(
				'name'            => 'mk-modal',
				'title'           => __( 'MK - Modal' ),
				'description'     => __( 'A block that displays testimonials.' ),
				'render_callback' => 'mk_modal_block_render_callback',
				'keywords'        => array( 'kitces', 'modal' ),
				'category'        => 'kitces-custom',
				'mode'            => 'preview',
				'align'           => 'full',
				'post_types'      => array( 'page' ),
				'supports'        => array(
					'align'           => false,
					'anchor'          => true,
					'customClassName' => false,
				),
			),
		);

	}
}

function mk_modal_block_render_callback( $block, $content = '', $is_preview = false, $post_id = 0 ) {
	// Block Meta Values
	$block_meta      = mk_block_meta( $block );
	$id              = mk_key_value( $block_meta, 'id' );
	$name            = mk_key_value( $block_meta, 'name' );
	$ga_event_action = mk_key_value( $block_meta, 'ga_event_action' );

	// Block Data Values
	$footer_buttons = mk_get_gb_field( 'footer_buttons' );
	$footer_text    = mk_get_gb_field( 'footer_text' );

	$preview_class = 'hidden';
	if ( $is_preview ) {
		$preview_class = null;
	}

	?>
	<section class="mk-block <?php echo $name; ?>-outer <?php echo $preview_class; ?>" id="<?php echo $id; ?>">
		<div class="<?php echo $name; ?>-inner">
			<div class="wrap">
				<?php if ( $is_preview ) : ?>
					<div class="mw-600 mx-auto text-center mb3">
						<h2>Modal Block</h2>
						<p>
							This entire block will not show on the front end of the site (nor will this text 😜). The contents of this block will be used in a modal that can be triggered with a button or link using the #<?php echo $id; ?> as the url. That ID can be changed in the <b>Advanced</b> settings for this block.
						</p>
						<p>
							The modal details will show up similar to below.
						</p>
					</div>
				<?php endif; ?>
				<div class="<?php echo $name; ?>-inner-inner" data-mk-block-event-action="<?php echo $ga_event_action; ?>">
					<?php mk_do_block_intro(); ?>
					<div class="mt1">
						<?php if ( ! empty( $footer_buttons ) ) : ?>
							<div class="button-wrap">
								<?php foreach ( $footer_buttons as $button ) : ?>
									<?php
									$b                = mk_key_value( $button, 'button' );
									$text             = mk_key_value( $button, 'text_above_button' );
									$text_color_class = mk_key_value( $button, 'text_color' );
									$button_color     = mk_key_value( $button, 'button_color' );
									$close_modal      = mk_key_value( $button, 'close_modal' );

									$button_class = 'button';
									if ( 'button' !== $button_color ) {
										$button_class = 'button ' . $button_color;
									}

									if ( 'default' === $text_color_class ) {
										$text_color_class = '';
									}

									if ( $close_modal ) {
										$button_class .= ' close-the-modal';

										if ( empty( $b ) ) {
											$button_class .= ' not-a-link';
											$b             = array(
												'title'  => 'Close',
												'url'    => '#close-the-modal',
												'target' => '',
											);
										}
									}

									?>
									<div class="tac">
										<?php if ( $text ) : ?>
											<div class="mbhalf f20 fwb <?php echo $text_color_class; ?>"><?php echo $text; ?></div>
										<?php endif; ?>
										<?php if ( $b ) : ?>
											<?php echo mk_link_html( $b, $button_class ); ?>
										<?php endif; ?>
									</div>
								<?php endforeach; ?>
							</div>
						<?php endif; ?>
						<?php if ( $footer_text ) : ?>
							<div class="mt1 fmt0 lmb0 tac mt3"><?php echo $footer_text; ?></div>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</div>
	</section>
	<?php
}
