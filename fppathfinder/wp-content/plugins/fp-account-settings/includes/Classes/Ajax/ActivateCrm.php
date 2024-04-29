<?php
/**
 * Activate CRM.
 *
 * @package    Fp_Account_Settings
 * @subpackage Fp_Account_Settings/Inlcudes/Classes/Ajax
 * @author     Objectiv
 * @copyright  Copyright (c) 2021, Objectiv
 * @license    GNU General Public License v2 or later
 * @since      1.0.0
 */

namespace FpAccountSettings\Includes\Classes\Ajax;

use FP_Core\Crms\Utilities as FPCoreCrmsUtillites;
use FpAccountSettings\Includes\Classes\TemplateParts\Pages\Integrations;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

/**
 * Activate CRM.
 *
 * @author Objectiv
 * @since  1.0.0
 */
class ActivateCrm {

	/**
	 * Initialize the class
	 *
	 * @author Objectiv
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
		add_action( 'wp_ajax_activate_crm', [ $this, 'activate_crm' ] );
	}

	/**
	 * Activate Crm Ajax
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function activate_crm() {
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

		foreach ( FPCoreCrmsUtillites::get_crms() as $crm ) {
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

		$response = wp_json_encode(
			array(
				'isActive' => $active,
				'value'    => $status,
				'slug'     => $slug,
				'status'   => $this->get_status_label( $active, $crm_info['tokens'] ),
			)
		);

		echo $response;
		wp_die();
	}

	/**
	 * Activate
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param int    $user_id The user ID.
	 * @param string $slug    The CRM slug.
	 *
	 * @return void
	 */
	public function activate( $user_id, $slug ) {
		update_user_meta( $user_id, 'current_active_crm', $slug );
		update_user_meta( $user_id, "{$slug}_integration_active", true );

		// Set the other CRMs to false
		foreach ( FPCoreCrmsUtillites::get_crms() as $crm ) {
			if ( $slug !== $crm['slug'] ) {
				update_user_meta( $user_id, "{$crm['slug']}_integration_active", false );
			}
		}
	}

	/**
	 * Deactivate
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param int    $user_id The user ID.
	 * @param string $slug    The CRM slug.
	 *
	 * @return void
	 */
	public function deactivate( $user_id, $slug ) {
		update_user_meta( $user_id, 'current_active_crm', '' );
		update_user_meta( $user_id, "{$slug}_integration_active", false );
	}

	/**
	 * Get Status Label
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param boolean $active If is active.
	 * @param string  $tokens The crm tokens.
	 *
	 * @return string
	 */
	public static function get_status_label( $active, $tokens ) {
		if ( $active && empty( $tokens ) ) {
			return 'Active but not connected, see Settings';
		}

		return '';
	}
}
