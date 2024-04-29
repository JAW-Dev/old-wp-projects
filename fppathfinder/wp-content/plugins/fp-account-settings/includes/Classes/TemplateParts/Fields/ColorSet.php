<?php
/**
 * Color Set Field.
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
 * Color Set Field.
 *
 * @author Objectiv
 * @since  1.0.0
 */
class ColorSet extends FieldsAbstract {

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
		$name                      = str_replace( '_', '-', $args['field'] );
		$color_sets                = get_field( 'pdf_generator_color_sets', 'option' );
		$color_sets_label_settings = get_field_object( 'pdf_generator_color_sets', 'option' )['sub_fields'][0]['sub_fields'];
		$color_sets_labels         = [];
		$is_group_whitelabel       = isset( $args['group_whitelabel'] ) && $args['group_whitelabel'];
		$is_share_link             = isset( $args['share_link'] ) && $args['share_link'];

		if ( ! empty( $color_sets_label_settings ) ) {
			foreach ( $color_sets_label_settings as $label_settings ) {
				$color_sets_labels[] = $label_settings['label'];
			}
		}

		$user_color_set        = $this->get_value( $args, $args['setting']['color_set'] ) ?? '';
		$user_color_set_choice = $this->get_value( $args, $args['setting']['color_set_choice'] ) ?? '';

		if ( $this->maybe_hide_field( $args['setting']['color_set'] . '_permission', $is_group_whitelabel ) ) {
			return;
		}

		?>
		<div class="tabs__body-section form-control <?php echo esc_attr( $name ); ?>">
			<div class="form-control__field-wrap">
				<?php $this->field_label( $args ); ?>
				<div class="color-contaier-wrap">
					<div id="colors-container-outer" class="colors-container-outer">
						<div id="<?php echo esc_attr( $name ); ?>-colors-container" class="colors-container">
							<?php
							if ( ! empty( $color_sets ) ) :
								foreach ( $color_sets as $index => $color_set ) :
									$set     = $color_set['color_set'];
									$index   = $index + 1;
									$checked = false;

									if ( $user_color_set_choice !== 'custom' && $user_color_set_choice === (string) $index ) {
										$checked = true;
									}

									if ( $user_color_set_choice === '' && $index === 1 ) {
										$checked = true;
									}
									?>
									<div id="set-<?php echo esc_attr( $index ); ?>-colors" class="set-<?php echo esc_attr( $index ); ?>-colors possible-color-selection set-colors <?php echo ( $checked ) ? 'selected' : ''; ?>">
										<div class="selector">
											<input type="radio" id="set-<?php echo esc_attr( $index ); ?>-color-scheme" name="<?php echo esc_attr( $name ); ?>-color-scheme" value="<?php echo esc_attr( $index ); ?>" <?php echo ( $checked ) ? 'checked' : ''; ?> />
										</div>
										<div id="color-set-container-<?php echo esc_attr( $index ); ?>" class="colors" data-selector="#set-<?php echo esc_attr( $index ); ?>-color-scheme">
											<?php foreach ( $set as $color_key => $color ) : ?>
												<?php
													$color_key = str_replace( '_', '-', $color_key );
												?>
												<div id="set-<?php echo esc_attr( $index ); ?>-<?php echo esc_attr( $color_key ); ?>" class="color-set-color" style="background-color: <?php echo esc_attr( $color ); ?>" data-hex-color="<?php echo esc_attr( $color ); ?>"></div>
											<?php endforeach; ?>
										</div>
									</div>
								<?php endforeach; ?>
							<?php endif; ?>
							<div id="<?php echo esc_attr( $name ); ?>-custom-color-box-button-wrap" class="custom-color-box-button-wrap possible-color-selection <?php echo esc_attr( ( $user_color_set_choice == 'custom' ) ? 'selected' : '' ); ?>">
								<button name="<?php echo esc_attr( $name ); ?>-custom-color-scheme-btn" id="<?php echo esc_attr( $name ); ?>-custom-color-scheme-btn">Custom</button>
							</div>
						</div>
					</div>

					<div id="<?php echo esc_attr( $name ); ?>-custom-color-scheme-table-container-wrap" class="custom-color-scheme-table-container-wrap">
						<div id="custom-color-scheme-table-container" class="custom-color-scheme-btn-container">
							<input type="hidden" id="<?php echo esc_attr( $name ); ?>-is-using-custom-colors" name="<?php echo esc_attr( $name ); ?>-is-using-custom-colors" value="<?php echo esc_attr( ( $user_color_set_choice === 'custom' ) ? 'true' : 'false' ); ?>" />
							<div id="custom-color-scheme-table">
								<?php foreach ( $color_sets_labels as $index => $label ) : ?>
									<?php
										$input_id = strtolower( explode( ' ', $label )[0] );
										$index    = ++$index;
									?>
									<div class="custom-color-box-show-and-hide-row">
										<div class="small-label"><?php echo esc_html( "$index. $label" ); ?></div>
										<div class="custom-color-input-control">
											<span class="hash">#</span>
											<input type="text" class="custom-color-input" id="custom-color-<?php echo esc_attr( $input_id ); ?>" name="<?php echo esc_attr( $name ); ?>-custom-color-<?php echo esc_attr( $input_id ); ?>" value="<?php echo esc_attr( ( $user_color_set && $user_color_set_choice === 'custom' ) ? substr( $user_color_set[ "color${index}" ], 1 ) : '000000' ); ?>" />
											<input type="text" class="spectrum-color-box" id="custom-color-input-<?php echo esc_attr( $input_id ); ?>" name="<?php echo esc_attr( $name ); ?>-custom-color-input-<?php echo esc_attr( $input_id ); ?>" value="<?php echo esc_attr( ( $user_color_set && $user_color_set_choice === 'custom' ) ? $user_color_set[ "color${index}" ] : '#000000' ); ?>" />
										</div>
									</div>
								<?php endforeach; ?>
							</div>
						</div>
					</div>
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
						'setting'          => $args['setting']['color_set'] . '_permission',
						'group_whitelabel' => true,
					]
				);
			}
			?>
		</div>
		<?php
	}
}
