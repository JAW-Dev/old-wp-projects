<?php
/**
 * Salesforce Subset
 *
 * @package    FP_Core/
 * @subpackage FP_Core/Includes/Utilities/Formatting
 * @author     Objectiv
 * @copyright  Copyright (c) 2020, Objectiv
 * @license    GPL-2.0
 * @version    1.0.0
 */

namespace FP_Core\Utilities\Crms;

use FP_Core\Crms\Apis\SalesforceAPI;
use FP_Core\Crms\Salesforce\OAuthController;
use FP_Core\Utilities as CoreUtilities;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

/**
 * Salesforce Subset
 *
 * @author Jason Witt
 * @since  1.0.0
 */
class SalesforceSubset {

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
		add_action( 'init', [ $this, 'maybe_set_subset' ] );
	}

	/**
	 * Maybe Set Subset
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function maybe_set_subset() {
		$user_id    = get_current_user_id();
		$active_crm = get_user_meta( $user_id, 'current_active_crm', true );
		$subset     = get_user_meta( $user_id, 'salesforce_subset', true );

		if ( $subset === 'none' ) {
			return;
		}

		if ( empty( $active_crm ) || $active_crm !== 'salesforce' ) {
			return;
		}

		if ( fp_is_feature_active( 'xlr8_crm' ) ) {
			$is_xlr8 = $this->salesforce_subset() === 'xlr8';

			if ( $is_xlr8 ) {
				$this->set_user_fields( $user_id, 'xlr8' );
			}
		}
	}

	/**
	 * Set User Fields
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param int    $user_id The user ID.
	 * @param string $slug    The slug of the subset CRM.
	 *
	 * @return void
	 */
	public function set_user_fields( int $user_id = null, string $slug = '' ) {
		if ( is_null( $user_id ) || empty( $slug ) ) {
			return;
		}

		update_user_meta( $user_id, 'current_active_crm', $slug );
		update_user_meta( $user_id, 'salesforce_integration_active', false );
		update_user_meta( $user_id, "{$slug}_integration_active", true );

		if ( function_exists( 'update_field' ) ) {
			$salesforce_tokens = get_user_meta( $user_id, 'salesforce_tokens', true );

			if ( ! empty( $salesforce_tokens ) ) {
				update_field( "{$slug}_tokens", $salesforce_tokens, "user_$user_id" );
				update_field( 'salesforce_tokens', '', "user_$user_id" );
			}
		}
	}

	/**
	 * Salesforce Subset
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return boolean|string
	 */
	public function salesforce_subset() {
		$user_id = get_current_user_id();

		if ( $this->xlr8_subset( $user_id ) ) {
			$subset = get_user_meta( $user_id, 'salesforce_subset', true );

			if ( $subset !== 'xlr8' ) {
				update_user_meta( $user_id, 'salesforce_subset', 'xlr8' );
			}

			return 'xlr8';
		}

		update_user_meta( $user_id, 'salesforce_subset', 'none' );

		return false;
	}

	/**
	 * XLR8 Subset
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param int $user_id The user ID.
	 *
	 * @return boolean
	 */
	public function xlr8_subset( int $user_id = null ) {
		if ( is_null( $user_id ) ) {
			return false;
		}

		if ( metadata_exists( 'user', $user_id, 'salesforce_subset' ) ) {
			$subset = get_user_meta( $user_id, 'salesforce_subset', true );

			if ( $subset === 'xlr8' ) {
				return true;
			}
		}

		$bearer_token = ( new OAuthController() )->get_user_token( $user_id );

		if ( empty( $bearer_token ) ) {
			return false;
		}

		$objects_request_args = array(
			'method'  => 'GET',
			'headers' => array(
				'Content-Type'  => 'application/json',
				'AUTHORIZATION' => 'Bearer ' . $bearer_token,
			),
		);

		$objects = wp_remote_request( SalesforceAPI::get_base_url() . 'sobjects/', $objects_request_args );

		if ( is_wp_error( $objects ) ) {
			return false;
		}

		$sobjects = json_decode( $objects['body'] )->sobjects ?? array();

		if ( ! empty( $sobjects ) ) {
			foreach ( $sobjects as $sobject ) {
				if ( $sobject->name === 'XLR8CS__Critical_Note__c' ) {
					return true;
					break;
				}
			}
		}

		return false;
	}
}
