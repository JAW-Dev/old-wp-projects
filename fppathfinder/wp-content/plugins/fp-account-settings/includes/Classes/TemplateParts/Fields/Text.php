<?php
/**
 * Text Field.
 *
 * @package    Fp_Account_Settings
 * @subpackage Fp_Account_Settings/Inlcudes/Classes/Template_Parts/Fields
 * @author     Objectiv
 * @copyright  Copyright (c) 2021, Objectiv
 * @license    GNU General Public License v2 or later
 * @since      1.0.0
 */

namespace FpAccountSettings\Includes\Classes\TemplateParts\Fields;

use FpAccountSettings\Includes\Classes\TemplateParts\Fields;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

/**
 * Text Field.
 *
 * @author Objectiv
 * @since  1.0.0
 */
class Text extends FieldsAbstract {

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
		$args_value          = isset( $args['value'] ) ?? '';
		$value               = $this->get_value( $args, $args['setting'] ) ? $this->get_value( $args, $args['setting'] ) : $args_value;
		$name                = str_replace( '_', '-', $args['field'] );
		$is_group_whitelabel = isset( $args['group_whitelabel'] ) && $args['group_whitelabel'];
		$is_group_share_link = isset( $args['group_share_link'] ) && $args['group_share_link'];

		if ( $this->maybe_hide_field( $args['setting'] . '_permission', $is_group_whitelabel ) || $this->maybe_hide_field( $args['setting'] . '_permission', $is_group_share_link ) ) {
			return;
		}

		// if ( $args['setting'] === 'pdf_generator_business_display_name' ) {
		// 	if ( $this->maybe_hide_field( $args['setting'], $is_group_whitelabel ) ) {
		// 		return;
		// 	}
		// } else {
		// 	if ( $this->maybe_hide_field( $args['setting'] . '_permission', $is_group_whitelabel ) || $this->maybe_hide_field( $args['setting'] . '_permission', $is_group_share_link ) ) {
		// 		return;
		// 	}
		// }
		?>
		<div class="tabs__body-section form-control <?php echo esc_attr( $name ); ?>">
			<div class="form-control__field-wrap">
				<?php $this->field_label( $args ); ?>
				<input type="text" id="<?php echo esc_attr( $name ); ?>" name="<?php echo esc_attr( $name ); ?>" value="<?php echo esc_attr( $value ); ?>"/>
			</div>
			<?php
			if ( $is_group_whitelabel || $is_group_share_link ) {
				$toggle_args = [
					'args'    => $args,
					'field'   => $args['field'],
					'setting' => $args['setting'] . '_permission',
				];

				if ( $is_group_whitelabel ) {
					$toggle_args['group_whitelabel'] = true;
				}

				if ( $is_group_share_link ) {
					$toggle_args['group_share_link'] = true;
				}

				fp_get_member_access(
					array( 'administer' => true ),
					[ new Fields\Toggle(), 'render' ],
					$toggle_args,
				);
			}
			?>
		</div>
		<?php
	}
}
