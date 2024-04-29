<?php
/**
 * GravityForms
 *
 * @package    Kitces_Members
 * @subpackage Kitces_Members/Inlcudes/Classes/MemberActions/Merge
 * @author     Objectiv
 * @copyright  Copyright (c) 2021, Objectiv
 * @license    GNU General Public License v2 or later
 * @since      1.0.0
 */

namespace Kitces_Members\Includes\Classes\MemberActions\Merge;

/**
 * GravityForms
 *
 * @author Jason Witt
 * @since  1.0.0
 */
class GravityForms extends Base {

	/**
	 * Do Merge
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return \WP_User
	 */
	public function do_merge() {
		global $wpdb;

		// Entries.
		if ( version_compare( \GFForms::$version, '2.3', '<' ) ) {
			$entry_table = \GFFormsModel::get_lead_table_name();
		} else {
			$entry_table = \GFFormsModel::get_entry_table_name();
		}

		// Move any content to destination user.
		return $wpdb->update( // phpcs:ignore
			$entry_table,
			array(
				'created_by' => $this->get_destination_wp_user()->ID,
			),
			array(
				'created_by' => $this->get_source_wp_user()->ID,
			)
		);
	}

	/**
	 * Is Available
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return boolean
	 */
	public function is_available() {
		return class_exists( '\\GFForms' );
	}
}
