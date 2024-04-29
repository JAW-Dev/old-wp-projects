<?php
/**
 * Contact
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
 * Contact
 *
 * @author Jason Witt
 * @since  1.0.0
 */
class Contact extends AcCore {

	/**
	 * Initialize the class
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function __construct() {
		parent::__construct();
	}

	/**
	 * Get
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return object
	 */
	public function get( $user_id = null ) {
		if ( ! $user_id ) {
			$user_id = get_current_user_id();
		}

		$user_data  = get_userdata( $user_id );
		$contact_id = get_user_meta( $user_id, 'ac_contact_id', true );
		$contact    = new \stdClass();

		if ( ! $contact_id ) {
			$get_contact = $this->ac_api->api( "contact/view?email={$user_data->user_email}" );

			if ( (int) $get_contact->success ) {
				$contact = $get_contact;

				update_user_meta( $user_id, 'ac_contact_id', $contact->id );
			}
		}

		return $contact;
	}

}
