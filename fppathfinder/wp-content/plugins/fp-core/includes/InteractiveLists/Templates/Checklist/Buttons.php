<?php
/**
 * Buttons
 *
 * @package    FP_Core/
 * @subpackage FP_Core/Templates/Checklist
 * @author     Objectiv
 * @copyright  Copyright (c) 2020, Objectiv
 * @license    GPL-2.0
 * @version    1.0.0
 */

namespace FP_Core\InteractiveLists\Templates\Checklist;

use FP_Core\InteractiveLists\Utilities\CRM;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

/**
 * Buttons
 *
 * @author Jason Witt
 * @since  1.0.0
 */
class Buttons {

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
	 * @param boolean $is_hidden  If is hidden.
	 * @param string  $advisor_id The advisor ID.
	 * @param string  $disclaimer The disclaimer text.
	 * @param string $crm_contact_id The contact ID.
	 * @param string $account_id     The account ID.
	 *
	 * @return void
	 */
	public function render( bool $is_hidden = false, string $advisor_id = '', string $disclaimer = '', $crm_contact_id = '', $account_id = '' ) {
		$share_link_active_class   = fp_is_feature_active( 'share_link_options' ) && fp_is_share_link() ? ' share-link-active' : '';
		$share_link_elements_class = fp_is_feature_active( 'share_link_options' ) && fp_is_share_link() ? ' share-link-active__elements' : '';
		$share_type                = sanitize_text_field( wp_unslash( $_GET['ty'] ?? '' ) );

		$nonce = wp_create_nonce( 'share-link-nonce' );

		if ( ! $is_hidden ) :
			if ( fp_is_share_link() ) :
				?>
				<div class="interactive-resource-buttons<?php echo esc_attr( $share_link_active_class ); ?>">
					<?php if ( fp_is_feature_active( 'share_link_options' ) ) : ?>
						<button id="group_prev" class="share-link-active__elements share-link-active__prev" >Previous</button>
						<div id="pagination-steps" class="share-link-active__elements share-link-active__steps">PAGE <span id="pagination-steps-current">1</span> OF <span id="pagination-steps-total">0</span></div>
						<button id="group_next" class="share-link-active__elements share-link-active__next">Next</button>
					<?php endif; ?>

					<?php if ( CRM::has_active_crm( $advisor_id ) && $share_type === 'single' ) : ?>
						<input type="submit" name="email_note_button" value="Send to Advisor" id="submit-button" class="send-to-crm<?php echo esc_attr( $share_link_elements_class ); ?>">
					<?php else : ?>
						<input type="submit" name="email_no_crm_note_button" value="Send to Advisor" id="submit-button" class="send-to-crm">
					<?php endif; ?>

				</div>
				<?php if ( ! empty( $disclaimer ) ) : ?>
					<p class="disclaimer">
						<?php echo wp_kses_post( $disclaimer ); ?>
					</p>
				<?php endif; ?>
			<?php else : ?>
				<div class="buttons">
					<input type="submit" value="Review Your Notes" id="submit-button" class="review-notes-button" name="review-submit">
					<?php if ( fp_is_feature_active( 'share_link_options' ) ) : ?>
						<span style="margin: 0 1rem;">Or</span>
						<?php if ( ! fp_is_feature_active( 'checklists_v_two' ) ) : ?>
							<span style="margin: 0 1rem;">Or</span>
						<?php endif; ?>
						<button
							id="resource-share-link-button-last"
							class="resource-share-link__button resource-share-link__button-trigger last"
							data-nonce="<?php echo esc_attr( $nonce ); ?>"
							data-contactId="<?php echo esc_attr( $crm_contact_id ); ?>"
							data-contactName="<?php echo esc_attr( $crm_contact_id ); ?>"
							data-contactEmail="<?php echo esc_attr( $crm_contact_id ); ?>"
							data-accountId="<?php echo esc_attr( $account_id ); ?>"
							data-advisorId="<?php echo esc_attr( get_current_user_id() ); ?>"
							data-resourceId="<?php echo esc_attr( get_the_ID() ); ?>"
							data-hideMoreIcons="false"
							data-showMoreDetails="false"
							data-removeQuestions="false"
							>
							Copy Share Link URL
						</button>
					<?php endif; ?>
				</div>
				<?php
			endif;
		endif;
	}
}
