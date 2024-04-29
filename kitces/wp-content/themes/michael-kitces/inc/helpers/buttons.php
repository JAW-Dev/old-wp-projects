<?php

function mk_link_html( $link_details = null, $class = null ) {

	if ( ! empty( $link_details ) ) {
		$title  = $link_details['title'];
		$url    = $link_details['url'];
		$target = $link_details['target'];

		if ( empty( $title ) ) {
			$title = 'Learn More';
		}

		if ( ! empty( $url ) && ! empty( $title ) ) {
			return '<a class="' . $class . '" target="' . $target . '" href="' . $url . '">' . $title . '</a>';
		} else {
			return '';
		}
	} else {
		return '';
	}

}
