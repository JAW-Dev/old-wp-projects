<?php

add_action( 'acf/init', 'mk_register_team_block' );
function mk_register_team_block() {

	// check that the register function exists.
	if ( function_exists( 'acf_register_block_type' ) ) {

		acf_register_block_type(
			array(
				'name'            => 'mk-team',
				'title'           => __( 'MK - Team' ),
				'description'     => __( 'A block that displays a grid of people.' ),
				'render_callback' => 'mk_team_block_render_callback',
				'keywords'        => array( 'kitces', 'team', 'people', 'grid' ),
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

function mk_team_block_render_callback( $block, $content = '', $is_preview = false, $post_id = 0 ) {

	// Block Meta Values
	$block_meta      = mk_block_meta( $block );
	$classes         = mk_key_value( $block_meta, 'classes' );
	$id              = mk_key_value( $block_meta, 'id' );
	$name            = mk_key_value( $block_meta, 'name' );
	$ga_event_action = mk_key_value( $block_meta, 'ga_event_action' );

	// Block Data Values
	$grid_items        = mk_get_gb_field( 'people' );
	$grid_size         = mk_get_gb_field( 'grid_size' );
	$individual_layout = mk_get_gb_field( 'individual_layout' );
	$round_headshot    = mk_get_gb_field( 'round_headshot' );
	$full_bio_pop_up   = mk_get_gb_field( 'full_bio_pop_up' );
	$display_short_bio = mk_get_gb_field( 'display_short_bio' );

	$grid_size_class = null;
	if ( $grid_size ) {
		$grid_size_class = 'grid-' . $grid_size;
	}

	$individual_layout_class = 'layout-vertical';
	if ( $individual_layout && 'two-columns' === $grid_size ) {
		$individual_layout_class = 'layout-' . $individual_layout;
	}

	$headshot_class = null;
	if ( $round_headshot ) {
		$headshot_class = 'rounded-headshot';
	}

	?>
	<section class="mk-block <?php echo $classes; ?>" id="<?php echo $id; ?>" data-mk-block-event-action="<?php echo $ga_event_action; ?>">
		<div class="wrap">
			<div class="<?php echo $name; ?>-inner">
				<?php mk_do_block_intro(); ?>
				<?php if ( $grid_items ) : ?>
					<div class="mt4 people-grid <?php echo $grid_size_class; ?>">
						<?php foreach ( $grid_items as $item ) : ?>
							<?php mk_team_block_person_grid_item( $item->ID, $full_bio_pop_up, $display_short_bio, $individual_layout_class, $headshot_class ); ?>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
				<?php mk_do_block_footer_buttons(); ?>
			</div>
		</div>
	</section>
	<?php
}

function mk_team_block_person_grid_item( $person_id = null, $do_bio_pop = false, $do_short_bio = false, $wrap_class = null, $image_class = null, $id_prefix = null, $do_creds = true ) {
	$item_data   = mk_get_person_details( $person_id );
	$person_id   = mk_key_value( $item_data, 'person_id' );
	$name        = mk_key_value( $item_data, 'name' );
	$headshot_id = mk_key_value( $item_data, 'headshot_id' );
	$short_bio   = mk_key_value( $item_data, 'short_bio' );
	$full_bio    = mk_key_value( $item_data, 'full_bio' );
	$modal_id    = obj_id_from_string( $id_prefix . ' ' . $name, false ) . '-' . $person_id;
	?>
	<div class="people-grid-item <?php echo $wrap_class; ?>">
		<?php if ( $headshot_id ) : ?>
			<div class="image-wrap <?php echo $image_class; ?>"><?php echo wp_get_attachment_image( $headshot_id, array( '300', '300' ) ); ?></div>
		<?php endif; ?>
		<div class="details">
			<?php mk_team_block_person_reused_details( $item_data, $do_creds ); ?>
			<?php if ( $short_bio && $do_short_bio ) : ?>
				<div class="small-spaced fmt0 lmb0 mtquarter f16"><?php echo wp_kses_post( wpautop( $short_bio ) ); ?></div>
			<?php endif; ?>
			<?php if ( $do_bio_pop ) : ?>
				<a href="#<?php echo $modal_id; ?>" class="block inline-modal no-slide f16">Bio</a>
				<div class="hidden" id="<?php echo $modal_id; ?>">
					<div class="people-grid-item-modal-content">
						<div class="image-wrap <?php echo $image_class; ?>"><?php echo wp_get_attachment_image( $headshot_id, array( '300', '300' ) ); ?></div>
						<div class="details mt1">
							<?php mk_team_block_person_reused_details( $item_data, $do_creds ); ?>
							<?php if ( $full_bio ) : ?>
								<div class="small-spaced fmt0 lmb0 mt1"><?php echo wp_kses_post( wpautop( $full_bio ) ); ?></div>
							<?php endif; ?>
						</div>
					</div>
				</div>
			<?php endif; ?>
		</div>
	</div>
	<?php
}

function mk_team_block_person_reused_details( $item = array(), $do_creds = true ) {
	$name        = mk_key_value( $item, 'name' );
	$credentials = mk_key_value( $item, 'credentials' );
	$job_title   = mk_key_value( $item, 'job_title' );
	$company     = mk_key_value( $item, 'company' );
	?>
	<?php if ( $name ) : ?>
		<h3 class="mb0 f16"><?php echo $name; ?></h3>
	<?php endif; ?>
	<?php if ( $credentials && $do_creds ) : ?>
		<div class="f16"><?php echo $credentials; ?></div>
	<?php endif; ?>
	<?php if ( $job_title ) : ?>
		<div class="italic f16"><?php echo $job_title; ?></div>
	<?php endif; ?>
	<?php if ( $company ) : ?>
		<div class="f16"><?php echo $company; ?></div>
	<?php endif; ?>
	<?php
}
