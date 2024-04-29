<?php
/**
 * Contact
 *
 * @package    Kitces_Members
 * @subpackage Kitces_Members/Inlcudes/Classes/ActiveCampaign
 * @author     Objectiv
 * @copyright  Copyright (c) 2021, Objectiv
 * @license    GNU General Public License v2 or later
 * @since      1.0.0
 */

namespace Kitces_Members\Includes\Classes\ActiveCampaign;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

require WP_CONTENT_DIR . '/vendor/autoload.php';

/**
 * Contact
 *
 * @author Jason Witt
 * @since  1.0.0
 */
class Contact extends Core  {

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
	 * @param int|null $user_id The user ID.
	 *
	 * @return object
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 */
	public function get( ?int $user_id = null, bool $force_refresh = false ) {
		if ( ! $user_id ) {
			$user_id = get_current_user_id();
		}

		$user_data = get_userdata( $user_id );

		// Do we have a cached contact? If so, just return it.
		$contact = get_user_meta( $user_id, 'ac_contact', true );

		if ( ! $contact || $contact->id === 0 || $force_refresh ) {
			$contact = new \stdClass(); // default empty object return

			if ( ! is_object( $user_data ) ) {
				return $contact;
			}

			$encoded_email = urlencode( $user_data->user_email ); // this helps with + signs in email addresses
			$get_contact = $this->ac_api->api( "contact/view?email={$encoded_email}" );

			if ( (int) $get_contact->success ) {
				$contact = $get_contact;

				update_user_meta( $user_id, 'ac_contact_id', $contact->id );
				update_user_meta( $user_id, 'ac_contact', $contact );
			}
		}

		return $contact;
	}

	/**
	 * Create
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param array $args The arguments.
	 *
	 * @return mixed
	 */
	public function create_reader_account( array $args = [] ) {
		if ( empty( $args ) ) {
			return [];
		}

		$email      = ! empty( $args['email'] ) ? $args['email'] : '';
		$first_name = ! empty( $args['first_name'] ) ? $args['first_name'] : '';
		$last_name  = ! empty( $args['last_name'] ) ? $args['last_name'] : '';
		$nerds_eye  = ! empty( $args['nerds_eye'] ) ? $args['nerds_eye'] : '';

		if ( empty( $email ) || empty( $first_name ) || empty( $last_name ) ) {
			return [];
		}

		$data = array(
			'email'     => $email,
			'firstName' => $first_name,
			'lastName'  => $last_name,
			'tags'      => 'Create_Reader_Account',
		);

		if ( $nerds_eye ) {
			$data['p[1]'] = '1';
		}

		$response   = $this->ac_api->api( 'contact/add', $data );
		$survey_url = function_exists( 'get_field' ) ? get_field( 'kitces_member_password_reset_form_id', 'option' ) : '';

		if ( ! empty( $survey_url ) ) {
			( new CustomFields() )->save_field( 'REGISTRATION_REDIRECT', $survey_url );
		}

		return $response;
	}
}
