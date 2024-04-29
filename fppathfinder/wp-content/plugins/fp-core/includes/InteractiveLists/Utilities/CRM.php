<?php
/**
 * CRM
 *
 * @package    FP_Core/
 * @subpackage FP_Core/InteractiveLists/Utilities
 * @author     Objectiv
 * @copyright  Copyright (c) 2020, Objectiv
 * @license    GPL-2.0
 * @version    1.0.0
 */

namespace FP_Core\InteractiveLists\Utilities;

use FP_Core\Crms\ContactLookup\ContactNameGetter;
use FP_Core\InteractiveLists\Utilities\Page;
use FP_Core\InteractiveLists\Tables\LinkShare;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

/**
 * CRM
 *
 * @author Jason Witt
 * @since  1.0.0
 */
class CRM {

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
	 * Has Active CRM
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param int $user_id    The user ID;
	 *
	 * @return void
	 */
	public static function has_active_crm( $user_id = null ) {
		$user_id   = null === $user_id ? get_current_user_id() : $user_id;
		$crms      = fp_get_interactive_lists_crms();
		$is_active = false;

		foreach ( $crms as $crm ) {
			$active = get_user_meta( $user_id, $crm . '_tokens', true );

			if ( $active ) {
				$is_active = true;
				break;
			}
		}

		$redtail_old = get_user_meta( $user_id, 'redtail_user_key', true );

		if ( $redtail_old ) {
			$is_active = true;
		}

		if ( $is_active === false && get_user_meta( $user_id, 'current_active_crm', true ) ) {
			update_user_meta( $user_id, 'current_active_crm', '' );
		}

		return $is_active;
	}

	/**
	 * Get CRM Type
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public static function get_crm_type() {
		foreach ( fp_get_interactive_lists_post_types() as $crm_post_type ) {
			if ( get_post_type() === $crm_post_type ) {
				return $crm_post_type;
			}
		}

		return;
	}


	/**
	 * Get Contact Name
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param int $contact_id The contact ID;
	 * @param int $user_id    The user ID;
	 *
	 * @return void
	 */
	public static function get_contact_name( $contact_id, $user_id ) {
		$client_name = sanitize_text_field( wp_unslash( $_POST['client_name'] ?? '' ) );

		if ( self::has_active_crm( $user_id ) ) {
			foreach ( fp_get_interactive_lists_crms() as $integrated ) {
				if ( get_user_meta( $user_id, "{$integrated}_integration_active", true ) ) {
					$crm = $integrated;
					break;
				}
			}
			return ContactNameGetter::get_contact_name( $user_id, $contact_id );
		}

		if ( Page::is_shared_link_post() ) {
			global $wpdb;

			$table = LinkShare::get_resource_share_link_table_name();
			$entry = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $table WHERE crm_contact_id = %s", $contact_id ), ARRAY_A );
			return $entry['client_name'];
		}

		return $client_name;
	}

	/**
	 * Get Contact Email
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param int $contact_id The contact ID;
	 * @param int $user_id    The user ID;
	 *
	 * @return void
	 */
	public static function get_contact_email( $contact_id, $user_id ) {
		$client_name = sanitize_text_field( wp_unslash( $_POST['client_email'] ?? '' ) );

		if ( Page::is_shared_link_post() ) {
			global $wpdb;

			$table = LinkShare::get_resource_share_link_table_name();
			$entry = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $table WHERE crm_contact_id = %s", $contact_id ), ARRAY_A );
			return $entry['client_email'];
		}

		return $client_name;
	}

	/**
	 * Get Client Name
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return string
	 */
	public static function get_client_name() {
		$name = sanitize_text_field( wp_unslash( $_POST['client_name'] ?? '' ) );

		if ( Page::is_shared_link_post() ) {
			global $wpdb;

			// Get the share link data from the database.
			$share_key  = sanitize_text_field( wp_unslash( $_GET['sh'] ?? '' ) );
			$table      = LinkShare::get_resource_share_link_table_name();
			$entry      = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $table WHERE share_key = %s", $share_key ), ARRAY_A ); // phpcs:ignore
			$contact_id = $entry['crm_contact_id'] ?? '';
			$advisor_id = $entry['advisor_user_id'] ?? '';

			if ( ! empty( $contact_id && $advisor_id ) ) {
				$name = self::get_contact_name( $contact_id, $advisor_id );
			}
		}

		return $name;
	}

	/**
	 * Get Client Email
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public static function get_client_email() {
		$name = sanitize_text_field( wp_unslash( $_POST['client_email'] ?? '' ) );

		if ( Page::is_shared_link_post() ) {
			global $wpdb;

			// Get the share link data from the database.
			$share_key  = sanitize_text_field( wp_unslash( $_GET['sh'] ?? '' ) );
			$table      = LinkShare::get_resource_share_link_table_name();
			$entry      = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $table WHERE share_key = %s", $share_key ), ARRAY_A ); // phpcs:ignore
			$contact_id = $entry['crm_contact_id'] ?? '';
			$advisor_id = $entry['advisor_user_id'] ?? '';

			if ( ! empty( $contact_id && $advisor_id ) ) {
				$name = self::get_contact_email( (string) $contact_id, (string) $advisor_id );
			}
		}

		return $name;
	}

	/**
	 * Is Production
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public static function is_production() {
		return strpos( $_SERVER['HTTP_HOST'], 'fppathfinder.com' ) !== false;
	}
}
