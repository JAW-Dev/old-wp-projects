<?php
/**
 * ActiveCampaign Itegration
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
 * ActiveCampaign Itegration
 *
 * @author John Geesey|Jason Witt
 * @since  1.0.0
 */
class ActiveCampaignIntegration {

	/**
	 * Initialize the class
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function __construct() {
		$this->init();
	}

	/**
	 * Should Init
	 *
	 * @author John Geesey|Jason Witt
	 * @since  1.0.0
	 *
	 * @return boolean
	 */
	private function should_init() {
		$settings = get_option( 'rcp_activecampaign_settings' );

		if ( empty( $settings ) || empty( $settings['api_url'] ) || empty( $settings['api_key'] ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Init
	 *
	 * @author John Geesey|Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function init() {
		if ( ! $this->should_init() && strpos( $_SERVER['HTTP_HOST'], 'fppathfinder.com' ) === false ) {
			Cron_Contact_Update_Queue::init();
			Custom_Field_Registrar::register_fields();
			new AcCore();
		}

		if ( strpos( $_SERVER['HTTP_HOST'], 'fppathfinder.com' ) !== false ) {
			Cron_Contact_Update_Queue::init();
			Custom_Field_Registrar::register_fields();
			new AcCore();
			new UpdateMember();
			Cron_Contact_Updater::init();
			Tracking_Pixel::init();
		}
	}
}
