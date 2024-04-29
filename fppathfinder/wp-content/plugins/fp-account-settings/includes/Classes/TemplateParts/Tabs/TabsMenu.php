<?php
/**
 * Tabs Menu.
 *
 * @package    Fp_Account_Settings
 * @subpackage Fp_Account_Settings/Inlcudes/Classes/Template_Parts/Tabs
 * @author     Objectiv
 * @copyright  Copyright (c) 2021, Objectiv
 * @license    GNU General Public License v2 or later
 * @since      1.0.0
 */

namespace FpAccountSettings\Includes\Classes\TemplateParts\Tabs;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

/**
 * Tabs Menu.
 *
 * @author Objectiv
 * @since  1.0.0
 */
class TabsMenu {

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
		$this->args = $args;
		$this->render();
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
		<ul class="sub-tabs-<?php echo esc_attr( $this->args['slug'] ); ?> tabs__list" role="tablist">
			<?php
			foreach ( $this->args['tabs'] as $item ) {
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

		$title   = $item['title'] ?? '';
		$id_name = ! empty( $title ) ? strtolower( str_replace( ' ', '-', $title ) ) : '';
		$id      = ! empty( $id_name ) ? ' id=' . $this->args['slug'] . '_' . $id_name . '' : '';
		?>
		<li
			class="sub-tabs-<?php echo esc_attr( $this->args['slug'] ); ?> tabs__list-item"<?php echo esc_attr( $id ); ?>
			tabindex="0"
			role="tab"
			aria-selected="false"
			aria-controls="<?php echo esc_attr( $id_name ); ?>-sub-tab">
				<?php if ( ! empty( $title ) ) : ?>
					<div class="tabs__list-title" tabindex="-1"><?php echo esc_html( $title ); ?></div>
				<?php endif; ?>
		</li>
		<?php
	}
}
