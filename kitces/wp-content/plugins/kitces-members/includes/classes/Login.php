<?php
/**
 * Login
 *
 * @package    Kitces_Members
 * @subpackage Kitces_Members/Inlcudes/Classes
 * @author     Objectiv
 * @copyright  Copyright (c) 2021, Objectiv
 * @license    GNU General Public License v2 or later
 * @since      1.0.0
 */

// TODO: Clean this file up by moving code to classes In the Login dir.

namespace Kitces_Members\Includes\Classes;

use Kitces_ChargeBee_Connector;
use Kitces_Members\Includes\Classes\ActiveCampaign;
use Kitces_Members\Includes\Classes\Login\PasswordLink;
use WP_User;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

/**
 * Login
 *
 * @author Jason Witt
 * @since  1.0.0
 */
class Login {

	/**
	 * Initialize the class
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function __construct() {
		$this->hooks();
		PasswordLink::instance()->init();
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
		add_action( 'wp_login', array( $this, 'kitces_after_user_login' ), 99, 2 );
		add_action( 'wp_login_failed', array( $this, 'login_fail' ) );
		add_action( 'switch_to_user', array( $this, 'sync_on_switch_to' ), 10, 1 );
	}

	/**
	 * Sync on switch to
	 *
	 * @author Clif Griffin
	 * @since  1.0.0
	 *
	 * @param int $user_id The User ID.
	 */
	public function sync_on_switch_to( $user_id ) {
		$user = get_user_by( 'id', $user_id );

		if ( $user ) {
			$this->update_user_ac_data( $user );
		}
	}

	/**
	 * Method
	 *
	 * @param string  $user_login The username.
	 * @param WP_User $user      The User object.
	 *
	 * @return void
	 * @since  1.0.0
	 *
	 * @author Clif Griffin
	 */
	public function kitces_after_user_login( string $user_login, WP_User $user ) {
		$this->update_user_ac_data( $user );
		$this->maybe_redirect( $user_login, $user );
	}

	/**
	 * Update User AC data
	 *
	 * @param WP_User|null $user The User object.
	 *
	 * @return void
	 * @author Jason Witt
	 * @since  1.0.0
	 */
	public function update_user_ac_data( ?WP_User $user = null ) {
		/**
		 *  Kitces_ChargeBee_Connector
		 *
		 * @var Kitces_ChargeBee_Connector
		 */
		global $Kitces_ChargeBee_Connector; // phpcs:ignore

		if ( empty( $user ) ) {
			return;
		}

		$contact = ( new ActiveCampaign\Contact() )->get( $user->ID, true );

		if ( empty( $contact ) ) {
			return;
		}

		$first_name = $contact->first_name;
		$last_name  = $contact->last_name;
		$email      = $contact->email;

		update_user_meta( $user->ID, 'first_name', $first_name );
		update_user_meta( $user->ID, 'last_name', $last_name );
		update_user_meta( $user->ID, 'ac_firstname', $first_name );
		update_user_meta( $user->ID, 'ac_lastname', $last_name );

		$tags = $contact->tags;

		update_user_meta( $user->ID, 'ac_tags', $tags );

		$fields     = $contact->fields;
		$get_fields = array(
			'CFP_CE_NUMBER',
			'IMCA_CE_NUMBER',
			'CPA_CE_NUMBER',
			'PTIN_CE_NUMBER',
			'AMERICAN_COLLEGE_ID',
			'IAR_CE_NUMBER',
			'EXPIRATION_DATE',
		);

		if ( empty( $fields ) ) {
			return;
		}

		foreach ( $fields as $field ) {
			foreach ( $get_fields as $get_field ) {
				if ( $field->perstag === $get_field ) {
					update_user_meta( $user->ID, 'ac_' . strtolower( $field->perstag ), $field->val );
				}
			}
		}

		$role = $Kitces_ChargeBee_Connector->get_wp_role_from_ac( $email, $contact->tags ); // phpcs:ignore

		$member_roles = array(
			'subscriber',
			'premier',
			'basic',
			'student',
			'reader',
		);

		foreach ( $member_roles as $member_role ) {
			$user->remove_role( $member_role );
		}

		$user->add_role( $role );
	}

	/**
	 * Maybe Redirect
	 *
	 * @param string  $user_login The username.
	 * @param WP_User $user       The User object.
	 *
	 * @return void
	 * @since  1.0.0
	 *
	 * @author Jason Witt
	 */
	public function maybe_redirect( string $user_login, WP_User $user ) {
		if ( empty( $user_login ) ) {
			exit();
		}

		$redirect_url = function_exists( 'kitces_get_survey_url' ) ? kitces_get_survey_url() : '';

		if ( ! empty( $redirect_url ) ) {
			wp_redirect( $redirect_url, 307 ); // phpcs:ignore
			exit();
		}

		$needs_password_reset = get_user_meta( $user->ID, 'needs_password_reset', true );

		if ( ! empty( $needs_password_reset ) ) {
			$url = home_url( 'member/my-account/#password-reset-form' );

			wp_redirect( $url, 307 ); // phpcs:ignore
			exit();
		}
	}

	/**
	 * Login Fail
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function login_fail() {
		$referrer = $_SERVER['HTTP_REFERER']; // phpcs:ignore

		if ( ! empty( $referrer ) && ! strstr( $referrer, 'wp-login' ) && ! strstr( $referrer, 'wp-admin' ) ) {
			$referrer = esc_url( remove_query_arg( 'login', $referrer ) );
			wp_redirect( add_query_arg( array( 'login' => 'failed' ), $referrer ) ); // phpcs:ignore
			exit;
		}
	}
}
