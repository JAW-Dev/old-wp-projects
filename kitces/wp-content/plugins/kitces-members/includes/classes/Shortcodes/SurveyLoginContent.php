<?php
/**
 * Survey Login Content
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
 * Survey Login Content
 *
 * @author Jason Witt
 * @since  1.0.0
 */
class SurveyLoginContent {

	/**
	 * Is Survey URL Link
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @var bool
	 */
	protected $is_survey_url_link = false;

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
		add_shortcode( 'kitces_is_survey_login', array( $this, 'is_survey_login' ) );
		add_shortcode( 'kitces_is_not_survey_login', array( $this, 'is_not_survey_login' ) );
	}

	/**
	 * Is Survey Content
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param array  $atts    Attributes.
	 * @param string $content The content to output.
	 *
	 * @return string
	 */
	public function is_survey_login( $atts, $content = null ) {
		$atts = shortcode_atts(
			array(),
			$atts,
			'kitces_reader_login_link'
		);

		$this->is_survey_url_link = function_exists( 'kitces_is_survey_url_link' ) ? kitces_is_survey_url_link() : false;

		if ( $this->is_survey_url_link ) {
			return $content;
		}

		return '';
	}

	/**
	 * Is Survey Content
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param array  $atts    Attributes.
	 * @param string $content The content to output.
	 *
	 * @return string
	 */
	public function is_not_survey_login( $atts, $content = null ) {
		$atts = shortcode_atts(
			array(),
			$atts,
			'kitces_reader_login_link'
		);

		$this->is_survey_url_link = function_exists( 'kitces_is_survey_url_link' ) ? kitces_is_survey_url_link() : false;

		if ( ! $this->is_survey_url_link ) {
			return $content;
		}

		return '';
	}

}
