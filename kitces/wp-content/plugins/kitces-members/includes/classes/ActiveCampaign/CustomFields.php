<?php
/**
 * Custom Fields
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
 * Custom Fields
 *
 * @author Jason Witt
 * @since  1.0.0
 */
class CustomFields extends Core {

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
	 * Field Lookup
	 *
	 * @param string   $field_name The field to lookup.
	 * @param int|null $user_id The user ID.
	 *
	 * @return string
	 * @since  1.0.0
	 *
	 * @author Jason Witt
	 */
	public function get_field( string $field_name, ?int $user_id ): string {
		if ( empty( $field_name ) ) {
			return '';
		}

		if ( empty( $user_id ) ) {
			$user_id = get_current_user_id();
		}

		$ac_field = get_user_meta( $user_id, 'ac_' . strtolower( $field_name ), true );

		if ( empty( $ac_field ) ) {
			$contact = ( new Contact() )->get( $user_id, true );

			if ( empty( $contact ) ) {
				return $ac_field;
			}

			$ac_fields = method_exists( $contact, 'fields' ) ? $contact->fields : array();

			if ( empty( $ac_fields ) ) {
				return $ac_field;
			}

			foreach ( $ac_fields as $field ) {
				$name  = $field->perstag;
				$value = $field->val;

				if ( $name !== strtoupper( $field_name ) ) {
					continue;
				}

				$ac_field = $value;

				update_user_meta( $user_id, 'ac_' . strtolower( $field_name ), $value );
			}
		}

		return (string) $ac_field;
	}

	/**
	 * Save Field
	 *
	 * @param string $field The field name.
	 * @param string $value The value to save.
	 *
	 * @return object|bool
	 * @since  1.0.0
	 *
	 * @author Jason Witt
	 */
	public function save_field( string $field, string $value = '' ) {
		if ( empty( $field ) ) {
			return false;
		}

		$ac_field   = strtoupper( $field );
		$user_id    = get_current_user_id();
		$user_data  = get_userdata( $user_id );
		$user_email = $user_data->user_email;

		$body = array(
			'id'    => get_user_meta( $user_id, 'ac_contact_id', true ),
			'email' => $user_email,
			'p[1]'  => 1,
		);

		$field_id = 'field[%' . $ac_field . '%,0]';

		// Because %cf is a URL encoded character.
		if ( $field === 'cfp_ce_number' ) {
			$ac_field = rawurlencode( '%' . $ac_field . '%' );
			$field_id = 'field[' . $ac_field . ',0]';
		}

		$body[ $field_id ] = $value;

		$result = $this->ac_api->api( 'contact/edit', $body );

		if ( (int) $result->success ) {
			$get_contact = $this->ac_api->api( "contact/view?email={$user_email}" );

			if ( (int) $get_contact->success ) {
				$contact = $get_contact;
				update_user_meta( $user_id, 'ac_contact', $contact );
			}

			return $result;
		}

		return false;
	}
}
