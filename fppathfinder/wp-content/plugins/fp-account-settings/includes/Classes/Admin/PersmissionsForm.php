<?php
/**
 * Persmissions Form
 *
 * @package    Fp_Account_Settings
 * @subpackage Fp_Account_Settings/Inlcudes/Classes/Admin
 * @author     Objectiv
 * @copyright  Copyright (c) 2021, Objectiv
 * @license    GNU General Public License v2 or later
 * @since      1.0.0
 */

namespace FpAccountSettings\Includes\Classes\Admin;

use FP_Core\Group_Settings\Database as CoreGroupSettings;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

/**
 * Persmissions Form
 *
 * @author Objectiv
 * @since  1.0.0
 */
class PersmissionsForm {

	/**
	 * Settings Name
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @var string
	 */
	protected $settings_name = 'enabled_permissions';

	/**
	 * Group ID
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @var int
	 */
	protected $group_id;

	/**
	 * Initialize the class
	 *
	 * @author Objectiv
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function __construct() {
		$this->hooks();
	}

	/**
	 * Hooks
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function hooks() {
		add_action( 'restrict_page_rcp-groups', array( $this, 'form' ), 999 );
	}

	/**
	 * Fields
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return array
	 */
	public function fields() {
		$fields_array = array(
			array(
				'title' => 'Enable No Advisor Names On PDFs Setting',
				'slug'  => 'enabled_no_advisor_name',
			),
			array(
				'title' => 'Enable Logo',
				'slug'  => 'logo',
			),
			array(
				'title' => 'Enable Color Scheme',
				'slug'  => 'color_set',
			),
			array(
				'title' => 'Enable Buisiness Display Name',
				'slug'  => 'business_display_name',
			),
		);

		return $fields_array;
	}

	/**
	 * Form
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function form() {
		$group_id = ! empty( $_REQUEST['rcpga-group'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['rcpga-group'] ) ) : '';

		if ( empty( $group_id ) ) {
			return;
		}

		$this->group_id = fp_get_group_id();
		$settings       = $this->get_settings();
		$url            = isset( $_SERVER['REQUEST_URI'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '';
		?>
		<div style="padding: 1rem;">
			<h2>Permissions Settings:</h2>
			<form method="post" action="<?php echo esc_url( $url ); ?>">
				<?php wp_nonce_field( 'save_' . $this->settings_name, $this->settings_name . '_nonce' ); ?>
				<?php
				foreach ( $this->fields() as $field ) :
					$value = $settings[ $field['slug'] ] ?? ''
					?>
					<div style="display: flex; align-items: center; margin: 1rem 0">
						<input type="checkbox" id="<?php echo esc_attr( $field['slug'] ); ?>" name="<?php echo esc_attr( $this->settings_name ); ?>[<?php echo esc_attr( $field['slug'] ); ?>]" <?php checked( $value, 'on' ); ?> />
						<label style="margin: 0 0 0 0.5rem; line-height: 1;" for="<?php echo esc_attr( $field['slug'] ); ?>>"><?php echo esc_html( $field['title'] ); ?></label>
					</div>
				<?php endforeach; ?>
				<input style="display: block; margin-top: 2rem;" class="button button-primary" type="submit" value="Update Group" />
			</form>
		</div>
		<?php
		$this->handle_submit();
	}

	/**
	 * Get Settings
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return array
	 */
	public function get_settings() {
		$no_enable_old       = CoreGroupSettings::get_group_setting( $this->group_id, 'Enable_No_Advisor_Names_On_PDFs' );
		$default_email_old   = CoreGroupSettings::get_group_setting( $this->group_id, 'EnableEmailDefaultSetting' );
		$enabled_permissions = get_metadata( 'rcp_group', $this->group_id, $this->settings_name, true );

		if ( empty( $enabled_permissions ) ) {
			$enabled_permissions = array();
		}

		if ( ! empty( $no_enable_old ) ) {
			if ( empty( $enabled_permissions['enabled_no_advisor_name'] ) ) {
				$enabled_permissions['enabled_no_advisor_name'] = 'checked' ? 'on' : '';

				$existing_setting = CoreGroupSettings::get_group_settings_rows( $this->group_id, 'Enable_No_Advisor_Names_On_PDFs' );

				foreach ( $existing_setting as $setting_row ) {
					CoreGroupSettings::delete_group_setting( $setting_row->id );
				}
			}
		}

		if ( ! empty( $default_email_old ) ) {
			if ( empty( $enabled_permissions['enabled_defult_email'] ) ) {
				$enabled_permissions['enabled_defult_email'] = 'checked' ? 'on' : '';

				$existing_setting = CoreGroupSettings::get_group_settings_rows( $this->group_id, 'EnableEmailDefaultSetting' );

				foreach ( $existing_setting as $setting_row ) {
					CoreGroupSettings::delete_group_setting( $setting_row->id );
				}
			}
		}

		if ( ( ! empty( $no_enable_old ) || ! empty( $default_email_old ) ) && ! empty( $enabled_permissions ) ) {
			update_metadata( 'rcp_group', $this->group_id, $this->settings_name, $enabled_permissions );
		}

		return get_metadata( 'rcp_group', $this->group_id, $this->settings_name, true );
	}

	/**
	 * Handle Submit
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function handle_submit() {
		$post = sanitize_post( wp_unslash( $_POST ) ) ?? '';

		if ( ! isset( $post[ $this->settings_name . '_nonce' ] ) || ! wp_verify_nonce( $post[ $this->settings_name . '_nonce' ], 'save_' . $this->settings_name ) ) {
			return;
		}

		if ( isset( $post['enabled_permissions'] ) ) {
			update_metadata( 'rcp_group', $this->group_id, $this->settings_name, $post['enabled_permissions'] );

			if ( ! empty( $this->group_id ) ) {
				$group_members = rcpga_get_group_members( array( 'group_id' => $this->group_id ) );

				foreach ( $group_members as $group_member ) {
					$group_user_id = $group_member->get_user_id();

					delete_transient( $group_user_id . '_whitelabel_resource_transient' );
				}
			}
		} else {
			update_metadata( 'rcp_group', $this->group_id, $this->settings_name, '' );
		}
	}
}
