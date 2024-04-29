<?php
/**
 * ActiceCampaign Group Sync
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
 * ActiceCampaign Group Sync
 *
 * @author Jason Witt
 * @since  1.0.0
 */
class AcSyncGroup {

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
		add_action( 'wp_ajax_sync_ac_group_member', array( $this, 'sync_customer' ) );
		add_action( 'wp_ajax_nopriv_sync_ac_group_member', array( $this, 'sync_customer' ) );
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

		if ( ! wp_verify_nonce( $nonce, 'sync-group-members' ) ) {
			wp_die();
		}

		$group_id = sanitize_text_field( wp_unslash( $_POST['groupid'] ?? '' ) );

		if ( ! $group_id ) {
			wp_die();
		}

		$offset = sanitize_text_field( wp_unslash( $_POST['offset'] ?? '' ) );
		$limit  = 10;

		$members_args = array(
			'group_id' => $group_id,
			'number'   => $limit,
			'offset'   => absint( $offset ),
		);

		$members = rcpga_get_group_members( $members_args );

		$total_members_args = array(
			'group_id' => $group_id,
			'number'   => 99999,
		);

		$total = sanitize_text_field( wp_unslash( $_POST['total'] ?? '' ) );

		if ( empty( $total ) ) {
			$get_members = rcpga_get_group_members( $total_members_args );
			$total       = count( $get_members );
		}

		foreach ( $members as $member ) {
			$member_id = $member->get_user_id();
			$email     = ( new \WP_User( $member_id ) )->user_email;

			$mem_level = new Custom_Fields\Group_Membership_Level();
			$mem_level->update_user( $member_id, $group_id );

			$group_name = new Custom_Fields\Group_Name();
			$group_name->update_user( $member_id, $group_id );

			$ind_level = new Custom_Fields\Individual_Membership_Level();
			$ind_level->update_user( $member_id, $group_id );

			$access_level = new Custom_Fields\Membership_Access_Level();
			$access_level->update_user( $member_id, $group_id );

			$expr_date = new Custom_Fields\Membership_Expiration_Date();
			$expr_date->update_user( $member_id, $group_id );

			$start_date = new Custom_Fields\Membership_Start_Date();
			$start_date->update_user( $member_id, $group_id );

			$start_date = new Custom_Fields\Membership_Status();
			$start_date->update_user( $member_id, $group_id );

			$start_list = new Custom_Fields\MasterList();
			$start_list->update_user( $member_id, $group_id );

			$tag = new Tags();
			$tag->add( $email, 'Member Updated' );
		}

		Cron_Contact_Updater::process_updates();

		echo json_encode(
			array(
				'offset' => absint( $offset ) + $limit,
				'total'  => absint( $total ),
			)
		);
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
		if ( 'restrict_page_rcp-groups' !== $hook ) {
			return;
		}

		$filepath = 'src/js/members-sync.js';
		$file     = FP_CORE_DIR_PATH . $filepath;
		$version  = file_exists( $file ) ? filemtime( $file ) : '1.0.0';

		wp_register_script( 'fp_members_sync', FP_CORE_DIR_URL . $filepath, array(), $version, true );
		wp_enqueue_script( 'fp_members_sync' );
	}
}
