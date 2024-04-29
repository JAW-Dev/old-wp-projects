<?php
/**
 * Share Link
 *
 * @package    FP_Core/
 * @subpackage FP_Core/Utilities/Checklist
 * @author     Objectiv
 * @copyright  Copyright (c) 2020, Objectiv
 * @license    GPL-2.0
 * @version    1.0.0
 */

namespace FP_Core\Utilities\Checklist;

use FP_Core\InteractiveLists\Tables\LinkShare;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

/**
 * Share Link
 *
 * @author Jason Witt
 * @since  1.0.0
 */
class ShareLink {

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
	 * Get DB Entry
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param object $wpdb The WpDb databes object.
	 *
	 * @return array
	 */
	public function get_db_entry( $wpdb = null ) {

		if ( is_null( $wpdb ) ) {
			global $wpdb;
		}

		$share_key  = sanitize_text_field( wp_unslash( $_GET['sh'] ?? '' ) );
		$share_type = sanitize_text_field( wp_unslash( $_GET['ty'] ?? '' ) );
		$entry      = [];

		if ( fp_is_feature_active( 'checklists_v_two' ) || $share_type === 'group' ) {
			$advisor_id    = sanitize_text_field( wp_unslash( $_GET['a'] ?? '' ) );
			$advisor_links = get_user_meta( $advisor_id, 'fp_advisor_share_links', true );

			if ( ! empty( $advisor_links ) ) {
				foreach ( $advisor_links as $link ) {
					if ( ! isset( $link['share_key'] ) ) {
						break;
					}

					$key = $link['share_key'];
					if ( $key === $share_key ) {
						$entry = $link;
						break;
					}
				}

				if ( ! empty( $entry ) ) {
					return $entry;
				}
			}
		}

		if ( empty( $share_key ) ) {
			return '';
		}

		$table = LinkShare::get_resource_share_link_table_name();
		$entry = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $table WHERE share_key = %s", $share_key ), ARRAY_A ); // phpcs:ignore

		return ! empty( $entry ) ? $entry : '';
	}

	/**
	 * Get DB Entry Array
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param object $wpdb The WpDb databes object.
	 *
	 * @return array
	 */
	public function get_db_entry_array( $wpdb = null ) {

		if ( is_null( $wpdb ) ) {
			global $wpdb;
		}

		$share_key = sanitize_text_field( wp_unslash( $_GET['sh'] ?? '' ) );

		if ( empty( $share_key ) ) {
			return '';
		}

		$table = LinkShare::get_resource_share_link_table_name();
		$entry = $this->get_db_entry( $wpdb );

		$array = [
			'share_key' => ! empty( $share_key ) ? $share_key : '',
			'table'     => ! empty( $table ) ? $table : '',
			'entry'     => ! empty( $entry ) ? $entry : '',
		];

		return $array;
	}
}
