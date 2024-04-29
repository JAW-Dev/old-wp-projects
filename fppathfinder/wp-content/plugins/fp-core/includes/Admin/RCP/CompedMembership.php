<?php
/**
 * Comped Membership
 *
 * @package    FP_Core
 * @subpackage FP_Core/Admin/RCP
 * @author     Objectiv
 * @copyright  Copyright (c) 2020, Objectiv
 * @license    GPL-2.0
 * @version    1.0.0
 */

namespace FP_Core\Admin\RCP;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

/**
 * Comped Membership
 *
 * @author Jason Witt
 * @since  1.0.0
 */
class CompedMembership {

	/**
	 * Initialize the class
	 *
	 * @author Jason Witt
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
		add_action( 'admin_enqueue_scripts', [ $this, 'scripts' ] );
		add_action( 'rcp_add_membership_after', [ $this, 'comped_field_add' ] );
		add_action( 'rcp_edit_membership_after', [ $this, 'comped_field_edit' ] );
		add_action( 'rcp_new_membership_added', [ $this, 'save' ] );
		add_action( 'rcp_after_membership_admin_update', [ $this, 'update' ] );
	}

	/**
	 * Comped Field Add
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function comped_field_add() {
		$this->markup();
	}

	/**
	 * Comped Field Edit
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function comped_field_edit() {
		$membership_id = sanitize_text_field( wp_unslash( $_GET['membership_id'] ?? '' ) );
		$comped_value  = ! empty( $membership_id ) ? rcp_get_membership_meta( $membership_id, 'comped_membership', true ) : '';

		if ( $comped_value ) {
			$this->markup( $comped_value );
		}
	}

	/**
	 * Markup
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param boolean $comped_value The filed value.
	 *
	 * @return void
	 */
	public function markup( $comped_value = 0 ) {
		?>
		<tr>
			<th scope="row" class="row-title">
				<label for="rcp-comped-membership"><?php _e( 'Comped Membership:', 'rcp' ); ?></label>
			</th>
			<td>
				<input type="checkbox" name="comped-membership" id="rcp-comped-membership" value="1" <?php checked( 1, $comped_value ); ?>/>
				<span class="rcp-help-tip dashicons dashicons-editor-help" title="<?php esc_attr_e( 'Note: This membership doesn\'t send data to Profitwell.', 'rcp' ); ?>"></span>
			</td>
		</tr>
		<?php
	}

	/**
	 * Scripts
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function scripts( $hook ) {
		if ( $hook !== 'toplevel_page_rcp-members' ) {
			return;
		}

		$filename = 'src/js/comped-membership.js';
		$file     = FP_CORE_DIR_PATH . $filename;
		$version  = file_exists( $file ) ? filemtime( $file ) : '1.0.0';

		wp_register_script( 'fp-comped-membership', FP_CORE_DIR_URL . $filename, array(), $version, true );
		wp_enqueue_script( 'fp-comped-membership' );
	}

	/**
	 * Save
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function save( $membership_id ) {
		$comped = $_POST['comped-membership'] ?? 0;
		rcp_update_membership_meta( $membership_id, 'comped_membership', $comped );
	}

	/**
	 * Update
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function update( $membership ) {
		$comped        = $_POST['comped-membership'] ?? 0;
		$membership_id = $membership->get_id();
		rcp_update_membership_meta( $membership_id, 'comped_membership', $comped );
	}
}
