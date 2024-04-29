<?php

/**
 * Create the shortcodes quiz article questions
 */
add_shortcode( 'quiz-articles', 'cgd_quiz_articles' );
function cgd_quiz_articles( $atts ) {
	$a = shortcode_atts(
		array(
			'id' => get_the_ID(),
		), $atts
	);

	$id       = $a['id'];
	$articles = get_field( 'article', $id );

	ob_start();

	?>
	<?php if ( ! empty( $articles ) ) : ?>
		<div class="quiz-articles accordion-block">
			<?php $count = 1; ?>
			<?php
			foreach ( $articles as $a ) {
				$id         = $a['article_link'];
				$title      = get_the_title( $id );
				$link       = get_the_permalink( $id );
				$over_title = $a['article_title_override'];

				if ( ! empty( $over_title ) ) {
					$title = $over_title;
				}
				?>
				<div class="quiz-article">
					<?php if ( $title ) : ?>
						<div class="quiz-article-title">
							<p>Article <?php echo $count; ?>: <span class="article-title"><a href="<?php echo $link; ?>"
							target="_blank"><?php echo $title; ?></a></span></p>
						</div>
					<?php endif; ?>
				</div>
				<?php
				$count++;
			}
			?>
		</div>
	<?php endif; ?>
	<?php

	return ob_get_clean();
}

function obj_do_quiz_questions( $questions = null ) {
	if ( ! empty( $questions ) ) {
		foreach ( $questions as $q ) {
			$question = $q['question'];
			$answers  = $q['answers'];

			if ( ! empty( $question ) ) {
			?>
				<div class="article-questions accordion-block">
					<div class="accordion-row">
						<div class="accordion-title">
							<span class="question-title">REVIEW QUESTION: <strong><?php echo $question; ?></strong></span>
						</div>
						<div class="accordion-info">
							<?php if ( ! empty( $answers ) ) : ?>
								<ul class="question-answers">
									<?php
									foreach ( $answers as $answer ) {
										$correct_incorrect = $answer['correct'];
										$choice            = $answer['choice'];
										$explanation       = $answer['explanation'];
										?>
										<li class="question-answer">
											<div class="question-answer-choice">
												<span><?php echo $choice; ?></span>
											</div>
											<div class="question-answer-explanation <?php echo 'is-' . $correct_incorrect; ?>">
												<span><?php echo $explanation; ?></span>
											</div>
										</li>
										<?php
									}
									?>
								</ul>
							<?php endif; ?>
						</div>
					</div>
				</div>
				<?php
			}
		}
	}
}


// Shortcode for Quiz Review Questions
add_shortcode( 'quiz-review-questions', 'cgd_quiz_review_questions' );
function cgd_quiz_review_questions() {
	$page                   = get_the_ID();
	$review_questions_array = get_field( 'review_questions', $page );
	$review_questions       = null;

	if ( is_array( $review_questions_array ) && ! empty( $review_questions_array ) && array_key_exists( 'questions', $review_questions_array ) ) {
		$review_questions = get_field( 'review_questions', $page )['questions'];
	}

	ob_start();

	if ( ! empty( $review_questions ) ) :
		echo( '<div class="quiz-review-sc-wrap">' );
		echo( '<h3>Review Questions</h3>' );
		obj_do_quiz_questions( $review_questions );
		echo ( '</div>' );
	endif;

	return ob_get_clean();
}

// Shortcode for Quiz Review Questions
add_shortcode( 'quiz-feedback-survey', 'cgd_quiz_feedback_survey' );
function cgd_quiz_feedback_survey() {
	$page         = get_the_ID();
	$form_setting = get_field( 'quiz_feedback_survey', $page );

	if ( 'live' === $form_setting ) {
		$form = get_field( 'live_feedback_survey', 'option' );
	} elseif( 'ethics' == $form_setting ) {
		$form = get_field( 'ethics_feedback_survey', 'option' );
	} else {
		$form = get_field( 'default_feedback_survey', 'option' );
    }

	ob_start();

	if ( ! empty( $form ) ) {
		$shortcode = '[gravityform id=' . $form['id'] . " title='false' description='false']";
		echo do_shortcode( $shortcode );
	} else {
		echo do_shortcode( '[gravityform id="66" title="false" description="false"]' );
	}

	return ob_get_clean();
}

