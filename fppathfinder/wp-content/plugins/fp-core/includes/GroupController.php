<?php
/**
 * Group Controller
 *
 * @package    FP_Core
 * @author     Objectiv
 * @copyright  Copyright (c) 2020, Objectiv
 * @license    GPL-2.0
 * @version    1.0.0
 */

namespace FP_Core;

use \FP_Core\Group_Settings\Settings\GroupMembersDiscountCode;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

/**
 * Group Controller
 *
 * @author John Geesey|Jason Witt
 * @since  1.0.0
 */
class GroupController {
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
	 * Initialize the class
	 *
	 * @author John Geesey|Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function hooks() {
		add_action( 'rcp_transition_group_member_role', [ $this, 'handle_member_role_transition' ], 100, 3 );
		add_filter( 'rcpga_notice_message', [ $this, 'alter_invite_landing_page_notice' ], 10, 2 );
	}

	/**
	 * After Invite Landing Page Notice
	 *
	 * @author John Geesey|Jason Witt
	 * @since  1.0.0
	 *
	 * @return string
	 */
	public function alter_invite_landing_page_notice( $message, $notice ) {
		return 'invite-accepted' === $notice ? "Group membership confirmed. Please update your info below and if you're a new user please make sure to set your password." : $message;
	}

	/**
	 * Handle Member Role Transition
	 *
	 * @author John Geesey|Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function handle_member_role_transition( $old_role, $new_role, $group_member_id ) {
		$is_entering_group = in_array( $old_role, array( 'invited', 'new' ) ) && in_array( $new_role, array( 'member', 'admin', 'owner' ) );

		if ( ! $is_entering_group ) {
			return;
		}

		$group_member = rcpga_get_group_member_by_id( $group_member_id );
		$user_id      = $group_member->get_user_id();
		$member       = new Member( $user_id );
		$membership   = $member->get_membership();

		if ( ! $membership || ! $membership->is_active() ) {
			return;
		}

		$group = $group_member->get_group();

		if ( ! $group->is_active() ) {
			return;
		}

		$membership_level_id       = $membership->get_object_id();
		$membership_access_level   = rcp_get_subscription_access_level( $membership_level_id );
		$group_membership_level_id = $group->get_membership()->get_object_id();
		$group_access_level        = rcp_get_subscription_access_level( $group_membership_level_id );

		if ( $group_access_level >= $membership_access_level ) { // they no longer need their individual membership
			$this->setup_cancellation_notification_email_filters( $group->get_name(), $membership->get_id() );

			$successful_cancellation = $membership->cancel_payment_profile( true );

			if ( $successful_cancellation ) {
				rcp_add_membership_meta( $membership->get_id(), 'cancelled_by_group_membership', date( 'm/d/Y h:i:s a', time() ) );
				( new ProrationController() )->disable( $membership->get_id() );
			}

			return;
		}

		$group_discount_code = ( new GroupMembersDiscountCode() )->get( $group_member->get_group_id() );

		if ( empty( $group_discount_code ) ) {
			return;
		}

		$success = Existing_Membership_Discounter::apply_discount( $membership->get_id(), $group_discount_code );

		if ( ! $success ) {
			return;
		}

		$this->send_discount_notification_email( $user_id, $group->get_name() );
	}

	/**
	 * Send Discount Notification Email
	 *
	 * @author John Geesey|Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	private function send_discount_notification_email( int $user_id, string $group_name ) {
		$user_data  = get_userdata( $user_id );
		$first_name = $user_data->get( 'first_name' );
		$to         = $user_data->user_email;
		$subject    = 'Your Membership has been updated!';
		$message    = "<p>Hi $first_name,</p>
		<p>We have some exciting news for you.</p>
		<p>Recently, you have joined an Enterprise Membership based on your connection with $group_name.  Going forward, $group_name will cover a portion of the cost for fpPathfinder Membership.  That means that your cost for your fpPathfinder membership will decrease!</p>
		<p>This email serves as confirmation that your account has been updated to reflect the change in the billing status.  You don't need to do anything else.  You can access fpPathfinder just like you always have.</p>
		<p>If you have any questions, <a href='mailto:support@fppathfinder.com'>please let me know</a>.</p>
		<p>Sincerely,</p>
		<p>Mike Lecours<br>Co-Founder<br><a href='https://www.fppathfinder.com'>fpPathfinder.com</a></p>";

		wp_mail( $to, $subject, $message, 'Content-type: text/html' );
	}

	/**
	 * Setup Cancelation Notification Email Filter
	 *
	 * @author John Geesey|Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	private function setup_cancellation_notification_email_filters( string $group_name, int $membership_id ) {

		$message_filter = function( $message, $user_id, $status, $membership ) use ( $group_name, $membership_id ) {
			if ( $membership->get_id() !== $membership_id ) {
				return $message;
			}

			$first_name = get_userdata( $user_id )->get( 'first_name' );

			return "<p>Hi $first_name,</p>
			<p>We have some exciting news for you.</p>
			<p>Recently, you have joined an Enterprise Membership based on your connection with $group_name.  Going forward, $group_name will cover the cost of your fpPathfinder membership at your current tier.  That means that you will no longer have to pay for your current level of fpPathfinder membership!</p>
			<p>This email serves as confirmation that your account has been updated to reflect the change in the billing status.  You don't need to do anything else.  You can access fpPathfinder just like you always have.  The only difference is that you will no longer be charged for your current level of access to fpPathfinder.</p>
			<p>One of the additional benefits with Enterprise Memberships is that you will receive a special discount if you ever decide to <a href='https://fppathfinder.com/become-a-member'>upgrade to Deluxe or Premier</a>.</p>
			<p>If you have any questions, <a href='mailto:support@fppathfinder.com'>please let me know</a>.</p>
			<p>Sincerely,</p>
			<p>Mike Lecours<br>Co-Founder<br><a href='https://www.fppathfinder.com'>fpPathfinder.com</a></p>";
		};

		$subject_filter = function( $subject, $user_id, $status, $membership ) use ( $membership_id ) {
			if ( $membership->get_id() !== $membership_id ) {
				return $subject;
			}

			return 'Your fpPathfinder Membership has been updated!';
		};

		add_filter( 'rcp_subscription_cancelled_email', $message_filter, 10, 4 );
		add_filter( 'rcp_subscription_cancelled_subject', $subject_filter, 10, 4 );
	}
}
