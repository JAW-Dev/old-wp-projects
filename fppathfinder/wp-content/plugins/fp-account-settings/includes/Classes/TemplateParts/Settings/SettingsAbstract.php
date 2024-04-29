<?php
/**
 * Settings Abstract.
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
 * Settings Abstract.
 *
 * @author Objectiv
 * @since  1.0.0
 */
abstract class SettingsAbstract {

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
		$title   = $this->args['title'] ?? '';
		$page    = strtolower( str_replace( ' ', '-', $title ) );
		$id_name = ! empty( $title ) ? strtolower( str_replace( ' ', '-', $title ) ) : '';
		?>
		<div
			class="account-settings__main account-settings__body-content"
			data-page="<?php echo esc_attr( $page ); ?>"
			role="tabpanel"
			id="<?php echo esc_attr( $id_name ); ?>-tab"
			aria-labelledby="<?php echo esc_attr( $id_name ); ?>"
			aria-expanded="true">
			<?php $this->render_content(); ?>
		</div>
		<?php
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
	}
}
