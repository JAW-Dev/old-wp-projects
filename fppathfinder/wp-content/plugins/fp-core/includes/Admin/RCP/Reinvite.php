<?php
/**
 * Reinvite
 *
 * @package    FP_Core
 * @subpackage FP_Core/Admin/RCP
 * @author     Objectiv
 * @copyright  Copyright (c) 2020, Objectiv
 * @license    GPL-2.0
 * @version    1.0.0
 */

namespace FP_Core\Admin\RCP;

use \FP_Core\Integrations\Active_Campaign\Custom_Fields;
use \FP_Core\Integrations\Active_Campaign\Cron_Contact_Updater;
use FP_Core\Integrations\Active_Campaign\Tags;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

/**
 * Reinvite
 *
 * @author Jason Witt
 * @since  1.0.0
 */
class Reinvite {

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
		if ( ! is_admin() ) {
			return;
		}

		add_action( 'restrict_page_rcp-groups', [ $this, 'markup' ], 9999 );
		add_action( 'admin_init', [ $this, 'resend_invites' ] );
		add_action( 'admin_notices', [ $this, 'notice' ] );
	}

	/**
	 * Notice
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function notice() {
		if ( empty( $_REQUEST['mass-invite'] ) ) {
			return;
		}

		?>
		<div class="error notice is-dismissible">
			<p>Mass invites has been sent!</p>
		</div>
		<?php
	}

	/**
	 * Resend Invites
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function resend_invites() {
		if ( empty( $_REQUEST['mass-invite'] ) ) {
			return;
		}

		$group_id = ! empty( $_REQUEST['rcpga-group'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['rcpga-group'] ) ) : '';

		if ( empty( $group_id ) ) {
			return;
		}

		$group_members = rcpga_get_group_members(
			[
				'group_id' => $group_id,
				'role'     => 'invited',
			]
		);

		foreach ( $group_members as $group_member ) {
			rcpga_send_group_invite( $group_member->get_user_id(), $group_id );
		}
	}

	/**
	 * Markup
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function markup() {
		$group_id = sanitize_text_field( wp_unslash( absint( $_GET['rcpga-group'] ?? '' ) ) );

		if ( ! $group_id ) {
			return;
		}

		$url = add_query_arg( array( 'mass-invite' => 'true' ), "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]" );

		?>
		<div id="mass-invite" class="mass-invite" style="padding: 1rem;">
			<h3>Reinvite Members</h3>
			<p>
				<a href="<?php echo esc_url( $url ); ?>" id="mass-invite-submit"  class="button button-primary">Mass Reinvite Members</a>
			</p>
		</div>
		<?php
	}
}
