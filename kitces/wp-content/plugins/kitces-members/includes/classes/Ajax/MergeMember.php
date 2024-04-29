<?php
/**
 * Merge Member
 *
 * @package    Kitces_Members
 * @subpackage Kitces_Members/Inlcudes/Classes/Ajax
 * @author     Objectiv
 * @copyright  Copyright (c) 2021, Objectiv
 * @license    GNU General Public License v2 or later
 * @since      1.0.0
 */

namespace Kitces_Members\Includes\Classes\Ajax;

use Kitces_Members\Includes\Classes\MemberActions\MergeMembers;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

/**
 * Merge Member
 *
 * @author Jason Witt
 * @since  1.0.0
 */
class MergeMember {

	/**
	 * AC API
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @var object
	 */
	protected $ac_api;

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
		add_action( 'wp_ajax_merge_member', array( $this, 'merge_member' ) );
		add_action( 'wp_ajax_nopriv_merge_member', array( $this, 'merge_member' ) );
	}

	/**
	 * Merge Member
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function merge_member() {
		$post = sanitize_post( wp_unslash( $_POST ) ) ?? '';

		if ( empty( $post ) ) {
			wp_die();
		}

		$member_email = isset( $post['member_email'] ) ? $post['member_email'] : '';

		if ( ! wp_verify_nonce( $post['nonce'], 'merge-member' ) ) {
			wp_die();
		}

		if ( empty( $post['member_email'] ) ) {
			echo 'no-member-email';
			wp_die();
		}

		$destination_user_id = $post['user_id'];
		$second_user         = get_user_by( 'email', $post['member_email'] );

		if ( empty( $second_user ) ) {
			echo 'not-a-user';
			wp_die();
		}

		$source_user_id = $second_user->ID;

		if ( empty( $source_user_id ) ) {
			echo 'no-user-id-error';
			wp_die();
		}

		( new MergeMembers() )->init( array( $source_user_id, $destination_user_id ) );

		echo 'success';
		wp_die();
	}
}
