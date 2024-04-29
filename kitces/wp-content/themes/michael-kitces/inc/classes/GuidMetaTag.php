<?php
/**
 * GUID Meta Tag
 *
 * @package    Kitces
 * @subpackage Kitces/Classes
 * @author     Objectiv
 * @copyright  2020 (c) Date, Objectiv
 * @license    GPL-2.0
 * @version    1.0.0
 * @since      1.0.0
 */

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}


/**
 * GUID Meta Tag
 *
 * @author Jason Witt
 * @since  1.0.0
 */
class GuidMetaTag {

	/**
	 * Initialize the class
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function __construct() {
		$this->hooks();
	}

	/**
	 * Hooks
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function hooks() {
		add_action( 'genesis_meta', array( $this, 'meta_tag' ) );
	}

	/**
	 * Meta Tag
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return string
	 */
	public function meta_tag() {
		if ( is_home() || is_front_page() || is_category() || is_archive() ) {
			return;
		}

		global $post;
		$guid = get_the_guid( $post );

		if ( is_page() || is_single() ) {
			echo "\n<meta name=\"guid\" content=\"$guid\"/>\n";
		}
	}
}
