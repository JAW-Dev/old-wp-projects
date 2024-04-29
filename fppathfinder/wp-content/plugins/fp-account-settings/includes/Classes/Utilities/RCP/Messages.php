<?php
/**
 * Display Messages.
 *
 * @package    Fp_Account_Settings
 * @subpackage Fp_Account_Settings/Inlcudes/Utilites/RCP
 * @author     Objectiv
 * @copyright  Copyright (c) 2021, Objectiv
 * @license    GNU General Public License v2 or later
 * @since      1.0.0
 */

namespace FpAccountSettings\Includes\Utilites\RCP;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

/**
 * Display Messages.
 *
 * @author Objectiv
 * @since  1.0.0
 */
class Messages {

	/**
	 * Initialize the class
	 *
	 * @author Objectiv
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function __construct() {}

	/**
	 * Get Access
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function display() {
		if ( ! isset( $_GET['rcp-message'] ) ) {
			return;
		}

		static $displayed = false;

		// Only one message at a time.
		if ( $displayed ) {
			return;
		}

		$message = '';
		$type    = 'success';
		$notice  = $_GET['rcp-message']; // phpcs:ignore

		switch ( $notice ) {
			case 'email-verified':
				$message = __( 'Your email address has been successfully verified.', 'rcp' );
				break;
			case 'verification-resent':
				$message = __( 'Your verification email has been re-sent successfully.', 'rcp' );
				break;
			case 'auto-renew-enabled':
				$message = __( 'Auto renew has been successfully enabled.', 'rcp' );
				break;
			case 'auto-renew-disabled':
				$message = __( 'Auto renew has been successfully disabled.', 'rcp' );
				break;
			case 'auto-renew-enable-failure':
				$error   = isset( $_GET['rcp-auto-renew-message'] ) ? rawurldecode( $_GET['rcp-auto-renew-message'] ) : ''; // phpcs:ignore
				$message = sprintf( __( 'Failed to enable auto renew: %s' ), esc_html( $error ) );
				break;

			case 'auto-renew-disable-failure':
				$error   = isset( $_GET['rcp-auto-renew-message'] ) ? rawurldecode( $_GET['rcp-auto-renew-message'] ) : ''; // phpcs:ignore
				$message = sprintf( __( 'Failed to disable auto renew: %s' ), esc_html( $error ) );
				break;

		}

		if ( empty( $message ) ) {
			return;
		}

		$class = ( 'success' === $type ) ? 'rcp_success' : 'rcp_error';
		printf( '<p class="%s"><span>%s</span></p>', $class, esc_html( $message ) ); // phpcs:ignore

		$displayed = true;
	}
}
