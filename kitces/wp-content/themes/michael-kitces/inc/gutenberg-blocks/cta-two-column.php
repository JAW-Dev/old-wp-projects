<?php

add_action( 'acf/init', 'mk_register_two_column_cta_block' );
function mk_register_two_column_cta_block() {

	// check that the register function exists.
	if ( function_exists( 'acf_register_block_type' ) ) {

		acf_register_block_type(
			array(
				'name'            => 'mk-video-two-column-cta',
				'title'           => __( 'MK - Two Column CTA' ),
				'description'     => __( 'A call to action section with two columns.' ),
				'render_callback' => 'mk_two_column_cta_block_render_callback',
				'keywords'        => array( 'kitces', 'cta', 'call to action' ),
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

function mk_two_column_cta_block_render_callback( $block, $content = '', $is_preview = false, $post_id = 0 ) {
	// Block Meta Values
	$block_meta      = mk_block_meta( $block );
	$classes         = mk_key_value( $block_meta, 'classes' );
	$id              = mk_key_value( $block_meta, 'id' );
	$name            = mk_key_value( $block_meta, 'name' );
	$text_color      = mk_get_gb_field( 'text_color' );
	$bg_color        = mk_get_gb_field( 'background_color' );
	$ga_event_action = mk_key_value( $block_meta, 'ga_event_action' );

	// Block Data Values
	$content_max_width = mk_get_gb_field( 'content_max_width' );
	$logo              = mk_get_gb_field( 'logo' );
	$intro_line        = mk_get_gb_field( 'intro_line' );
	$title             = mk_get_gb_field( 'title' );
	$blurb             = mk_get_gb_field( 'blurb' );
	$detail            = mk_get_gb_field( 'detail' );
	$blue_button       = mk_get_gb_field( 'blue_button' );
	$green_button      = mk_get_gb_field( 'green_button' );
	$footer_text       = mk_get_gb_field( 'footer_text' );
	$align_items       = mk_get_gb_field( 'align_items' );

	$blue_button_class = 'light-blue';
	if ( 'tc-white' === $text_color && 'bg-light-blue' === $bg_color ) {
		$blue_button_class = 'medium-blue';
	}

	?>
	<section class="mk-block <?php echo $classes; ?>" id="<?php echo $id; ?>" data-mk-block-event-action="<?php echo $ga_event_action; ?>">
		<div class="wrap">
			<div class="<?php echo $name; ?>-inner content-width-<?php echo $content_max_width; ?> alignment-<?php echo $align_items ?>">
				<div class="content-side">
					<?php if ( $logo ) : ?>
						<div class="logo"><?php echo wp_get_attachment_image( $logo['id'], array( '450' ), false, array( 'class' => 'block' ) ); ?></div>
					<?php endif; ?>
					<?php if ( $intro_line ) : ?>
						<div class="mthalf mbhalf italic"><?php echo wp_kses_post( $intro_line ); ?></div>
					<?php endif; ?>
					<?php if ( $title ) : ?>
						<h2 class="fwb f36 mb0important <?php echo $text_color; ?>"><?php echo wp_kses_post( $title ); ?></h2>
					<?php endif; ?>
					<?php if ( $blurb ) : ?>
						<div class="fmt0 lmb0 small-spaced"><?php echo wp_kses_post( wpautop( $blurb ) ); ?></div>
					<?php endif; ?>
				</div>
				<div class="other-side">
					<?php if ( $detail ) : ?>
						<div class="mt1 mb1 f22"><?php echo wp_kses_post( $detail ); ?></div>
					<?php endif; ?>
					<?php if ( $blue_button || $green_button ) : ?>
						<div class="button-wrap">
							<?php if ( $blue_button ) : ?>
								<?php echo mk_link_html( $blue_button, 'button ' . $blue_button_class ); ?>
							<?php endif; ?>
							<?php if ( $green_button ) : ?>
								<?php echo mk_link_html( $green_button, 'button' ); ?>
							<?php endif; ?>
						</div>
					<?php endif; ?>
					<?php if ( $footer_text ) : ?>
						<div class="fmt0 lmb0 small-spaced mt1"><?php echo wp_kses_post( wpautop( $footer_text ) ); ?></div>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</section>
	<?php
}
