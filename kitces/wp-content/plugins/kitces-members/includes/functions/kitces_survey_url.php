<?php
/**
 * Kitces Survey URL.
 *
 * @package    Kitces_Members
 * @subpackage Kitces_Members/Inlcudes/Functions
 * @author     Objectiv
 * @copyright  Copyright (c) 2021, Objectiv
 * @license    GNU General Public License v2 or later
 * @since      1.0.0
 */

use Kitces_Members\Includes\Classes\Utilities\SurveyUrl;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

if ( ! function_exists( 'kitces_get_survey_url' ) ) {
    /**
	 * Get Survey URL
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return string
	 */
	function kitces_get_survey_url() {
		return ( new SurveyUrl() )->get_decoded_redirect_url_parameter();
	}
}

if ( ! function_exists( 'kitces_is_survey_url_link' ) ) {
    /**
	 * Get Survey URL
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return string
	 */
	function kitces_is_survey_url_link() {
		return ( new SurveyUrl() )->is_survey_url_redirection_link();
	}
}
