<?php

require '../../wp/wp-load.php';

$args = array(
	'posts_per_page' => -1,
	'post_type'      => 'post',
	'status'         => 'publish',
	'meta_query'     => array(
		array(
			'key'     => 'ce_quiz_link',
			'value'   => '',
			'compare' => '!=',
		),
	),
);

$posts                   = get_posts( $args );
$posts_not_updated       = array();
$posts_updated_ids       = array();
$posts_unable_to_connect = array();
$count                   = 1;

if ( ! empty( $posts ) && is_array( $posts ) ) {
	foreach ( $posts as $post ) {
		$id            = $post->ID;
		$quiz_link     = get_field( 'ce_quiz_link', $id );
		$quiz_id_field = get_field( 'ce_quiz_page', $id );
		$quiz_id       = url_to_postid( $quiz_link );

		print( '<pre>' . print_r( $title, true ) . '</pre>' );

		if ( empty( $quiz_id_field ) && ! empty( $quiz_id ) ) {

			// Update post meta with post id and quiz id
			$meta_id_set = update_post_meta( $id, 'ce_quiz_page', $quiz_id );

			array_push(
				$posts_updated_ids,
				array(
					'post_title'    => $post->post_title,
					'post_url'      => get_the_permalink( $id ),
					'post_id'       => $id,
					'quiz_link'     => $quiz_link,
					'quiz_id'       => $quiz_id,
					'quiz_link_new' => get_the_permalink( $quiz_id ),
				)
			);
			$count++;
		} elseif ( ! empty( $quiz_id_field ) ) {
			array_push(
				$posts_not_updated,
				array(
					'post_title'    => $post->post_title,
					'post_url'      => get_the_permalink( $id ),
					'post_id'       => $id,
					'quiz_link'     => $quiz_link,
					'quiz_id_field' => $quiz_id_field,
					'quiz_link_new' => get_the_permalink( $quiz_id_field ),
				)
			);
		} else {
			array_push(
				$posts_unable_to_connect,
				array(
					'post_title'    => $post->post_title,
					'post_url'      => get_the_permalink( $id ),
					'post_id'       => $id,
					'quiz_link'     => $quiz_link,
					'quiz_id_field' => $quiz_id_field,
				)
			);
		}
	}

	echo "<h2 class=''>Changed</h2>";
	print( '<pre>' . print_r( $posts_updated_ids, true ) . '</pre>' );

	echo "<h2 class=''>Error Matching</h2>";

	echo "<ol class=''>";
	foreach ( $posts_updated_ids as $post ) {
		$old_url  = get_field( 'ce_quiz_link', $post['post_id'] );
		$new_url  = get_permalink( get_field( 'ce_quiz_page', $post['post_id'] ) );
		$title    = $post['post_title'];
		$post_url = $post['post_url'];

		if ( $old_url !== $new_url ) {
			echo "<li><a href='$post_url'>$title</a></li>";
		}
	}
	echo '</ol>';

	echo '<br>';
	echo '<br>';

	echo "<h2 class=''>Not Changed</h2>";
	print( '<pre>' . print_r( $posts_not_updated, true ) . '</pre>' );

	echo "<h2 class=''>Error Matching</h2>";

	echo "<ol class=''>";
	foreach ( $posts_not_updated as $post ) {
		$old_url  = get_field( 'ce_quiz_link', $post['post_id'] );
		$new_url  = get_permalink( get_field( 'ce_quiz_page', $post['post_id'] ) );
		$title    = $post['post_title'];
		$post_url = $post['post_url'];

		if ( $old_url !== $new_url ) {
			echo "<li><a href='$post_url'>$title</a></li>";
		}
	}
	echo '</ol>';

	echo '<br>';
	echo '<br>';

	echo "<h2 class=''>Unable to Connect</h2>";
	print( '<pre>' . print_r( $posts_unable_to_connect, true ) . '</pre>' );

	echo "<h2 class=''>Error Matching</h2>";

	echo "<ol class=''>";
	foreach ( $posts_unable_to_connect as $post ) {
		$old_url  = get_field( 'ce_quiz_link', $post['post_id'] );
		$new_url  = get_permalink( get_field( 'ce_quiz_page', $post['post_id'] ) );
		$title    = $post['post_title'];
		$post_url = $post['post_url'];

		if ( $old_url !== $new_url ) {
			echo "<li><a href='$post_url'>$title</a></li>";
		}
	}
	echo '</ol>';
}
