<?php
/**
 * Group Settings.
 *
 * @package    Fp_Account_Settings
 * @subpackage Fp_Account_Settings/Inlcudes/Classes/Template_Parts/Pages/GroupSettings
 * @author     Objectiv
 * @copyright  Copyright (c) 2021, Objectiv
 * @license    GNU General Public License v2 or later
 * @since      1.0.0
 */

namespace FpAccountSettings\Includes\Classes\TemplateParts\Pages\GroupSettings;

use FpAccountSettings\Includes\Classes\TemplateParts\Settings\SettingsAbstract;
use FpAccountSettings\Includes\Classes\TemplateParts\Tabs\TabsSection;
use FpAccountSettings\Includes\Classes\Conditionals;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

/**
 * Group Settings.
 *
 * @author Objectiv
 * @since  1.0.0
 */
class GroupSettings extends SettingsAbstract {

	/**
	 * Initialize the class
	 *
	 * @author Objectiv
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function __construct( $args = [] ) {
		parent::__construct( $args );
	}

	/**
	 * Tabs Settings
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return array
	 */
	public function tabs_settings() {
		$scheme = [
			'slug' => 'group-settings', // Make sure the matches the namespace.
			'tabs' => [
				[
					'title'  => 'Members',
					'access' => array( 'administer' => true ),
				],
				[
					'title'  => 'White Labeling',
					'access' => fp_get_member_access( array( 'membership_level' => 6 ) ),
				],
				[
					'title'  => 'Share Link',
					'access' => fp_get_member_access( array( 'membership_level' => 7 ) ),
				],
			],
		];

		$subsections = function_exists( 'get_field' ) ? get_field( 'subsections_' . $scheme['slug'], 'option' ) : array();

		foreach ( $scheme['tabs'] as $key => $value ) {
			$title        = strtolower( str_replace( ' ', '_', $scheme['tabs'][ $key ]['title'] ) );
			$section_slug = $scheme['slug'] . '_' . $title;

			if ( empty( $subsections ) ) {
				continue;
			}

			foreach ( $subsections as $sub_key => $sub_value ) {
				if ( ! empty( $sub_key ) ) {
					$scheme['tabs'][ $key ][ $sub_key ] = $sub_value;
				}
			}

			$scheme['tabs'][ $key ]['section_slug'] = $section_slug;
		}

		return $scheme;
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
		$access = fp_get_member_access(
			array(
				'name'       => 'Group Settings pages',
				'administer' => true,
			)
		);

		if ( ! $access ) {
			return;
		}

		$slug = strtolower( str_replace( ' ', '-', $this->args['title'] ) );
		?>
		<section class="body-section body-section__<?php echo esc_attr( $slug ); ?>">
			<header class="body-section__header">
				<h2 id="body-section__title"><?php echo esc_html( $this->args['title'] ); ?></h2>
				<?php
				if ( ! empty( $this->args['blurb'] ) ) :
					?>
					<div class="body-section__header-blurb">
						<?php
						if ( Conditionals::is_enterprise_essentials() ) {
							echo wp_kses_post( $this->args['blurb']['heading_blurb_essential_group_settings'] );
						} else {
							echo wp_kses_post( $this->args['blurb']['heading_blurb_general_group_settings'] );
						}
						?>
					</div>
					<?php
				endif;
				?>
				<div id="form-notification-area-wrap"><ul></ul></div>
				<div id="group-settings-whitelabel-save-successful" class="success-notice"> Settings saved successfully.</div>
			</header>
			<?php new TabsSection( $this->tabs_settings() ); ?>
		</section>
		<?php
	}
}
