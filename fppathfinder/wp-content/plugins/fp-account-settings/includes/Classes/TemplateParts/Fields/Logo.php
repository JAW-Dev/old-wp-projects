<?php
/**
 * Logo Field.
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
 * Logo Field.
 *
 * @author Objectiv
 * @since  1.0.0
 */
class Logo extends FieldsAbstract {

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
		$default_logo        = $this->get_default_logo();
		$get_logo            = $this->get_value( $args, $args['setting'] ) ?? '';
		$logo                = ! empty( $get_logo ) ? $get_logo : $default_logo;
		$upload_text         = ! empty( $get_logo ) ? 'Replace' : 'Upload';
		$name                = str_replace( '_', '-', $args['field'] );
		$is_group_whitelabel = isset( $args['group_whitelabel'] ) && $args['group_whitelabel'];

		if ( $this->maybe_hide_field( $args['setting'] . '_permission', $is_group_whitelabel ) ) {
			return;
		}

		?>
		<div class="tabs__body-section form-control <?php echo esc_attr( $name ); ?>">
			<div class="form-control__field-wrap">
				<?php $this->field_label( $args ); ?>
				<div id="<?php echo esc_attr( $name ); ?>-logo-upload" class="logo-upload__container">
					<div id="<?php echo esc_attr( $name ); ?>-logo-upload__logo" class="logo-upload__logo">
						<div id="<?php echo esc_attr( $name ); ?>-file-upload-wrap" class="file-upload-wrap"></div>
					</div>
					<div id="<?php echo esc_attr( $name ); ?>-file-container" class="file-container">
						<span id="<?php echo esc_attr( $name ); ?>-file-upload-mock-btn" class="file-upload-mock-btn button-text logo-btn"><?php echo esc_html( $upload_text ); ?></span>
						<input type="file" id="<?php echo esc_attr( $name ); ?>-upload-field" class="logo-upload-field" name="<?php echo esc_attr( $name ); ?>-logo-upload-field" accept="image/png,image/jpeg" value=""/>
						<input type="hidden" id="<?php echo esc_attr( $name ); ?>-data" name="<?php echo esc_attr( $name ); ?>-data" value="<?php echo esc_attr( $logo ); ?>" />
						<!-- Used for reset -->
						<input type="hidden" id="<?php echo esc_attr( $name ); ?>-logo" name="<?php echo esc_attr( $name ); ?>-default-logo" value="<?php echo esc_attr( $default_logo ); ?>" />
						<input type="hidden" id="<?php echo esc_attr( $name ); ?>-previous-loaded-logo-field" name="<?php echo esc_attr( $name ); ?>-previous-logo-loaded-field" value="<?php echo esc_attr( $logo ); ?>" />
					</div>
				</div>
				<div id="<?php echo esc_attr( $name ); ?>-error-message"></div>
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
