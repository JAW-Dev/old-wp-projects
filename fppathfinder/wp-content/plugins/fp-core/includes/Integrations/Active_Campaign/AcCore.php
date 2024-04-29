<?php
/**
 * AC Core
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
 * AC Core
 *
 * @author Jason Witt
 * @since  1.0.0
 */
class AcCore {

	/**
	 * ActiveCampaign API
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @var object
	 */
	protected $ac_api;

	/**
	 * Initialize the class
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function __construct() {
		$settings = get_option( 'rcp_activecampaign_settings' );

		if ( empty( $settings ) || empty( $settings['api_url'] ) || empty( $settings['api_key'] ) ) {
			return false;
		}

		$this->ac_api = new \ActiveCampaign( $settings['api_url'], $settings['api_key'] );
	}
}
