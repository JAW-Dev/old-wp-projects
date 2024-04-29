<?php
/**
 * Fields.
 *
 * @package    Kitces_Quizzes
 * @subpackage Kitces_Quizzes/Inlcudes/Classes
 * @author     Objectiv
 * @copyright  Copyright (c) 2022, Objectiv
 * @license    GNU General Public License v2 or later
 * @since      1.0.0
 */

namespace KitcesQuizzes\Includes\Classes;

use KitcesQuizzes\Includes\Classes\Tables\Query;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

if ( ! class_exists( __NAMESPACE__ . '\\Fields' ) ) {

	/**
	 * Fields.
	 *
	 * @author Objectiv
	 * @since  1.0.0
	 */
	class Fields {

		/**
		 * Initialize the class
		 *
		 * @author Objectiv
		 * @since  1.0.0
		 *
		 * @return void
		 */
		public function __construct() {
			$this->hooks();
		}

		/**
		 * Hooks
		 *
		 * @author Jason Witt
		 * @since  1.0.0
		 *
		 * @return void
		 */
		public function hooks() {
			add_filter( 'gform_pre_render', array( $this, 'quiz_timer_field_pre_render' ), 10 );
			add_filter( 'gform_field_content', array( $this, 'quiz_timer_field_content' ), 10, 2 );
			add_action( 'gform_pre_submission', array( $this, 'quiz_timer_pre_submission' ), 10 );
		}

		/**
		 * Quiz Timer Field Pre Render
		 *
		 * @author Jason Witt
		 * @since  1.0.0
		 *
		 * @param string $form The current form object to be filtered.
		 *
		 * @return bool
		 */
		public function quiz_timer_field_pre_render( $form ) {
			$is_quiz = function_exists( 'kitces_is_quiz_page' ) ? kitces_is_quiz_page() : '';

			if ( ! $is_quiz ) {
				return $form;
			}

			$form         = \GFAPI::get_form( $form['id'] );
			$new_field_id = 0;

			foreach ( $form['fields'] as $field ) {
				if ( $field->name === 'quiz_time_start' ) {
					return $form;
				}

				if ( $field->id > $new_field_id ) {
					$new_field_id = $field->id;
				}
			}

			$new_field_id++;

			$properties['type'] = 'hidden';
			$properties['name'] = 'quiz_time_start';

			$field            = \GF_Fields::create( $properties );
			$field->id        = $new_field_id;
			$form['fields'][] = $field;

			\GFAPI::update_form( $form );

			return $form;
		}

		/**
		 * Quiz Timer Field Content
		 * Populate quiz input field value.
		 *
		 * @author Jason Witt
		 * @since  1.0.0
		 *
		 * @param string $content The field content to be filtered.
		 * @param object $field   The field that this input tag applies to.
		 *
		 * @return string
		 */
		public function quiz_timer_field_content( $content, $field ) {
			if ( $field->type === 'hidden' && $field->name === 'quiz_time_start' ) {
				$date_time = new \DateTime();
				$content   = str_replace( "value=''", "value='" . $date_time->format( 'Y-m-d H:i:s' ) . "'", $content );
			}

			return $content;
		}

		/**
		 * Pre Submission Quiz Time
		 *
		 * @author Jason Witt
		 * @since  1.0.0
		 *
		 * @return void
		 */
		public function quiz_timer_pre_submission( $form ) {
			$entry = $_POST;

			if ( empty( $entry ) ) {
				return;
			}

			global $post;

			$quiz_time_field_id = '';

			foreach ( $form['fields'] as $field ) {
				if ( $field->name === 'quiz_time_start' ) {
					$quiz_time_field_id = 'input_' . $field->id;
				}
			}

			$quiz_time_start = new \DateTime( $entry[ $quiz_time_field_id ] );
			$date_time       = new \DateTime();
			$quiz_time_end   = $date_time->format( 'Y-m-d H:i:s' );
			$difference      = $quiz_time_start->diff( new \DateTime( $quiz_time_end ) )->format( '%H:%I:%S' );

			$form = \GFAPI::get_form( $entry['gform_submit'] );

			$array = array(
				'post_id'      => $post->ID,
				'quiz_id'      => $entry['gform_submit'],
				'user_id'      => get_current_user_id(),
				'time_started' => $quiz_time_start->format( 'Y-m-d H:i:s' ),
				'time_ended'   => $quiz_time_end,
				'time_total'   => $difference,
			);

			( new Query() )->add( $array );
		}
	}
}
