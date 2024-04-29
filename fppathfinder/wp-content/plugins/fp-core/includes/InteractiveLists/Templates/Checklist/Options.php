<?php
/**
 * Options
 *
 * @package    FP_Core/
 * @subpackage FP_Core/Templates/Checklist
 * @author     Objectiv
 * @copyright  Copyright (c) 2020, Objectiv
 * @license    GPL-2.0
 * @version    1.0.0
 */

namespace FP_Core\InteractiveLists\Templates\Checklist;

use FP_Core\InteractiveLists\Utilities\Page;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

/**
 * Options
 *
 * @author Jason Witt
 * @since  1.0.0
 */
class Options {

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
	 * @param string $border_color   The question group border color.
	 * @param string $crm_contact_id The contact ID.
	 * @param string $account_id     The account ID.
	 *
	 * @return void
	 */
	public function render( string $border_color = '', string $crm_contact_id = '', string $account_id = '' ) {
		$nonce = wp_create_nonce( 'share-link-nonce' );

		$post_url   = function_exists( 'get_field' ) ? get_field( 'fp_share_link_instruction_post', 'option' ) : '';
		$post_title = function_exists( 'get_field' ) ? get_field( 'fp_share_link_instruction_post_title', 'option' ) : '';

		$tag_replacements       = $this->tags();
		$hide_more_title_option = function_exists( 'get_field' ) ? get_field( 'fp_share_link_hide_more_info_text', 'option' ) : '';
		$hide_more_title        = ! empty( $hide_more_title_option ) ? fp_custom_text_tags( $hide_more_title_option['title'], $tag_replacements ) : '';
		$hide_more_blurb        = ! empty( $hide_more_title_option ) ? $hide_more_title_option['blurb'] : '';
		$add_more_title_option  = function_exists( 'get_field' ) ? get_field( 'fp_share_link_add_more_info_text', 'option' ) : '';
		$add_more_title         = ! empty( $add_more_title_option ) ? fp_custom_text_tags( $add_more_title_option['title'], $tag_replacements ) : '';
		$add_more_blurb         = ! empty( $add_more_title_option ) ? $add_more_title_option['blurb'] : '';
		$remove_question_option = function_exists( 'get_field' ) ? get_field( 'fp_share_link_remove_questions_text', 'option' ) : '';
		$remove_question_title  = ! empty( $remove_question_option ) ? fp_custom_text_tags( $remove_question_option['title'], $tag_replacements ) : '';
		$remove_question_blurb  = ! empty( $remove_question_option ) ? $remove_question_option['blurb'] : '';


		$nonce             = wp_create_nonce( 'share-link-nonce' );
		$is_options_active = fp_is_feature_active( 'share_link_options' );
		$current_user_id   = get_current_user_id();
		$post_id           = get_the_id();
		$button_data_attrs = " data-nonce='$nonce' data-contactId='$crm_contact_id' data-accountId='$account_id' data-advisorId='$current_user_id' data-resourceId='$post_id'";

		?>
		<div id="share-link-options" class="question-group" style="display: none; <?php echo esc_attr( $border_color ); ?>">

			<?php if ( $is_options_active ) : ?>
				<div style="margin-bottom: 1.5rem">
					<button
						id="resource-share-link-button-first"
						class="resource-share-link__button resource-share-link__button-trigger first"
						data-nonce="<?php echo esc_attr( $nonce ); ?>"
						data-contactId="<?php echo esc_attr( $crm_contact_id ); ?>"
						data-accountId="<?php echo esc_attr( $account_id ); ?>"
						data-advisorId="<?php echo esc_attr( get_current_user_id() ); ?>"
						data-resourceId="<?php echo esc_attr( get_the_ID() ); ?>"
						data-hideMoreIcons="false"
						data-showMoreDetails="false"
						data-removeQuestions="false"
						>Copy Share Link URL
					</button>
				</div>
			<?php endif; ?>

			<h6 style="display: inline-block; margin-bottom: 1rem; font-weight: bold">Share Link Options</h6>
			<div class="styled-switches form-control">
				<label for="checklist-hide-more-icons">
					<input type="checkbox" id="checklist-hide-more-icons" name="checklist-hide-more-icons">
					<div class="switch"></div>
					<div class="label-text">
						<h4><?php echo $hide_more_title; ?></h4>
						<p><?php echo wp_kses_post( $hide_more_blurb ); ?></p>
					</div>
				</label>
			</div>

			<div class="styled-switches form-control">
				<label for="checklist-show-more-details">
					<input type="checkbox" id="checklist-show-more-details" name="checklist-show-more-details">
					<div class="switch"></div>
					<div class="label-text">
						<h4><?php echo $add_more_title; ?></h4>
						<p><?php echo wp_kses_post( $add_more_blurb ); ?></p>
					</div>
				</label>
			</div>

			<div class="styled-switches form-control">
				<label for="checklist-remove-questions">
					<input type="checkbox" id="checklist-remove-questions" name="checklist-remove-questions">
					<div class="switch"></div>
					<div class="label-text">
						<h4><?php echo $remove_question_title; ?></h4>
						<p><?php echo wp_kses_post( $remove_question_blurb ); ?></p>
					</div>
				</label>
			</div>

			<?php if ( ! empty( $post_url ) || ! empty( $post_title ) ) : ?>
				<div class="styled-switches form-control">
					<p><a href="<?php echo esc_url( $post_url ); ?>" target="_blank"><?php echo esc_html( $post_title ); ?></a></p>
				</div>
			<?php endif; ?>
			</div>
		<?php
	}

	/**
	 * Tags
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function tags() {
		$icons_dir = get_stylesheet_directory() . '/assets/icons/src';

		return [
			'{more_info_icon}' => '<span style="display: inline-block; width: 18px;">' . fp_get_svg( $icons_dir, 'exclamation-circle' ) . '</span>',
			'{note_icon}'      => '<span style="display: inline-block; width: 18px; margin-right: 3px;">' . fp_get_svg( $icons_dir, 'note' ) . '</span>',
		];
	}
}
