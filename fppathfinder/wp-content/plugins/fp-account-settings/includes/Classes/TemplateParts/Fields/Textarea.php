<?php
/**
 * Textarea Field.
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
 * Textarea Field.
 *
 * @author Objectiv
 * @since  1.0.0
 */
class Textarea extends FieldsAbstract {

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
		$textarea_limits     = get_field( 'pdf_generator_text_area_limits', 'option' );
		$args_value          = isset( $args['value'] ) ?? '';
		$value               = $this->get_value( $args, $args['setting'] ) ? $this->get_value( $args, $args['setting'] ) : $args_value;
		$name                = str_replace( '_', '-', $args['field'] );
		$is_group_whitelabel = isset( $args['group_whitelabel'] ) && $args['group_whitelabel'];

		if ( $this->maybe_hide_field( $args['setting'] . '_permission', $is_group_whitelabel ) ) {
			return;
		}
		?>
		<div class="tabs__body-section form-control <?php echo esc_attr( $name ); ?>">
			<div class="form-control__field-wrap">
				<?php $this->field_label( $args ); ?>
				<div class="input-info-box-wrap">
					<textarea maxlength="<?php echo esc_attr( $textarea_limits['second_page_body_copy_limit'] ); ?>" rows="5" id="<?php echo esc_attr( $name ); ?>" class="textarea" name="<?php echo esc_attr( $name ); ?>"><?php echo wp_kses_post( $value ); ?></textarea>
				</div>
			</div>
			<?php
			if ( $is_group_whitelabel ) {
				fp_get_member_access(
					array( 'administer' => true ),
					[ new Fields\Toggle(), 'render' ],
					[
						'args'             => $args,
						'field'            => $args['field'],
						'setting'          => $args['setting'] . '_permission',
						'group_whitelabel' => true,
					]
				);
			}
			?>
		</div>
		<?php
	}
}
