<?php

namespace FP_Core\Integrations\Active_Campaign\Custom_Fields;

class Membership_Cancellation_Date extends Custom_Field {
	public function __construct() {
		parent::__construct();
	}

	/**
	 * Get the unique tag for this custom field in AC.
	 *
	 * Can be found here: https://fppathfinder.activehosted.com/app/fields/contacts
	 */
	public function get_tag(): string {
		return 'MEMBERSHIP_CANCELLATION_DATE';
	}

	public function add_hooks(): void {
		add_action( 'rcp_membership_post_cancel', $this->build_safe_method( array( $this, 'cancel_action_handler' ) ), 10, 2 );
		// possibly change to set_expiration date
	}

	public function cancel_action_handler( int $membership_id, \RCP_Membership $membership ) {
		if ( empty( $membership ) ) {
			return;
		}

		$user_id = $membership->get_customer()->get_user_id();

		if ( empty( $user_id ) ) {
			return;
		}

		$cancellation_date = substr( $membership->get_cancellation_date( false ), 0, 10 );

		if ( empty( $cancellation_date ) ) {
			return;
		}

		$email = ( new \WP_User( $user_id ) )->user_email;

		if ( empty( $email ) ) {
			return;
		}

		parent::add_update( $email, $cancellation_date );
	}
}
