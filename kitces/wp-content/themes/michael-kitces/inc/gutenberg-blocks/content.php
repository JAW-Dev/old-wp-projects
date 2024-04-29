<?php

add_action( 'acf/init', 'mk_register_content_block' );
function mk_register_content_block() {

	// check that the register function exists.
	if ( function_exists( 'acf_register_block_type' ) ) {

		acf_register_block_type(
			array(
				'name'            => 'mk-content',
				'title'           => __( 'MK - Content' ),
				'description'     => __( 'A block for WYSIWYG content' ),
				'render_callback' => 'mk_content_block_render_callback',
				'keywords'        => array( 'kitces', 'content', 'wysiwyg', 'column' ),
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

function mk_content_block_render_callback( $block, $content = '', $is_preview = false, $post_id = 0 ) {
	// Block Meta Values
	$block_meta      = mk_block_meta( $block );
	$classes         = mk_key_value( $block_meta, 'classes' );
	$id              = mk_key_value( $block_meta, 'id' );
	$name            = mk_key_value( $block_meta, 'name' );
	$text_color      = mk_get_gb_field( 'text_color' );
	$ga_event_action = mk_key_value( $block_meta, 'ga_event_action' );

	// Block Data Values
	$content_layout = mk_get_gb_field( 'content_layout' );
	$content_first  = mk_get_gb_field( 'content_first' );
	$content_second = mk_get_gb_field( 'content_second' );
	$content_third  = mk_get_gb_field( 'content_third' );

	$one   = 'one' === $content_layout && ! empty( $content_first );
	$two   = 'two' === $content_layout && ! empty( $content_first ) && ! empty( $content_second );
	$three = 'three' === $content_layout && ! empty( $content_first ) && ! empty( $content_second ) && ! empty( $content_third );

	?>

	<section class="mk-block <?php echo $classes; ?>" id="<?php echo $id; ?>" data-mk-block-event-action="<?php echo $ga_event_action; ?>">
		<div class="wrap">
			<div class="<?php echo $name; ?>-inner">
				<?php mk_do_block_intro(); ?>
				<div class="mt4 content-<?php echo $content_layout; ?>">
					<?php if ( $one || $two || $three ) : ?>
						<div class="fmt0 lmb0"><?php echo wp_kses_post( wpautop( $content_first ) ); ?></div>
					<?php endif; ?>
					<?php if ( $two || $three ) : ?>
						<div class="fmt0 lmb0"><?php echo wp_kses_post( wpautop( $content_second ) ); ?></div>
					<?php endif; ?>
					<?php if ( $three ) : ?>
						<div class="fmt0 lmb0"><?php echo wp_kses_post( wpautop( $content_third ) ); ?></div>
					<?php endif; ?>
				</div>
				<?php mk_do_block_footer_buttons(); ?>
			</div>
		</div>
	</section>

	<?php
}
