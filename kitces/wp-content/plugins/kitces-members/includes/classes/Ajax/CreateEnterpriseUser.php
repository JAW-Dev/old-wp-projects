<?php
/**
 * Create Enterprise User
 *
 * @package    Kitces_Members
 * @subpackage Kitces_Members/Inlcudes/Classes/Ajax
 * @author     Objectiv
 * @copyright  Copyright (c) 2021, Objectiv
 * @license    GNU General Public License v2 or later
 * @since      1.0.0
 */

namespace Kitces_Members\Includes\Classes\Ajax;

use Carbon\Carbon;
use Kitces_ChargeBee_Connector;
use Kitces_Members\Includes\Classes\ActiveCampaign\Contact;
use Kitces_Members\Includes\Classes\ActiveCampaign\Core;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

/**
 * Create Enterprise User
 *
 * @author Jason Witt
 * @since  1.0.0
 */
class CreateEnterpriseUser extends Core {
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
		add_action( 'wp_ajax_create_enterprise_user', array( $this, 'create_user' ) );
		add_action( 'wp_ajax_nopriv_create_enterprise_user', array( $this, 'create_user' ) );
	}

	/**
	 * Save
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function create_user() {
		/**
		 * Kitces_ChargeBee_Connector
		 *
		 * @var Kitces_ChargeBee_Connector
		 */
	    global $Kitces_ChargeBee_Connector; // phpcs:ignore

		$post = sanitize_post( wp_unslash( $_POST ) ) ?? '';
		$code = isset( $_GET['code'] ) ? sanitize_text_field( wp_unslash( $_GET['code'] ) ) : '';

		if ( $code !== 'saib_rosk0dosk7MOB' ) {
			wp_die();
		}

		if ( empty( $post ) ) {
			wp_die();
		}

		$contact = $post['contact'];

		if ( empty( $contact ) ) {
			return;
		}

		$ac_contact_id = $contact['id'];
		$email         = $contact['email'];
		$first_name    = $contact['first_name'];
		$last_name     = $contact['last_name'];
		$password      = wp_generate_password( 20, false, false );
		$current_user  = get_user_by( 'email', $email );
		$id            = $current_user ? $current_user->ID : false;

		$args = array(
			'ID'         => $id,
			'user_pass'  => $password,
			'user_login' => $email,
			'user_email' => $email,
			'first_name' => $first_name,
			'last_name'  => $last_name,
		);

		$new_user = wp_insert_user( $args );

		if ( is_numeric( $new_user ) ) {
			$date = Carbon::now()->addDays( 30 ); // 24 hours after now

			$key           = sha1( $password . $email . uniqid( time(), true ) );
			$reset_page_id = function_exists( 'get_field' ) ? get_field( 'kitces_new_member_reset_link_page', 'option' ) : '';

			if ( empty( $reset_page_id ) ) {
				return;
			}

			$url = add_query_arg(
				array(
					'nmb_key' => $key,
					'nmb'     => $new_user,
				),
				get_the_permalink( $reset_page_id )
			);

			$contact_update = array(
				'email'               => $email,
				'field[%PASSWORD%,0]' => $url,
			);

			$this->ac_api->api( 'contact/sync', $contact_update );

			update_user_meta( $new_user, 'member_activation_key', $key );
			update_user_meta( $new_user, 'member_activation_key_expiry', $date->timestamp );
			update_user_meta( $new_user, 'needs_password_reset', true );
			update_user_meta( $new_user, 'ac_contact_id', $ac_contact_id );

			$user       = get_user_by( 'id', $new_user );
			$ac_contact = ( new Contact() )->get( $user->ID, true );
			$role       = $Kitces_ChargeBee_Connector->get_wp_role_from_ac( $email, $ac_contact->tags ); // phpcs:ignore

			$member_roles = array(
				'subscriber',
				'premier',
				'basic',
				'student',
                'reader'
			);

			foreach ( $member_roles as $member_role ) {
				$user->remove_role( $member_role );
			}

			$user->add_role( $role );
		} else {
			error_log( 'Error creating enterprise user ' . $email . ' ' . print_r( $new_user, true ) ); // phpcs:ignore
		}
	}
}
