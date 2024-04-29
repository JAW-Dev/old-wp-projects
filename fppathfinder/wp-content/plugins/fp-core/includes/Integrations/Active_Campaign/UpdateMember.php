<?php
/**
 * Update Member
 *
 * @package    FP_Core
 * @subpackage FP_Core/Integrations/ActiveCampaign
 * @author     Objectiv
 * @copyright  Copyright (c) 2020, Objectiv
 * @license    GPL-2.0
 * @version    1.0.0
 */

namespace FP_Core\Integrations\Active_Campaign;

use FP_Core\Member;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

/**
 * Update Profile
 *
 * @author Jason Witt
 * @since  1.0.0
 */
class UpdateMember extends AcCore {

	/**
	 * Initialize the class
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function __construct() {
		$settings = get_option( 'rcp_activecampaign_settings' );

		if ( empty( $settings ) || empty( $settings['api_url'] ) || empty( $settings['api_key'] ) ) {
			return false;
		}

		$this->ac_api = new \ActiveCampaign( $settings['api_url'], $settings['api_key'] );

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
		add_action( 'rcp_user_profile_updated', [ $this, 'maybe_update_email' ], 999, 3 );
	}

	/**
	 * Update Email
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param int   $user_id  The user ID.
	 * @param array $userdata {
	 *     The user data to update.
	 *
	 *     @type int    $ID           The user ID.
	 *     @type string $first_name   The user's first name.
	 *     @type string $last_name    The user's last name.
	 *     @type string $display_name The user's display name.
	 *     @type string $user_email   The user's email.
	 *     @type string $user_pass    The user's password.
	 * }
	 * @param WP_User $old_data The old user data.
	 *
	 * @return object|boolean
	 */
	public function maybe_update_email( ?int $user_id, array $userdata, $old_data ) {
		// Profile field change request
		if ( empty( $_POST['rcp_action'] ) || $_POST['rcp_action'] !== 'edit_user_profile' || ! is_user_logged_in() ) {
			return false;
		}

		// Nonce security
		if ( ! wp_verify_nonce( $_POST['rcp_profile_editor_nonce'], 'rcp-profile-editor-nonce' ) ) {
			return false;
		}

		if ( $userdata['user_email'] === $old_data->user_email ) {
			return false;
		}

		if ( ! class_exists( 'Stripe\Stripe' ) ) {
			require_once RCP_PLUGIN_DIR . 'includes/libraries/stripe/init.php';
		}

		global $rcp_options;

		if ( rcp_is_sandbox() ) {
			$secret_key = isset( $rcp_options['stripe_test_secret'] ) ? trim( $rcp_options['stripe_test_secret'] ) : '';
		} else {
			$secret_key = isset( $rcp_options['stripe_live_secret'] ) ? trim( $rcp_options['stripe_live_secret'] ) : '';
		}

		if ( empty( $secret_key ) ) {
			return;
		}

		\Stripe\Stripe::setApiKey( $secret_key );

		$member      = new Member( get_current_user_id() );
		$membership  = $member->get_membership();
		$customer_id = $membership->get_gateway_customer_id();
		$updated     = [
			'email' => $userdata['user_email'],
		];

		\Stripe\Customer::update( $customer_id, $updated );
	}
}
