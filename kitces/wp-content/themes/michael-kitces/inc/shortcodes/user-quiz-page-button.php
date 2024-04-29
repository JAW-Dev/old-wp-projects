<?php
/**
 * User Quiz Page Button.
 *
 * @author Jason Witt
 *
 * @param array $atts The shortcode arguments.
 *
 * @return void
 */
function kitces_quiz_history_page_link( $atts = array() ) {
	$attributes = shortcode_atts(
		array(
			'href'    => '',
			'text'    => '',
			'classes' => '',
		),
		$atts
	);

	$option  = function_exists( 'get_field' ) ? get_field( 'user_quiz_table', 'option' ) : '';
	$href    = ! empty( $attributes['href'] ) ? $attributes['href'] : $option;
	$text    = ! empty( $attributes['text'] ) ? $attributes['text'] : 'Quiz History and Completion Certificates.';
	$classes = ! empty( $attributes['classes'] ) ? ' ' . $attributes['classes'] : '';

	ob_start();
	?>
	<a href="<?php echo esc_url( $href ); ?>" class="green-button user-quiz-page-link<?php echo esc_attr( $classes ); ?>"><?php echo esc_html( $text ); ?></a>
	<?php
	return ob_get_clean();
}
add_shortcode( 'quiz-history-page-link', 'kitces_quiz_history_page_link' );
