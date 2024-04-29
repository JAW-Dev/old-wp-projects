<?php
/**
 * GfGroupForm.
 *
 * @package    Kitces
 * @subpackage Kitces/Includes/Classes/ActiveCampaign
 * @author     Objectiv
 * @copyright  Copyright (c) 2020, Objectiv
 * @license    GNU General Public License v2 or later
 * @since      1.0.0
 */

namespace Kitces\Includes\Classes\ActiveCampaign;

use Kitces\Includes\Classes\Chargebee as Chargebee;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

/**
 * GfGroupForm.
 *
 * @author Objectiv
 * @since  1.0.0
 */
class GfGroupForm {

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
	 * New Contact Tags
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @var array
	 */
	protected $new_contact_control_tag = array( 'NewGroupMember-New-Subscriber' );

	/**
	 * Existing Contact Tags
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @var array
	 */
	protected $existing_contact_control_tag = array( 'NewGroupMember-Existing-Subscriber' );

	/**
	 * Old Tags
	 * New Contact Tags
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @var array
	 */
	protected $existing_membership_tags = array(
		'MemberAdmin-Basic',
		'MemberAdmin-Student',
		'MemberAdmin-NewsletterMember',
	);

	/**
	 * Initialize the class
	 *
	 * @author Objectiv
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function __construct() {
		$this->ac_api = new \ActiveCampaign( KITCES_AC_API_URL, KITCES_AC_API_KEY );
		$this->hooks();
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
		add_action( 'gform_after_submission', array( $this, 'create_ac_accounts' ), 10, 2 );
		add_action( 'wp_ajax_chargebee_cancel_subscription', array( $this, 'chargebee_cancel_subscription' ) );
		add_action( 'wp_ajax_nopriv_chargebee_cancel_subscription', array( $this, 'chargebee_cancel_subscription' ) );
	}

	/**
	 * Create ActiveCampaign Acctounts.
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param array  $entry The entry object.
	 * @param object $form  The form object.
	 *
	 * @return void
	 */
	public function create_ac_accounts( $entry, $form ) {
		if ( ! empty( $entry ) ) {

			if ( $this->is_active_campaign_form( $entry ) ) {
				$data = new Data();

				$this->set_admin_ac_account( $entry, $data );
				$this->set_members_ac_account( $entry, $data );
				$this->add_account( $entry, $data );
			}
		}
	}

	/**
	 * Add Account
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param array  $entry The entry object.
	 * @param object $data The data object.
	 *
	 * @return void
	 */
	public function add_account( $entry, $data ) {
		// Create account.
		$endpoint = 'https://kitces.api-us1.com/api/3/accounts';

		$fields = array(
			array(
				'customFieldId' => 3,
				'fieldValue'    => $entry[7] . ' ' . $entry[8] . ', ' . $entry[9] . ' ' . $entry[10],
			),
			array(
				'customFieldId' => 13,
				'fieldValue'    => $entry[2] . ' ' . $entry[3],
			),
			array(
				'customFieldId' => 14,
				'fieldValue'    => $entry[4],
			),
		);

		$body = json_encode(
			array(
				'account' => array(
					'name'       => $entry[1],
					'accountUrl' => $entry[5],
					'fields'     => $fields,
				),
			)
		);

		$request = array(
			'method'  => 'POST',
			'body'    => $body,
			'headers' => array(
				'content-type' => 'application/json',
				'Api-Token'    => KITCES_AC_API_KEY,
			),
		);

		$response = wp_remote_request( $endpoint, $request );

		// Add Members.
		$account    = json_decode( $response['body'] );
		$account_id = $account->account->id;

		$admin      = $data->get_admin_data( $entry );
		$members_id = get_field( 'kitces_member_nested_form_id', 'option' ) ? get_field( 'kitces_member_nested_form_id', 'option' ) : '14';
		$members    = $data->get_members_data( explode( ',', rgar( $entry, $members_id ) ) );

		if ( ! empty( $entry['13.1'] ) ) {
			$members = array_merge( $members, $admin );
		}

		$ids = array();

		foreach ( $members as $member ) {
			$member_email = $member['email'];
			$contact      = $this->ac_api->api( "contact/view?email={$member_email}" );
			$ids[]        = $contact->id;
		}

		foreach ( $ids as $id ) {
			$endpoint = 'https://kitces.api-us1.com/api/3/accountContacts';
			$request  = array(
				'method'  => 'POST',
				'body'    => json_encode(
					array(
						'accountContact' => array(
							'contact' => $id,
							'account' => $account_id,
						),
					)
				),
				'headers' => array(
					'content-type' => 'application/json',
					'Api-Token'    => KITCES_AC_API_KEY,
				),
			);

			$response = wp_remote_request( $endpoint, $request );
		}
	}

	/**
	 * Set Admin
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param array  $entry The entry object.
	 * @param object $data The data object.
	 *
	 * @return void
	 */
	public function set_admin_ac_account( $entry, $data ) {
		// Bail if empty.
		if ( empty( $entry ) ) {
			return;
		}

		if ( ! empty( $entry['13.1'] ) ) {
			$admin_data = $data->get_admin_data( $entry );
			$contact    = ! empty( $admin_data[0] ) ? $admin_data[0] : array();

			if ( ! empty( $contact ) ) {
				$ac_account = ! empty( $this->get_ac_contact( $contact['email'] ) ) ? $this->get_ac_contact( $contact['email'] ) : array();

				$this->set_ac_account( $contact, $ac_account, array( 'MemberType-Kitces-Report-Group-Administrator' ) );
			}
		}
	}

	/**
	 * Set Members AC Account
	 *
	 * @param array  $entry The entry object.
	 * @param object $data  The data object.
	 *
	 * @return void
	 */
	public function set_members_ac_account( $entry, $data ) {
		// Bail if empty.
		if ( empty( $entry ) ) {
			return;
		}

		$members_id = get_field( 'kitces_member_nested_form_id', 'option' ) ? get_field( 'kitces_member_nested_form_id', 'option' ) : '14';
		$members    = $data->get_members_data( explode( ',', rgar( $entry, $members_id ) ) );

		foreach ( $members as $contact ) {
			if ( ! empty( $contact ) ) {
				$ac_account = ! empty( $this->get_ac_contact( $contact['email'] ) ) ? $this->get_ac_contact( $contact['email'] ) : array();

				$this->set_ac_account( $contact, $ac_account );
			}
		}
	}

	/**
	 * Set AC Account
	 *
	 * @param array $contact The form data for the contact.
	 * @param array $ac_account The AC contact data.
	 * @param array $additional_tags
	 *
	 * @return void
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 */
	public function set_ac_account( $contact, $ac_account, $additional_tags = array() ) {
		if ( ! empty( $ac_account ) && ! empty( $ac_account['tags'] ) && $this->has_membership_tags( $ac_account['tags'] ) ) {
			$params = array(
				'id'   => $ac_account['id'],
				'tags' => array_merge( $this->existing_contact_control_tag, $additional_tags ),
			);

			$this->ac_api->api( 'contact/tag_add', $params );
		} else {
			$add_contact = $this->ac_api->api( 'contact/add', $contact );

			if ( $add_contact->success ) {
				$contact = $this->get_ac_contact( $contact['email'] );
				$params = array(
					'id'   => $contact['id'],
					'tags' => array_merge( $this->new_contact_control_tag, $additional_tags ),
				);

				$this->ac_api->api( 'contact/tag_add', $params );
			}
		}
	}

	/**
	 * AC contact
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param string $email The user's email address.
	 *
	 * @return array
	 */
	public function get_ac_contact( $email ) {
		// Bail if empty.
		if ( empty( $email ) ) {
			return array();
		}

		$contact = $this->ac_api->api( "contact/view?email={$email}" );
		$data    = array();

		if ( $contact->success ) {
			$data['id']   = $contact->id;
			$data['tags'] = $contact->tags;
		}

		return $data;
	}

	/**
	 * Is ActiveCampaign Form
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param array $entry The entry object.
	 *
	 * @return boolean
	 */
	public function is_active_campaign_form( $entry ) {
		if ( ! empty( $entry ) ) {
			foreach ( $entry as $key => $value ) {
				if ( $value === 'ac_submit' ) {
					return true;
				}
			}
		}

		return false;
	}

	/**
	 * Has Tags
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param array $tags An array of contact's current tags.
	 *
	 * @return boolean
	 */
	public function has_membership_tags( $tags ) {
		if ( ! empty( $tags ) ) {
			foreach ( $tags as $tag ) {
				if ( in_array( $tag, $this->existing_membership_tags, true ) ) {
					return true;
				}
			}

			return false;
		}

		return false;
	}

	/**
	 * Chargebee Cancel Description
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function chargebee_cancel_subscription() {
		$email             = $_POST['contact']['email'];
		$chargebee         = new Chargebee\ChargebeeApi();
		$chargebee_user    = $chargebee->get_user_by_email( $email );
		$chargebee_user_id = $chargebee_user->id;

		$chargebee->cancel_subscription( $chargebee_user_id );
	}
}
