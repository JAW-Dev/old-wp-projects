<?php

function page_has_shortcode( $shortcodes = array() ) {
	if ( ( is_page() || is_single() ) && ! empty( $shortcodes ) ) {
		global $post;

		$content       = get_the_content( null, false, $post->ID ) ? get_the_content( null, false, $post->ID ) : '';
		$has_shortcode = false;

		if ( $content ) {
			if ( is_array( $shortcodes ) ) {
				foreach ( $shortcodes as $shortcode ) {
					if ( has_shortcode( $content, $shortcode ) ) {
						$has_shortcode = true;
					}
				}
			} else {
				if ( has_shortcode( $content, $shortcodes ) ) {
					$has_shortcode = true;
				}
			}
		}
	}

	return $has_shortcode;
}