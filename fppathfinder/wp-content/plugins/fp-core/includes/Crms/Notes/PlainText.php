<?php
/**
 * PlainText Notes Format
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
 * PlainText Notes Format
 *
 * @author Jason Witt
 * @since  1.0.0
 */
class PlainText {

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
	 * @return string
	 */
	public static function build_note(): string {
		$question_groups = function_exists( 'get_field' ) ? get_field( 'question_groups' ) : [];
		$client_name     = fp_get_crm_client_name();
		$lines           = array(
			implode(
				"\r\n",
				array(
					'Name: ' . $client_name,
					'Completed Date: ' . substr( current_time( 'mysql' ), 0, 10 ),
					'Checklist: ' . get_the_title(),
				)
			),
		);

		$group_id          = 0;
		$entry             = fp_get_share_link_db_entry();
		$advisor_settings  = ! empty( $entry ) ? json_decode( $entry['fields'] ) : [];
		$show_more_details = ! empty( $advisor_settings ) ? $advisor_settings->show_more_details : 0;

		$questions_groups_array = Checklist::build_questions_array( $question_groups, $_POST );
		$yes_answers            = $questions_groups_array['yes'];
		$not_answers            = $questions_groups_array['not'];

		if ( ! empty( $yes_answers ) ) {
			$lines[] = '---- Possible Planning Issues Identified ----';

			foreach ( $yes_answers as $yes_answer ) {
				if ( empty( $yes_answer ) ) {
					continue;
				}

				$question_id = 0;
				$heading     = $yes_answer['heading'] ?? '';
				$lines[]     = '--- ' . $heading . ' ---' . "\r\n";

				if ( empty( $yes_answer['questions'] ) ) {
					continue;
				}

				foreach ( $yes_answer['questions'] as $question_array ) {
					$lines[] = self::get_line( $question_array, $show_more_details );

					$question_id++;
				}

				$group_id++;
			}
		}

		if ( ! empty( $not_answers ) ) {
			$lines[] = '---- Review the Answers ----';

			foreach ( $not_answers as $not_answer ) {
				if ( empty( $not_answer ) ) {
					continue;
				}

				$question_id       = 0;
				$heading           = $not_answer['heading'] ?? '';
				$show_more_details = $not_answer['show_more_details'] ?? '';
				$lines[]           = '--- ' . $heading . ' ---' . "\r\n";

				if ( empty( $not_answer['questions'] ) ) {
					continue;
				}

				foreach ( $not_answer['questions'] as $question_array ) {
					$lines[] = self::get_line( $question_array, $show_more_details );

					$question_id++;
				}

				$group_id++;
			}
		}

		return implode( "\r\n\r\n", $lines );
	}

	/**
	 * Get Line
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param array $question_array    The questions array.
	 * @param bool  $show_more_details If to sdhow the tooltip.
	 *
	 * @return void
	 */
	public static function get_line( array $question_array, bool $show_more_details ) {
		$value         = $question_array['value'];
		$note          = $question_array['note'];
		$line          = $question_array['question'] . "\r\n";
		$hide          = $question_array['hide'];
		$value_summary = $hide ? '' : Checklist::get_checkbox_value( $value ) . "\r\n";
		$notes_line    = '';
		$line         .= ' ' . $value_summary;

		if ( ! $hide ) {

			if ( $show_more_details && ! empty( $question_array['tooltip'] ) ) {
				$line .= ' ! ' . str_replace( [ '&#8211;' ], [ '- ' ], wp_strip_all_tags( $question_array['tooltip'] ) ) . "\r\n";
			}

			if ( ! $note ) {
				$notes_line .= ' No Notes.' . "\r\n";
			} else {
				$notes_line .= ' Note: ' . $note . "\r\n";
			}
		} else {
			$notes_line .= ' Question was not presented.' . "\r\n";
		}

		$line .= $notes_line;

		return $line;
	}
}
