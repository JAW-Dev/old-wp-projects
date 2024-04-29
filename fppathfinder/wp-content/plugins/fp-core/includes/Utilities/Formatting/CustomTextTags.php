<?php
/**
 * Custom Text Tags
 *
 * @package    FP_Core/
 * @subpackage FP_Core/Includes/Utilities/Formatting
 * @author     Objectiv
 * @copyright  Copyright (c) 2020, Objectiv
 * @license    GPL-2.0
 * @version    1.0.0
 */

namespace FP_Core\Utilities\Formatting;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

/**
 * Custom Text Tags
 *
 * @author Jason Witt
 * @since  1.0.0
 */
class CustomTextTags {

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
	 * Formatted Tags
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param string $text The text to format.
	 * @param array  $tags The tags and values.
	 *
	 * @return string
	 */
	public function replace_tags( $text, $tags ) {
		if ( empty( $text ) || empty( $tags ) ) {
			return $text;
		}

		foreach ( $tags as $key => $value ) {
			$tag_key = $key;
			$text    = str_replace( $tag_key, $value, $text );
		}

		return $text;
	}
}
