<?php
/**
 * Custom Text Tags.
 *
 * @package    FP_Core/
 * @subpackage FP_Core/Includes/Functions
 * @author     Objectiv
 * @copyright  Copyright (c) 2020, Objectiv
 * @license    GPL-2.0
 * @version    1.0.0
 */

use FP_Core\Utilities\Formatting\CustomTextTags;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

if ( ! function_exists( 'fp_custom_text_tags' ) ) {
	/**
	 * Custom Text Tags.
	 *
	 * @param string $text The text to format.
	 * @param array  $tags The tags and values.
	 *
	 * @return array
	 */
	function fp_custom_text_tags( $text = '', $tags = [] ) {
		return ( new CustomTextTags() )->replace_tags( $text, $tags );
	}
}
