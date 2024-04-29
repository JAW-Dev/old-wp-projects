<?php

namespace FP_Core;

class All_RCP_Memberships_Getter {
	static public function get(): array {
		$all_memberships        = array();
		$most_recent_membership = current( rcp_get_memberships( array( 'number' => 1 ) ) );
		$highest_membership_id  = intval( $most_recent_membership->get_id() );

		for ( $i = 1; $i <= $highest_membership_id + 1; $i++ ) {
			$membership = rcp_get_membership( $i );

			if ( ! $membership ) {
				continue;
			}

			$all_memberships[] = $membership;
		}

		return $all_memberships;
	}
}
