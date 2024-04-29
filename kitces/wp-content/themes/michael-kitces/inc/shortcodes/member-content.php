<?php

function member_content_image_block( $atts ) {

	$page_id = mk_key_value( $atts, 'page_id' );
	if ( empty( $page_id ) ) {
		$page_id = get_the_ID();
	}

	$images = get_field( 'second_content_first_set_of_images', $page_id );

	if ( ! empty( $images ) ) {
		ob_start(); ?>
		<div class="second-content-img-box">
		<?php
		if ( ! empty( $images ) && is_array( $images ) ) {
			foreach ( $images as $i ) {
				$id    = $i['image'];
				$image = wp_get_attachment_image( $id, 'medium_large' );
				if ( ! empty( $image ) ) {
					echo( '<div>' );
					echo( $image );
					echo( '</div>' );
				}
			}
		}
		?>
		</div>
		<?php
		return ob_get_clean();
	} else {
		return '';
	}
}

add_shortcode( 'mem-cont-img-set', 'member_content_image_block' );
