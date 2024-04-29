<?php
/**
 * Permissions.
 *
 * @package    Fp_Account_Settings
 * @subpackage Fp_Account_Settings/Inlcudes/Classes/Template_Parts/GroupSettings
 * @author     Objectiv
 * @copyright  Copyright (c) 2021, Objectiv
 * @license    GNU General Public License v2 or later
 * @since      1.0.0
 */

namespace FpAccountSettings\Includes\Classes\TemplateParts\Pages\GroupSettings;

use FpAccountSettings\Includes\Classes\TemplateParts\Tabs\TabsAbstract;
use FpAccountSettings\Includes\Classes\TemplateParts\Fields;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

/**
 * Permissions.
 *
 * @author Objectiv
 * @since  1.0.0
 */
class Permissions extends TabsAbstract {

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
		$slug = strtolower( str_replace( ' ', '-', $this->args['title'] ) );
		?>
		<section class="body-section body-section__<?php echo esc_attr( $slug ); ?>">
			<?php $this->tab_blurb( 'group_settings' ); ?>
			<form id="group-settings-permissions-form" class="group-settings-permissions-form" method="POST" target="" action="">
				<?php wp_nonce_field( 'save_group_settings_permissions', 'group_settings_permissions' ); ?>
				<input type="hidden" id="group-settings-permissions-action" class="group-settings-permissions-action" name="action" value="" />
				<input type="hidden" id="group-settings-permissions-security" class="group-settings-permissions-security" name="security" value="<?php echo esc_attr( wp_create_nonce( 'group-settings-permissions-6253' ) ); ?>" />
				<?php
				$this->fields();
				$this->buttons();
				?>
			</form>
		</section>
		<?php
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
		$section_blurb = $this->args[ 'sections_' . $this->args['section_slug'] ][ $section . '_blurb_' . $this->args['section_slug'] ] ?? [];

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
	 * Fields
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function fields() {
		$this->whitelabel();
		// if ( Utillites::get_membership_access_level() === 5 || Utillites::get_membership_access_level() === 6 ) {
		// 	$this->profile();
		// }
	}

	/**
	 * Whitelable
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function whitelabel() {
		$title = $this->args[ 'sections_' . $this->args['section_slug'] ]['white_labeling_title_group-settings_white_labeling'] ?? [];
		$blurb = $this->args[ 'sections_' . $this->args['section_slug'] ]['white_labeling_blurb_group-settings_white_labeling'] ?? [];
		?>
			<section class="tabs__body-section group-settings-permissions-white-labeling">
				<div class="form-label">
					<h4><?php echo esc_html( $title ); ?></h4>
					<div class="form-label__blurb label-text"><?php echo wp_kses_post( $blurb ); ?></div>
				</div>
				<div id="group-settings-permissions-white-labeling-fields-wrap" class="fields-wrap">
					<?php
					$fields = [
						'all',
						'logo',
						'business_display_name',
						'advisor_name',
						'color_set',
						'second_page_title',
						'second_page_body_title',
						'second_page_body_copy',
						'job_title',
						'address',
						'email',
						'phone',
						'website',
					];

					foreach ( $fields as $field ) {
						fp_get_member_access(
							array( 'administer' => true ),
							[ new Fields\Toggle(), 'render' ],
							[
								'args'        => $this->args,
								'field'       => "white_label_$field",
								'setting'     => "white_label_$field",
								'permissions' => true,
							]
						);
					}
					?>
				</div>
			</section>
		<?php
	}

	/**
	 * Whitelable
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function profile() {
		$title = $this->args[ 'sections_' . $this->args['section_slug'] ]['profile_title_group-settings_profile'] ?? [];
		$blurb = $this->args[ 'sections_' . $this->args['section_slug'] ]['profile_blurb_group-settings_profile'] ?? [];
		?>
			<section class="tabs__body-section group-settings-permissions-profile">
				<div class="form-label">
					<h4><?php echo esc_html( $title ); ?></h4>
					<div class="form-label__blurb label-text"><?php echo wp_kses_post( $blurb ); ?></div>
				</div>
				<div id="group-settings-permissions-profile-fields-wrap" class="fields-wrap">
					<?php
					$fields = [
						'all',
						'first_name',
						'last_name',
						'display_name',
						'email_address',
						'job_title',
						'phone',
					];

					foreach ( $fields as $field ) {
						fp_get_member_access(
							array( 'administer' => true ),
							[ new Fields\Toggle(), 'render' ],
							[
								'args'        => $this->args,
								'field'       => "profile_$field",
								'setting'     => "profile_$field",
								'permissions' => true,
							]
						);
					}
					?>
				</div>
			</section>
		<?php
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
			<button id="group-settings-permissions-settings-save">Save Settings</button>
		</div>
		<?php
	}
}
