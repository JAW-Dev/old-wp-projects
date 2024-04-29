<?php
/**
 * Hidden Field.
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
 * Hidden Field.
 *
 * @author Objectiv
 * @since  1.0.0
 */
class Hidden extends FieldsAbstract {

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
		$args_value = isset( $args['value'] ) ? $args['value'] : '';
		$value      = $args_value ? $args_value : $this->get_value( $args, $args['setting'] );
		$name       = str_replace( '_', '-', $args['field'] );

		?>
		<input type="hidden" id="<?php echo esc_attr( $name ); ?>" name="<?php echo esc_attr( $name ); ?>" value="<?php echo esc_attr( $value ); ?>"/>
		<?php
	}
}
