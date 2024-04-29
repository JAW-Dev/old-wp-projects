<?php
/**
 * Utilities
 *
 * @package    FP_Core/
 * @subpackage FP_Core/Includes/Crms
 * @author     Objectiv
 * @copyright  Copyright (c) 2020, Objectiv
 * @license    GPL-2.0
 * @version    1.0.0
 */

namespace FP_Core\Crms;

use FP_Core\Crms\Main;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

/**
 * Utilities
 *
 * @author Jason Witt
 * @since  1.0.0
 */
class Utilities {

	/**
	 * Initialize the class
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function __construct() {}

	/**
	 * Get CRMs
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param int $user_id The user ID.
	 *
	 * @return array
	 */
	public static function get_crms( $user_id = null ) {
		$user_id = $user_id ? $user_id : get_current_user_id();
		$crms    = [];

		foreach ( Main::set_crms() as $crm ) {
			$slug_setup = explode( '_', strtolower( $crm ) );
			$slug       = array_shift( $slug_setup );
			$name       = str_replace( '_', ' ', ucwords( $crm ) );
			$tokens     = get_user_meta( $user_id, "{$slug}_tokens", true );

			if ( $slug === 'redtail' ) {
				$tokens = empty( $tokens ) ? get_user_meta( $user_id, 'redtail_user_key', true ) : $tokens;
			}

			$crms[] = [
				'slug'      => $slug,
				'name'      => $name,
				'is_active' => self::is_crm_active( $slug ),
				'tokens'    => $tokens,
			];
		}

		return $crms;
	}

	/**
	 * Get CRM Info
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param string $slug    The CRM slug.
	 * @param int    $user_id The user ID.
	 *
	 * @return void
	 */
	public static function get_crm_info( $slug, $user_id = null ) {
		$user_id      = $user_id ? $user_id : get_current_user_id();
		$returned_crm = '';

		foreach ( self::get_crms( $user_id ) as $crm ) {
			if ( $slug === $crm['slug'] ) {
				$returned_crm = $crm;
				break;
			}
		}

		return $returned_crm;
	}

	/**
	 * Get Active CRM
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param int $user_id The user ID.
	 *
	 * @return array
	 */
	public static function get_active_crm( $user_id = null ) {
		$user_id    = $user_id ? $user_id : get_current_user_id();
		$active_crm = get_user_meta( $user_id, 'current_active_crm', true );

		return $active_crm;
	}

	/**
	 * Get CRM Token
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param int $user_id The user ID.
	 *
	 * @return void
	 */
	public static function get_crm_token( $user_id = null ) {
		$user_id    = $user_id ? $user_id : get_current_user_id();
		$active_crm = self::get_active_crm( $user_id );
		$crm        = self::get_crm_info( $active_crm, $user_id );
		$tokens     = ! empty( $crm ) ? $crm['tokens'] : '';

		return $tokens;
	}

	/**
	 * Is CRM Active
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param string $slug The CRM slug.
	 *
	 * @return boolean
	 */
	public static function is_crm_active( $slug ) {
		if ( get_user_meta( get_current_user_id(), 'current_active_crm', true ) === $slug ) {
			return true;
		}

		return false;
	}

	/**
	 * Get Status Label
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public static function get_status_label( $active, $tokens ) {
		if ( $active && $tokens ) {
			return 'Active and connected';
		}

		if ( $active ) {
			return 'Active but not connected, see Settings';
		}

		return 'Inactive';
	}
}
