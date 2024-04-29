<?php
/**
 * ActiveCampaign Sync
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
 * ActiveCampaign Sync
 *
 * @author Jason Witt
 * @since  1.0.0
 */
class AcSync {

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
		add_filter( 'rcp_tools_tabs', [ $this, 'tab' ] );
		add_action( 'rcp_tools_tab_acsync', [ $this, 'markup' ] );
		add_action( 'wp_ajax_tools_ac_sync', [ $this, 'sync' ] );
		add_action( 'wp_ajax_nopriv_tools_ac_sync', [ $this, 'sync' ] );
	}

	/**
	 * Tab
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function tab( $tabs ) {
		$tabs['acsync'] = __( 'AC Sync', 'rcp' );

		return $tabs;
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
		$nonce = wp_create_nonce( 'tools-ac-sync' );
		?>
		<div id="acsync-users" class="acsync__users">
			<h3 for="acsync-users-emails-list">Emails</h3>
			<textarea id="acsync-user-emails-list" rows="20" style="width: 800px; height: 400px;"></textarea>
			<div style="display: flex; align-items: center; width: 800px;">
				<p>
					<button id="acsync-users-submit" class="button-secondary" data-nonce="<?php echo esc_attr( $nonce ); ?>">Sync Users</button>
				</p>
				<div id="progress-bar" style="position: relative; background: white; padding: 5px 0; text-align: center; flex-grow: 1; margin-left: 1rem; height: 20px;">
					<div style="position: relative; z-index: 100; width: 100%; text-align: center; font-weight: bold;"><span id="progress-bar-percent">0</span>%</div>
					<div id="progress-bar-progress" style="position:absolute; z-index: 1; top: 0; left: 0; height: 100%; width: 0%; background: CornflowerBlue;"></div>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Sync
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function sync() {
		$post  = $_POST;
		$cycle = 0;

		$user_id = ! empty( $post['userid'] ) ? (int) $post['userid'] : 0;
		$emails  = ! empty( $post['emails'] ) ? (array) $post['emails'] : [];
		$total   = ! empty( $_POST['total'] ) ? (int) $_POST['total'] : 0;
		$cycle   = ! empty( $_POST['cycle'] ) ? (int) $_POST['cycle'] : 0;
		$percent = ! empty( $_POST['percent'] ) ? (int) $_POST['percent'] : 0;

		if ( $user_id && empty( $emails ) ) {
			$user     = get_user_by( 'ID', $user_id );
			$emails[] = $user->user_email;
		}

		if ( empty( $emails ) ) {
			wp_die();
		}

		foreach ( $emails as $key => $value ) {
			if ( empty( $value ) ) {
				unset( $emails[ $key ] );
			}
		}

		if ( empty( $emails ) ) {
			echo 'No users to sync!';
			wp_die();
		}

		foreach ( $emails as $key => $value ) {
			set_time_limit( 0 );
			$user = get_user_by( 'email', $value );

			if ( empty( $user ) ) {
				continue;
			}

			$user_id  = $user->ID;
			$group_id = function_exists( 'fp_get_group_id' ) ? fp_get_group_id( $user_id ) : '';

			( new Custom_Fields\Group_Membership_Level() )->update_user( $user_id, $group_id );
			( new Custom_Fields\Group_Name() )->update_user( $user_id, $group_id );
			( new Custom_Fields\Individual_Membership_Level() )->update_user( $user_id, $group_id );
			( new Custom_Fields\Membership_Access_Level() )->update_user( $user_id, $group_id );
			( new Custom_Fields\Membership_Expiration_Date() )->update_user( $user_id, $group_id );
			( new Custom_Fields\Membership_Start_Date() )->update_user( $user_id, $group_id );
			( new Custom_Fields\Membership_Status() )->update_user( $user_id, $group_id );
			( new Custom_Fields\MasterList() )->update_user( $user_id, $group_id );
			( new Tags() )->add( $value, 'Member Updated' );

			unset( $emails[ $key ] );
			$cycle++;
			break;
		}

		$remainder = count( $emails );

		$data = json_encode(
			[
				'total'     => (int) $total,
				'remainder' => $remainder,
				'cycle'     => $cycle,
				'emails'    => $emails,
				'percent'   => $percent,
			]
		);

		echo $data;
		wp_die();
	}

	/**
	 * Scripts
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param string $hook The page hook.
	 *
	 * @return void
	 */
	public function scripts( $hook ) {
		if ( 'restrict_page_rcp-tools' !== $hook ) {
			return;
		}

		$filepath = 'src/js/members-sync.js';
		$file     = FP_CORE_DIR_PATH . $filepath;
		$version  = file_exists( $file ) ? filemtime( $file ) : '1.0.0';

		wp_register_script( 'fp_members_sync', FP_CORE_DIR_URL . $filepath, array(), $version, true );
		wp_enqueue_script( 'fp_members_sync' );
	}
}
