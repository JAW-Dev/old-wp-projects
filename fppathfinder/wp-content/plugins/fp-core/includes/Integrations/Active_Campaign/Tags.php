<?php
/**
 * Add ActiveCampaign Tag
 *
 * @package    FP_Core
 * @subpackage FP_Core/Integrations/ActiveCampaign
 * @author     Objectiv
 * @copyright  Copyright (c) 2020, Objectiv
 * @license    GPL-2.0
 * @version    1.0.0
 */

namespace FP_Core\Integrations\Active_Campaign;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

/**
 * Add ActiveCampaign Tag
 *
 * @author Jason Witt
 * @since  1.0.0
 */
class Tags {

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
	 * Add Tag
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function add( $email, $tag ) {
		$settings = get_option( 'rcp_activecampaign_settings' );

		if ( empty( $settings ) || empty( $settings['api_url'] ) || empty( $settings['api_key'] ) ) {
			return false;
		}

		$active_campaign = new \ActiveCampaign( $settings['api_url'], $settings['api_key'] );

		if ( defined( 'OBJECTIV_DEV_SITE' ) && OBJECTIV_DEV_SITE ) {
			return;
		}

		$data = array(
			'email' => $email,
			'tags'  => $tag,
		);

		$result = $active_campaign->api( 'contact/sync', $data );

		if ( 1 !== $result->result_code ) {
			return;
		}
	}
}
