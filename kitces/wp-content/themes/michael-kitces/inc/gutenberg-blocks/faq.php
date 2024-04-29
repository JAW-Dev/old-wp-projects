<?php

add_action( 'acf/init', 'mk_register_faq_block' );
function mk_register_faq_block() {

	// check that the register function exists.
	if ( function_exists( 'acf_register_block_type' ) ) {

		acf_register_block_type(
			array(
				'name'            => 'mk-faq',
				'title'           => __( 'MK - FAQ' ),
				'description'     => __( 'A block that displays a toggleable list of FAQs.' ),
				'render_callback' => 'mk_faq_block_render_callback',
				'keywords'        => array( 'kitces', 'faq', 'question', 'accordion' ),
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

function mk_faq_block_render_callback( $block, $content = '', $is_preview = false, $post_id = 0 ) {
	// Block Meta Values
	$block_meta      = mk_block_meta( $block );
	$classes         = mk_key_value( $block_meta, 'classes' );
	$id              = mk_key_value( $block_meta, 'id' );
	$name            = mk_key_value( $block_meta, 'name' );
	$ga_event_action = mk_key_value( $block_meta, 'ga_event_action' );

	// Block Data Values
	$faq_items = mk_get_gb_field( 'faq_items' );

	?>

	<section class="mk-block <?php echo $classes; ?>" id="<?php echo $id; ?>" data-mk-block-event-action="<?php echo $ga_event_action; ?>">
		<div class="wrap">
			<div class="<?php echo $name; ?>-inner">
				<?php mk_do_block_intro(); ?>
				<?php if ( $faq_items ) : ?>
					<div class="mt4">
						<?php obj_do_faq_accordion_list( $faq_items ); ?>
					</div>
				<?php endif; ?>
				<?php mk_do_block_footer_buttons(); ?>
			</div>
		</div>
	</section>

	<?php
}
