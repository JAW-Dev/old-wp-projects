<?php

// If we don't have a search term lets redirect
$search_term = get_search_query();
$author      = ! empty( $_GET['by-author'] ) ? sanitize_text_field( wp_unslash( $_GET['by-author'] ) ) : '';
$category    = ! empty( $_GET['by-category'] ) ? sanitize_text_field( wp_unslash( $_GET['by-category'] ) ) : '';
$from_date   = ! empty( $_GET['from-date'] ) ? sanitize_text_field( wp_unslash( $_GET['from-date'] ) ) : '';
$to_date     = ! empty( $_GET['to-date'] ) ? sanitize_text_field( wp_unslash( $_GET['to-date'] ) ) : '';
// if ( empty( $search_term ) && empty( $author ) && empty( $category ) && empty( $from_date ) && empty( $to_date ) ) {
// 	wp_redirect( '/search' );
// 	exit;
// }

function search_title() {
	$search    = get_search_query();
	$author    = ! empty( $_GET['by-author'] ) ? sanitize_text_field( wp_unslash( $_GET['by-author'] ) ) : '';
	$category  = ! empty( $_GET['by-category'] ) ? sanitize_text_field( wp_unslash( $_GET['by-category'] ) ) : '';
	$from_date = ! empty( $_GET['from-date'] ) ? sanitize_text_field( wp_unslash( $_GET['from-date'] ) ) : '';
	$to_date   = ! empty( $_GET['to-date'] ) ? sanitize_text_field( wp_unslash( $_GET['to-date'] ) ) : '';
	$title     = '';

	if ( $search ) {
		$title .= '"' . $search. '"';
	}

	if ( $author ) {
		$first_name  = get_the_author_meta( 'first_name', $author );
		$last_name   = get_the_author_meta( 'last_name', $author );
		$author_name = ! empty( $first_name ) && ! empty( $last_name ) ? $first_name . ' ' . $last_name : get_the_author_meta( 'display_name', $author );
		$title .= '<br/>Author: ' . $author_name;
	}

	if ( $category ) {
		$title .= '<br/>Category: ' . get_cat_name( $category );
	}

	if ( ! empty( $from_date ) && empty( $to_date ) ) {
		$from_month = date( 'F', strtotime( $from_date ) );
		$from_year  = date( 'Y', strtotime( $from_date ) );
		$title     .= '<br/>Date: ' . $from_month . ' ' . $from_year;
	}

	if ( ! empty( $from_date ) && ! empty( $to_date ) ) {
		$from_day   = date( 'j', strtotime( $from_date ) );
		$from_month = date( 'F', strtotime( $from_date ) );
		$from_year  = date( 'Y', strtotime( $from_date ) );

		$to_day   = date( 'j', strtotime( $to_date ) );
		$to_month = date( 'F', strtotime( $to_date ) );
		$to_year  = date( 'Y', strtotime( $to_date ) );

		$title .= '<br/>Date: ' . $from_month . ' ' . $from_day . ', ' . $from_year . ' - ' . $to_month . ' ' . $to_day . ', ' . $to_year;
	}

	return $title;
}

remove_filter( 'document_title_parts', 'genesis_document_title_parts' );
add_filter( 'document_title_parts', 'kitces_search_title' );

function kitces_search_title( $parts ) {
	$genesis_document_title_parts         = new Genesis_SEO_Document_Title_Parts();
	$custom_document_title_parts          = $genesis_document_title_parts->get_parts( $parts );
	$custom_document_title_parts['title'] = 'Search results for: ' . search_title();

	return $custom_document_title_parts;
}

add_action( 'genesis_after_header', 'objectiv_search_banner' );
function objectiv_search_banner() {
	$search_title = 'Search results for: ' . search_title();
	?>
	<?php if ( ! empty( $search_title ) ) : ?>
		<section class="page-hero">
			<h1 class="page-hero-title"><?php echo $search_title; ?></h1>
		</section>
	<?php endif; ?>
	<?php
}

remove_action( 'genesis_loop_else', 'genesis_do_noposts' );
add_action( 'genesis_loop_else', 'themeprefix_genesis_do_noposts' );
function themeprefix_genesis_do_noposts() {
	?>
	<script>
		function openAdvancedTrigger(parent) {
			setTimeout(() => {
				const advancedMenu = jQuery(`${parent} .search-advanced`);
				const searchField = document.querySelector(`${parent} .search-basic`);
				const searchFieldWidth = searchField.clientWidth;

				advancedMenu.addClass('opened');
				advancedMenu.css('width', `${searchFieldWidth}px`);
			}, 100);
		}

		function clearAdvancedTrigger(parent) {
			const advancedMenu = jQuery(`${parent} .search-advanced`);
			advancedMenu.removeClass('opened');
		}

		function openAdvanced() {
			const mediaQuery = window.matchMedia('(min-width: 1040px)');
			const search = jQuery('body');
			let parent = '';

			if ( mediaQuery.matches ) {
				parent = '.search-wrap';
				clearAdvancedTrigger(parent)
				search.removeClass('mobile-menu-is-open');
				search.addClass('search-open');
				openAdvancedTrigger(parent);
			} else {
				parent = '.mobile-search-wrap';
				clearAdvancedTrigger(parent)
				search.removeClass('search-open');
				search.addClass('mobile-menu-is-open');
				setTimeout(() => {
					const button = jQuery('.advanced-toggle');
					jQuery('.mobile-search-toggle').toggle();
					jQuery('.mobile-search-wrap').slideToggle();
					jQuery('.mobile-search-wrap .search-form-input').focus();
					button.text('Close');
					openAdvancedTrigger(parent);
				}, 100);
			}
		}
	</script>
	<p>Sorry, no results matched your search.</p>
	<p><a href="javascript:void(0)" onclick="openAdvanced()">Try advanced</a></p>
	<?php
}

add_action( 'genesis_loop_else', 'cgd_search' );
add_action( 'genesis_loop_else', 'cgd_search_links' );

function objectiv_remove_shortcode_from_index( $content ) {
	if ( is_search() ) {
		$gravityform_regex = get_shortcode_regex( array( 'gravityform' ) );
		if ( ! empty( $gravityform_regex ) ) {
			$shortcode_array = preg_match( '/' . $gravityform_regex . '/s', $content, $fsmatches );
			$shortcode       = ! isset( $fsmatches ) ? $fsmatches[0] : '';
			$content         = str_replace( $shortcode, '', $content );
		}
	}
	return $content;
}
add_filter( 'the_content', 'objectiv_remove_shortcode_from_index' );

// remove_action( 'genesis_header', 'genesis_do_header' );
remove_action( 'genesis_entry_header', 'genesis_do_post_title' );
remove_action( 'genesis_entry_header', 'genesis_post_info', 12 );
add_action( 'genesis_entry_header', 'kitces_entry_header' );

genesis();
