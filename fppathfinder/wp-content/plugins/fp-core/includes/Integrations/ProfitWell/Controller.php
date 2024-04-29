<?php

namespace FP_Core\Integrations\ProfitWell;

use FP_Core\Utilities;
use FP_Core\Member;

class Controller {
	static public function add_hooks() {
		// New membership
		add_action( 'rcp_successful_registration', __CLASS__ . '::membership_added' );

		// Upgraded membership
		add_action( 'rcp_successful_registration', __CLASS__ . '::membership_upgraded' );

		// Declined card canceled membership
		add_action( 'rcp_recurring_payment_failed', __CLASS__ . '::delinquent_card', 10, 2 );

		// Membership unexpired
		add_action( 'rcp_transition_membership_status', __CLASS__ . '::membership_unexpired', 10, 3 );

		// Membership Expired
		add_action( 'rcp_transition_membership_status_expired', __CLASS__ . '::membership_expired', 10, 2 );
	}

	/**
	 * Membership Added
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public static function membership_added( $member ) {
		$customer = rcp_get_customer_by_user_id( $member->ID );

		if ( empty( $customer ) ) {
			return;
		}

		$memberships = $customer->get_memberships();

		foreach ( $memberships as $membership ) {

			if ( empty( $membership ) ) {
				return;
			}

			if ( self::membership_should_be_excluded( $membership ) ) {
				return;
			}

			$user_id    = $membership->get_user_id();
			$user_data  = '';
			$user_email = '';

			if ( ! empty( $user_id ) ) {
				$user_data  = get_userdata( $user_id );
				$user_email = $user_data->user_email;
			}

			$response = ProfitWell_API::search_for_user( $user_email );
			$body     = json_decode( $response['body'] );

			if ( $membership->get_upgraded_from() && ! empty( $body ) ) {
				return;
			}

			if ( empty( $body ) ) {
				self::create_subscription( $customer, $membership );
			}
		}
	}

	/**
	 * Membership Upgraded
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param int $membership_id The membership ID.
	 *
	 * @return void
	 */
	public static function membership_upgraded( $member ) {
		$customer = rcp_get_customer_by_user_id( $member->ID );

		if ( empty( $customer ) ) {
			return;
		}

		$memberships = $customer->get_memberships();

		foreach ( $memberships as $membership ) {

			if ( empty( $membership ) ) {
				return;
			}

			if ( self::membership_should_be_excluded( $membership ) ) {
				return;
			}

			$user_id    = $membership->get_user_id();
			$user_data  = '';
			$user_email = '';

			if ( ! empty( $user_id ) ) {
				$user_data  = get_userdata( $user_id );
				$user_email = $user_data->user_email;
			}

			$response = ProfitWell_API::search_for_user( $user_email );
			$body     = json_decode( $response['body'] );

			if ( $membership->get_upgraded_from() && ! empty( $body ) ) {
				self::update_subscription( $membership );
				return;
			}
		}
	}

	/**
	 * Delinquent Card
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param RCP_Member          $member
	 * @param RCP_Payment_Gateway $gateway
	 *
	 * @return void
	 */
	public static function delinquent_card( $member, $gateway ) {
		$membership = $gateway->membership;

		if ( empty( $membership ) ) {
			return;
		}

		$user_id    = $membership->get_user_id();
		$user_data  = '';
		$user_email = '';

		if ( ! empty( $user_id ) ) {
			$user_data  = get_userdata( $user_id );
			$user_email = $user_data->user_email;
		}

		$membership_id = $membership->get_id();

		if ( self::membership_should_be_excluded( $membership ) ) {
			return;
		}

		// Bail if is trial membership.
		if ( $membership->get_trial_end_date() > 0 ) {
			return;
		}

		$unix_effective = strtotime( gmdate( 'Y-m-d H:i:s' ) );

		$subscription_id = Subscription_Id_Meta_Manager::get( $membership_id );
		ProfitWell_API::churn_subscription( $user_id, $user_email, $subscription_id, $unix_effective, 'delinquent' );

		$membership->add_note( 'Profitwell API: Subscription Chrned Delinquent' );
	}

	/**
	 * Membership Unexpired
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param string $old_status    The old status.
	 * @param string $new_status    The new status.
	 * @param int    $membership_id The membership ID.
	 *
	 * @return void
	 */
	public static function membership_unexpired( $old_status, $new_status, $membership_id ) {
		$membership = rcp_get_membership( $membership_id );

		if ( empty( $membership ) ) {
			return;
		}

		$user_id    = $membership->get_user_id();
		$user_data  = '';
		$user_email = '';

		if ( ! empty( $user_id ) ) {
			$user_data  = get_userdata( $user_id );
			$user_email = $user_data->user_email;
		}

		$subscription_id = Subscription_Id_Meta_Manager::get( $membership_id );

		if ( self::membership_should_be_excluded( $membership ) ) {
			return;
		}

		if ( 'expired' === $old_status && $new_status !== 'cancelled' ) {
			$response = ProfitWell_API::unchurn_subscription( $user_id, $user_email, $subscription_id );

			if ( empty( $response ) ) {
				return;
			}

			$membership->add_note( 'Profitwell API: Unchurned Subscription' );
		}
	}

	/**
	 * Membership Expires
	 *
	 * Runs when membership status is changed to expired.
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param string $old_status    The old status.
	 * @param int    $membership_id The membership ID.
	 *
	 * @return void
	 */
	public static function membership_expired( $old_status, $membership_id ) {
		$membership = rcp_get_membership( $membership_id );

		if ( empty( $membership ) ) {
			return;
		}

		$user_id    = $membership->get_user_id();
		$user_data  = '';
		$user_email = '';

		if ( ! empty( $user_id ) ) {
			$user_data  = get_userdata( $user_id );
			$user_email = $user_data->user_email;
		}

		// Bail if is tiral membership.
		if ( $membership->get_trial_end_date() > 0 ) {
			return;
		}

		$user_id = $membership->get_customer()->get_user_id();

		$groups = Utilities::get_groups( $user_id );

		if ( ! empty( $groups ) ) {
			return;
		}

		// Bail on expired and new $old_status.
		if ( 'expired' === $old_status || 'new' === $old_status || self::membership_should_be_excluded( $membership ) ) {
			return;
		}

		$unix_effective = strtotime( gmdate( 'Y-m-d H:i:s' ) );

		$subscription_id = Subscription_Id_Meta_Manager::get( $membership_id );
		$response        = ProfitWell_API::churn_subscription( $user_id, $user_email, $subscription_id, $unix_effective );

		if ( empty( $response ) ) {
			return;
		}

		$membership->add_note( 'Profitwell API: Churned Subscription' );
	}

	static public function create_subscription( \RCP_Customer $customer, \RCP_Membership $membership ) {

		if ( empty( $customer ) || empty( $membership ) ) {
			return;
		}

		if ( self::membership_should_be_excluded( $membership ) ) {
			return;
		}

		$user_id        = $customer->get_user_id();
		$email          = ( new \WP_User( $user_id ) )->user_email;
		$plan_id        = Plan_Creator::get_id( $membership );
		$value_in_cents = floatval( $membership->get_recurring_amount( false ) ) * 100;
		$unix_effective = strtotime( $membership->get_activated_date() ?? $membership->get_created_date( false ) );
		$plan_interval  = 'year';
		$response       = ProfitWell_API::create_subscription( $email, $plan_id, $value_in_cents, $unix_effective, $plan_interval, '', $user_id );

		if ( empty( $response ) ) {
			return;
		}

		$response_body = json_decode( $response['body'] );

		Subscription_Id_Meta_Manager::set( $response_body->subscription_id, $membership->get_id() );
		User_Id_Meta_Manager::set( intval( $user_id ), $response_body->user_id );

		$membership->add_note( 'Profitwell API: Created New Subscription' );

		return;
	}

	static public function update_subscription( \RCP_Membership $membership ) {

		if ( empty( $membership ) ) {
			return;
		}

		if ( self::membership_should_be_excluded( $membership ) ) {
			return;
		}

		$user_id    = $membership->get_user_id();
		$user_data  = '';
		$user_email = '';

		if ( ! empty( $user_id ) ) {
			$user_data  = get_userdata( $user_id );
			$user_email = $user_data->user_email;
		}

		$subscription_id = Subscription_Id_Meta_Manager::get( $membership->get_id() );
		$new_plan_id     = Plan_Creator::get_id( $membership );
		$value_in_cents  = floatval( $membership->get_recurring_amount( false ) ) * 100;
		$unix_effective  = strtotime( $membership->get_activated_date() ?? $membership->get_created_date() );
		$response        = ProfitWell_API::update_subscription( $user_id, $user_email, $subscription_id, $new_plan_id, $value_in_cents, $unix_effective );

		if ( empty( $response ) ) {
			return;
		}

		$membership->add_note( 'Profitwell API: Updated subscription' );

		Subscription_Id_Meta_Manager::set( $subscription_id, $membership->get_id() );
	}

	static public function membership_should_be_excluded( \RCP_Membership $membership ) {

		if ( empty( $membership ) ) {
			return true;
		}

		if ( rcpga_is_level_group_accounts_enabled( $membership->get_object_id() ) ) {
			return true;
		}

		if ( 'none' === $membership->get_expiration_date() ) {
			// echo "no expiration date\n";
			return true;
		}

		// Free or Trial membership
		if ( ( $membership->get_object_id() === '9' || $membership->get_initial_amount() === '0.00' ) && ! $membership->get_upgraded_from() ) {
			return true;
		}

		// Comped Membership.
		if ( rcp_get_membership_meta( $membership->get_id(), 'comped_membership', true ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Update User Data
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public static function update_user_data( $user_id ) {
		$user       = get_user_by( 'ID', $user_id );
		$email      = $user->user_email;
		$member     = new Member( $user_id );
		$membership = $member->get_membership();

		$search_response = ProfitWell_API::search_for_user( $email );

		if ( ! $search_response['response'] || 200 !== $search_response['response']['code'] ) {
			return;
		}

		$search_body = json_decode( $search_response['body'] );

		if ( empty( $search_body ) ) {
			return;
		}

		$customer_id       = end( $search_body )->customer_id;
		$saved_customer_id = User_Id_Meta_Manager::get( $user_id );

		if ( $customer_id !== $saved_customer_id ) {
			User_Id_Meta_Manager::set( intval( $user_id ), $customer_id );
		}

		$history_response = ProfitWell_API::get_user_history( $customer_id );

		if ( ! $history_response['response'] || 200 !== $history_response['response']['code'] ) {
			return;
		}

		$history_body = json_decode( $history_response['body'] );

		if ( empty( $history_body ) ) {
			return;
		}

		$subsciption_id = end( $history_body )->subscription_id;

		if ( ! empty( $membership ) ) {
			$saved_subsciption_id = Subscription_Id_Meta_Manager::get( $membership->get_id() );

			if ( $subsciption_id !== $saved_subsciption_id ) {
				Subscription_Id_Meta_Manager::set( $subsciption_id, intval( $membership->get_id() ) );
			}
		}

		return array(
			'customer_id'    => $customer_id,
			'subsciption_id' => $subsciption_id,
		);
	}
}
