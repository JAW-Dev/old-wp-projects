<?php
/**
 * Reader Login Link
 *
 * @package    Kitces_Members
 * @subpackage Kitces_Members/Inlcudes/Classes/Shortcodes
 * @author     Objectiv
 * @copyright  Copyright (c) 2021, Objectiv
 * @license    GNU General Public License v2 or later
 * @since      1.0.0
 */

namespace Kitces_Members\Includes\Classes\Shortcodes;

use Kitces_Members\Includes\Classes\Utilities\SurveyUrl;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

/**
 * Reader Login Link
 *
 * @author Jason Witt
 * @since  1.0.0
 */
class ReaderLoginLink {

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
		add_shortcode( 'kitces_reader_login_link', array( $this, 'reader_login_link' ) );
	}

	/**
	 * Password Reset Form
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param array  $atts    Attributes.
	 * @param string $content The content to output.
	 *
	 * @return string
	 */
	public function reader_login_link( $atts, $content = null  ) {
		$atts = shortcode_atts(
			array(),
			$atts,
			'kitces_reader_login_link'
		);

		$is_survey_url_link = function_exists( 'kitces_is_survey_url_link' ) ? kitces_is_survey_url_link() : false;
		$content            = '<a href="https://kitces.test/reader-account-sign-up/">' . $content . '</a>';

		if ( ! $is_survey_url_link ) {
			return $content;
		}

		$redirect_url = function_exists( 'kitces_get_survey_url' ) ? kitces_get_survey_url() : '';

		if ( $is_survey_url_link && ! empty( $redirect_url ) ) {
			return '<a href="https://kitces.test/reader-account-sign-up/?redirect_url=' . $redirect_url . '">' . $content . '</a>';
		}

		return $content;
	}
}
