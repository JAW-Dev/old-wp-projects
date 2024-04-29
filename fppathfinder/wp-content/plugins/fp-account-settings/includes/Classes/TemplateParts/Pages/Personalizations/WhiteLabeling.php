<?php
/**
 * White Labeling.
 *
 * @package    Fp_Account_Settings
 * @subpackage Fp_Account_Settings/Inlcudes/Classes/Template_Parts/Pages/Personalizations
 * @author     Objectiv
 * @copyright  Copyright (c) 2021, Objectiv
 * @license    GNU General Public License v2 or later
 * @since      1.0.0
 */

namespace FpAccountSettings\Includes\Classes\TemplateParts\Pages\Personalizations;

use FpAccountSettings\Includes\Classes\TemplateParts\Tabs\TabsAbstract;
use FpAccountSettings\Includes\Classes\TemplateParts\Fields;
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
	 * Use Advanced
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @var string
	 */
	protected $use_advanced;

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
		$user_settings      = fp_get_user_settings();
		$this->use_advanced = ! empty( $user_settings['whitelabel']['use_advanced'] ) ? $user_settings['whitelabel']['use_advanced'] : 'false';
		$value              = 'false';

		if ( $this->use_advanced === 'true' ) {
			$value = 'true';
		}

		$this->tab_blurb( 'white_labeling' );
		?>
		<form id="pdf-generator-settings-form" class="pdf-generator-preview-form" method="POST" target="" action="">
			<?php wp_nonce_field( 'save_whitelabel_settings', 'whitelabel_settings' ); ?>
			<input type="hidden" name="form-version" value="pdf-generator" />
			<input type="hidden" id="pdf-generator-settings-action" class="pdf-generator-settings-action" name="action" value="" />
			<input type="hidden" id="pdf-generator-settings-security" class="pdf-generator-settings-security" name="security" value="<?php echo esc_attr( wp_create_nonce( 'pdf-generator-6253' ) ); ?>" />
			<input type='hidden' id='pdf-generator-color-set-settings-selected-colors' class='pdf-generator-settings-selected-colors' name='pdf-generator-selected-colors' value='' />
			<?php
			if ( fp_is_feature_active( 'individual_advanced_back_page' ) ) {
				$this->hidden_field->render(
					[
						'args'     => $this->args,
						'field'    => 'pdf_generator_use_advanced',
						'setting'  => 'use_advanced',
						'advanced' => true,
						'value'    => $value,
					]
				);
			}

			$this->resource_section_header( 'resource_page_header' );
			$this->pdf_resource_page();
			$this->back_page_section_header( 'back_page_header' );
			$this->pdf_back_page();

			if ( fp_is_feature_active( 'individual_advanced_back_page' ) ) {
				$this->pdf_back_page_advanced();
			}

			$this->buttons();
			?>
		</form>
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
		$transient_name = get_current_user_id() . '_whitelabel_resource_transient';
		$transient      = get_transient( $transient_name );
		$no_cache       = ! empty( $_GET['no-cache'] ) ? (bool) sanitize_text_field( wp_unslash( $_GET['no-cache'] ) ) : false;

		if ( ( ! empty( $transient ) && ! $no_cache ) && is_user_logged_in() ) {
			echo $transient; // phpcs:ignore
		} else {

			ob_start();
			?>
			<div id="resource-page-fields" class="resource-page-fields">
			<?php
				$this->logo_field->render(
					[
						'args'       => $this->args,
						'field'      => 'pdf_generator_logo',
						'setting'    => 'logo',
						'whitelabel' => true,
					]
				);

				$this->text_field->render(
					[
						'args'       => $this->args,
						'field'      => 'pdf_generator_business_display_name',
						'setting'    => 'business_display_name',
						'whitelabel' => true,
					]
				);

				$this->hidden_field->render(
					[
						'args'       => $this->args,
						'field'      => 'pdf_generator_advisor_name',
						'setting'    => 'advisor_name',
						'value'      => fp_get_advisor_name(),
						'whitelabel' => true,
					]
				);

				$this->colorset_field->render(
					[
						'args'       => $this->args,
						'field'      => 'pdf_generator_color_set',
						'setting'    => [
							'color_set'        => 'color_set',
							'color_set_choice' => 'color_set_choice',
						],
						'whitelabel' => true,
					]
				);

				$fields = [
					'logo',
					'business_display_name',
					'advisor_name',
					'color_set',
				];

				echo wp_kses_post( $this->maybe_show_hidden_fields_message( $fields ) );
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
	 * Pdf Back Page
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function pdf_back_page() {
		$transient_name = get_current_user_id() . '_whitelabel_back_page_transient';
		$transient      = get_transient( $transient_name );
		$display        = 'display: block;';

		if ( $this->use_advanced === 'true' ) {
			$display = 'display: none';
		}

		$no_cache = ! empty( $_GET['no-cache'] ) ? (bool) sanitize_text_field( wp_unslash( $_GET['no-cache'] ) ) : false;

		if ( ( ! empty( $transient ) && ! $no_cache ) && is_user_logged_in() ) {
			echo $transient; // phpcs:ignore
		} else {

			ob_start();
			?>
			<div id="back-page-fields" class="back-page-fields" style="<?php echo esc_attr( $display ); ?>">
			<?php
				$this->text_field->render(
					[
						'args'       => $this->args,
						'field'      => 'pdf_generator_second_page_title',
						'setting'    => 'second_page_title',
						'whitelabel' => true,
						'advanced'   => true,
					]
				);

				$this->text_field->render(
					[
						'args'       => $this->args,
						'field'      => 'pdf_generator_second_page_body_title',
						'setting'    => 'second_page_body_title',
						'whitelabel' => true,
					]
				);

				$this->wysiwyg_field->render(
					[
						'args'       => $this->args,
						'field'      => 'pdf_generator_second_page_body_copy',
						'setting'    => 'second_page_body_copy',
						'whitelabel' => true,
						'advanced'   => true,
					]
				);

				$this->text_field->render(
					[
						'args'       => $this->args,
						'field'      => 'pdf_generator_job_title',
						'setting'    => 'job_title',
						'whitelabel' => true,
					]
				);

				$this->textarea_field->render(
					[
						'args'       => $this->args,
						'field'      => 'pdf_generator_address',
						'setting'    => 'address',
						'whitelabel' => true,
					]
				);

				$this->text_field->render(
					[
						'args'       => $this->args,
						'field'      => 'pdf_generator_email',
						'setting'    => 'email',
						'whitelabel' => true,
					]
				);

				$this->text_field->render(
					[
						'args'       => $this->args,
						'field'      => 'pdf_generator_phone',
						'setting'    => 'phone',
						'whitelabel' => true,
					]
				);

				$this->text_field->render(
					[
						'args'       => $this->args,
						'field'      => 'pdf_generator_website',
						'setting'    => 'website',
						'whitelabel' => true,
					]
				);

				$fields = [
					'second_page_title',
					'second_page_body_title',
					'second_page_body_copy',
					'job_title',
					'address',
					'email',
					'phone',
					'website',
				];

				echo wp_kses_post( $this->maybe_show_hidden_fields_message( $fields ) );

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
		$transient_name = get_current_user_id() . '_whitelabel_back_page_advanced_transient';
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
			<div id="advanced-back-page-fields" class="advanced-back-page-fields" style="<?php echo esc_attr( $display ); ?>">
			<?php
				$this->wysiwyg_field->render(
					[
						'args'     => $this->args,
						'field'    => 'pdf_generator_second_page_advanced_body',
						'setting'  => 'advanced_body',
						'advanced' => true,
					]
				);

				$fields = [ 'advanced_body' ];

				echo wp_kses_post( $this->maybe_show_hidden_fields_message( $fields ) );
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
				<?php if ( fp_is_feature_active( 'individual_advanced_back_page' ) ) : ?>
					<div class="tabs__body-section-resource-button">
						<button id="advanced-button" class="advanced-button" style="width: 267px">
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
			<button id="pdf-generator-settings-preview" class="red-button"/>Preview Flowchart</button>
			<?php if ( is_user_logged_in() && \FP_PDF_Generator\Customization_Controller::user_can_save_white_label_settings( get_current_user_id() ) ) : ?>
				<button id="pdf-generator-settings-save">Save Settings</button>
			<?php else : ?>
				<button id="pdf-generator-settings-save" disabled="disabled">Save Settings</button>
			<?php endif; ?>
		</div>
		<?php
	}

	/**
	 * Maybe Show Hidden Fields Message
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param array $fields The fields.
	 *
	 * @return string
	 */
	public function maybe_show_hidden_fields_message( $fields ) {
		$section = ! empty( $this->args[ 'sections_' . $this->slug . '_white_labeling' ] ) ? $this->args[ 'sections_' . $this->slug . '_white_labeling' ] : '';
		$message = ! empty( $section['pdf_generator_permissions_blurb_personalizations_white_labeling'] ) ? $section['pdf_generator_permissions_blurb_personalizations_white_labeling'] : '';

		foreach ( $fields as $field ) {
			$value = ( new Fields\FieldsAbstract )->maybe_hide_field( '[group_whitelabel_settings][ ' . $field . '_permision]' );

			if ( $value ) {
				return wp_kses_post( $message );
			}
		}

		return '';
	}
}
