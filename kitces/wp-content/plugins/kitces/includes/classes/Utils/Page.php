<?php
/**
 * Page
 *
 * @package    Kitces
 * @subpackage Kitces/Includes/Classes/Utils
 * @author     Objectiv
 * @copyright  Copyright (c) 2020, Objectiv
 * @license    GNU General Public License v2 or later
 * @since      1.0.0
 */

namespace Kitces\Includes\Classes\Utils;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

/**
 * Class_Name
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
	 * Get Content
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param int $post_id The post_id.
	 *
	 * @return string
	 */
	public static function get_content( $post_id = '' ) {
		if ( empty( $post_id ) ) {
			global $post;

			if ( empty( $post ) ) {
				return '';
			}

			return $post->post_content;
		}

		return '';
	}
}
