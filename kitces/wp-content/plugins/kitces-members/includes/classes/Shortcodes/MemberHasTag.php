<?php
/**
 * Member Has Tag
 *
 * @package    Kitces_Members
 * @subpackage Kitces_Members/Inlcudes/Classes/Shortcodes
 * @author     Objectiv
 * @copyright  Copyright (c) 2021, Objectiv
 * @license    GNU General Public License v2 or later
 * @since      1.0.0
 */

namespace Kitces_Members\Includes\Classes\Shortcodes;

use Kitces_Members\Includes\Classes\ActiveCampaign;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

/**
 * Member Has Tag
 *
 * @author Jason Witt
 * @since  1.0.0
 */
class MemberHasTag {

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
		add_shortcode( 'kitces_member_has_tag', array( $this, 'has_tag' ) );
	}

	/**
	 * Has Tag
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param array  $atts {
	 *     The shortcode attributes.
	 *
	 *     @type string $tagid The tag ids.
	 * }
	 *
	 * @param string $content The content to output.
	 *
	 * @return string
	 */
	public function has_tag( $atts, $content = null ) {
		$atts = shortcode_atts(
			array(
				'tagid' => '',
			),
			$atts,
			'kitces_member_has_tag'
		);

		$tags         = ( new ActiveCampaign\Tags() )->list();
		$check_tags   = explode( ',', $atts['tagid'] );
		$member_tags  = array();

		if ( empty( $tags ) ) {
			return '';
		}

		foreach ( $tags as $tag ) {
			if ( in_array( (string) $tag, $check_tags, true ) ) {
				$member_tags[] = $tag;
			}
		}

		if ( empty( $member_tags ) ) {
			return '';
		}

		return $content;
	}
}
