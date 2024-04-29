<?php

// Single page for list items

add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_full_width_content' );

remove_action( 'genesis_loop', 'genesis_do_loop' );
add_action( 'genesis_after_header', 'mkl_single_item_content' );

add_filter( 'body_class', 'obj_body_class' );
function obj_body_class( $classes ) {
	$classes[] = 'list-single';
	return $classes;
}

function mkl_single_item_content() {
	$post_type      = get_post_type();
	$list_id        = mkl_list_post_id( $post_type );
	$list_details   = mkl_get_list_details( $list_id );
	$block_settings = mk_key_value( $list_details, 'single_block_details' );
	$name_plural    = mk_key_value( $list_details, 'name_plural' );
	$archive_link   = get_post_type_archive_link( $post_type );

	?>
	<section class="page-section spt spb conference-list-section">
		<div class="wrap mw-1024">
			<div class="mk-list-block">
				<?php mkl_list_block_inner( get_the_ID(), $block_settings, true ); ?>
			</div>
			<?php if ( ! empty( $archive_link ) ) : ?>
				<a href="<?php echo $archive_link; ?>" class="mt2 inline-block">View All <?php echo $name_plural; ?></a>
			<?php endif; ?>
		</div>
	</section>
	<?php
}

genesis();
