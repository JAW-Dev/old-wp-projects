<?php
/**
 * Page
 *
 * @package    FP_Core/
 * @subpackage FP_Core/InteractiveLists/Utilities
 * @author     Objectiv
 * @copyright  Copyright (c) 2020, Objectiv
 * @license    GPL-2.0
 * @version    1.0.0
 */

namespace FP_Core\InteractiveLists\Utilities;

use FP_Core\InteractiveLists\Tables\LinkShare;
use FP_Core\InteractiveLists\Utilities\CRM;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

/**
 * Page
 *
 * @author Jason Witt
 * @since  1.0.0
 */
class Page {

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
	 * Is Interactive Resource
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public static function is_interactive_resource( $type = null ) {
		$type = $type !== null ? $type : fp_get_interactive_lists_post_types();

		if ( ! is_singular( $type ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Is Shared Link
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public static function is_shared_link() {
		$share_key = sanitize_text_field( wp_unslash( $_GET['sh'] ?? '' ) );

		if ( empty( $share_key ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Is Share Link Post
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public static function is_shared_link_post() {
		if ( 'POST' === $_SERVER['REQUEST_METHOD'] && fp_is_share_link() ) {
			return true;
		}

		return false;
	}

	/**
	 * Is Share Key Valid
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return boolean|object
	 */
	public static function is_share_key_valid() {
		$share_key  = sanitize_text_field( wp_unslash( $_GET['sh'] ?? '' ) );
		$advisor_id = sanitize_text_field( wp_unslash( $_GET['a'] ?? '' ) );

		if ( empty( $share_key ) ) {
			return false;
		}

		$entry = fp_get_share_link_db_entry();

		if ( is_array( $entry ) && ! empty( $entry ) ) {
			foreach ( $entry as $link ) {
				if ( ! isset( $link['share_key'] ) ) {
					break;
				}

				$key = $link['share_key'];
				if ( $key === $share_key ) {
					return true;
				}
			}
		}

		if ( empty( $entry ) ) {
			return false;
		}

		return $entry;
	}

	/**
	 * Is Shared Link Type
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public static function is_shared_link_type() {
		$share_type = sanitize_text_field( wp_unslash( $_GET['ty'] ?? '' ) );

		if ( empty( $share_key ) ) {
			return 'single';
		}

		return $share_type;
	}

	/**
	 * Is Resource Share Link Completed
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param array $entry The share link entry.
	 *
	 * @return boolean
	 */
	public static function is_resourece_share_link_completed( $entry = array() ) {
		$entry = ! empty( $entry ) ? $entry : self::is_share_key_valid();

		if ( ! empty( $entry ) ) {
			$completed = ! empty( $entry['completed'] ) ? $entry['completed'] : 0;

			if ( $completed ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Is Share Link Expired
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public static function is_share_link_expired() {
		$entry = self::is_share_key_valid();

		if ( $entry ) {
			$expiration = ! empty( $entry['expiration'] ) ? strtotime( $entry['expiration'] ) : '';
			$datetime   = new \DateTime();
			$now        = strtotime( $datetime->format( 'Y-m-d H:i:s' ) );

			if ( $now > $expiration ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Is example Interactive List
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return boolean
	 */
	public static function is_example_interactive_list() {
		global $post;

		if ( empty( $post ) ) {
			return;
		}

		if ( ! rcp_is_restricted_content( $post->ID ) && ! CRM::has_active_crm( get_current_user_id() ) ) {
			return true;
		}

		return false;
	}
}
