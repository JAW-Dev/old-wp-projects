<?php
/**
 * Chargebee
 *
 * @package    Package_Name
 * @subpackage Package_Name/Subpackage_Name
 * @author     Author_Name
 * @copyright  Copyright (c) Date, Author_Name
 * @license    GPL-2.0
 * @version    1.0.0
 * @since      1.0.0
 */

require '../../wp/wp-load.php';
require '../vendor/autoload.php';

/**
 * Chargebee
 *
 * @author Jason Witt
 * @since  1.0.0
 */
class ChargebeeTest {

	/**
	 * Name
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @var Type
	 */
	protected $site = 'kitces'; //'kitces-test';

	/**
	 * Name
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @var Type
	 */
	protected $api_key = 'live_rFgkk8fXHznL4tvIWdavqz0HQROD6cdFF';

	/**
	 * Initialize the class
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function __construct() {
		ChargeBee_Environment::configure( $this->site, $this->api_key );

		// $retrieve          = ChargeBee_Customer::retrieve( '1mbDWgFRRgxeT8SYs' );
		// $chargeBeeCustomer = $retrieve->customer();

		$card_result = ChargeBee_Card::retrieve( '1mbDWgFRRgxeT8SYs' );
		$card_result = $card_result->card();

		// TODO: REMOVE!
		error_log( ': ' . print_r( $card_result, true ) ); // phpcs:ignore
		// error_log( ': ' . print_r( $card->expiryMonth, true ) ); // phpcs:ignore
		// error_log( ': ' . print_r( $card->expiryYear, true ) ); // phpcs:ignore

		// $portal_result = ChargeBee_PortalSession::create(
		// 	array(
		// 		'redirectUrl' => 'https://kitces.com/member',
		// 		'customer' => array( 'id' => '1mbDWgFRRgxeT8SYs' )
		// 	)
		// );

		// $portalSession = $portal_result->portalSession();

		// TODO: REMOVE!
		// error_log( '$portalSession: ' . print_r( $portalSession, true ) ); // phpcs:ignore
	}
}

new ChargebeeTest();
