<?php
/**
 * Share Link.
 *
 * @package    Fp_Account_Settings
 * @subpackage Fp_Account_Settings/Inlcudes/Classes/Template_Parts/Pages/GroupSettings
 * @author     Objectiv
 * @copyright  Copyright (c) 2021, Objectiv
 * @license    GNU General Public License v2 or later
 * @since      1.0.0
 */

namespace FpAccountSettings\Includes\Classes\TemplateParts\Pages\GroupSettings;

use FpAccountSettings\Includes\Classes\TemplateParts\Tabs\TabsAbstract;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

/**
 * Share Link.
 *
 * @author Objectiv
 * @since  1.0.0
 */
class ShareLink extends TabsAbstract {

	/**
	 * Args
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @var array
	 */
	protected $args;

	/**
	 * Initialize the class
	 *
	 * @author Objectiv
	 * @since  1.0.0
	 *
	 * @param string $slug The tab slug.
	 * @param array  $args The args.
	 *
	 * @return void
	 */
	public function __construct( $slug, $args = [] ) {
		parent::__construct( $slug, $args );
	}

	/**
	 * Render Content
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function render_content() {
		$this->tab_blurb( 'share_link' );
		?>
		<form id="group-settings-share-link-settings-form" class="group-settings-share-link-preview-form" method="POST" target="" action="">
			<?php wp_nonce_field( 'group_settings_save_share_link_settings', 'group_settings_share_link_settings' ); ?>
			<input type="hidden" id="group-settings-share-link-settings-action" class="share-link-settings-action" name="action" value="" />
			<input type="hidden" id="group-settings-share-link-settings-security" class="share-link-settings-security" name="security" value="<?php echo esc_attr( wp_create_nonce( 'pdf-generator-6253' ) ); ?>" />
			<?php
			$this->fields();
			$this->buttons();
			?>
		</form>
		<?php
	}

	/**
	 * Fields
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function fields() {
		$this->section_header( 'share_link_header' );

		$transient_name = get_current_user_id() . '_group_share_link_transient';
		$transient      = get_transient( $transient_name );
		$no_cache       = ! empty( $_GET['no-cache'] ) ? (bool) sanitize_text_field( wp_unslash( $_GET['no-cache'] ) ) : false;

		if ( ( ! empty( $transient ) && ! $no_cache ) && is_user_logged_in() ) {
			echo $transient;
		} else {
			ob_start();

			$personalizations = function_exists( 'get_field' ) ? get_field( 'subsections_personalizations', 'option' ) : [];
			$section          = ! empty( $personalizations['sections_personalizations_share_link'] ) ? $personalizations['sections_personalizations_share_link'] : '';
			$heading_text     = ! empty( $section['share_link_header_default_text_personalizations_share_link'] ) ? $section['share_link_header_default_text_personalizations_share_link'] : '';

			$this->wysiwyg_field->render(
				[
					'args'             => $this->args,
					'field'            => 'group_settings_heading_text',
					'setting'          => 'heading_text',
					'group_share_link' => true,
					'default'          => $heading_text,
				]
			);

			$this->wysiwyg_field->render(
				[
					'args'             => $this->args,
					'field'            => 'group_settings_disclaimer',
					'setting'          => 'disclaimer',
					'group_share_link' => true,
				]
			);

			$this->text_field->render(
				[
					'args'             => $this->args,
					'field'            => 'group_settings_share_link_email',
					'setting'          => 'share_link_email',
					'group_share_link' => true,
				]
			);

			$this->text_field->render(
				[
					'args'             => $this->args,
					'field'            => 'group_settings_share_link_phone',
					'setting'          => 'share_link_phone',
					'group_share_link' => true,
				]
			);
			$return = ob_get_clean();

			set_transient( $transient_name, trim( $return ), 30 * DAY_IN_SECONDS );
			echo $return;
		}
	}

	/**
	 * Tab Blurb
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param string $section The section.
	 *
	 * @return void
	 */
	public function tab_blurb( $section ) {
		$section_blurb = ! empty( $this->args['section_slug'] ) ? $this->args[ 'sections_' . $this->args['section_slug'] ][ $section . '_blurb_' . $this->args['section_slug'] ] : [];

		if ( ! empty( $section_blurb ) ) :
			?>
			<section class="tabs__body-section">
				<?php
				if ( ! empty( $section_blurb ) ) :
					echo wp_kses_post( $section_blurb );
				endif;
				?>
			</section>
			<?php
		endif;
	}

	/**
	 * Section Header
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param string $section The section.
	 *
	 * @return void
	 */
	public function section_header( $section ) {
		$section_title = ! empty( $this->args['section_slug'] ) ? $this->args[ 'sections_' . $this->args['section_slug'] ][ $section . '_title_' . $this->args['section_slug'] ] : [];
		$section_blurb = ! empty( $this->args['section_slug'] ) ? $this->args[ 'sections_' . $this->args['section_slug'] ][ $section . '_blurb_' . $this->args['section_slug'] ] : [];

		if ( ! empty( $section_title ) || ! empty( $section_blurb ) ) :
			?>
			<section class="tabs__body-section">
				<?php if ( ! empty( $section_title ) ) : ?>
					<h3><?php echo esc_html( $section_title ); ?></h3>
					<?php
				endif;
				if ( ! empty( $section_blurb ) ) :
					echo wp_kses_post( $section_blurb );
				endif;
				?>
			</section>
			<?php
		endif;
	}

	/**
	 * Buttons
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function buttons() {
		?>
		<div class="account-settings__buttons">
		<?php if ( is_user_logged_in() && \FP_PDF_Generator\Customization_Controller::user_can_save_white_label_settings( get_current_user_id() ) ) : ?>
				<button id="group-settings-share-link-settings-save">Save Settings</button>
			<?php else : ?>
				<button id="group-settings-share-link-settings-save" disabled="disabled">Save Settings</button>
			<?php endif; ?>
		</div>
		<?php
	}
}
