<?php
/**
 * Tracking API
 *
 * @package    Kitces
 * @author     Objectiv
 * @copyright  Copyright (c) 2020, Objectiv
 * @license    GPL-2.0
 * @since      1.0.0
 */

namespace Kitces\Includes\Classes\Facebook;

use FacebookAds\Api;
use FacebookAds\Object\ServerSide\ActionSource;
use FacebookAds\Object\ServerSide\Content;
use FacebookAds\Object\ServerSide\CustomData;
use FacebookAds\Object\ServerSide\Event;
use FacebookAds\Object\ServerSide\EventRequest;
use FacebookAds\Object\ServerSide\UserData;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

/**
 * Class_Name
 *
 * @author Eldon Yoder
 * @since  1.0.0
 */
class ConversionAPI {

	private $access_token = 'EAADa2Ade8xkBABRa5WcxVncvrqJj49N6BE89AkUHTNTfQGKX3tuuW5kp5FQlo2NFvsNgJtJmQFMjF3x51RoM9n1cBoii5JgIGZBulq7WSg5shsYZBnb3v4G7i6u9WQKRfjbV3njCvwECkNJZBml2jAu2dsSJvFn4yX2Gtm9fR4xZCzpCqPsP';
	private $pixel_id     = '1758157654416704';
	private $api;

	/**
	 * Initialize the class
	 *
	 * @author Eldon Yoder
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function __construct() {
		$this->api = API::init( null, null, $this->access_token );
	}

	public function track_conversion( $email = null, $product_id = null, $price = '0.00', $quantity = 1, $conversion_url = '' ) {

		$fb_click_cookie = $this->get_cookie( '_fbc' );
		$fb_pixel_cookie = $this->get_cookie( '_fbp' );

		$user_data = ( new UserData() )
			->setEmails( array( $email ) )
			->setClientIpAddress( $_SERVER['REMOTE_ADDR'] )
			->setClientUserAgent( $_SERVER['HTTP_USER_AGENT'] )
			->setFbc( $fb_click_cookie )
			->setFbp( $fb_pixel_cookie );

		$content = ( new Content() )
			->setProductId( $product_id )
			->setQuantity( $quantity );

		$custom_data = ( new CustomData() )
			->setContents( array( $content ) )
			->setCurrency( 'usd' )
			->setValue( $price );

		$event = ( new Event() )
			->setEventName( 'Purchase' )
			->setEventTime( time() )
			->setEventSourceUrl( $conversion_url )
			->setUserData( $user_data )
			->setCustomData( $custom_data )
			->setActionSource( ActionSource::WEBSITE );

		$events = array();
		array_push( $events, $event );

		$request  = ( new EventRequest( $this->pixel_id ) )->setEvents( $events );
		$response = $request->execute();

		if ( defined( 'MK_LOG_FB_CONVERSION_RESPONSE' ) && MK_LOG_FB_CONVERSION_RESPONSE ) {
			error_log( $response );
		}
	}

	public function get_cookie( $cookie_name = null ) {
		$cookie_value = null;

		if ( empty( $cookie_name ) && isset( $_COOKIE[ $cookie_name ] ) ) {
			$cookie_value = $_COOKIE[ $cookie_name ];
		}

		return $cookie_value;
	}

}
