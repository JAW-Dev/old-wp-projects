<?php
/**
 * Quiz Table Shortcode.
 *
 * @author Jason Witt
 *
 * @return void
 */
function kitces_user_quiz_list_shortcode() {
	$table = new UserQuizTable();
	ob_start();
	echo wp_kses_post( $table->output_table() );
	return ob_get_clean();
}
add_shortcode( 'user-quiz-list', 'kitces_user_quiz_list_shortcode' );
