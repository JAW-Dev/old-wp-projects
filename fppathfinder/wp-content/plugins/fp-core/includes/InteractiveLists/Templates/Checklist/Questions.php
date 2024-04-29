<?php
/**
 * Questions
 *
 * @package    FP_Core/
 * @subpackage FP_Core/Templates/Checklist
 * @author     Objectiv
 * @copyright  Copyright (c) 2020, Objectiv
 * @license    GPL-2.0
 * @version    1.0.0
 */

namespace FP_Core\InteractiveLists\Templates\Checklist;

use function Patchwork\Utils\args;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

/**
 * Questions
 *
 * @author Jason Witt
 * @since  1.0.0
 */
class Questions {

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
	 * Render
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param array  $question_groups The question groups.
	 * @param string $border_color    The question group border color.
	 *
	 * @return void
	 */
	public function render( array $question_groups = [], string $border_color = '' ) {
		if ( fp_is_feature_active( 'share_link_options' ) && fp_is_share_link() ) {
			$this->client_version( $question_groups, $border_color );
		} else {
			$this->advisor_version( $question_groups, $border_color );
		}
	}

	/**
	 * Client version
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param array  $question_groups The question groups.
	 * @param string $border_color    The question group border color.
	 *
	 * @return void
	 */
	public function client_version( array $question_groups = [], string $border_color = '' ) {
		if ( empty( $question_groups ) ) {
			return;
		}

		$entry             = fp_get_share_link_db_entry();
		$advisor_settings  = ! empty( $entry ) ? json_decode( $entry['fields'] ) : [];
		$fields            = ! empty( $advisor_settings ) ? (array) $advisor_settings->fields : [];
		$hide_more_info    = ! empty( $advisor_settings ) ? $advisor_settings->hide_more_icon : 'false';
		$show_more_details = ! empty( $advisor_settings ) ? $advisor_settings->show_more_details : 'false';
		$notes             = ! empty( $advisor_settings ) ? (array) $advisor_settings->notes : [];
		$group_id          = 0;
		$question_id       = 0;

		// Remove any question groups with no questions.
		foreach ( $question_groups as $key => $value ) {
			$skip = true;

			foreach ( $fields as $fields_key => $fields_value ) {

				if ( $fields_key === $key ) {
					foreach ( $fields_value as $entry ) {
						if ( isset( $entry ) && $entry->show === 'false' ) {
							$skip = false;
							break 2;
						}
					}
				}
			}
			if ( $skip ) {
				unset( $fields[ $key ] );
				unset( $question_groups[ $key ] );
			}
		}

		// Re-index arrays.
		$question_groups = array_values( $question_groups );
		$fields          = array_values( $fields );

		// Rename the field question ids.
		if ( ! empty( $fields ) ) {
			foreach ( $fields as $field ) {
				$question_id = 0;
				foreach ( $field as $item ) {
					$item->id = substr_replace( $field[ $question_id ]->id, (string) $group_id . '_' . $question_id, 9 );
					$question_id++;
				}
				$group_id++;
			}
		}

		// Reset for render.
		$group_id    = 0;
		$question_id = 0;

		foreach ( $question_groups as $key => $value ) {
			?>
			<div id="question-group-<?php echo esc_attr( $key ); ?>" class="question-group pagination-group" style="<?php echo esc_attr( $border_color ); ?>">
				<h3 class="question-group-heading"><?php echo $value['heading']; ?></h3>
				<?php
				foreach ( $value['questions'] as $question_array ) {
					$line_id        = 'question_' . $group_id . '_' . $question_id;
					$field_groups   = ! empty( $fields ) && isset( $fields[ $group_id ] ) ? $fields[ $group_id ] : [];
					$field_question = ! empty( $field_groups ) && isset( $fields[ $group_id ][ $question_id ] ) ? $fields[ $group_id ][ $question_id ] : [];
					$hide           = ! empty( $field_question ) && isset( $fields[ $group_id ][ $question_id ]->show ) ? $fields[ $group_id ][ $question_id ]->show : 'false';

					if ( $hide === 'true' ) {
						$question_id++;
						continue;
					}

					$question_args = [
						'question_array'    => $question_array,
						'line_id'           => $line_id,
						'group_id'          => $key,
						'question_id'       => $question_id,
						'hide_more_info'    => $hide_more_info,
						'show_more_details' => $show_more_details,
						'notes'             => $notes,
					];

					$this->question_markup( $question_args );
					$question_id++;
				}
				?>
				</div>
			<?php
			$question_id = 0;
			$group_id++;
		}
	}

	/**
	 * Advisor Version
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param array  $question_groups The question groups.
	 * @param string $border_color    The question group border color.
	 *
	 * @return void
	 */
	public function advisor_version( array $question_groups = [], string $border_color = '' ) {
		if ( empty( $question_groups ) ) {
			return;
		}

		$group_id = 0;

		foreach ( $question_groups as $question_group ) {
			$question_id = 0;
			?>
			<div class="question-group" style="<?php echo esc_attr( $border_color ); ?>">
				<h3 class="question-group-heading"><?php echo $question_group['heading']; ?></h3>
				<?php
				foreach ( $question_group['questions'] as $question_array ) {
					$line_id = 'question_' . $group_id . '_' . $question_id;

					$question_args = [
						'question_array' => $question_array,
						'line_id'        => $line_id,
						'group_id'       => $group_id,
						'question_id'    => $question_id,
					];

					$this->question_markup( $question_args );
					$question_id++;
				}
				?>
			</div>
			<?php
			$group_id++;
		}
	}

	/**
	 * Question Markup
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param array $args The question arguments.
	 *
	 * @return void
	 */
	public function question_markup( array $args ) {
		$defaults = [
			'question_array'    => [],
			'line_id'           => '',
			'group_id'          => '',
			'question_id'       => '',
			'hide_more_info'    => 'false',
			'show_more_details' => 'false',
			'notes'             => [],
		];

		$args = wp_parse_args( $args, $defaults );

		$question      = ! empty( $args['question_array']['question'] ) ? $args['question_array']['question'] : '';
		$tooltip       = $args['hide_more_info'] === true ? '' : apply_filters( 'checklist_tooltip', $args['question_array']['tooltip'] );
		$id            = $args['question_array']['id'];
		$current_value = $_POST[ 'question_' . $id . '_value' ] ?? false;
		$current_note  = sanitize_textarea_field( $_POST[ 'question_' . $id . '_note' ] ?? '' );
		?>
		<div id="question-<?php echo esc_attr( $args['group_id'] . '-' . $args['question_id'] ); ?>" class="question">
			<div class="first-row">
				<label class="question__checkbox-top-label styled-checkbox" for="<?php echo esc_attr( $args['line_id'] ); ?>">
					<span class="checkbox__label-text">Hide</span>
					<input
						type="checkbox"
						class="question__checkbox"
						name="questions[][<?php echo esc_attr( 'question[' . $args['group_id'] . '][' . $args['question_id'] . ']' ); ?>]"
						id="<?php echo esc_attr( $args['line_id'] ); ?>"
						data-groupid="<?php echo esc_attr( $args['group_id'] ); ?>">
					<span class="checkmark"></span>
				</label>

				<div class="text">
					<?php echo wp_kses_post( $question ); ?>
				</div>

				<?php if ( $tooltip ) : ?>
					<div class="tooltip-button">
						<div class="icon__exclamation"></div>
						<div class="tooltip-content">
							<?php echo strip_tags( $tooltip ); ?>
						</div>
					</div>
				<?php endif; ?>

				<div class="notes-button">
					<div class="icon__note"></div>
					<div class="notes-content">
						Click to add a note.
					</div>
				</div>

				<div class="inputs">
					<input type="radio" class="checklist-question-radio-input" name="question_<?php echo $id; ?>_value" id="question_<?php echo $id; ?>_value_unset" value="unset" <?php echo ! $current_value || 'unset' === $current_value ? 'checked' : ''; ?>>
					<div class="checklist-radio-input">
						<label for="question_<?php echo $id; ?>_value_yes">Yes</label>
						<input type="radio" class="checklist-question-radio-input" name="question_<?php echo $id; ?>_value" id="question_<?php echo $id; ?>_value_yes" value="yes" <?php echo 'yes' === $current_value ? 'checked' : ''; ?>>
					</div>
					<div class="checklist-radio-input">
						<label for="question_<?php echo $id; ?>_value_no">No</label>
						<input type="radio" class="checklist-question-radio-input" name="question_<?php echo $id; ?>_value" id="question_<?php echo $id; ?>_value_no" value="no" <?php echo 'no' === $current_value ? 'checked' : ''; ?>>
					</div>
				</div>
			</div>

			<div class="second-row">
				<?php if ( $tooltip ) : ?>
					<?php if ( fp_is_share_link() ) : ?>
						<div class="tooltip-content" id="#question_<?php echo $id; ?>_tooltip" style="display: none;"><?php echo strip_tags( $tooltip ); ?></div>
					<?php else : ?>
						<div class="tooltip-content" id="#question_<?php echo $id; ?>_tooltip" style="display: none;"><?php echo $tooltip; ?></div>
					<?php endif ?>
				<?php endif; ?>
				<?php if ( ( fp_is_feature_active( 'share_link_options' ) && fp_is_share_link() ) && ! empty( $args['notes'][ 'question_' . $id . '_note' ] ) && $args['show_more_details'] === true ) : ?>
					<p><?php echo wp_kses_post( $args['notes'][ 'question_' . $id . '_note' ] ); ?></p>
				<?php endif; ?>
				<div class="notes" <?php echo $current_note ? '' : 'style="display: none;"'; ?>>
					<textarea type="text" name="question_<?php echo $id; ?>_note" id="question_<?php echo $id; ?>_note" cols="30" rows="5"><?php echo $current_note; ?></textarea>
				</div>
			</div>
		</div>
		<?php
	}
}
