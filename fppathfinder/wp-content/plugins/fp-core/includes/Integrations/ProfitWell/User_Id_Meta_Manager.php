<?php

namespace FP_Core\Integrations\ProfitWell;

class User_Id_Meta_Manager {
	static public function get_meta_key(): string {
		return 'profitwell_user_id';
	}

	static public function set( int $user_id, string $profitwell_user_id ) {
		delete_user_meta( $user_id, self::get_meta_key() );
		return add_user_meta( $user_id, self::get_meta_key(), $profitwell_user_id, true );
	}

	static public function get( int $user_id ) {
		return get_user_meta( $user_id, self::get_meta_key(), true );
	}
}
