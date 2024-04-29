<?php

function objectiv_gs_simplify_questions_answers( $qs = null ) {
	$r_questions = array();
	$q_count     = 1;

	if ( ! empty( $qs ) && is_array( $qs ) ) {
		foreach ( $qs as $q ) {
			$question          = obj_key_value( $q, 'question_text' );
			$options           = obj_key_value( $q, 'answer_options' );
			$quid              = 'q-' . $q_count;
			$answers           = array();
			$answer_items      = array();
			$answer_item_modes = array();

			if ( ! empty( $question ) && ! empty( $options ) && is_array( $options ) ) {
				foreach ( $options  as $key => $option ) {
					$answers[ $quid . '-' . $key ]           = $option['answer_option'];
					$answer_item_modes[ $quid . '-' . $key ] = $option['answer_display_mode'];
					$answer_items[ $quid . '-' . $key ]      = $option['answer_related_items'];
				}

				if ( ! empty( $answers ) ) {
					$r_questions[ 'question-' . $q_count ]['qid']               = $quid;
					$r_questions[ 'question-' . $q_count ]['question']          = $question;
					$r_questions[ 'question-' . $q_count ]['answer_item_modes'] = $answer_item_modes;
					$r_questions[ 'question-' . $q_count ]['answers']           = $answers;
					$r_questions[ 'question-' . $q_count ]['answer_items']      = $answer_items;
					$q_count++;
				}
			}
		}
	}

	return $r_questions;
}
