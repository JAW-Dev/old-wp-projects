<?php
/**
 * Checklist Single
 *
 * @package    FP_Core/
 * @subpackage FP_Core/Templates/Checklist
 * @author     Objectiv
 * @copyright  Copyright (c) 2020, Objectiv
 * @license    GPL-2.0
 * @version    1.0.0
 */

use FP_Core\InteractiveLists\Templates\Checklist;
use FpAccountSettings\Includes\Classes\Conditionals;

if ( is_user_logged_in() && ! Conditionals::is_premier_member_or_owner() && ! fp_is_share_link() ) {
	wp_safe_redirect( home_url(), 307 );
}

if ( ! is_user_logged_in() && ! fp_is_share_link() && ! rcp_user_can_access() ) {
	global $wp;

	$url = add_query_arg( array( 'redirect' => home_url( $_SERVER['REQUEST_URI'] ) ), home_url( 'login' ) );

	wp_safe_redirect( $url, 307 );
}

do_action( 'before_single_interactive_resource_view' );

// full width layout
add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_full_width_content' );
remove_action( 'genesis_entry_header', 'genesis_do_post_title' );

// Remove 'site-inner' from structural wrap
add_theme_support( 'genesis-structural-wraps', array( 'header', 'nav', 'subnav', 'footer-widgets', 'footer' ) );

add_action( 'objectiv_page_content', 'objectiv_single_checklist_content' );
function objectiv_single_checklist_content() { ?>
	<div class="single-resource-content-outer single-checklist-content-outer">
		<div class="wrap">
			<?php
			do_action( 'interactive_resource_notification' );
			objectiv_interactive_checklist();
			?>
		</div>
	</div>
	<?php
}

function objectiv_interactive_checklist() {
	$is_edit          = sanitize_text_field( wp_unslash( $_POST['edit_entry_button'] ?? false ) );
	$is_client_lookup = sanitize_text_field( wp_unslash( $_POST['client_lookup'] ?? false ) );
	$client_name      = sanitize_text_field( wp_unslash( $_POST['client_name'] ?? '' ) );
	$is_complete      = apply_filters( 'interactive_resource_is_complete', ( ! $is_edit && ! $is_client_lookup && $client_name ) );

	if ( ! FP_Core\InteractiveLists\Templates\ShareLink\Messages::error() ) {

		if ( fp_is_share_link() && ! $is_edit ) {
			$is_complete = false;
		}

		if ( $is_complete ) {
			objectiv_interactive_checklist_completed_view();
		} else {
			objectiv_interactive_checklist_edit_form();
		}
	}
}

function objectiv_interactive_checklist_edit_form( bool $is_hidden = false ) {
	$question_groups    = function_exists( 'get_field' ) ? get_field( 'question_groups' ) : '';
	$crm_contact_id     = apply_filters( 'crm_resource_contact_id', $_POST['crm_contact_id'] ?? $_GET['contact_id'] ?? $_GET['wbcid'] ?? 0 );
	$client_name        = apply_filters( 'interactive_checklist_client_name', sanitize_text_field( $_POST['client_name'] ?? '' ) );
	$account_id         = sanitize_text_field( $_POST['account_id'] ?? '' );
	$last_updated       = function_exists( 'get_field' ) ? get_field( 'last_updated' ) : '';
	$share_border_color = '';
	$advisor_id         = '';
	$heading_text       = '';
	$disclaimer         = '';

	if ( fp_is_share_link() ) {
		$entry                 = fp_get_share_link_db_entry();
		$advisor_user_id       = ! empty( $entry['advisor_user_id'] ) ? $entry['advisor_user_id'] : '';
		$advisor_id            = ! empty( $entry['advisor_id'] ) ? $entry['advisor_id'] : '';
		$advisor_id            = ! empty( $advisor_user_id ) ? $advisor_user_id : $advisor_id ;
		$whitelabel_settings   = fp_get_whitelabel_settings( fp_get_user_settings( $advisor_id ) );
		$share_link_settings   = fp_get_share_link_settings( fp_get_user_settings( $advisor_id ) );
		$color                 = ! empty( $whitelabel_settings['color_set']['color3'] ) ? $whitelabel_settings['color_set']['color3'] : '';
		$disclaimer            = ! empty( $share_link_settings['disclaimer'] ) ? $share_link_settings['disclaimer'] : '';
		$heading_text          = ! empty( $share_link_settings['heading_text'] ) ? $share_link_settings['heading_text'] : '';
		$share_link_disclaimer = function_exists( 'get_field' ) ? get_field( 'share_link_disclaimer', 'option' ) : '';

		// The client's info.
		if ( ! fp_is_feature_active( 'checklists_v_two' ) ) {
			$client_name    = $entry['client_name'];
			$crm_contact_id = $entry['crm_contact_id'];
		}

		if ( empty( $heading_text ) ) {
			$personalizations = function_exists( 'get_field' ) ? get_field( 'subsections_personalizations', 'option' ) : [];
			$section          = ! empty( $personalizations['sections_personalizations_share_link'] ) ? $personalizations['sections_personalizations_share_link'] : '';
			$heading_text     = ! empty( $section['share_link_header_default_text_personalizations_share_link'] ) ? $section['share_link_header_default_text_personalizations_share_link'] : '';
		}

		if ( ! empty( $color ) ) {
			$share_border_color = "border-color: $color";
		}
	}

	?>
	<form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post" id="interactive-checklist-form" <?php echo $is_hidden ? 'class="hidden"' : ''; ?>>
		<?php
		if ( ! fp_is_share_link() ) {
			do_action( 'interative_resource_before', $advisor_id, $crm_contact_id, $client_name, $is_hidden, $account_id );
		}

		if ( fp_is_feature_active( 'share_link_options' ) ) {
			( new Checklist\Options() )->render( $share_border_color, $crm_contact_id, $account_id );
		}

		( new Checklist\Header() )->render( $heading_text );

		$share_type = sanitize_text_field( wp_unslash( $_GET['ty'] ?? '' ) );
		?>
		<input type="hidden" name="share-link-type" id="share-link-type" value="<?php echo esc_attr( $share_type ); ?>">
		<?php

		if ( fp_is_feature_active( 'checklists_v_two' ) && fp_is_share_link() && $share_type === 'group' ) {
			?>
			<label for="client_name">Your Name:</label>
			<input type="text" class="interactive-resource-form__field" name="share-link-client-name" id="share-link-client-name"" required>
			<label for="client_name">Your Email:</label>
			<input type="text" class="interactive-resource-form__field" name="share-link-client-email" id="share-link-client-email"" required>
			<?php
		}

		( new Checklist\Questions() )->render( $question_groups, $share_border_color );
		( new Checklist\Buttons() )->render( $is_hidden, $advisor_id, $disclaimer, $crm_contact_id, $account_id );

		if ( ! $is_hidden ) :
			if ( ! empty( $last_updated ) ) :
				?>
				<div class="resource_updated">
					<?php echo wp_kses_post( $last_updated ); ?>
				</div>
			<?php endif; ?>
			<?php if ( FP_Core\InteractiveLists\Utilities\Page::is_shared_link() && ! empty( $share_link_disclaimer ) ) : ?>
				<div class="resource_updated">
					<?php echo wp_kses_post( $share_link_disclaimer ); ?>
				</div>
			<?php endif; ?>
		<?php endif; ?>
		<input type="hidden" name="resource_type" value="checklist" />
	</form>
	<?php
}

function objectiv_interactive_checklist_completed_view() {
	$name           = fp_get_crm_client_name();
	$completed_date = substr( current_time( 'mysql' ), 0, 10 );
	$checklist_name = get_the_title();
	$plain_text     = FP_Core\Crms\NoteCreator::build_note( true );
	$last_updated   = function_exists( 'get_field' ) ? get_field( 'last_updated' ) : '';

	objectiv_interactive_checklist_edit_form( true );

	?>
	<div class="complete-checklist">

		<?php if ( FP_Core\InteractiveLists\Utilities\CRM::has_active_crm( get_current_user_id() ) || FP_Core\InteractiveLists\Utilities\Page::is_example_interactive_list() ) : ?>
			<div class="name">Client: <?php echo esc_html( $name ); ?></div>
		<?php endif; ?>

		<div class="completion-date">Completed: <?php echo esc_html( $completed_date ); ?></div>
		<div class="checklist-name">Checklist: <?php echo esc_html( $checklist_name ); ?></div>
		<?php FP_Core\Crms\Notes\Checklist::build_html_note(); ?>

		<div class="completed-checklist-buttons">
			<div class="edit-button"><input type="submit" form="interactive-checklist-form" name="edit_entry_button" value="edit entry" id="submit-button"></div>
			<div class="print-button"><span class="button"><a href="#" id="interactive-checklist-print-button">PRINT</a></span></div>
			<div class="copy-button"><span class="button"><a href="#" id="interactive-checklist-copy-button">COPY TEXT</a></span></div>
			<?php do_action( 'interactive_resource_integration_note_button', 'interactive-checklist-form' ); ?>
		</div>
		<div id="interactive-checklist-plain-text" style="display: none;"><?php echo $plain_text; ?></div>
		<?php if ( ! empty( $last_updated ) ) : ?>
			<div class="resource_updated">
				<?php echo wp_kses_post( $last_updated ); ?>
			</div>
		<?php endif; ?>
	</div>
	<?php
}

get_header();
do_action( 'objectiv_page_content' );
get_footer();
