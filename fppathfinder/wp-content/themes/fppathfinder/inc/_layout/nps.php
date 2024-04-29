<?php

add_action( 'wp_ajax_nps_ajax_callback', 'nps_ajax_callback' );
add_action( 'wp_ajax_nopriv_nps_ajax_callback', 'nps_ajax_callback' );
function nps_ajax_callback() {
	$html = fp_get_nps_slide_in_html();
	echo $html;
	wp_die();
}

// Add a holding div so that we dont have to add lister to site-container for click
add_action( 'wp_footer', 'fp_nps_slide_in_holder' );
function fp_nps_slide_in_holder() {
	echo "<div class='nps-holder'></div>";
}

// add_action( 'wp_footer', 'fp_get_nps_slide_in_html' ); // using this for testing to display more quickly
function fp_get_nps_slide_in_html() {
	$title     = get_field( 'slide_in_title', 'nps_settings' );
	$form_page = get_field( 'form_page', 'nps_settings' );
	$display   = fp_decide_display_nps();
	$count     = 0;
	ob_start();
	?>
	<?php if ( $display && ! empty( $title ) && ! empty( $form_page ) ) : ?>
		<section class="nps-outer">
			<div class="close-nps"><svg fill="none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 14 14"><path d="M1 1l12 12M1 13L13 1 1 13z" stroke="#293D52" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg></div>
			<div class="wrap max-width-960">
				<div class="nps-inner">
					<?php if ( ! empty( $title ) ) : ?>
						<h2 class=""><?php echo $title; ?></h2>
					<?php endif; ?>
					<div class="survey-links">
						<div class="not-likely">Not Likely</div>
						<?php while ( $count <= 10 ) { ?>
							<a target="_blank" href="<?php echo get_permalink( $form_page ); ?>?score=<?php echo $count; ?>" class=""><?php echo $count; ?></a>
							<?php
							$count++;
						}
						?>
						<div class="very-likely">Very Likely</div>
					</div>
					<div class="survey-links-explainer">0 = Not Likely and 10 = Very Likely</div>
				</div>
			</div>
		</section>
	<?php endif; ?>
	<?php
	$html = ob_get_contents();
	ob_end_clean();

	return $html;
}

function fp_decide_display_nps() {
	$display            = false;
	$show_allways_admin = get_field( 'always_show_for_admin', 'nps_settings' );
	$lottery_pool_size  = get_field( 'lottery_pool_size', 'nps_settings' );
	$form_page          = get_field( 'form_page', 'nps_settings' );

	if ( is_user_logged_in() ) {

		$showed_cookie     = $_COOKIE['nps-showed'] ?? '';
		$closed_cookie     = $_COOKIE['nps-closed'] ?? '';
		$filled_out_cookie = $_COOKIE['nps-filled-out'] ?? '';

		// If either cookie is set we won't display
		if ( ! empty( $showed_cookie ) || ! empty( $closed_cookie ) || ! empty( $filled_out_cookie ) ) {
			$display = false;
		} else {
			// Lottery display
			if ( ! empty( $lottery_pool_size ) ) {
				$lottery_size_int = (int) $lottery_pool_size;
				$random_number    = mt_rand( 1, $lottery_size_int );

				if ( 1 === $random_number ) {
					$display = true;
				}
			}
		}

		// logic if is an admin and if that setting is on
		if ( $show_allways_admin && current_user_can( 'administrator' ) ) {
			$display = true;
		}

		// If this happens to be the form page we won't show it for sure either
		if ( get_the_ID() === $form_page ) {
			$display = false;
		}
	}

	return $display;
}

add_filter( 'body_class', 'fp_nps_body_class' );
function fp_nps_body_class( $classes ) {
	$form_page = get_field( 'form_page', 'nps_settings' );

	if ( get_the_ID() === $form_page ) {
		$classes[] = 'nps-page';
	}

	return $classes;
}

add_filter( 'gform_pre_render', 'fp_nps_form_prefill' );
add_filter( 'gform_pre_validation', 'fp_nps_form_prefill' );
add_filter( 'gform_pre_submission_filter', 'fp_nps_form_prefill' );
add_filter( 'gform_admin_pre_render', 'fp_nps_form_prefill' );

function fp_nps_form_prefill( $form ) {
	foreach ( $form['fields'] as &$field ) {

		if ( is_user_logged_in() && ! is_admin() ) {
			$member_name      = 'text' === $field->type && strpos( $field->cssClass, 'member-name' ) !== false;
			$member_email     = 'email' === $field->type && strpos( $field->cssClass, 'member-email' ) !== false;
			$membership_level = 'text' === $field->type && strpos( $field->cssClass, 'membership-level' ) !== false;
			$nps_score        = 'radio' === $field->type && strpos( $field->cssClass, 'nps-score' ) !== false;

			if ( $member_name ) {
				$field->defaultValue = obj_get_users_full_name();
			}

			if ( $member_email ) {
				$field->defaultValue = obj_get_users_email();
			}

			if ( $membership_level ) {
				$field->defaultValue = obj_get_users_subscription_level_name();
			}

			if ( $nps_score ) {
				if ( isset( $_GET['score'] ) ) {
					$passed_score = $_GET['score'];
					foreach ( $field->choices as &$choice ) {
						if ( $choice['text'] === $passed_score ) {
							$choice['isSelected'] = true;
						}
					}
				}
			}
		}
	}

	return $form;
}
