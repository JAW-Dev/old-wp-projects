<?php

namespace FP_PDF_Generator;

class Customization_Controller {
	static function user_can_save_white_label_settings( int $user_id ) {
		if ( ! $user_id ) {
			return false;
		}

		return ( function_exists( 'rcp_user_has_access' ) && rcp_user_has_access( $user_id, 4 ) ) || self::settings_are_managed_by_group_owner( $user_id ) || current_user_can( 'administrator' );
	}

	static function user_can_view_white_label_settings( int $user_id ) {
		return ! self::settings_are_managed_by_group_owner( $user_id );
	}

	static function user_can_customize_pdf( int $user_id ) {
		if ( ! $user_id ) {
			return false;
		}

		$member = new \FP_Core\Member( $user_id );

		$possible_reasons = array(
			self::user_can_save_white_label_settings( $user_id ),
			$member->is_active_at_level( FP_ENTERPRISE_DELUXE_ID ),
			$member->is_active_at_level( FP_DELUXE_ID ),
			$member->is_active_at_level( FP_FIRM_WIDE_DELUXE_ID ),
			$member->is_active_at_level( FP_PREMIER_ID ),
			$member->is_active_at_level( FP_FIRM_WIDE_PREMIER_ID ),
			$member->is_active_at_level( FP_ENTERPRISE_PREMIER_ID ),
			current_user_can( 'administrator' ),
		);
		return ! empty( array_filter( $possible_reasons ) );
	}

	static function settings_are_managed_by_group_owner( int $user_id ) {
		if ( ! $user_id ) {
			return false;
		}

		$member = new \FP_Core\Member( $user_id );

		if ( ! $member->get_group() ) {
			return false;
		}

		if ( $user_id === $member->get_group()->get_owner_id() ) {
			return false;
		}

		if ( $member->is_active_at_level( FP_ENTERPRISE_DELUXE_ID ) || $member->is_active_at_level( FP_ENTERPRISE_PREMIER_ID ) ) {
			return true;
		}

		return false;
	}
}
