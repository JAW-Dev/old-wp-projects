<?php

use FpAccountSettings\Includes\Classes\Conditionals;

function fp_user_can_favorite( $user_id ) {

	$can_favorite = false;

	if ( ! empty( $user_id ) ) {
		$customer = rcp_get_customer_by_user_id( $user_id );
		if ( $customer ) {
			$can_favorite = function_exists( 'rcp_user_has_access' ) && rcp_user_has_access( $user_id, 4 );
		} else {
			$group_member = rcpga_user_is_group_member( $user_id );
			if ( $group_member ) {
				$can_favorite = Conditionals::is_deluxe_or_premier_group_member( $user_id );
			}
		}
	}

	return $can_favorite;
}
