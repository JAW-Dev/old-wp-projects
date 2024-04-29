<?php
/**
 * Fields Abstract.
 *
 * @package    Fp_Account_Settings
 * @subpackage Fp_Account_Settings/Inlcudes/Classes/Template_Parts/Fields
 * @author     Objectiv
 * @copyright  Copyright (c) 2021, Objectiv
 * @license    GNU General Public License v2 or later
 * @since      1.0.0
 */

namespace FpAccountSettings\Includes\Classes\TemplateParts\Fields;

use FpAccountSettings\Includes\Classes\Conditionals;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

/**
 * Fields Abstract.
 *
 * @author Objectiv
 * @since  1.0.0
 */
class FieldsAbstract {

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
	 * User Settings
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @var object
	 */
	protected $user_settings;

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
	public function __construct( $args = [] ) {
		$this->args          = $args;
		$this->user_settings = fp_get_user_settings();
	}

	/**
	 * Field Label
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param array $args The field arguments.
	 *
	 * @return void
	 */
	public function field_label( $args = [] ) {
		$slug    = ! empty( $args['args']['section_slug'] ) ? $args['args']['section_slug'] : '';
		$section = ! empty( $args['args'][ 'sections_' . $slug ] ) ? $args['args'][ 'sections_' . $slug ] : '';
		$field   = ! empty( $args['field'] ) ? $args['field'] : '';
		$title   = ! empty( $section[ $field . '_title_' . $slug ] ) ? $section[ $field . '_title_' . $slug ] : '';
		$blurb   = ! empty( $section[ $field . '_blurb_' . $slug ] ) ? $section[ $field . '_blurb_' . $slug ] : '';
		?>
		<label class="form-label">
			<?php if ( ! empty( $title ) ) : ?>
				<h4><?php echo esc_html( $title ); ?></h4>
			<?php endif; ?>
			<?php if ( ! empty( $blurb ) ) : ?>
				<div class="form-label__blurb"><?php echo wp_kses_post( $blurb ); ?></div>
			<?php endif; ?>
		</label>
		<?php
	}

	/**
	 * Get Default Logo
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return string
	 */
	public static function get_default_logo() {
		return 'data:image/png;base64,' . base64_encode( file_get_contents( get_attached_file( get_field( 'pdf_generator_default_logo', 'option' )['ID'] ) ) ); // phpcs:ignore
	}

	/**
	 * Get Value
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param array  $args    The arguments.
	 * @param string $setting The field setting name.
	 *
	 * @return string
	 */
	public function get_value( $args, $setting ) {
		$is_whitelabel             = isset( $args['whitelabel'] ) && $args['whitelabel'];
		$is_advanced               = isset( $args['advanced'] ) && $args['advanced'];
		$is_share_link             = isset( $args['share_link'] ) && $args['share_link'];
		$is_group_whitelabel       = isset( $args['group_whitelabel'] ) && $args['group_whitelabel'];
		$is_group_share_link       = isset( $args['group_share_link'] ) && $args['group_share_link'];
		$is_group_advanced         = isset( $args['group_advanced'] ) && $args['group_advanced'];
		$user_settings             = fp_get_user_settings();
		$whitelabel_settings       = fp_get_whitelabel_settings( $user_settings );
		$group_whitelabel_settings = fp_get_group_whitelabel_settings( $user_settings );
		$share_link_settings       = fp_get_share_link_settings( $user_settings );
		$group_share_link_settings = fp_get_group_share_link_settings( $user_settings );

		if ( $is_whitelabel || $is_advanced ) {
			if ( $setting === 'color_set_choice' ) {
				return isset( $user_settings['whitelabel']['color_set_choice'] ) ? $user_settings['whitelabel']['color_set_choice'] : '';
			}

			return isset( $whitelabel_settings[ $setting ] ) ? $whitelabel_settings[ $setting ] : '';
		}

		if ( $is_share_link ) {
			return isset( $share_link_settings[ $setting ] ) ? $share_link_settings[ $setting ] : '';
		}

		if ( $is_group_whitelabel || $is_group_advanced ) {
			if ( $setting === 'color_set_choice' ) {
				return isset( $user_settings['group_whitelabel_settings']['color_set_choice'] ) ? $user_settings['group_whitelabel_settings']['color_set_choice'] : '';
			}

			return isset( $group_whitelabel_settings[ $setting ] ) ? $group_whitelabel_settings[ $setting ] : '';
		}

		if ( $is_group_share_link ) {
			return isset( $group_share_link_settings[ $setting ] ) ? $group_share_link_settings[ $setting ] : '';
		}

		return '';
	}

	/**
	 * Maybe Hide Field
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param string  $field The field to check.
	 * @param boolean $show  Show the field.
	 *
	 * @return boolean
	 */
	public function maybe_hide_field( $field, $show = false ) {
		if ( Conditionals::is_essentials_group_member() || ! is_user_logged_in() ) {
			return false;
		}

		$group_id = fp_get_group_id();

		if ( empty( $group_id ) || $field === 'advisor_name' ) {
			return false;
		}

		$whitelabel_permissions = fp_get_group_permissions();
		$group_settings         = fp_get_group_settings();

		if ( ! empty( $whitelabel_permissions ) ) {
			foreach ( $whitelabel_permissions as $key => $value ) {
				if ( $key === $field ) {
					if ( $value === 'on' ) {
						return false;
					}

					if ( $value === 'on' && ( $key !== 'pdf_generator_logo' || $key !== 'pdf_generator_business_display_name' || $key !== 'pdf_generator_color_set' ) ) {
						return false;
					}

					if ( $key === 'logo_permission' ) {
						if ( ! empty( $group_settings['logo'] ) ) {
							return false;
						}
					}

					if ( $key === 'business_display_name_permission' ) {
						if ( ! empty( $group_settings['business_display_name'] ) ) {
							return false;
						}
					}

					if ( $key === 'color_set_permission' ) {
						if ( ! empty( $group_settings['color_set'] ) ) {
							return false;
						}
					}
				}
			}
		}

		$share_link_permissions = isset( $this->user_settings['group_link_share_settings'] ) ? $this->user_settings['group_link_share_settings'] : array();

		if ( ! empty( $share_link_permissions ) ) {
			foreach ( $share_link_permissions as $key => $value ) {
				if ( $key === $field ) {
					if ( $value === 'on' ) {
						return false;
					}
				}
			}
		}

		$group_administer = Conditionals::can_administer_group();

		if ( $show ) {
			return false;
		}

		if ( $group_administer ) {
			return false;
		}

		return true;
	}
}
