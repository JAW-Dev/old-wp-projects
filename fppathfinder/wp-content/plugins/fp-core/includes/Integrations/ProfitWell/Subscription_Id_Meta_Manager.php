<?php

namespace FP_Core\Integrations\ProfitWell;

class Subscription_Id_Meta_Manager {
	static public function get_meta_key(): string {
		return 'profitwell_subscription_id';
	}

	static public function set( string $id, int $membership_id ) {
		rcp_delete_membership_meta( $membership_id, self::get_meta_key() );
		return rcp_add_membership_meta( $membership_id, self::get_meta_key(), $id, true );
	}

	static public function get( int $membership_id ) {
		return rcp_get_membership_meta( $membership_id, self::get_meta_key(), true );
	}
}
