<?php
/**
 * Posts
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
 * Posts
 *
 * @author Jason Witt
 * @since  1.0.0
 */
class Posts extends Base {

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

		// Move any content to destination user.
		return $wpdb->update( // phpcs:ignore
			$wpdb->posts,
			array(
				'post_author' => $this->get_destination_wp_user()->ID,
			),
			array(
				'post_author' => $this->get_source_wp_user()->ID,
			)
		);
	}
}
