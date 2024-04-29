<?php
/**
 * Members Contact
 *
 * @package    Kitces_Members
 * @subpackage Kitces_Members/Inlcudes/Classes/Shortcodes
 * @author     Objectiv
 * @copyright  Copyright (c) 2021, Objectiv
 * @license    GNU General Public License v2 or later
 * @since      1.0.0
 */

namespace Kitces_Members\Includes\Classes\Shortcodes;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

/**
 * Members Contact
 *
 * @author Jason Witt
 * @since  1.0.0
 */
class MembersContact {

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
		add_shortcode( 'kitces_members_contact', array( $this, 'get_members_contact' ) );
	}

	/**
	 * Get Members Contact
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param array $atts {
	 *     The shortcode attributes.
	 *
	 *     @type string $field The AC field name
	 *     @type int    $user  The user ID (optional)
	 * }
	 *
	 * @return string
	 */
	public function get_members_contact( array $atts ) {
		$atts = shortcode_atts(
			array(
				'field' => '',
				'user'  => '',
			),
			$atts,
			'kitces_members_contact'
		);

		return kitces_members_get_meta( $atts['field'], (int) $atts['user'] );
	}
}
