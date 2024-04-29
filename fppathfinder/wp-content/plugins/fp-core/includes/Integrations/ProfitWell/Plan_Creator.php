<?php

namespace FP_Core\Integrations\ProfitWell;

class Plan_Creator {
	static public function get_id( \RCP_Membership $membership ) {
		return str_replace( ' ', '_', strtolower( self::get_membership_level_name( $membership ) ) );
	}

	static public function get_name( \RCP_Membership $membership ) {
		return self::get_membership_level_name( $membership );
	}

	static private function get_membership_level_name( \RCP_Membership $membership ) {
		$membership_level_id = $membership->get_object_id();
		$level               = ( new \RCP_Levels() )->get_level( $membership_level_id );

		return $level->name;
	}
}
