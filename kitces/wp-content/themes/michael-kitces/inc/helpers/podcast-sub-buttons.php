<?php

function objectiv_do_podcast_image_buttons( $buttons = null ) {
	if ( is_array( $buttons ) ) {
		echo "<div class='podcast-image-links-wrap'>";
		foreach ( $buttons as $button ) {
			objectiv_do_podcast_image_button( $button );
		}
		echo '</div>';
	}
}

function objectiv_do_podcast_image_button( $button ) {
	$link  = mk_key_value( $button, 'link' );
	$image = mk_key_value( $button, 'image' );

	if ( is_array( $link ) && is_array( $image ) ) {
		$url     = $link['url'];
		$target  = $link['target'];
		$title   = $link['title'];
		$img_url = $image['url'];
		$img_alt = $image['alt'];

		echo "<a href='$url' target='$target' title='$title' class=''>";
		echo "<img class='mh-48' src='$img_url' alt='$img_alt'>";
		echo '</a>';
	}
}

function objectiv_fa_get_sub_buttons_html( $classes = null ) {
	$sub_buttons = mk_get_field( 'podcast_subscribe_links', 'options', true, true );

	if ( empty( $classes ) ) {
		$classes = 'mt1 mb1';
	}

	ob_start();
	echo "<div class='$classes'>";
	objectiv_do_podcast_image_buttons( $sub_buttons );
	echo '</div>';
	return ob_get_clean();
}

add_shortcode( 'fa-sub-buttons', 'objectiv_fa_get_sub_buttons_html' );
