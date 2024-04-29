<?php
/**
 * Checklist
 *
 * @package    FP_Core/
 * @subpackage FP_Core/Includes/Crms
 * @author     Objectiv
 * @copyright  Copyright (c) 2020, Objectiv
 * @license    GPL-2.0
 * @version    1.0.0
 */

namespace FP_Core\Crms\Notes;

use FP_Core\Crms\Utilities;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

/**
 * Page
 *
 * @author Jason Witt
 * @since  1.0.0
 */
class Checklist {

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
	public static function build_note( $plain = false ) {
		$advisor_id = isset( $_GET['a'] ) ? sanitize_text_field( wp_unslash( $_GET['a'] ) ) : '';
		$active_crm = Utilities::get_active_crm( $advisor_id );
		$crm        = Utilities::get_crm_info( $active_crm );
		$crm_slug   = $crm['slug'] ?? '';
		$lines      = '';

		if ( fp_is_feature_active( 'xlr8_crm' ) ) {
			if ( $plain ) {
				$lines = PlainText::build_note();
			} else {
				switch ( $crm_slug ) {
					case 'salesforce':
						$lines = Salesforce::build_note();
						break;
					default:
						$lines = PlainText::build_note();
						break;
				}
			}
		} else {
			$lines = PlainText::build_note();
		}

		return $lines;
	}

	/**
	 * Build Tasks
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return array
	 */
	public static function build_tasks( $attatchment = false ) {
		$question_groups = get_field( 'question_groups' );
		$tasks           = [];
		$yes_count       = 1;
		$unset_count     = 1;

		$advisor_id = isset( $_GET['a'] ) ? sanitize_text_field( wp_unslash( $_GET['a'] ) ) : '';

		$active_crm = Utilities::get_active_crm( $advisor_id );
		$crm        = Utilities::get_crm_info( $active_crm );
		$crm_slug   = $crm['slug'] ?? '';

		$newline = "\n";
		$return  = "\r";

		if ( $crm_slug === 'redtail' || $attatchment ) {
			$newline = '<br>';
			$return  = '<br>';
		}

		$group_id          = 0;
		$entry             = fp_get_share_link_db_entry();
		$advisor_settings  = ! empty( $entry ) ? json_decode( $entry['fields'] ) : [];
		$fields            = ! empty( $advisor_settings ) ? (array) $advisor_settings->fields : [];
		$is_options_active = fp_is_feature_active( 'share_link_options' );

		foreach ( $question_groups as $question_group ) {
			$question_id = 0;

			foreach ( $question_group['questions'] as $question_array ) {
				$question      = $question_array['question'] ?? '';
				$id            = $question_array['id'] ?? '';
				$value         = $_POST[ 'question_' . $id . '_value' ] ?? '';
				$note          = $_POST[ 'question_' . $id . '_note' ] ?? '';
				$note          = str_replace( '\\', '', implode( '', explode( '\\', $note ) ) );
				$line          = $question . $newline;
				$value_summary = self::get_checkbox_value( $value );
				$line         .= $value_summary;

				if ( ! $note ) {
					$line .= ' No Notes.';
				} else {
					$line .= $newline . 'Note: ' . $note;
				}

				$field_groups   = ! empty( $fields ) && isset( $fields[ $group_id ] ) ? $fields[ $group_id ] : [];
				$field_question = ! empty( $field_groups ) && isset( $fields[ $group_id ][ $question_id ] ) ? $fields[ $group_id ][ $question_id ] : [];
				$hide           = ! empty( $field_question ) && isset( $fields[ $group_id ][ $question_id ]->show ) ? $fields[ $group_id ][ $question_id ]->show : 'false';

				if ( $hide === 'true' && $is_options_active ) {
					$line .= " Question was not presented.\r\n";
				}

				if ( $value === 'yes' ) {
					if ( empty( $tasks['yes'] ) ) {
						$tasks['yes'][] = [
							'question' => $question,
							'line'     => $return . strval( $yes_count ) . '. ' . $line,
						];
					} else {
						if ( ! in_array( $question, $tasks['yes'], true ) ) {
							$tasks['yes'][] = [
								'question' => $question,
								'line'     => $return . strval( $yes_count ) . '. ' . $line,
							];
						}
					}
					$yes_count++;
				}

				if ( empty( $value ) || is_null( $value ) || 'unset' === $value ) {
					if ( empty( $tasks['unset'] ) ) {
						$tasks['unset'][] = [
							'question' => $question,
							'line'     => $return . strval( $unset_count ) . '. ' . $line,
						];
					} else {
						if ( ! in_array( $question, $tasks['unset'], true ) ) {
							$tasks['unset'][] = [
								'question' => $question,
								'line'     => $return . strval( $unset_count ) . '. ' . $line,
							];
						}
					}
					$unset_count++;
				};

				$question_id++;
			}

			$group_id++;
		}

		$formated_tasks = [];

		$break_one = $return . $newline . $return . $newline;

		if ( $crm_slug === 'redtail' ) {
			$break_one = $return . $newline;
		}

		$break_two = $return . $newline;

		if ( array_key_exists( 'yes', $tasks ) ) {
			$lead = 'Checklist: ' . get_the_title() . $break_one . 'The following planning issues have been identified in completing this checklist.  These issues may need to be further addressed or analyzed.' . $break_two;
			$temp = [];
			foreach ( $tasks['yes'] as $task ) {
				$temp[] = $task['line'];
			}

			$formated_tasks[] = [
				'title' => 'Research and address possible planning issues',
				'lines' => $lead . implode( $newline, $temp ),
			];
		}

		if ( array_key_exists( 'unset', $tasks ) ) {
			$lead = 'Checklist: ' . get_the_title() . $break_one . 'The questions listed below were not answered when completing this checklist. Additional research and information is needed to determine whether there are more planning issues to consider.' . $break_two;
			$temp = [];
			foreach ( $tasks['unset'] as $task ) {
				$temp[] = $task['line'];
			}

			$formated_tasks[] = [
				'title' => 'Gather more information to identify possible planning issues',
				'lines' => $lead . implode( $newline, $temp ),
			];
		}

		return $formated_tasks;
	}

	/**
	 * Build Email Note
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return string
	 */
	public static function build_email_note() {
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
		$question_groups   = get_field( 'question_groups' );
		$group_id          = 0;
		$question_id       = 0;
		$entry             = fp_get_share_link_db_entry();
		$advisor_settings  = ! empty( $entry ) ? json_decode( $entry['fields'] ) : [];
		$show_more_details = ! empty( $advisor_settings ) ? $advisor_settings->show_more_details : 0;

		$questions_groups_array = Checklist::build_questions_array( $question_groups, $_POST );
		$yes_answers            = $questions_groups_array['yes'];
		$not_answers            = $questions_groups_array['not'];


		if ( ! empty( $yes_answers ) ) {
			$lines[] = '<h3>Possible Planning Issues Identified</h3><div class="question-group">';

			foreach ( $yes_answers as $yes_answer ) {
				if ( empty( $yes_answer ) ) {
					continue;
				}

				$question_id = 0;
				$heading     = $yes_answer['heading'] ?? '';
				$lines[]     = '<h4>' . $heading . '</h4>';

				if ( empty( $yes_answer['questions'] ) ) {
					continue;
				}

				foreach ( $yes_answer['questions'] as $question_array ) {
					$lines[] = self::get_line( $question_array, $show_more_details );

					$question_id++;
				}

				$group_id++;
			}

			$lines[] = '</div>';

		}

		if ( ! empty( $not_answers ) ) {
			$lines[] = '<h3>Review the Answers</h3><div class="question-group">';

			foreach ( $not_answers as $not_answer ) {
				if ( empty( $not_answer ) ) {
					continue;
				}

				$question_id       = 0;
				$heading           = $not_answer['heading'] ?? '';
				$show_more_details = $not_answer['show_more_details'] ?? '';
				$lines[]           = '<h4>' . $heading . '</h4>';

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

		echo implode( '', $lines );
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
		$line          = '<div class="question-outer" style="margin-bottom: 2rem;"><p>' . $question_array['question'] . '</p>';
		$hide          = $question_array['hide'];
		$value_summary = $hide ? '' : Checklist::get_checkbox_value( $value );
		$notes_line    = '';
		$line         .= ' <p>' . $value_summary . '</p>';

		if ( ! $hide ) {

			if ( $show_more_details && ! empty( $question_array['tooltip'] ) ) {
				$line .= '<p> ! ' . $question_array['tooltip'] . '</p>';
			}

			if ( ! $note ) {
				$notes_line .= '<p> No Notes.<p>';
			} else {
				$notes_line .= '<p> Note: ' . $note . '</p>';
			}
		} else {
			$notes_line .= '<p> Question was not presented.</p>';
		}

		$line .= $notes_line . '</div>';

		return $line;
	}

	/**
	 * Get Checkbox Value
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param string $value The value of the checkbox.
	 *
	 * @return void
	 */
	public static function get_checkbox_value( $value ) {
		$value_summary = '';

		if ( empty( $value ) || is_null( $value ) || 'unset' === $value ) {
			$value_summary = 'Not reviewed or need more information.';
		} elseif ( 'no' === $value ) {
			$value_summary = 'No. No Issue Identified.';
		} else {
			$value_summary = 'Yes. Possible Issue Identified.';
		}

		return $value_summary;
	}

	/**
	 * Buils Questions Array
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param array $questions_group The questions.
	 * @param array $post            The $_POST array.
	 *
	 * @return array
	 */
	public static function build_questions_array( array $question_groups = [], array $post = [] ) {

		if ( empty( $question_groups ) || empty( $post ) ) {
			return [];
		}

		$group_id          = 0;
		$entry             = fp_get_share_link_db_entry();
		$advisor_settings  = ! empty( $entry ) ? json_decode( $entry['fields'] ) : [];
		$fields            = ! empty( $advisor_settings ) ? (array) $advisor_settings->fields : [];
		$show_more_details = ! empty( $advisor_settings ) ? $advisor_settings->show_more_details : 0;

		foreach ( $question_groups as $question_group ) {
			$question_id     = 0;
			$heading         = $question_group['heading'] ?? '';
			$questions_array = [];

			foreach ( $question_group['questions'] as $question_array ) {
				$id             = $question_array['id'] ?? '';
				$question       = $question_array['question'] ?? '';
				$tooltip        = wp_strip_all_tags( $question_array['tooltip'] );
				$value          = $post[ 'question_' . $id . '_value' ] ?? '';
				$note           = $post[ 'question_' . $id . '_note' ] ?? '';
				$field_groups   = ! empty( $fields ) && isset( $fields[ $group_id ] ) ? $fields[ $group_id ] : [];
				$field_question = ! empty( $field_groups ) && isset( $fields[ $group_id ][ $question_id ] ) ? $fields[ $group_id ][ $question_id ] : [];
				$hide           = ! empty( $field_question ) && isset( $fields[ $group_id ][ $question_id ]->show ) ? filter_var( $fields[ $group_id ][ $question_id ]->show, FILTER_VALIDATE_BOOLEAN ) : false;

				$questions_array[] = [
					'id'       => $id,
					'question' => $question,
					'tooltip'  => $tooltip,
					'value'    => $value,
					'note'     => str_replace( '\\', '', implode( '', explode( '\\', $note ) ) ),
					'hide'     => empty( $hide ) ? 0 : $hide,
				];

				$question_id++;
			}

			$questions_groups_array[] = [
				'heading'           => $heading,
				'questions'         => $questions_array,
				'show_more_details' => $show_more_details,
			];

			$group_id++;
		}

		return self::separate_yes_questions( $questions_groups_array );
	}

	/**
	 * Separate Yes Answers
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param array $questions_groups_array The questions array.
	 *
	 * @return array
	 */
	public static function separate_yes_questions( array $questions_groups_array = [] ) {
		if ( empty( $questions_groups_array ) ) {
			return [];
		}

		$yes_answers = [];
		$not_answers = [];

		foreach ( $questions_groups_array as $question_group ) {
			$yes_temp = [];
			$not_temp = [];

			foreach ( $question_group['questions'] as $question_array ) {
				if ( $question_array['value'] === 'yes' ) {
					$yes_temp[] = $question_array;
				} else {
					$not_temp[] = $question_array;
				}
			}

			if ( ! empty( $yes_temp ) ) {
				$yes_answers[] = [
					'heading'           => $question_group['heading'],
					'questions'         => $yes_temp,
					'show_more_details' => $question_group['show_more_details'],
				];
			}

			$not_answers[] = [
				'heading'           => $question_group['heading'],
				'questions'         => $not_temp,
				'show_more_details' => $question_group['show_more_details'],
			];
		}

		$questions_groups_array = [
			'yes' => $yes_answers,
			'not' => $not_answers,
		];

		return $questions_groups_array;
	}
}
