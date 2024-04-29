<?php

function mk_learn_content_nav_ids( $content = null ) {
	if ( empty( $content ) ) {
		return null;
	}

	$dom = new DOMDocument();
	@ $dom->loadHTML( mb_convert_encoding( $content, 'HTML-ENTITIES', 'UTF-8' ) );
	$xpath       = new DOMXpath( $dom );
	$items_to_id = $xpath->query( "//h1 | //h2 | //h3 | //h4 | //h5 | //h6 | //a[@class='side-link']" );

	if ( $items_to_id->length > 0 ) {
		foreach ( $items_to_id as $item_to_id ) {
			$current_id = $item_to_id->getAttribute( 'id' );
			if ( empty( $current_id ) ) {
				$item_text       = wp_strip_all_tags( $item_to_id->textContent, true );
				$item_text_clean = preg_replace( '/[^A-Za-z0-9\s]/', '', $item_text );
				$item_id         = sanitize_title( $item_text_clean );
				$item_to_id->setAttribute( 'id', $item_id );
			}
		}
	}

	return $dom->saveHTML();
}
add_filter( 'the_content', 'mk_learn_content_nav_ids' );

function mk_get_single_post_header_nav( $content = null ) {

	if ( empty( $content ) ) {
		return null;
	}

	$post_title = get_the_title();
	$content    = mk_learn_content_nav_ids( $content );

	$dom = new DOMDocument();
	@ $dom->loadHTML( mb_convert_encoding( $content, 'HTML-ENTITIES', 'UTF-8' ) );
	$xpath        = new DOMXpath( $dom );
	$headings_raw = $xpath->query( "//h2 | //h3 | //a[@class='side-link']" );

	$nav_items = array(
		array(
			'title' => 'Executive Summary',
			'link'  => 'executive-summary',
			'level' => 'h2',
		),
	);

	if ( $headings_raw->length > 0 ) {
		foreach ( $headings_raw as $heading ) {
			$title         = htmlspecialchars_decode( $heading->textContent );
			$id            = $heading->getAttribute( 'id' );
			$heading_level = $heading->nodeName;

			if ( ! empty( $title ) && ! empty( $id ) && ! empty( $heading_level ) ) {
				array_push(
					$nav_items,
					array(
						'title' => $title,
						'link'  => $id,
						'level' => $heading_level,
					)
				);
			}
		}
	} else {
		return null;
	}

	$star_output = mk_get_star_ratings_output();

	$nav_items_list = null;

	if ( ! empty( $star_output ) || ( ! empty( $nav_items ) && is_array( $nav_items ) ) ) {
		$nav_items_list .= "<div class='inpost-nav-wrap' id='inpost-nav-wrap' data-post-title='$post_title'>";
		if ( ! empty( $star_output ) ) {
			$nav_items_list .= $star_output;
		}
		if ( ! empty( $nav_items ) && is_array( $nav_items ) ) {
			$nav_items_list .= "<div class='inpost-nav'>";
			$nav_items_list .= "<a class='no-slide mobile-nav-title' href='#'><div><span>Table of Contents Navigation</span><span class='plus' ><svg width='28' height='28' fill='none' xmlns='http://www.w3.org/2000/svg'><path d='M14 14H7m7-7v7-7zm0 7v7-7zm0 0h7-7z' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'/></svg></span></div></a>";
			$nav_items_list .= "<div class='inpost-nav-items'>";
			foreach ( $nav_items as $nav_item ) {
				$title = $nav_item['title'];
				$link  = $nav_item['link'];
				$level = $nav_item['level'];

				$nav_items_list .= "<a class='$level-link' href='#$link' data-slide-offset='all-the-floaties'>$title</a>";
			}
			$nav_items_list .= '</div>';
			$nav_items_list .= '</div>';
		}
		$nav_items_list .= '</div>';
	}

	return $nav_items_list;

}
