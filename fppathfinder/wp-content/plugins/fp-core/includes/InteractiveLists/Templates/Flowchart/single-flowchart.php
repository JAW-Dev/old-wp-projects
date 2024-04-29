<?php
/**
 * Single Flowchart
 *
 * @package    fpPathfinder
 * @author     Objectiv
 * @copyright  Copyright (c) 2020, Objectiv
 * @license    GPL-2.0
 */
if ( ! is_user_logged_in() && ! fp_is_share_link() && ! rcp_user_can_access() ) {
	global $wp;

	$url = add_query_arg( array( 'redirect' => home_url( $_SERVER['REQUEST_URI'] ) ), home_url( 'login' ) );

	wp_safe_redirect( $url, 307 );
}

if ( ! fp_is_feature_active( 'flowcharts' ) ) {
	wp_safe_redirect( home_url(), 307 );
}

do_action( 'before_single_interactive_resource_view' );

// full width layout.
add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_full_width_content' );
remove_action( 'genesis_entry_header', 'genesis_do_post_title' );

// Remove 'site-inner' from structural wrap.
add_theme_support( 'genesis-structural-wraps', array( 'header', 'nav', 'subnav', 'footer-widgets', 'footer' ) );

/**
 * Content Wrap
 */
function single_resourse_content_wrap() { ?>
	<div class="single-resource-content-outer single-flowchart-content-outer">
		<div class="wrap">
			<?php
			do_action( 'interactive_resource_notification' );
			interactive_flowchart();
			?>
		</div>
	</div>
	<?php
}
add_action( 'objectiv_page_content', 'single_resourse_content_wrap' );

/**
 * Flowchart
 */
function interactive_flowchart() {
	$is_edit          = sanitize_text_field( wp_unslash( $_POST['edit_entry_button'] ?? false ) );
	$is_client_lookup = sanitize_text_field( wp_unslash( $_POST['client_lookup'] ?? false ) );
	$client_name      = sanitize_text_field( wp_unslash( $_POST['client_name'] ?? '' ) );
	$is_complete      = apply_filters( 'interactive_resource_is_complete', ( ! $is_edit && ! $is_client_lookup && $client_name ) );

	if ( FP_Core\InteractiveLists\Utilities\Page::is_shared_link_post() && ! $is_edit ) {
		$is_complete = true;
	}

	if ( $is_complete ) {
		flowchart_completed_view();
	} else {
		flowchart_edit_view();
	}
}

/**
 * Edit View
 *
 * @param boolean $is_hidden If the view should be shown.
 */
function flowchart_edit_view( $is_hidden = false ) {
	$crm_contact_id      = apply_filters( 'crm_resource_contact_id', $_POST['crm_contact_id'] ?? $_GET['contact_id'] ?? $_GET['wbcid'] ?? 0 );
	$client_name         = apply_filters( 'interactive_checklist_client_name', sanitize_text_field( $_POST['client_name'] ?? '' ) );
	$account_id          = sanitize_text_field( $_POST['account_id'] ?? '' );
	$additional_comments = $_POST['additional-comments'] ?? '';
	$share_border_color  = '';
	$advisor_id          = '';
	$last_updated        = function_exists( 'get_field' ) ? get_field( 'last_updated' ) : '';

	if ( fp_is_share_link() ) {
		global $wpdb;
		$share_key    = sanitize_text_field( wp_unslash( $_GET['sh'] ?? '' ) );
		$table        = FP_Core\InteractiveLists\Tables\LinkShare::get_resource_share_link_table_name();
		$entry        = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $table WHERE share_key = %s", $share_key ), ARRAY_A ); // phpcs:ignore
		$advisor_id   = $entry['advisor_user_id'] ?? '';
		$settings     = get_user_meta( $advisor_id, 'pdf-generator-settings', true );
		$color        = $settings['colorSet']['color3'] ?? '';
		$disclaimer   = $settings['shareLinkInfo']['disclaimer'] ?? '';
		$heading_text = $settings['shareLinkInfo']['headingText'] ?? '';

		// The client's info.
		$client_name    = $entry['client_name'];
		$crm_contact_id = $entry['crm_contact_id'];

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
	<form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post" id="interactive-flowchart-form" <?php echo $is_hidden ? 'class="hidden"' : ''; ?>>
		<?php if ( fp_is_share_link() ) : ?>
			<div class="interactive-resource__share-message">
				<p><?php echo $heading_text; ?></p>
			</div>
		<?php endif; ?>
		<?php do_action( 'interative_resource_before', $advisor_id, $crm_contact_id, $client_name, $is_hidden, $account_id ); ?>

		<div id="interactive-flowchart-container" style="<?php echo esc_attr( $share_border_color ); ?>"></div>

		<div class="question-group accordion-row">
			<div class="accordion-row-header">
				<h3 class="ac-row-title question-group-heading">Additional Comments</h3>
				<div class="ac-row-toggle"></div>
			</div>
			<div class="accordion-row-content" style="margin-top: 1rem">
				<textarea rows="10" name="additional-comments"><?php echo esc_html( $additional_comments ); ?></textarea>
			</div>
		</div>
		<?php if ( ! $is_hidden ) : ?>
			<?php if ( fp_is_share_link() ) : ?>
				<div class="send-to-advisor-button">
					<?php
					$share_type = sanitize_text_field( wp_unslash( $_GET['ty'] ?? '' ) );
					if ( FP_Core\InteractiveLists\Utilities\CRM::has_active_crm( $advisor_id ) && $share_type === 'single' ) :
						?>
						<input type="submit" name="email_note_button" value="Send to Advisor" id="submit-button" class="send-to-crm">
					<?php else : ?>
						<input type="submit" name="email_no_crm_note_button" value="Send to Advisor" id="submit-button" class="send-to-crm">
					<?php endif; ?>
				</div>
				<p class="disclaimer"><?php echo wp_kses_post( $disclaimer ); ?></p>
			<?php else : ?>
				<div class="buttons">
					<input type="submit" value="Review" id="submit-button" name="review-submit">
				</div>
			<?php endif; ?>
			<?php if ( ! empty( $last_updated ) ) : ?>
				<div class="resource_updated">
					<?php echo wp_kses_post( $last_updated ); ?>
				</div>
			<?php endif; ?>
		<?php endif; ?>
		<input type="hidden" name="resource_type" value="flowchart" />
	</form>
	<?php
}

/**
 * Completed View
 */
function flowchart_completed_view( $is_hidden = false ) {
	$name                = FP_Core\InteractiveLists\Utilities\CRM::get_client_name();
	$completed_date      = substr( current_time( 'mysql' ), 0, 10 );
	$checklist_name      = get_the_title();
	$questions           = $_POST['chart_data']['questions'];
	$result              = $_POST['chart_data']['result'];
	$plain_text          = FP_Core\Crms\NoteCreator::build_note();
	$additional_comments = $_POST['additional-comments'];
	$last_updated        = function_exists( 'get_field' ) ? get_field( 'last_updated' ) : '';


	flowchart_edit_view( true );

	?>
	<div class="complete-checklist">

		<?php if ( ! FP_Core\InteractiveLists\Utilities\CRM::has_active_crm( get_current_user_id() ) || FP_Core\InteractiveLists\Utilities\Page::is_example_interactive_list()  ) : ?>
			<div class="name">Client: <?php echo esc_html( $name ); ?></div>
		<?php else : ?>
			<div class="name">Client: <?php echo esc_html( $name ); ?></div>
		<?php endif; ?>

		<div class="completion-date">Completed: <?php echo esc_html( $completed_date ); ?></div>
		<div class="checklist-name">Checklist: <?php echo esc_html( $checklist_name ); ?></div>
		<?php FP_Core\Crms\Notes\Flowchart::build_html_note(); ?>
		<div class="completed-checklist-buttons">
			<div class="edit-button"><input type="submit" form="interactive-flowchart-form" name="edit_entry_button" value="edit entry" id="submit-button"></div>
			<div class="print-button"><span class="button"><a href="#" id="interactive-checklist-print-button">PRINT</a></span></div>
			<div class="copy-button"><span class="button"><a href="#" id="interactive-checklist-copy-button">COPY TEXT</a></span></div>
			<?php do_action( 'interactive_resource_integration_note_button', 'interactive-flowchart-form' ); ?>
		</div>
		<?php if ( ! empty( $last_updated ) ) : ?>
			<div class="resource_updated">
				<?php echo wp_kses_post( $last_updated ); ?>
			</div>
		<?php endif; ?>
		<div id="interactive-checklist-plain-text" style="display: none;"><?php echo $plain_text; ?></div>
	</div>
	<?php
}

/**
 * Scripts
 */
function scripts() {
	global $wpdb;

	$flowchart    = get_field( 'fp_flowcharts_nodes' );
	$share_key    = sanitize_text_field( wp_unslash( $_GET['sh'] ?? '' ) );
	$table        = FP_Core\InteractiveLists\Tables\LinkShare::get_resource_share_link_table_name();
	$entry        = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $table WHERE share_key = %s", $share_key ), ARRAY_A ); // phpcs:ignore
	$user_id      = $entry['advisor_user_id'] ?? '';
	$pdf_settings = get_user_meta( $user_id, 'pdf-generator-settings', true );
	$colors       = $pdf_settings['colorSet'] ?? array(
		'color1' => '#0f1e2c',
		'color2' => '#9e251f',
		'color3' => '#596924',
		'color4' => '#cea232',
	);

	$data = array(
		'chartData' => $flowchart,
		'colors'    => $colors,
		'post'      => $_POST ?? new \stdClass(),
	);

	wp_localize_script(
		'fp-flowchart-vue',
		'flowchartData',
		$data
	);
}
add_action( 'wp_enqueue_scripts', 'scripts' );


get_header();
do_action( 'objectiv_page_content' );
get_footer();
