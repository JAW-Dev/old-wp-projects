<?php
/**
 * Settings Section.
 *
 * @package    Fp_Account_Settings
 * @subpackage Fp_Account_Settings/Inlcudes/Classes/Settings/Template_Parts
 * @author     Objectiv
 * @copyright  Copyright (c) 2021, Objectiv
 * @license    GNU General Public License v2 or later
 * @since      1.0.0
 */

namespace FpAccountSettings\Includes\Classes\TemplateParts\Settings;

use FpAccountSettings\Includes\Classes\Conditionals;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

/**
 * Settings Section.
 *
 * @author Objectiv
 * @since  1.0.0
 */
class SettingsSection {

	/**
	 * Icons Path
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @var string
	 */
	protected $icons_path = '';

	/**
	 * Initialize the class
	 *
	 * @author Objectiv
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function __construct() {
		$this->icons_path = FP_ACCOUNT_SETTINGS_DIR_PATH . 'assets/images/icons/';
		$this->render();
	}

	/**
	 * Settings Sections
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return array
	 */
	public function settings_sections() {
		$scheme = [
			[
				'icon'   => fp_get_svg( $this->icons_path, 'gear' ),
				'title'  => 'Membership',
				'access' => fp_get_member_access(
					array(
						'name'             => 'Membership setting',
						'membership_level' => 2,
					)
				),
			],
			[
				'icon'   => fp_get_svg( $this->icons_path, 'profile' ),
				'title'  => 'Profile',
				'access' => fp_get_member_access(
					array(
						'name'             => 'Profile setting',
						'membership_level' => 2,
					)
				),
			],
			[
				'icon'   => fp_get_svg( $this->icons_path, 'white-label' ),
				'title'  => 'Personalizations',
				'access' => true,
			],
			[
				'icon'   => fp_get_svg( $this->icons_path, 'group' ),
				'title'  => 'Group Settings',
				'access' => fp_get_member_access(
					array(
						'name'       => 'Group Settings setting',
						'administer' => true,
					)
				),
			],
			[
				'icon'   => fp_get_svg( $this->icons_path, 'integrations' ),
				'title'  => 'Integrations',
				'access' => Conditionals::is_premier_member() ||
							Conditionals::is_premier_group_member() ||
							Conditionals::is_deluxe_member() ||
							Conditionals::is_deluxe_group_owner() ||
							current_user_can( 'administrator' ),
			],
			[
				'icon'   => fp_get_svg( $this->icons_path, 'support' ),
				'title'  => 'Support',
				'access' => fp_get_member_access(
					array(
						'name'             => 'Support setting',
						'membership_level' => 2,
					)
				),
			],
		];

		if ( fp_is_feature_active( 'checklists_v_two' ) ) {
			array_push( $scheme, [
				'icon'   => fp_get_svg( $this->icons_path, 'share' ),
				'title'  => 'Shared Links',
				'access' => Conditionals::is_premier_member() ||
							Conditionals::is_premier_group_member() ||
							Conditionals::is_deluxe_member() ||
							Conditionals::is_deluxe_group_owner() ||
							current_user_can( 'administrator' ),
			]);
		}

		foreach ( $scheme as $key => $value ) {
			$slug                    = ! empty( $value['title'] ) ? strtolower( str_replace( ' ', '_', $value['title'] ) ) : '';
			$scheme[ $key ]['slug']  = $slug;
			$scheme[ $key ]['blurb'] = function_exists( 'get_field' ) ? get_field( 'heading_blurb_' . $slug, 'option' ) : '';
		}

		return $scheme;
	}

	/**
	 * Init Classes
	 *
	 * @author Objectiv
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function render() {
		?>
		<section id="account-settings" class="account-settings">
			<nav id="account-settings-nav" class="account-settings__nav"><?php new SettingsMenu( $this->settings_sections() ); ?></nav>
			<section class="account-settings__body">
				<?php
				// Make sure the class filename and classname is the section title Pascal cased.
				foreach ( $this->settings_sections() as $section ) {
					$name      = ucwords( str_replace( ' ', '', $section['title'] ) );
					$classname = 'FpAccountSettings\\Includes\\Classes\\TemplateParts\\Pages\\' . $name . '\\' . $name;

					if ( class_exists( '\\' . $classname ) ) {
						new $classname( $section );
					}
				}
				?>
			</section>
		</section>
		<?php
	}
}
