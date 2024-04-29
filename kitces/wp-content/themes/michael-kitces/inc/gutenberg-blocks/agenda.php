<?php

add_action( 'acf/init', 'mk_register_agenda_block' );
function mk_register_agenda_block() {

	// check that the register function exists.
	if ( function_exists( 'acf_register_block_type' ) ) {

		acf_register_block_type(
			array(
				'name'            => 'mk-agenda',
				'title'           => __( 'MK - Agenda' ),
				'description'     => __( 'A block that displays a schedule/agenda.' ),
				'render_callback' => 'mk_agenda_block_render_callback',
				'keywords'        => array( 'kitces', 'agenda', 'schedule' ),
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

function mk_agenda_block_render_callback( $block, $content = '', $is_preview = false, $post_id = 0 ) {
	// Block Meta Values
	$block_meta      = mk_block_meta( $block );
	$classes         = mk_key_value( $block_meta, 'classes' );
	$id              = mk_key_value( $block_meta, 'id' );
	$name            = mk_key_value( $block_meta, 'name' );
	$ga_event_action = mk_key_value( $block_meta, 'ga_event_action' );

	// Block Data Values
	$agenda_items = mk_get_gb_field( 'agenda_items' );

	?>
	<?php if ( $agenda_items ) : ?>
		<section class="mk-block <?php echo $classes; ?>" id="<?php echo $id; ?>" data-mk-block-event-action="<?php echo $ga_event_action; ?>">
			<div class="wrap">
				<div class="<?php echo $name; ?>-inner">
					<?php mk_do_block_intro(); ?>
					<?php if ( $agenda_items && is_array( $agenda_items ) ) : ?>
						<div class="agenda-list mt4">
							<?php foreach ( $agenda_items as $ai ) : ?>
								<?php
								$time_slot   = mk_key_value( $ai, 'time_slot' );
								$title       = mk_key_value( $ai, 'title' );
								$description = mk_key_value( $ai, 'description' );
								$speakers    = mk_key_value( $ai, 'speakers' );
								?>
								<div class="agenda-item">
									<div class="time">
										<?php if ( $time_slot ) : ?>
											<div class="fwb"><?php echo $time_slot; ?></div>
										<?php endif; ?>
									</div>
									<div class="agenda-details">
										<?php if ( $title ) : ?>
											<h5 class="uppercase tc-med-blue f20"><?php echo $title; ?></h5>
										<?php endif; ?>
										<?php if ( $description ) : ?>
											<div class="description fmt0 lmb0 f16 small-spaced"><?php echo wp_kses_post( wpautop( $description ) ); ?></div>
										<?php endif; ?>
										<div class="details-lower">
											<div class="speakers-wrap">
												<?php if ( $speakers ) : ?>
													<?php foreach ( $speakers as $speaker ) : ?>
														<?php $speaker_id = $speaker->ID; ?>
														<?php mk_team_block_person_grid_item( $speaker_id, true, false, null, null, $title, false ); ?>
													<?php endforeach; ?>
												<?php endif; ?>
											</div>
											<div class="button-wrap">
												<button class="button small-button light-blue" onclick="showMoreLess(this)">MORE</button>
											</div>
										</div>
									</div>
								</div>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>
					<?php mk_do_block_footer_buttons(); ?>
				</div>
			</div>
		</section>
	<?php endif; ?>
		<?php
}
