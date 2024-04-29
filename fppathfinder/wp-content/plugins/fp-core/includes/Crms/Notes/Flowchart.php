<?php
/**
 * Flowchart
 *
 * @package    FP_Core/
 * @subpackage FP_Core/Includes/Crms
 * @author     Objectiv
 * @copyright  Copyright (c) 2020, Objectiv
 * @license    GPL-2.0
 * @version    1.0.0
 */

namespace FP_Core\Crms\Notes;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

/**
 * Page
 *
 * @author Jason Witt
 * @since  1.0.0
 */
class Flowchart {

	/**
	 * Initialize the class
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function __construct() {}

	/**
	 * Build Note
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param string $client_name The client name.
	 *
	 * @return void
	 */
	public static function build_note( $client_name = null ) {
		$questions           = $_POST['chart_data']['questions'] ?? '';
		$result              = $_POST['chart_data']['result'] ?? '';
		$client_name         = $client_name ?? $_POST['client_name'] ?? '';
		$additional_comments = $_POST['additional-comments'] ?? '';

		$lines = array(
			implode(
				"\r\n",
				array(
					'Name: ' . $client_name,
					'Completed Date: ' . substr( current_time( 'mysql' ), 0, 10 ),
					'Checklist: ' . get_the_title(),
				)
			),
		);

		$lines[] = '--- Questions ---';

		foreach ( $questions as $question ) {
			$lines[] = $question['question']['text'] ?? '';
			$lines[] = $question['answer']['text'] ?? '';
		}

		$lines[] = '--- Result ---';
		$lines[] = strip_tags( $result['text'] ) ?? '';

		$lines[] = '--- Additional Comments ---';
		$lines[] = strip_tags( $additional_comments ) ?? '';

		return implode( "\r\n\r\n", $lines );
	}

	/**
	 * Build Email Note
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public static function build_email_note( $client_name ) {
		$client_name = $client_name ? $client_name : $_POST['client_name'];
		ob_start();
		?>
		<div class="checklist-summary">
			<h3><?php echo esc_html( get_the_title() ); ?></h3>
			<?php self::note_inner(); ?>
		</div>
		<?php

		return ob_get_clean();
	}

	/**
	 * Build HTML Note
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public static function build_html_note() {
		?>
		<div class="checklist-summary">
			<?php self::note_inner(); ?>
		</div>
		<?php
	}

	/**
	 * Note Inner
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public static function note_inner() {
		$questions           = $_POST['chart_data']['questions'] ?? '';
		$result              = $_POST['chart_data']['result'] ?? '';
		$additional_comments = $_POST['additional-comments'];
		?>
		<div class="question-group">
			<?php
			foreach ( $questions as $question ) {
				?>
				<div class="question-outer">
					<div class="question"><?php echo esc_html( wp_strip_all_tags( $question['question']['text'] ) ); ?></div>
					<div class="value"><?php echo esc_html( wp_strip_all_tags( $question['answer']['text'] ) ); ?></div>
				</div>
				<?php
			}
			?>
		</div>
		<div class="result"><?php echo esc_html( wp_strip_all_tags( $result['text'] ) ); ?></div>

		<?php if ( ! empty( $additional_comments ) ) : ?>
			<div class="question-group">
				<div class="question-outer">
					<div class="question">Additional Comments</div>
					<div><?php echo esc_html( wp_strip_all_tags( $additional_comments ) ); ?></div>
				</div>
			</div>
		<?php endif; ?>
		<?php
	}
}
