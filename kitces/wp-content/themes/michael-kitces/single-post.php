<?php
remove_action( 'genesis_entry_content', 'genesis_do_post_content' );
add_action( 'genesis_entry_content', 'kitces_single_post_content' );

function kitces_single_post_content() {
	global $post;

	$new_content = '';
	$content     = $post->post_content;
	$inpost_nav  = mk_get_single_post_header_nav( $content );
	$content     = explode( '<!--more-->', $content );

	if ( ! is_array( $content ) ) {
		the_content();

		return;
	}

	echo $inpost_nav;

	$nonce        = wp_create_nonce( 'user_star_rating' );
	$new_content  = "<div class='executive-summary'><div class='blockquote'><h3>Executive Summary</h3>{$content[0]}</div></div>";
	$new_content .= objectiv_author_block( $post->post_author, $post->ID );
	$new_content .= $content[1];
	$body_enable  = get_field( 'kictes_star_ratings_body', $post->ID );
	$all_enable   = get_field( 'kitces_enable_for_all_posts', 'option' );
	$enable       = $all_enable ? $all_enable : $body_enable;

	if ( $enable ) {
		$new_content .= '<div class="star-ratings__wrapper star-ratings__wrapper-body">
			<div id="" class="star-ratings star-ratings__body">
				<h3 class="star-ratings__question"></h3>
				<div class="star-ratings__stars" data-nonce="' . $nonce . '" data-version="">
				</div>
				<div class="star-ratings__message">
					<p class="hide"></p>
				</div>
			</div>
		</div>';
	}

	echo apply_filters( 'the_content', $new_content );
}

add_action( 'genesis_entry_footer', 'kitces_single_post_review_questions', 1 );
function kitces_single_post_review_questions() {
	$prefix      = '_kitces_';
	$show_banner = get_post_meta( get_the_ID(), $prefix . 'show_ce_banner', true );
	$questions   = get_field( 'review_questions' );

	$quiz_page_id    = get_post_meta( get_the_ID(), 'ce_quiz_page', true );
	$has_quiz_access = false;
	$logged_in       = is_user_logged_in();
	$expired_quiz    = get_post_meta( $quiz_page_id, 'kitces_expired_quiz', true );

	$quiz_url = null;
	if ( ! empty( $quiz_page_id ) ) {
		$quiz_url        = get_permalink( $quiz_page_id );
		$has_quiz_access = $logged_in && kitces_member_can_access_post( $quiz_page_id, get_current_user_id() );
	}

	if ( $has_quiz_access && $show_banner == 'on' && ! empty( $questions ) && ! $expired_quiz ) {
		if ( array_key_exists( 'questions', $questions ) && ! empty( $questions['questions'] ) ) {
			?>
		<div class="post-review-questions">
		<h3 class="post-review-question-title">Review Question(s)</h3>
			<?php
			obj_do_quiz_questions( $questions['questions'] );
			if ( ! empty( $quiz_url ) ) {
				?>
					<a href="<?php echo $quiz_url; ?>" class="go-to-quiz">Click here to take the CE quiz for this article!</a>
				<?php
			}
			?>
		</div>
			<?php
		} else {
			if ( $show_banner == 'on' ) {
				echo ( '<div class="post-footer-bar-wrap">' );
				cgd_ce_boxes();
				echo ( '<div>' );
			}
		}
	} else {
		if ( $show_banner == 'on' ) {
			echo( '<div class="post-footer-bar-wrap">' );
			cgd_ce_boxes();
			echo ( '<div>' );
		}
	}

    ?>

    <div class="back-to-top-wrap tac">
        <button class="back-to-top button">Back to Top</button>
    </div>

    <?php
}

add_action( 'genesis_entry_footer', 'objectiv_thrive_post_display' );
function objectiv_thrive_post_display() {
	$current_post_id      = get_the_ID();
	$current_post_cat     = wp_get_post_categories( $current_post_id )[0];
	$category_post_thrive = get_field( 'post_footer_cta_shortcode', 'category_' . $current_post_cat );
	$single_post_thrive   = get_post_meta( $current_post_id, '_kitces_' . 'custom_thrive_shortcode', true );

	$thrive_this_post = '';
	if ( ! empty( $single_post_thrive ) ) {
		$thrive_this_post = $single_post_thrive;
	} elseif ( ! empty( $category_post_thrive ) ) {
		$thrive_this_post = $category_post_thrive;
	}

	if ( ! empty( $thrive_this_post ) ) {
		?>
		<div class="single-post-thrive-cta">
			<?php echo do_shortcode( $thrive_this_post ); ?>
		</div>
		<?php
	}
}

add_action( 'genesis_entry_footer', 'kitces_single_post_related' );
function kitces_single_post_related() {
	if ( defined( 'SEARCHWP_VERSION' ) && defined( 'SEARCHWP_RELATED_VERSION' ) ) {
		$related = new SearchWP_Related\Template();
		echo $related->get_template();
	}
}

// remove_action( 'genesis_header', 'genesis_do_header' );
remove_action( 'genesis_entry_header', 'genesis_do_post_title' );
remove_action( 'genesis_entry_header', 'genesis_post_info', 12 );
add_action( 'genesis_entry_header', 'kitces_entry_header' );

genesis();
