<?php
/**
 * White Labeling.
 *
 * @package    Fp_Account_Settings
 * @subpackage Fp_Account_Settings/Inlcudes/Classes/Template_Parts/Pages/Customizations
 * @author     Objectiv
 * @copyright  Copyright (c) 2021, Objectiv
 * @license    GNU General Public License v2 or later
 * @since      1.0.0
 */

namespace FpAccountSettings\Includes\Classes\TemplateParts\Pages\GroupSettings;

use FpAccountSettings\Includes\Classes\TemplateParts\Tabs\TabsAbstract;
use FP_Core\Utilities as CoreUtilities;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

/**
 * White Labeling.
 *
 * @author Objectiv
 * @since  1.0.0
 */
class WhiteLabeling extends TabsAbstract {

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
	 * @param string $slug The section slug.
	 * @param array  $args The sections args.
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
		$slug               = strtolower( str_replace( ' ', '-', $this->args['title'] ) );
		$whitelabel         = fp_get_group_whitelabel_settings();
		$this->use_advanced = ! empty( $whitelabel['use_advanced'] ) ? $whitelabel['use_advanced'] : 'false';
		$value              = $this->use_advanced === 'false' ? 'false' : 'true';

		?>
		<section class="body-section body-section__<?php echo esc_attr( $slug ); ?>">
			<?php $this->tab_blurb( 'white_labeling' ); ?>
			<form id="group-settings-pdf-settings-form" class="group-settings-pdf-preview-form" method="POST" target="" action="">
				<?php wp_nonce_field( 'save_whitelabel_settings', 'whitelabel_settings' ); ?>
				<input type="hidden" name="form-version" value="group-settings-pdf" />
				<input type="hidden" id="group-settings-pdf-settings-action" class="group-settings-pdf-settings-action" name="action" value="" />
				<input type="hidden" id="group-settings-pdf-settings-security" class="group-settings-pdf-settings-security" name="security" value="<?php echo esc_attr( wp_create_nonce( 'group-settings-pdf-6253' ) ); ?>" />
				<input type='hidden' id='group-settings-pdf-color-set-settings-selected-colors' class='group-settings-pdf-settings-selected-colors' name='group-settings-pdf-selected-colors' value='' />
				<?php
				if ( fp_is_feature_active( 'group_advanced_back_page' ) ) {
					$this->hidden_field->render(
						[
							'args'           => $this->args,
							'field'          => 'group_settings_pdf_use_advanced',
							'setting'        => 'use_advanced',
							'group_advanced' => true,
							'value'          => $value,
						]
					);
				}

				$this->resource_section_header( 'resource_page_header' );
				$this->pdf_resource_page();
				$this->back_page_section_header( 'back_page_header' );
				$this->pdf_back_page();

				if ( fp_is_feature_active( 'group_advanced_back_page' ) ) {
					$this->pdf_back_page_advanced();
				}

				$this->buttons();
				?>
			</form>
		</section>
		<?php
	}

	/**
	 * PDF Front Page
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function pdf_resource_page() {
		$transient_name = get_current_user_id() . '_group_whitelabel_resource_transient';
		$transient      = get_transient( $transient_name );
		$no_cache       = ! empty( $_GET['no-cache'] ) ? (bool) sanitize_text_field( wp_unslash( $_GET['no-cache'] ) ) : false;

		if ( ( ! empty( $transient ) && ! $no_cache ) && is_user_logged_in() ) {
			echo $transient; // phpcs:ignore
		} else {
			ob_start();
			?>
			<div id="group-resource-page-fields" class="group-resource-page-fields">
				<?php
				$this->logo_field->render(
					[
						'args'             => $this->args,
						'field'            => 'group_settings_pdf_logo',
						'setting'          => 'logo',
						'group_whitelabel' => true,
					]
				);

				$this->text_field->render(
					[
						'args'             => $this->args,
						'field'            => 'group_settings_pdf_business_display_name',
						'setting'          => 'business_display_name',
						'group_whitelabel' => true,
					]
				);

				$this->colorset_field->render(
					[
						'args'             => $this->args,
						'field'            => 'group_settings_pdf_color_set',
						'setting'          => [
							'color_set'        => 'color_set',
							'color_set_choice' => 'color_set_choice',
						],
						'group_whitelabel' => true,
					]
				);
				?>
			</div>
			<?php
			$return = ob_get_clean();

			set_transient( $transient_name, trim( $return ), 30 * DAY_IN_SECONDS );
			echo $return; // phpcs:ignore
		}
	}

	/**
	 * Pdf Back Page
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function pdf_back_page() {
		$transient_name = get_current_user_id() . '_group_whitelabel_back_page_transient';
		$transient      = get_transient( $transient_name );
		$display        = 'display: block;';

		if ( $this->use_advanced === 'true' ) {
			$display = 'display: none;';
		}

		$no_cache = ! empty( $_GET['no-cache'] ) ? (bool) sanitize_text_field( wp_unslash( $_GET['no-cache'] ) ) : false;

		if ( ( ! empty( $transient ) && ! $no_cache ) && is_user_logged_in() ) {
			echo $transient; // phpcs:ignore
		} else {
			ob_start();
			?>
			<div id="group-back-page-fields" class="group-back-page-fields" style="<?php echo esc_attr( $display ); ?>">
				<?php
				$this->text_field->render(
					[
						'args'             => $this->args,
						'field'            => 'group_settings_pdf_second_page_title',
						'setting'          => 'second_page_title',
						'group_whitelabel' => true,
					]
				);

				$this->text_field->render(
					[
						'args'             => $this->args,
						'field'            => 'group_settings_pdf_second_page_body_title',
						'setting'          => 'second_page_body_title',
						'group_whitelabel' => true,
					]
				);

				$this->wysiwyg_field->render(
					[
						'args'             => $this->args,
						'field'            => 'group_settings_pdf_second_page_body_copy',
						'setting'          => 'second_page_body_copy',
						'group_whitelabel' => true,
					]
				);

				$this->text_field->render(
					[
						'args'             => $this->args,
						'field'            => 'group_settings_pdf_job_title',
						'setting'          => 'job_title',
						'group_whitelabel' => true,
					]
				);

				$this->textarea_field->render(
					[
						'args'             => $this->args,
						'field'            => 'group_settings_pdf_address',
						'setting'          => 'address',
						'group_whitelabel' => true,
					]
				);

				$this->text_field->render(
					[
						'args'             => $this->args,
						'field'            => 'group_settings_pdf_email',
						'setting'          => 'email',
						'group_whitelabel' => true,
					]
				);

				$this->text_field->render(
					[
						'args'             => $this->args,
						'field'            => 'group_settings_pdf_phone',
						'setting'          => 'phone',
						'group_whitelabel' => true,
					]
				);

				$this->text_field->render(
					[
						'args'             => $this->args,
						'field'            => 'group_settings_pdf_website',
						'setting'          => 'website',
						'group_whitelabel' => true,
					]
				);
				?>
			</div>
			<?php
			$return = ob_get_clean();

			if ( $this->set_tranisents ) {
				set_transient( $transient_name, trim( $return ), 30 * DAY_IN_SECONDS );
			}

			echo $return; // phpcs:ignore
		}
	}

	/**
	 * Pdf Back Page Advanced
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function pdf_back_page_advanced() {
		$transient_name = get_current_user_id() . '_group_whitelabel_back_page_advanced_transient';
		$transient      = get_transient( $transient_name );
		$display        = 'display: none;';

		if ( $this->use_advanced === 'true' ) {
			$display = 'display: block;';
		}

		$no_cache = ! empty( $_GET['no-cache'] ) ? (bool) sanitize_text_field( wp_unslash( $_GET['no-cache'] ) ) : false;

		if ( ( ! empty( $transient ) && ! $no_cache ) && is_user_logged_in() ) {
			echo $transient; // phpcs:ignore
		} else {

			ob_start();
			?>
			<div id="group-advanced-back-page-fields" class="group-advanced-back-page-fields" style="<?php echo esc_attr( $display ); ?>">
			<?php
				$this->wysiwyg_field->render(
					[
						'args'           => $this->args,
						'field'          => 'group_settings_pdf_advanced_body',
						'setting'        => 'advanced_body',
						'group_advanced' => true,
					]
				);
			?>
			</div>
			<?php
			$return = ob_get_clean();

			if ( $this->set_tranisents ) {
				set_transient( $transient_name, trim( $return ), 30 * DAY_IN_SECONDS );
			}

			echo $return; // phpcs:ignore
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
	 * Resource Section Header
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param string $section The section.
	 *
	 * @return void
	 */
	public function resource_section_header( $section ) {
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
	 * Back Page Section Header
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param string $section The section.
	 *
	 * @return void
	 */
	public function back_page_section_header( $section ) {
		$section_title = ! empty( $this->args['section_slug'] ) ? $this->args[ 'sections_' . $this->args['section_slug'] ][ $section . '_title_' . $this->args['section_slug'] ] : [];
		$section_blurb = ! empty( $this->args['section_slug'] ) ? $this->args[ 'sections_' . $this->args['section_slug'] ][ $section . '_blurb_' . $this->args['section_slug'] ] : [];
		$button_text   = 'Switch to Advanced';

		if ( $this->use_advanced === 'true' ) {
			$button_text = 'Switch to Basic';
		}

		if ( ! empty( $section_title ) || ! empty( $section_blurb ) ) :
			?>
			<section class="tabs__body-section tabs__body-section-resource">
				<div class="tabs__body-section-resource-header">
					<?php if ( ! empty( $section_title ) ) : ?>
						<h3><?php echo esc_html( $section_title ); ?></h3>
						<?php
					endif;
					if ( ! empty( $section_blurb ) ) :
						echo wp_kses_post( $section_blurb );
					endif;
					?>
				</div>
				<?php if ( fp_is_feature_active( 'group_advanced_back_page' ) ) : ?>
					<div class="tabs__body-section-resource-button">
						<button id="group-advanced-button" class="group-advanced-button" style="width: 267px">
							<?php echo esc_html( $button_text ); ?>
						</button>
					</div>
				<?php endif; ?>
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
			<button id="group-settings-pdf-settings-preview" class="red-button"/>Preview Flowchart</button>
			<?php if ( is_user_logged_in() && \FP_PDF_Generator\Customization_Controller::user_can_save_white_label_settings( get_current_user_id() ) ) : ?>
				<button id="group-settings-pdf-settings-save">Save Settings</button>
			<?php else : ?>
				<button id="group-settings-pdf-settings-save" disabled="disabled">Save Settings</button>
			<?php endif; ?>
		</div>
		<?php
	}
}
