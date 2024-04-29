<?php
/**
 * Other Settings.
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
 * Other Settings.
 *
 * @author Objectiv
 * @since  1.0.0
 */
class OtherSettings extends TabsAbstract {

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
			<?php
			$this->tab_blurb( 'other_settings' );
			?>
			<form id="group-settings-other-form" class="group-settings-other-form" method="POST" target="" action="">
			<?php wp_nonce_field( 'save_group_settings_other', 'group_settings_other' ); ?>
			<input type="hidden" id="group-settings-other-action" class="group-settings-other-action" name="action" value="" />
			<input type="hidden" id="group-settings-other-security" class="group-settings-other-security" name="security" value="<?php echo esc_attr( wp_create_nonce( 'group-settings-other-6253' ) ); ?>" />
				<?php $this->fields(); ?>
			</form>
			<?php
			$this->buttons();
			?>
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
	 * Fields
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function fields() {
		$user_settings  = $this->user_settings;
		$group_settings = fp_get_group_settings( $user_settings );

		if ( empty( $group_settings ) ) {
			return;
		}

		if ( ! isset( $group_settings['enabled_no_advisor_name'] ) || $group_settings['enabled_no_advisor_name'] !== 'on' ) {
			return;
		}

		$settings             = ! empty( $group_id ) ? get_metadata( 'rcp_group', $group_id, 'enabled_permissions', true ) : '';
		$advisor_name_setting = isset( $settings['enabled_no_advisor_name'] ) ? $settings['enabled_no_advisor_name'] : '';

		fp_get_member_access(
			array( 'administer' => true ),
			[ new Fields\Toggle(), 'render' ],
			[
				'args'             => $this->args,
				'field'            => 'other_settings_advisor_name',
				'setting'          => 'no_advisor_name',
			]
		);
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
		$user_settings  = $this->user_settings;
		$group_settings = fp_get_group_settings( $user_settings );

		if ( ! isset( $group_settings['enabled_no_advisor_name'] ) || $group_settings['enabled_no_advisor_name'] !== 'on' ) {
			return;
		}

		?>
		<div class="account-settings__buttons">
			<button id="group-settings-other-save">Save Settings</button>
		</div>
		<?php
	}
}
