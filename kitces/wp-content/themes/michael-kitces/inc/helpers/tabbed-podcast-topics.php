<?php

function objectiv_do_tabbed_podcast_topics() {
	$topic_links     = objectiv_get_podcast_topic_links();
	$podcasts        = objectiv_get_podcast_posts();
	$display_section = is_array( $topic_links ) && ! empty( $topic_links ) && is_array( $podcasts ) && ! empty( $podcasts );

	if ( $display_section ) {
		echo "<div class='podcast-episodes-by-topic-wrap'>";
		objectiv_output_topic_links( $topic_links );
		objectiv_output_podcast_blocks( $podcasts );
		echo "<div class='podcast-episodes-pagination mlra mt2'></div>";
		echo '</div>';
	}
}

// Get and set up the podcast topic links
// Pulls from the settings page
// Returns an array of links
function objectiv_get_podcast_topic_links() {
	$topic_rows  = mk_get_field( 'podcast_topics_rows', 'options', true, true );
	$topic_links = array();
	$row_count   = 1;

	if ( is_array( $topic_rows ) && ! empty( $topic_rows ) ) {
		foreach ( $topic_rows as $topic_row ) {
			$row_topics = mk_key_value( $topic_row, 'row_topics' );
			$row_links  = array();

			if ( is_array( $row_topics ) && ! empty( $row_topics ) ) {
				if ( $row_count == 1 ) {
					$all_link      = "<a href='#' class='podcast-topic-filter-link active' data-topic-slug='all-topics'><div class='text'>All</div></a>";
					$row_links[00] = $all_link;
				}
				foreach ( $row_topics as $topic ) {
					$topic_obj      = get_term( $topic, 'podcast-topic' );
					$topic_id       = $topic_obj->term_id;
					$topic_title    = $topic_obj->name;
					$topic_slug     = $topic_obj->slug;
					$topic_count    = $topic_obj->count;
					$topic_icon     = mk_get_field( 'icon', $topic_obj, true, true );
					$topic_icon_img = $topic_icon['url'];

					if ( $topic_count >= 1 && ! empty( $topic_title ) && ! empty( $topic_slug ) ) {
						$link  = "<a href='#' class='podcast-topic-filter-link' data-topic-slug='$topic_slug'>";
						$link .= "<div class='text'>$topic_title</div>";
						$link .= "<div class='icon'><img src='$topic_icon_img'></div>";
						$link .= '</a>';

						$row_links[ $topic_id ] = $link;
					}
				}
			}

			$topic_links[ $row_count ] = $row_links;
			$row_count++;
		}
		return $topic_links;
	}
}

// Takes in an array of topic links and outputs the section around that.
function objectiv_output_topic_links( $topic_links = null ) {
	if ( ! empty( $topic_links ) && is_array( $topic_links ) ) {
		echo "<div class='podcast-topics-links-outer mw-970 mlra mt2'>";
		echo "<div class='podcast-topics-links-title'>Topics:</div>";
		echo "<div class='podcast-topics-links'>";
		foreach ( $topic_links as $topic_link_row ) {
			if ( ! empty( $topic_link_row ) && is_array( $topic_link_row ) ) {
				echo "<div class='podcast-topics-links-row'>";
				foreach ( $topic_link_row as $topic_link ) {
					echo $topic_link;
				}
				echo '</div>';
			}
		}
		echo '</div>';
		echo '</div>';
	}
}

// Returns posts that are in the podcast category
function objectiv_get_podcast_posts() {

	$topics_ids = array();
	$topics     = get_terms(
		array(
			'taxonomy'   => 'podcast-topic',
			'hide_empty' => true,
		)
	);

	foreach ( $topics as $topic ) {
		array_push( $topics_ids, $topic->term_id );
	}

	$args = array(
		'posts_per_page' => -1,
		'post_type'      => 'post',
		'post_status'    => 'publish',
		'tax_query'      => array(
			'relation' => 'AND',
			array(
				'taxonomy' => 'podcast-topic', // Must have a podcast topic set
				'field'    => 'term_id',
				'terms'    => $topics_ids,
			),
			array(
				'taxonomy' => 'category', // Must also be in the Financial Advisor Success category
				'field'    => 'term_id',
				'terms'    => 87,
			),
		),
	);

	$posts = new WP_Query( $args );

	if ( $posts->have_posts() ) {
		$podcasts = $posts->posts;
	} else {
		$podcasts = false;
	}

	return $podcasts;
}

function objectiv_output_podcast_blocks( $podcasts = null ) {

	if ( ! empty( $podcasts ) && is_array( $podcasts ) ) {
		echo "<div class='podcast-topics-posts-outer mt2'>";
		foreach ( $podcasts as $podcast ) {
			$post_id       = $podcast->ID;
			$title         = $podcast->post_title;
			$permalink     = get_permalink( $post_id );
			$topics        = get_the_terms( $post_id, 'podcast-topic' );
			$image         = mk_get_field( 'podcast_page_image', $post_id, true, true );
			$topic_classes = objectiv_get_podcast_topic_classes( $topics );
			$topic_names   = objectiv_get_podcast_topic_names( $topics );

			echo "<a href='$permalink' target='_blank' class='podcast-topics-post bg-white soft-shadow hover-shadow all-topics $topic_classes'>";
			echo "<div class='screen-reader-text'>$title</div>";
			if ( ! empty( $image ) ) {
				echo wp_get_attachment_image( $image['id'], 'podcast-wide' );
			} else {
				echo "<div class='image-holder'>$title</div>";
			}
			echo "<div class='podcast-topic-post-bottom'>";
			if ( ! empty( $topic_names ) ) {
				echo "<div class='podcast-topic-post-topics mb1'><span class='fwb'>Topics: </span>$topic_names</div>";
			}
			echo "<div class='podcast-topic-post-read-more'>Listen Now + Read The Transcript</div>";
			echo '</div>';
			echo '</a>';
		}
		echo '</div>';
	}

}

function objectiv_get_podcast_topic_classes( $topics = null ) {
	if ( is_array( $topics ) && ! empty( $topics ) ) {
		$count   = 1;
		$classes = null;

		foreach ( $topics as $topic ) {
			if ( $count == 1 ) {
				$classes = $topic->slug;
			} else {
				$classes .= ' ' . $topic->slug;
			}
			$count++;
		}

		return $classes;
	}
}

function objectiv_get_podcast_topic_names( $topics = null ) {
	if ( is_array( $topics ) && ! empty( $topics ) ) {
		$count = 1;
		$names = null;

		foreach ( $topics as $topic ) {
			if ( $count == 1 ) {
				$names = $topic->name;
			} else {
				$names .= ' | ' . $topic->name;
			}
			$count++;
		}

		return $names;
	}
}
