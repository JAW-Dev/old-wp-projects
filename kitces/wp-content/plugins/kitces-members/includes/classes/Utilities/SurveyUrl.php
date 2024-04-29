<?php
/**
 * Survey Url
 *
 * @package    Kitces_Members
 * @subpackage Kitces_Members/Inlcudes/Classes/Utilities
 * @author     Objectiv
 * @copyright  Copyright (c) 2021, Objectiv
 * @license    GNU General Public License v2 or later
 * @since      1.0.0
 */

namespace Kitces_Members\Includes\Classes\Utilities;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

/**
 * Survey Url
 *
 * @author Jason Witt
 * @since  1.0.0
 */
class SurveyUrl {

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
		/*
		Test URL
		https://kitces.test/login/?redirect_url=https%3A%2F%2Fwww.kitces.com%2Flogin%2F%3Fredirect_url%3Dhttp%3A%2F%2Fkitces.com%2Fmarketing-survey-2022%2F%3Futm_source%3DKitces%2520banner%26utm_medium%3Dblog%26utm_campaign%3D2022%2520marketing%2520study%2520no%2520offer
		*/
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
		add_action( 'wp', array( $this, 'maybe_redirect_user_to_survey' ) );
	}

	/**
	 * Get Survey URL
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return string
	 */
	public function get_decoded_redirect_url_parameter(): string {
		$param = isset( $_GET['redirect_url'] ) ? $_GET['redirect_url'] : '';

		if ( empty( $param ) ) {
			return '';
		}

		$sanitized = sanitize_text_field( wp_unslash( $param ) );
		$decoded   = rawurldecode( $sanitized );

		return $decoded;
	}

	/**
	 * Is Survey URL Link
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return bool
	 */
	public function is_survey_url_redirection_link(): bool {
		$redirect_url = $this->get_url_parts( $this->get_decoded_redirect_url_parameter() );

		if ( empty( $redirect_url ) ) {
			return false;
		}

		$path = $redirect_url['path'];

		if ( empty( $redirect_url['path'] ) ) {
			return false;
		}

		$survey_url = function_exists( 'get_field' ) ? get_field( 'kitces_survey_redirect_url', 'option' ) : '';

		if ( empty( $survey_url ) ) {
			return false;
		}

		return stripos( $survey_url, $path ) !== false;
	}


	/**
	 * Maybe Redirect User to Survey
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function maybe_redirect_user_to_survey() {
		if ( ! is_user_logged_in() ) {
			return;
		}

		if ( ! $this->is_survey_url_redirection_link() ) {
			return;
		}

		wp_redirect( $this->get_decoded_redirect_url_parameter(), 307 );
		exit;
	}

	/**
	 * Decode Survey URL
	 *
	 * @param string $string The URL string to decode.
	 *
	 * @return array
     * @author Jason Witt
	 * @since  1.0.0
	 *
	 */
	public function get_url_parts( string $string = '' ): array {
		if ( empty( $string ) ) {
			return array(
                'path' => '',
                'query' => '',
            );
		}

		$result = parse_url( $string );
		$path   = ! empty( $result['path'] ) ? $result['path'] : '';
		$path   = str_replace( 'redirect_url=', '', $path );
		$query  = ! empty( $result['query'] ) ? $result['query'] : '';

		$array = array(
			'path'  => $path,
			'query' => $query,
		);

		return $array;
	}
}
