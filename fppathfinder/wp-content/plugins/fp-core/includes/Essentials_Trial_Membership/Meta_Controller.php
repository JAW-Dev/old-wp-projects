<?php

namespace FP_Core\Essentials_Trial_Membership;

class Meta_Controller {
	static public function init() {
		add_action( 'rcp_successful_registration', __CLASS__ . '::handle_rcp_successful_registration', 10, 3 );
	}

	static public function handle_rcp_successful_registration( \RCP_Member $member, \RCP_Customer $customer, \RCP_Membership $membership ) {
		$code_array = Settings::get_request_code();

		if ( ! $code_array ) {
			return;
		}

		rcp_add_membership_meta( $membership->get_id(), 'essentials-access-code', $code_array, true );

		$membership->add_note( 'Essentials Trial Access Code Used: ' . $code_array['name'] );
	}
}
