<?php
/**
 * Settings Menu.
 *
 * @package    Fp_Account_Settings
 * @subpackage Fp_Account_Settings/Inlcudes/Classes/Settings/Template_Parts
 * @author     Objectiv
 * @copyright  Copyright (c) 2021, Objectiv
 * @license    GNU General Public License v2 or later
 * @since      1.0.0
 */

namespace FpAccountSettings\Includes\Classes\TemplateParts\Settings;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

/**
 * Settings Menu.
 *
 * @author Objectiv
 * @since  1.0.0
 */
class SettingsMenu {

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
	 * @param array $args The settings sections args.
	 *
	 * @return void
	 */
	public function __construct( $args = [] ) {
		$this->icons_path = FP_ACCOUNT_SETTINGS_DIR_PATH . 'assets/images/icons/';
		$this->args       = $args;
		$this->render();
	}


	/**
	 * Render
	 *
	 * @author Objectiv
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function render() {
		?>
		<header class="nav-list__heading accordian-header">
			<h2>Settings</h2>
			<div class="nav-list__chevron toggle-chevron"></div>
		</header>
		<ul id="account-settings-nav-list" class="account-settings__main account-settings__nav-list nav-list accordian-body" role="tablist" aria-label="Settings">
			<?php
			foreach ( $this->args as $item ) {
				$this->render_menu_item( $item );
			}
			?>
		</ul>
		<?php
	}

	/**
	 * Render Menu Item
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param array $item The menu item data.
	 *
	 * @return void
	 */
	public function render_menu_item( $item = [] ) {
		if ( empty( $item ) ) {
			return;
		}

		$access = $item['access'] ?? false;

		if ( ! $access ) {
			return;
		}

		$title       = $item['title'] ?? '';
		$id_name     = ! empty( $title ) ? strtolower( str_replace( ' ', '-', $title ) ) : '';
		$slug        = $item['slug'] ?? '';
		$icon        = $item['icon'] ?? '';
		$description = fp_get_acf_field_default_value( 'menu_item_description_' . $slug, 'Account Page Settings' );
		$id          = ! empty( $id_name ) ? ' id=' . $id_name . '' : '';

		?>
		<li
			class="account-settings__main nav-list__item"<?php echo esc_attr( $id ); ?>
			tabindex="0"
			role="tab"
			aria-selected="false"
			aria-controls="<?php echo esc_attr( $id_name ); ?>-tab">
			<div class="nav-list__item-wrap">
				<?php if ( ! empty( $icon ) ) : ?>
					<div class="nav-list__item-icon small-svg-icon"><?php echo wp_kses( $icon, fp_svg_kses() ); ?></div>
				<?php endif; ?>
				<div class="nav-list__item-text">
					<?php if ( ! empty( $title ) ) : ?>
						<div class="nav-list__item-text-title"><?php echo esc_html( $title ); ?></div>
					<?php endif; ?>
					<?php if ( ! empty( $description ) ) : ?>
						<div class="nav-list__item-text-description"><?php echo esc_html( $description ); ?></div>
					<?php endif; ?>
				</div>
			</div>
		</li>
		<?php
	}
}
