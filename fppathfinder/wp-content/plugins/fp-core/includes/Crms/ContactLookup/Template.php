<?php
/**
 * Template
 *
 * @package    FP_Core/
 * @subpackage FP_Core/Included/Crms
 * @author     Objectiv
 * @copyright  Copyright (c) 2020, Objectiv
 * @license    GPL-2.0
 * @version    1.0.0
 */

namespace FP_Core\Crms\ContactLookup;

use FP_Core\InteractiveLists\Utilities\Page;
use FP_Core\InteractiveLists\Utilities\CRM;
use FP_Core\Crms\Utilities;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

/**
 * Template
 *
 * @author Jason Witt
 * @since  1.0.0
 */
class Template {

	/**
	 * Initialize the class
	 *
	 * @author Jason Witt
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
		// Input hooks
		add_action( 'interative_resource_before', array( $this, 'render_input' ), 10, 5 );
		add_action( 'interactive_resource_notification', array( $this, 'notice' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'scripts' ) );

		// Results hooks
		add_action( 'interactive_resource_after_client_name', [ $this, 'render_linked_results' ], 10, 3 );
		add_filter( 'crm_resource_contact_id', [ $this, 'maybe_remove_contact_id' ], 99 );
		add_filter( 'interactive_checklist_client_name', [ $this, 'maybe_remove_client_name' ], 99 );
		add_filter( 'interactive_resource_is_complete', [ $this, 'maybe_make_incomplete' ] );
	}

	/**
	 * Render
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param int     $advisor_id     The advisor ID.
	 * @param int     $crm_contact_id The Contact ID.
	 * @param string  $client_name    The Contact name.
	 * @param boolean $is_hidden      If is hidden.
	 *
	 * @return void
	 */
	public function render_input( $advisor_id, $crm_contact_id, $client_name, $is_hidden, $account_id ) {
		$this->fields_markup( $advisor_id, $crm_contact_id, $client_name, $is_hidden, $account_id );
	}

	/**
	 * Notice
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function notice() {
		$link    = get_the_permalink();
		$message = 'It looks like you followed a client sharing link but you\'re logged in as an advisor. Click <a href="' . esc_url( $link ) . '">here</a> to go to the resource.';

		if ( CRM::has_active_crm() && fp_is_share_link() ) {
			?>
			<div class="interactive-resource-notification error"><?php echo $message; ?></div>
			<?php
		}
	}

	/**
	 * Fields Markup
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param int     $advisor_id     The advisor ID.
	 * @param int     $crm_contact_id The Contact ID.
	 * @param string  $client_name    The Contact name.
	 * @param boolean $is_hidden      If is hidden.
	 * @param string  $account_id     The XLR8 account ID.
	 *
	 * @return void
	 */
	public function fields_markup( $advisor_id, $crm_contact_id, $client_name, $is_hidden, $account_id ) {
		$advisor_button_text        = function_exists( 'get_field' ) ? get_field( 'fp_label_resources_checklist_path_advisor_button_text', 'option' ) : '';
		$advisor_button_description = function_exists( 'get_field' ) ? get_field( 'fp_label_resources_checklist_path_advisor_button_description', 'option' ) : '';
		$client_button_text         = function_exists( 'get_field' ) ? get_field( 'fp_label_resources_checklist_path_client_button_text', 'option' ) : '';
		$client_button_description  = function_exists( 'get_field' ) ? get_field( 'fp_label_resources_checklist_path_client_button_description', 'option' ) : '';
		$group_button_text          = function_exists( 'get_field' ) ? get_field( 'fp_label_resources_checklist_path_group_button_text', 'option' ) : '';
		$group_button_description   = function_exists( 'get_field' ) ? get_field( 'fp_label_resources_checklist_path_group_button_description', 'option' ) : '';

		$icons_dir = get_stylesheet_directory() . '/assets/icons/src';
		$info_icon = fp_get_svg( $icons_dir, 'exclamation-circle' );

		?>
		<div class="interactive-resource__client-wrap">

			<?php if ( fp_is_feature_active( 'checklists_v_two' ) && ! fp_is_share_link() ) : ?>
				<div id="advisor-path-buttons" class="advisor-path-buttons">
					<button id="checklist-control-advisor-path-button" class="checklist-control-button active">
						<?php echo esc_html( $advisor_button_text ) . $info_icon; ?>
					</button>
					<button id="checklist-control-client-path-button" class="checklist-control-button">
						<?php echo esc_html( $client_button_text ) . $info_icon; ?>
					</button>
					<div class="icon__exclamation"></div>
					<button
						id="checklist-control-group-path-button" class="checklist-control-button">
						<?php echo esc_html( $group_button_text ) . $info_icon; ?>
					</button>
					<div class="icon__exclamation"></div>
				</div>

				<div class="advisor-path-descriptions">
					<div id="checklist-control-description-advisor" class="checklist-control-description" style="display: none;">
						<?php echo wp_kses_post( $advisor_button_description ); ?>
					</div>
					<div id="checklist-control-description-client" class="checklist-control-description" style="display: none;">
						<?php echo wp_kses_post( $client_button_description ); ?>
					</div>
					<div id="checklist-control-description-group" class="checklist-control-description" style="display: none;">
						<?php echo wp_kses_post( $group_button_description ); ?>
					</div>
				</div>
			<?php endif; ?>
			<div class="interactive-resource__client-wrap" style="width: 100%">
				<div class="interactive-resource__client-name-field">

					<?php if ( CRM::has_active_crm() && is_user_logged_in() ) : ?>
						<input type="hidden" id="crm_contact_id" name="crm_contact_id" value="<?php echo esc_attr( $crm_contact_id ); ?>">
						<input type="hidden" id="account_id" name="account_id" value="<?php echo esc_attr( $account_id ); ?>">

						<div class="interactive-resource__client-form-controls">
							<p class="client-field-error error">Please enter a contact name and link the contact!</p>
							<label for="client_name">Client:</label>
							<input type="text" name="client_name" id="client-name" class="interactive-resource-form__field" value="<?php echo esc_attr( $client_name ); ?>" required>
						</div>
					<?php endif; ?>

					<?php if ( ( ! CRM::has_active_crm() && is_user_logged_in() ) || Page::is_example_interactive_list() ) : ?>
						<input type="hidden" id="crm_contact_id" name="crm_contact_id" value="<?php echo esc_attr( $crm_contact_id ); ?>">

						<div class="interactive-resource__client-form-controls">
							<p class="client-name-field-error error">Please enter your client's name!</p>
							<label for="client_name">Client Name:</label>
							<input type="text" name="client_name" id="client-name" class="interactive-resource-form__field" value="<?php echo esc_attr( $client_name ); ?>">
						</div>
					<?php endif; ?>

				</div>
				<?php do_action( 'interactive_resource_after_client_name_field', $client_name, $crm_contact_id, $is_hidden, $account_id ); ?>
			</div>
		</div>
		<?php do_action( 'interactive_resource_after_client_name', $client_name, $crm_contact_id, $is_hidden, $account_id ); ?>
		<div id="checklist-client-lookup-results" class="checklist-client-lookup-results"></div>
		<?php
	}

	/**
	 * Render Results
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param int     $crm_contact_id The Contact ID.
	 * @param string  $client_name    The Contact name.
	 * @param boolean $is_hidden      If is hidden.
	 *
	 * @return void
	 */
	public function render_linked_results( $client_name, $contact_id, $is_hidden ) {
		if ( ! $client_name || ! $contact_id || $is_hidden || fp_is_share_link() ) {
			return;
		}

		$active_crm = Utilities::get_active_crm();
		$crm        = Utilities::get_crm_info( $active_crm );
		$crm_name   = $crm['name'] ?? '';

		?>
		<div class="crm-contact-association-notification" style="margin-bottom: 1.5rem; display: flex; align-items:center;">
			<div class="text">Linked <?php echo esc_html( $crm_name ); ?> Contact - <strong><?php echo $client_name; ?> (ID: <?php echo $contact_id; ?>)</strong></div>
			<input id="hidden-client-name" type="hidden" value="<?php echo esc_attr( $client_name ); ?>" />
			<input class="crm-contact-lookup-result-button" type="submit" name="unlink_crm_contact" value="Unlink">
		</div>
		<?php
	}

	/**
	 * Scripts
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function scripts() {
		if ( ! Page::is_interactive_resource() ) {
			return;
		}

		$file     = 'src/js/contact-lookup.js';
		$file_dir = FP_CORE_DIR_PATH . $file;
		$file_uri = FP_CORE_DIR_URL . $file;
		$version  = file_exists( $file_dir ) ? filemtime( $file_dir ) : '1.0.0';
		$hook     = 'fp-resource-contact-lookup';

		wp_register_script( $hook, $file_uri, array(), $version, true );
		wp_enqueue_script( $hook );
		wp_localize_script(
			$hook,
			'contactLookupData',
			array(
				'ajax_url'   => admin_url( 'admin-ajax.php' ),
				'nonce'      => wp_create_nonce( 'resource-contact-lookup' ),
				'active_crm' => CRM::has_active_crm(),
			)
		);
	}

	/**
	 * Remove the contact
	 *
	 * @param int $contact_id The contact ID.
	 *
	 * @return int
	 */
	public function maybe_remove_contact_id( $contact_id ) {
		return isset( $_POST['unlink_crm_contact'] ) ? 0 : $contact_id;
	}

	/**
	 * Remove the Client Name
	 *
	 * @param string $name The contact name.
	 *
	 * @return string
	 */
	public function maybe_remove_client_name( $name ) {
		return isset( $_POST['unlink_crm_contact'] ) ? '' : $name;
	}

	/**
	 * Mark Incomplete
	 *
	 * @param boolean $complete If complete.
	 *
	 * @return bookean
	 */
	public function maybe_make_incomplete( $complete ) {
		return isset( $_POST['unlink_crm_contact'] ) ? false : $complete;
	}
}
