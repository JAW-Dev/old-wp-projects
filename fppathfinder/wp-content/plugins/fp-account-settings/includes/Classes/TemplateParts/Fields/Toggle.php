<?php
/**
 * Toggle Field.
 *
 * @package    Fp_Account_Settings
 * @subpackage Fp_Account_Settings/Inlcudes/Classes/Template_Parts/Fields
 * @author     Objectiv
 * @copyright  Copyright (c) 2021, Objectiv
 * @license    GNU General Public License v2 or later
 * @since      1.0.0
 */

namespace FpAccountSettings\Includes\Classes\TemplateParts\Fields;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

/**
 * Toggle Field.
 *
 * @author Objectiv
 * @since  1.0.0
 */
class Toggle extends FieldsAbstract {

	/**
	 * Initialize the class
	 *
	 * @author Objectiv
	 * @since  1.0.0
	 *
	 * @param array $args The field args.
	 *
	 * @return void
	 */
	public function __construct( $args = array() ) {
		parent::__construct( $args );
	}

	/**
	 * Resource Fields
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param array $args The passed arguments.
	 *
	 * @return void
	 */
	public function render( $args = [] ) {
		$args_value = isset( $args['value'] ) ?? '';
		$value      = $this->get_value( $args, $args['setting'] ) ? $this->get_value( $args, $args['setting'] ) : $args_value;
		$name       = str_replace( '_', '-', $args['field'] . '_permission' );

		if ( isset( $args['args']['args'] ) ) {
			$section_slug = isset( $args['args']['args']['section_slug'] ) ? $args['args']['args']['section_slug'] : '';
			$sections     = isset( $args['args']['args'][ 'sections_' . $section_slug ] ) ? $args['args']['args'][ 'sections_' . $section_slug ] : '';
			$label        = isset( $sections[ $args['field'] . '_permission_' . $section_slug ] ) ? $sections[ $args['field'] . '_permission_' . $section_slug ] : '';
		} else {
			$section_slug = isset( $args['args']['section_slug'] ) ? $args['args']['section_slug'] : '';
			$sections     = isset( $args['args'][ 'sections_' . $section_slug ] ) ? $args['args'][ 'sections_' . $section_slug ] : '';
			$label        = isset( $sections[ $args['field'] . '_' . $section_slug ] ) ? $sections[ $args['field'] . '_' . $section_slug ] : '';
		}

		if ( ! $this->maybe_hide_permission( $args ) ) {
			return;
		}
		?>
		<div class="styled-switches form-control">
			<div class="tabs__body-section <?php echo esc_attr( $name ); ?>">
				<label for="<?php echo esc_attr( $name ); ?>">
					<input type="checkbox" id="<?php echo esc_attr( $name ); ?>" name="<?php echo esc_attr( $name ); ?>" <?php checked( $value, 'on' ); ?>>
					<div class="switch"></div>
					<div class="label-text">
						<?php echo wp_kses_post( $label ); ?>
					</div>
				</label>
			</div>
		</div>
		<?php
	}

	/**
	 * Matbey Hide Permission
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param array $args The field arguments.
	 *
	 * @return boolean
	 */
	public function maybe_hide_permission( $args ) {
		$admin_only_fields = [
			'logo',
			'business_display_name',
			'color_set',
		];

		$group_settings = isset( $this->user_settings['group_settings'] ) ? $this->user_settings['group_settings'] : array();
		$show           = true;

		foreach ( $admin_only_fields as $admin_only_field ) {

			if ( strpos( $args['setting'], $admin_only_field ) !== false ) {
				$show = false;

				if ( ! empty( $group_settings ) ) {
					foreach ( $group_settings as $key => $value ) {
						if ( $admin_only_field === $key ) {
							$show = true;
						}
					}
				}
			}
		}

		return $show;
	}
}
