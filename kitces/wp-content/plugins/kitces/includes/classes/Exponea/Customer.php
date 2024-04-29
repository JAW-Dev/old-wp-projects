<?php
/**
 * Customer
 *
 * @package    Kitces
 * @subpackage Kitces/Includes/Classes/Exponea
 * @author     Objectiv
 * @copyright  Copyright (c) 2020, Objectiv
 * @license    GNU General Public License v2 or later
 * @since      1.0.0
 */

namespace Kitces\Includes\Classes\Exponea;

use WP_Query;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

/**
 * Customer.
 *
 * @author Objectiv
 * @since  1.0.0
 */
class Customer {

	/**
	 * Exponea URL
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @var string
	 */
	protected $exponea_url;

	/**
	 * Auth Header
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @var string
	 */
	protected $auth_header;

	/**
	 * Data Url
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @var string
	 */
	protected $data_url = 'https://api.us1.exponea.com/data/v2/projects/';

	/**
	 * Initialize the class
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function __construct() {
		$this->exponea_url = $this->data_url . KITCES_EXPONEA_PROJECT_TOKEN . '/';
		$this->auth_header = "Basic " . base64_encode( KITCES_EXPONEA_API_KEY . ":" . KITCES_EXPONEA_API_SECRET ); // phpcs:ignore
	}

	/**
	 * Get Customer
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return object
	 */
	public function get_customer() {
		$endpoint = $this->exponea_url . 'customers/export-one';
		$user     = get_userdata( get_current_user_id() );

		if ( empty( $user ) ) {
			return new \stdClass();
		}

		$email = $user->user_email;
		// $email = 'christine.celaya@betterment.com';

		if ( empty( $email ) ) {
			return new \stdClass();
		}

		$body = wp_json_encode( array( 'customer_ids' => array( 'email_id' => $email ) ) );

		$request = array(
			'method'  => 'POST',
			'body'    => $body,
			'headers' => array(
				'Accept'        => 'application/json',
				'Authorization' => $this->auth_header,
				'Content-type'  => 'application/json',
			),
		);

		$response = wp_remote_request( $endpoint, $request );

		if ( empty( $response ) || $response['response']['code'] !== 200 ) {
			return new \stdClass();
		}

		$response_body = json_decode( $response['body'] );

		if ( empty( $response_body ) ) {
			return new \stdClass();
		}

		return $response_body;
	}

	/**
	 * Get Viewed Posts
	 *
	 * Posts viewed in the last 30 days.
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return array
	 */
	public function get_viewed_posts() {
		$data = $this->get_customer();

		if ( empty( $data ) ) {
			return array();
		}

		$events       = isset( $data->events ) ? $data->events : new \stdClass();
		$viewed_posts = array();

		if ( empty( $events ) ) {
			return array();
		}

		foreach ( $events as $event ) {
			if ( ! isset( $event->type ) ) {
				continue;
			}

			if ( $event->type === 'view_item' ) {
				$now = new \DateTime( 'now' );
				$now->modify( '-30 days' );
				$thirty_days_ago = $now->format( 'Y-m-d H:i:s' );

				if ( $event->timestamp < strtotime( $thirty_days_ago ) ) {
					continue;
				}

				$blog_id = isset( $event->properties->blog_id ) ? $event->properties->blog_id : '';

				if ( empty( $blog_id ) ) {
					continue;
				}

				$viewed_posts[] = substr( $blog_id, strpos( $blog_id, '=' ) + 1 );
			}
		}

		return $viewed_posts;
	}

	/**
	 * Get Not Viewed Posts
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return array
	 */
	public function get_not_viewed_posts() {
		$viewed_posts = $this->get_viewed_posts();

		if ( empty( $viewed_posts ) ) {
			return array();
		}

		$args = array(
			'post_type'    => 'post',
			'order'        => 'DESC',
			'post__not_in' => $viewed_posts,
			'date_query'   => array(
				array(
					'after'  => '-30 days',
					'column' => 'post_date',
				),
			),
		);

		$query = new \WP_Query( $args );

		if ( empty( $query ) ) {
			return array();
		}

		return ! empty( $query->posts ) ? $query->posts : array();
	}
}
