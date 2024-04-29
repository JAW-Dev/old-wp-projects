<?php

function mk_filter_block_categories_when_post_provided( $block_categories, $editor_context ) {
	if ( ! empty( $editor_context->post ) ) {
		array_push(
			$block_categories,
			array(
				'slug'  => 'kitces-custom',
				'title' => __( 'Kitces Custom', 'kitces-custom' ),
				'icon'  => 'dashicons-hammer',
			)
		);
	}
	return $block_categories;
}

if ( class_exists( 'WP_Block_Editor_Context' ) ) {
	add_filter( 'block_categories_all', 'mk_filter_block_categories_when_post_provided', 10, 2 );
} else {
	add_filter( 'block_categories', 'mk_filter_block_categories_when_post_provided', 10, 2 );
}

// Setting up a get_field wrapper for get_field
// function to be used only in gutenberg blocks
// If we don't use the acf function we won't have
// any preview as data is filled in for each block
function mk_get_gb_field( $selector = null ) {
	$result = null;

	$result = get_field( $selector );

	if ( empty( $result ) ) {
		$result = false;
	}

	return $result;
}

// Here is where functions for determining block styles classes
function mk_block_meta( $block = null ) {

	$block_name = mk_block_name( $block );
	$block_id   = mk_block_id( $block );

	return array(
		'name'            => $block_name,
		'id'              => $block_id,
		'classes'         => mk_block_classes( $block, $block_name ),
		'ga_event_action' => mk_block_ga_event_action( $block, $block_id ),
	);

}

function mk_block_name( $block ) {
	$name = mk_key_value( $block, 'name' );

	if ( $name ) {
		return str_replace( 'acf/', '', $name );
	}

	return null;
}

function mk_block_ga_event_action( $block, $block_id ) {

	$ga_event_section_name = mk_get_gb_field( 'ga_event_section_name' );

	if ( $ga_event_section_name ) {
		return $ga_event_section_name;
	}

	$title = mk_key_value( $block, 'title' );
	if ( $title ) {
		return str_replace( 'MK - ', '', $title ) . ' ' . $block_id;
	}

	return null;
}

function mk_block_id( $block ) {
	$id     = mk_key_value( $block, 'id' );
	$anchor = mk_key_value( $block, 'anchor' );

	if ( $anchor ) {
		return $anchor;
	} elseif ( $id ) {
		return $id;
	}

	return null;

}

function mk_block_classes( $block, $block_name ) {
	$classes = $block_name;

	$custom_classes = mk_key_value( $block, 'className' );
	if ( $custom_classes ) {
		$cc_array = explode( ' ', $custom_classes );
		if ( ! empty( $cc_array ) && is_array( $cc_array ) ) {
			foreach ( $cc_array as $custom_class ) {
				if ( ! mk_str_starts_with( $custom_class, 'wp-block-acf' ) ) {
					$classes .= ' ' . $custom_class;
				}
			}
		}
	}

	$background_color = mk_get_gb_field( 'background_color' );
	$text_color       = mk_get_gb_field( 'text_color' );
	$margin           = mk_get_gb_field( 'margin' );
	$padding          = mk_get_gb_field( 'padding' );

	if ( $background_color ) {
		$classes .= ' ' . $background_color;
	}

	if ( $text_color ) {
		$classes .= ' ' . $text_color;
	}

	if ( $margin ) {
		$classes .= ' ' . str_replace( '_', ' ', $margin );
	}

	if ( $padding ) {
		$classes .= ' ' . str_replace( '_', ' ', $padding );
	}

	return $classes;
}

function mk_do_block_intro() {
	$section_title = mk_get_gb_field( 'section_title' );
	$section_blurb = mk_get_gb_field( 'section_blurb' );

	?>
	<?php if ( ! empty( $section_title ) || ! empty( $section_blurb ) ) : ?>
		<div class="mw-800 tac mlra">
			<?php if ( ! empty( $section_title ) ) : ?>
				<h2 class="border0 f36 fwb"><?php echo $section_title; ?></h2>
			<?php endif; ?>
			<?php if ( ! empty( $section_blurb ) ) : ?>
				<div class="fmt0 lmb0 f20">
					<?php echo wp_kses_post( wpautop( $section_blurb ) ); ?>
				</div>
			<?php endif; ?>
		</div>
	<?php endif; ?>
	<?php
}

function mk_do_block_footer_buttons( $centered = true, $classes = 'mt3' ) {
	$buttons        = mk_get_gb_field( 'footer_buttons' );
	$centered_class = 'centered';
	if ( ! $centered ) {
		$centered_class = null;
	}

	?>
	<?php if ( ! empty( $buttons ) ) : ?>
		<div class="block-footer-buttons-wrap <?php echo $classes; ?> <?php echo $centered_class; ?>">
			<?php foreach ( $buttons as $b ) : ?>
				<?php
					$class = 'button';
					$color = mk_key_value( $b, 'button_color' );
				if ( 'button' !== $color ) {
					$class = 'button ' . $color;
				}
				?>
				<?php echo mk_link_html( mk_key_value( $b, 'button_link' ), $class ); ?>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>
	<?php
}

function mk_str_starts_with( $string, $startString ) {
	$len = strlen( $startString );
	return ( substr( $string, 0, $len ) === $startString );
}
