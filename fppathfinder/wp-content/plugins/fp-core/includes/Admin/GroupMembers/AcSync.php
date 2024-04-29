<?php
/**
 * ActiceCampaign Sync
 *
 * @package    FP_Core
 * @subpackage FP_Core/Admin/GroupMembers
 * @author     Objectiv
 * @copyright  Copyright (c) 2020, Objectiv
 * @license    GPL-2.0
 * @version    1.0.0
 */

namespace FP_Core\Admin\GroupMembers;

use \FP_Core\Integrations\Active_Campaign\Custom_Fields;
use \FP_Core\Integrations\Active_Campaign\Cron_Contact_Updater;
use FP_Core\Integrations\Active_Campaign\Tags;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

/**
 * ActiceCampaign Sync
 *
 * @author Jason Witt
 * @since  1.0.0
 */
class AcSync {

	/**
	 * Member ID
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @var int
	 */
	protected $member_id;

	/**
	 * Initialize the class
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function __construct() {
		add_action( 'admin_enqueue_scripts', array( $this, 'scripts' ) );
		add_action( 'wp_ajax_sync_ac_member', array( $this, 'sync_customer' ) );
		add_action( 'wp_ajax_nopriv_sync_ac_member', array( $this, 'sync_customer' ) );
		add_action( 'show_user_profile', array( $this, 'sync_customer_markup' ) );
		add_action( 'edit_user_profile', array( $this, 'sync_customer_markup' ) );
	}

	/**
	 * Sync Custoner
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function sync_customer() {
		$nonce = sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) ?? '';

		if ( ! wp_verify_nonce( $nonce, 'sync-member' ) ) {
			wp_die();
		}

		$user_id = sanitize_text_field( wp_unslash( $_POST['userid'] ?? '' ) );

		if ( ! $user_id ) {
			wp_die();
		}

		$email = ( new \WP_User( $user_id ) )->user_email;

		$group_id = fp_get_group_id( $user_id );

		if ( empty( $group_id ) ) {
			wp_die();
		}

		$mem_level = new Custom_Fields\Group_Membership_Level();
		$mem_level->update_user( $user_id, $group_id );

		$group_name = new Custom_Fields\Group_Name();
		$group_name->update_user( $user_id, $group_id );

		$ind_level = new Custom_Fields\Individual_Membership_Level();
		$ind_level->update_user( $user_id, $group_id );

		$access_level = new Custom_Fields\Membership_Access_Level();
		$access_level->update_user( $user_id, $group_id );

		$expr_date = new Custom_Fields\Membership_Expiration_Date();
		$expr_date->update_user( $user_id, $group_id );

		$start_date = new Custom_Fields\Membership_Start_Date();
		$start_date->update_user( $user_id, $group_id );

		$start_date = new Custom_Fields\Membership_Status();
		$start_date->update_user( $user_id, $group_id );

		$start_list = new Custom_Fields\MasterList();
		$start_list->update_user( $user_id, $group_id );

		Cron_Contact_Updater::process_updates();

		$tag = new Tags();
		$tag->add( $email, 'Member Updated' );

		echo 'success';
		wp_die();
	}

	/**
	 * Sync Customer
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function sync_customer_markup( $user ) {
		$nonce = wp_create_nonce( 'sync-member' );

		?>
		<h3>Re-sync to ActiveCampaign</h3>

		<table class="form-table">
			<tr>
				<th>
					<a href="" id="member-sync-button" class="button" data-nonce="<?php echo esc_attr( $nonce ); ?>" data-userid="<?php echo esc_attr( $user->ID ); ?>"><?php _e( 'Sync Member', 'fp-core' ); ?></a>
					<span id="member-sync-indicator" style="display: inline-block; line-height: 2; height: 30px; margin-left: 16px;"></span>
				</th>
			</tr>
		</table>
		<?php
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
		if ( 'user-edit.php' !== $hook ) {
			return;
		}

		$filepath = 'src/js/members-sync.js';
		$file     = FP_CORE_DIR_PATH . $filepath;
		$version  = file_exists( $file ) ? filemtime( $file ) : '1.0.0';

		wp_register_script( 'fp_members_sync', FP_CORE_DIR_URL . $filepath, array(), $version, true );
		wp_enqueue_script( 'fp_members_sync' );
	}
}
