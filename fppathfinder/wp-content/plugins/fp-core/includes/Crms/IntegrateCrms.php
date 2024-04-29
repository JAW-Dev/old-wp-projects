<?php
/**
 * IntegrateCrms
 *
 * @package    FP_Core/
 * @subpackage FP_Core/Includes/Crms
 * @author     Objectiv
 * @copyright  Copyright (c) 2020, Objectiv
 * @license    GPL-2.0
 * @version    1.0.0
 */

namespace FP_Core\Crms;


if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

/**
 * IntegrateCrms
 *
 * @author Jason Witt
 * @since  1.0.0
 */
class IntegrateCrms {

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
		add_action( 'wp_ajax_app_integration_activation', [ $this, 'activate_crm_ajax' ] );
	}

	/**
	 * Activate Crm Ajax
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function activate_crm_ajax() {
		$user_id  = get_current_user_id();
		$nonce    = $_GET['nonce'] ?? false;
		$response = '';
		$crm_info = array();
		$status   = '';

		if ( ! $nonce ) {
			wp_die();
		}

		$safe_nonce = sanitize_text_field( wp_unslash( $nonce ) );

		if ( ! wp_verify_nonce( $safe_nonce, 'app-activate-nonce' ) ) {
			wp_die();
		}

		$slug = $_GET['slug'] ?? false;

		if ( ! $slug ) {
			wp_die();
		}

		$active = $_GET['active'] ?? false;

		foreach ( Utilities::get_crms() as $crm ) {
			if ( $slug === $crm['slug'] ) {
				$crm_info = $crm;
				break;
			}
		}

		if ( empty( $crm_info ) ) {
			wp_die();
		}

		if ( '0' === $active ) {
			$this->deactivate( $user_id, $slug );
			$status = 'Inactive';
		}

		if ( '1' === $active ) {
			$this->activate( $user_id, $slug );
			$status = 'Active';
		}

		$response = json_encode(
			array(
				'isActive' => $active,
				'value'    => $status,
				'slug'     => $slug,
				'status'   => Utilities::get_status_label( $active, $crm_info['tokens'] ),
			)
		);

		echo $response;
		wp_die();
	}

	public function activate( int $user_id, $slug ) {
		update_user_meta( $user_id, 'current_active_crm', $slug );
		update_user_meta( $user_id, "{$slug}_integration_active", true );

		// Set the other CRMs to false
		foreach ( Utilities::get_crms() as $crm ) {
			if ( $slug !== $crm['slug'] ) {
				update_user_meta( $user_id, "{$crm['slug']}_integration_active", false );
			}
		}
	}

	public function deactivate( int $user_id, $slug ) {
		update_user_meta( $user_id, 'current_active_crm', '' );
		update_user_meta( $user_id, "{$slug}_integration_active", false );
	}
}
