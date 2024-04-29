<?php

/*
Template Name: Getting Started
*/

add_theme_support( 'genesis-structural-wraps', array( 'header', 'nav', 'subnav', 'footer-widgets', 'footer' ) );

// full width layout
add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_full_width_content' );
remove_action( 'genesis_entry_header', 'genesis_do_post_title' );

add_filter( 'body_class', 'objectiv_gs_body_class' );
function objectiv_gs_body_class( $classes ) {

	$classes[] = 'getting-started';
	return $classes;

}

add_action( 'objectiv_gs_page_content', 'objectiv_gs_page_content' );
function objectiv_gs_page_content() {
	objectiv_gs_page_banner();
	objectiv_gs_qas();
}

function objectiv_gs_page_banner() {
	$title            = get_the_title();
	$title_overide    = get_field( 'title_override' );
	$subtitle         = get_field( 'sub_title_for_questions' );
	$subtitle_answers = get_field( 'sub_title_for_answers' );

	if ( ! empty( $title_overide ) ) {
		$title = $title_overide;
	}

	?>
		<section class="page-banner blue">
			<div class="wrap">
				<div class="page-banner__content lmb0">
					<h1 class="page-banner__title"><?php echo $title; ?></h1>

					<?php if ( ! empty( $subtitle ) ) : ?>
						<p class="page-banner__subtitle questions"><?php echo $subtitle; ?></p>
					<?php endif; ?>

					<?php if ( ! empty( $subtitle_answers ) ) : ?>
						<p class="page-banner__subtitle answer"><?php echo $subtitle_answers; ?></p>
					<?php endif; ?>


				</div>
			</div>
		</section>
	<?php
}

function objectiv_gs_qas() {
	$qc        = get_field( 'questions_content' );
	$questions = objectiv_gs_simplify_questions_answers( $qc );

	?>
	<?php if ( ! empty( $qc ) && is_array( $qc ) ) : ?>
		<section class=" sectionpt sectionpb">
			<div class="wrap">
				<div class="questions-answers-wrap">
					<div class="questions-wrap">
						<div class="error smallmb">Please select at least one item.</div>
						<?php foreach ( $questions as $q ) : ?>
							<?php
								$qid      = obj_key_value( $q, 'qid' );
								$question = obj_key_value( $q, 'question' );
								$answers  = obj_key_value( $q, 'answers' );
							?>
							<div class="select-outer-wrap">
								<label for="select-<?php echo $qid; ?>"><?php echo $question; ?></label>
								<div class="select-wrap">
									<select name="select-<?php echo $qid; ?>" id="select-<?php echo $qid; ?>" data-question-id="<?php echo $qid; ?>">
										<option selected value="">Select one</option>
										<?php foreach ( $answers as $key => $answer ) : ?>
											<option value="<?php echo $key; ?>"><?php echo $answer; ?></option>
										<?php endforeach; ?>
									</select>
								</div>
							</div>
						<?php endforeach; ?>
						<span class="red-button">
							<a href="#" class="gs-start">Get Started</a>
						</span>
					</div>
					<div class="answers-wrap">
					<?php foreach ( $questions as $q ) : ?>
							<?php
								$qid             = obj_key_value( $q, 'qid' );
								$a_items         = obj_key_value( $q, 'answer_items' );
								$a_display_modes = obj_key_value( $q, 'answer_item_modes' );
							?>
							<?php if ( ! empty( $a_items ) ) : ?>
								<?php foreach ( $a_items as $key => $items ) : ?>
									<?php
										$aid               = $key;
										$all_related_items = $items;
										$display_mode      = $a_display_modes[ $key ];
									?>
									<div class=" answer lmb0" data-question-id="<?php echo $qid; ?>" data-question-mode="<?php echo $display_mode; ?>" data-answer-id="<?php echo $aid; ?>">
										<?php if ( ! empty( $all_related_items ) && is_array( $all_related_items ) ) : ?>
											<?php foreach ( $all_related_items as $related_item ) : ?>
												<?php
													$type = get_post_type( $related_item );
												?>
													<?php if ( 'post' === $type ) : ?>
														<div class="post-intro">
															<?php objectiv_custom_blog_block( $related_item ); ?>
														</div>
													<?php elseif ( 'resource' === $type ) : ?>
														<?php
															$content   = get_field( 'description', $related_item );
															$excerpt   = wp_trim_words( $content, 35 );
															$permalink = get_permalink( $related_item );
														?>
														<div class="resource-intro">
															<?php fp_resource_card( get_post( $related_item ) ); ?>
															<div class="excerpt-side">
																<?php if ( ! empty( $excerpt ) ) : ?>
																	<div class="">
																		<?php echo $excerpt; ?>
																	</div>
																<?php endif; ?>
																<?php if ( ! empty( $permalink ) ) : ?>
																	<a href="<?php echo $permalink; ?>">View Resource</a>
																<?php endif; ?>
															</div>
														</div>
													<?php endif; ?>
											<?php endforeach; ?>
										<?php endif; ?>
									</div>
								<?php endforeach; ?>
							<?php endif; ?>
						<?php endforeach; ?>
					</div>
				</div>
			</div>
		</section>
	<?php endif; ?>
	<?php
}

// Template output
get_header();
do_action( 'objectiv_gs_page_content' );
get_footer();
