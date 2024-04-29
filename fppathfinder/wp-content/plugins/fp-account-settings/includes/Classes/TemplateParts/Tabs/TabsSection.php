<?php
/**
 * Tabs Section.
 *
 * @package    Fp_Account_Settings
 * @subpackage Fp_Account_Settings/Inlcudes/Classes/Template_Parts/Tabs
 * @author     Objectiv
 * @copyright  Copyright (c) 2021, Objectiv
 * @license    GNU General Public License v2 or later
 * @since      1.0.0
 */

namespace FpAccountSettings\Includes\Classes\TemplateParts\Tabs;

use FpAccountSettings\Includes\Classes\TemplateParts\Tabs\TabsMenu;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

/**
 * Tabs Section.
 *
 * @author Objectiv
 * @since  1.0.0
 */
class TabsSection {

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
		<section id="tabs-<?php echo esc_attr( $this->args['slug'] ); ?>" class="tabs tabs-<?php echo esc_attr( $this->args['slug'] ); ?>">
			<nav class="tabs__nav tabs__nav-<?php echo esc_attr( $this->args['slug'] ); ?>"><?php new TabsMenu( $this->args ); ?></nav>
			<section class="tabs__body tabs__body-<?php echo esc_attr( $this->args['slug'] ); ?>">
				<?php
				// Make sure the class filename and classname is the section title Pascal cased.
				foreach ( $this->args['tabs'] as $section ) {
					$title     = $section['title'] ?? '';
					$slug      = ucwords( str_replace( '-', ' ', $this->args['slug'] ) );
					$namespace = str_replace( ' ', '', $slug );
					$name      = ucwords( str_replace( ' ', '', $title ) );
					$classname = 'FpAccountSettings\\Includes\\Classes\\TemplateParts\Pages\\' . $namespace . '\\' . $name;

					if ( class_exists( '\\' . $classname ) ) {
						new $classname( $this->args['slug'], $section );
					}
				}
				?>
			</section>
		</section>
		<?php
	}
}
