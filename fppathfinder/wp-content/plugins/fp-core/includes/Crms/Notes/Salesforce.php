<?php
/**
 * Salesforce Notes Format
 *
 * @package    FP_Core/
 * @subpackage FP_Core/Includes/Crms
 * @author     Objectiv
 * @copyright  Copyright (c) 2020, Objectiv
 * @license    GPL-2.0
 * @version    1.0.0
 */

namespace FP_Core\Crms\Notes;

use FP_Core\InteractiveLists\Utilities\CRM;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

/**
 * Salesforce Notes Format
 *
 * @author Jason Witt
 * @since  1.0.0
 */
class Salesforce {

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

		$lines = [
			"<p>Name: $client_name</p>",
			'<p>Completed Date: ' . substr( current_time( 'mysql' ), 0, 10 ) . '</p>',
			'<p>Checklist: ' . get_the_title() . '</p><p>&nbsp;</p>',
		];

		$group_id          = 0;
		$entry             = fp_get_share_link_db_entry();
		$advisor_settings  = ! empty( $entry ) ? json_decode( $entry['fields'] ) : [];
		$show_more_details = ! empty( $advisor_settings ) ? $advisor_settings->show_more_details : 0;

		$questions_groups_array = Checklist::build_questions_array( $question_groups, $_POST );
		$yes_answers            = $questions_groups_array['yes'];
		$not_answers            = $questions_groups_array['not'];

		if ( ! empty( $yes_answers ) ) {
			$lines[] = '<h2><b><u>Possible Planning Issues Identified</u></b></h2>';

			foreach ( $yes_answers as $yes_answer ) {
				if ( empty( $yes_answer ) ) {
					continue;
				}

				$question_id = 0;
				$heading     = $yes_answer['heading'] ?? '';
				$lines[]     = '<h3><b><u>' . $heading . '</u></b></h3><p>&nbsp;</p></h3>';

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
			$lines[] = '<h2><b><u>Review the Answers</u></b></h2>';

			foreach ( $not_answers as $not_answer ) {
				if ( empty( $not_answer ) ) {
					continue;
				}

				$question_id       = 0;
				$heading           = $not_answer['heading'] ?? '';
				$show_more_details = $not_answer['show_more_details'] ?? '';
				$lines[]           = '<h3><b><u>' . $heading . '</u></b></h3><p>&nbsp;</p></h3>';

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

		return implode( '', $lines );
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
		$line          = '<p><strong>' . $question_array['question'] . '</strong></p></br>';
		$hide          = $question_array['hide'];
		$value_summary = $hide ? '' : Checklist::get_checkbox_value( $value );
		$notes_line    = '';
		$line         .= ' <p>' . $value_summary . '</p></br>';

		if ( ! $hide ) {

			if ( $show_more_details && ! empty( $question_array['tooltip'] ) ) {
				$line .= '<p> ! ' . $question_array['tooltip'] . '</p></br>';
			}

			if ( ! $note ) {
				$notes_line .= '<p> No Notes.<p></br>';
			} else {
				$notes_line .= '<p> Note: ' . $note . '</p></br>';
			}
		} else {
			$notes_line .= '<p> Question was not presented.</p></br>';
		}

		$line .= $notes_line;

		return $line;
	}
}
