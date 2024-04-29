<?php

require '../../wp/wp-load.php';

// To make quiz related changes for the end of a year.
// 1. Use exports from the following scripts to get current data
// 1.1 scripts/new-year-export-banner-posts.php
// 1.2 scripts/new-year-export-quiz-pages-ce.php
// 1.3 scripts/new-year-export-quiz-pages-nev-blog.php
// 1.4 scripts/new-year-export-quiz-pages-webinars.php
// 2. Give resulting CSV files to Kitces people to tell you what needs to be changed.
// 3. Change the values in this file to make sure we're updating the proper things
// 2.1 Generally we set banners for previous year blog posts to show the "Old CE" message. Those post ids go in an array below.
// 2.2 Generally there are quiz pages that need to be "retired" as well. Those page ids go in an array below.


if ( is_user_logged_in() && current_user_can( 'manage_options' ) ) {

	// Post IDS we are changing ce banner message to "OLD CE" into 2021
	$posts_to_set_old_ce_message_on = array(
		37229,
		37369,
		37510,
		37717,
		37782,
		37855,
		37995,
		38142,
		38225,
		38344,
		38526,
		38604,
		38808,
		39038,
		39151,
		39268,
		39409,
		39030,
		39655,
		39723,
		39860,
		39969,
		39468,
		39725,
		40468,
		40276,
		38894,
		41197,
		41318,
		41450,
		41637,
		41688,
		41909,
		42065,
		42345,
		42620,
		42778,
		42978,
		43101,
		43241,
		43153,
		43422,
		43510,
		43652,
		43951,
		43851,
		44231,
		44260,
		44596,
		44777,
	);

	if ( ! empty( $posts_to_set_old_ce_message_on ) && is_array( $posts_to_set_old_ce_message_on ) ) {
		echo '<h2>Posts set to show the OLD CE banner.</h2>';
		echo '<ul>';
		foreach ( $posts_to_set_old_ce_message_on as $post_id ) {
			update_post_meta( $post_id, '_kitces_show_ce_old_banner', 'on' );
			echo '<li>';
			echo "<a href='" . get_permalink( $post_id ) . "'>" . get_the_title( $post_id ) . '</a>';
			echo '</li>';
		}
		echo '</ul>';
	}

	$quiz_pages_to_set_to_expired = array(
		51487, // Webinar Programs to Retire
		51033,
		46664,
		44375,
		43972,
		39117,
		23707,
		52443, // Blog Quiz Pages to Retire
		51969,
		50874,
		50007,
		48294,
		47230,
		44973,
		44970,
		44245,
		44247,
		43634,
		42825,
		42819,
		41876,
		41268,
		40243,
		39897,
		39634,
		39148,
		38391,
		38035,
		38033,
		20861, // Newsletter CE Quiz Pages to Retire
		18765,
		16222,
		6308,
		5635,
	);

	if ( ! empty( $quiz_pages_to_set_to_expired ) && is_array( $quiz_pages_to_set_to_expired ) ) {
		echo '<h2>Quiz pages retired (and related details)</h2>';
		echo '<ul>';
		foreach ( $quiz_pages_to_set_to_expired as $quiz_page_id ) {
			// Sets quiz to expired
			update_post_meta( $quiz_page_id, 'kitces_expired_quiz', 1 );

			// Make course catalog item private
			$course_cat_item = get_post_meta( $quiz_page_id, 'course_catalog_item', true );

			if ( ! empty( $course_cat_item ) ) {
				$course_cat_item_title = 'Course catalog item - ' . get_the_title( $course_cat_item ) . ' - set to private.';
				$course_cat_item_post  = array(
					'ID'          => $course_cat_item,
					'post_status' => 'private',
				);
				wp_update_post( $course_cat_item_post, false, false );

				// Set associated_quiz_form form to not active
				$quiz_form_id = get_post_meta( $course_cat_item, 'associated_quiz_form', true );

				if ( ! empty( $quiz_form_id ) ) {
					$form = GFAPI::get_form( $quiz_form_id );

					if ( is_array( $form ) && array_key_exists( 'title', $form ) ) {
						GFAPI::update_form_property( $quiz_form_id, 'is_active', 0 );
						$form_title = 'Quiz form - ' . $form['title'] . ' - set to inactive.';
					} else {
						$form_title = 'Unable to set quiz form to inactive';
					}
				}
			} else {
				$course_cat_item_title = 'Unable to find related course catalog item.';
			}

			echo '<li style="margin-top:12px;">';
			echo "<a href='" . get_permalink( $quiz_page_id ) . "'>" . get_the_date( 'Y.m.d', $quiz_page_id ) . ' - ' . $quiz_page_id . ' - ' . get_the_title( $quiz_page_id ) . '</a>';
			echo '<br>';
			echo 'Quiz page (link above) has been set to expired.';
			echo '<br>';
			echo $course_cat_item_title;
			echo '<br>';
			echo $form_title;
			echo '</li>';
		}
		echo '</ul>';

	}
} else {
	echo 'There once was a chicken nugget.';
}
