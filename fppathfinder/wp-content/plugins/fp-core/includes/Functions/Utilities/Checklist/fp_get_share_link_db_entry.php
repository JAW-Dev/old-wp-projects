<?php
/**
 * Get Share Link Database Entry.
 *
 * @package    FP_Core/
 * @subpackage FP_Core/Includes/Functions/Checklist
 * @author     Objectiv
 * @copyright  Copyright (c) 2020, Objectiv
 * @license    GPL-2.0
 * @version    1.0.0
 */

use FP_Core\Utilities\Checklist\ShareLink;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

if ( ! function_exists( 'fp_get_share_link_db_entry' ) ) {
	/**
	 * Get Share Link Database Entry.
	 *
	 * @return array
	 */
	function fp_get_share_link_db_entry() {
		return ( new ShareLink() )->get_db_entry();
	}
}

if ( ! function_exists( 'fp_get_share_link_db_entry_object' ) ) {
	/**
	 * Get Share Link Database Entry array.
	 *
	 * @param object $wpdb The WpDb databes object.
	 *
	 * @return array
	 */
	function fp_get_share_link_db_entry_array( $wpdb = null ) {
		return ( new ShareLink() )->get_db_entry_array( $wpdb );
	}
}
