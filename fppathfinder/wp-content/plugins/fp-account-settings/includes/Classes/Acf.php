<?php
/**
 * Acf.
 *
 * @package    Fp_Account_Settings
 * @subpackage Fp_Account_Settings/Inlcudes/Classes
 * @author     Objectiv
 * @copyright  Copyright (c) 2021, Objectiv
 * @license    GNU General Public License v2 or later
 * @since      1.0.0
 */

namespace FpAccountSettings\Includes\Classes;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

/**
 * Acf.
 *
 * @author Objectiv
 * @since  1.0.0
 */
class Acf {

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
		add_action( 'init', [ $this, 'set_options_page' ] );
		add_action( 'acf/save_post', [ $this, 'clear_user_settings_transients' ] );
	}

	/**
	 * Set Options Page
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function set_options_page() {
		if ( function_exists( 'acf_add_options_page' ) ) {
			acf_add_options_sub_page(
				array(
					'page_title'  => 'Account Settings Page Settings',
					'menu_title'  => 'Account Settings',
					'parent_slug' => 'theme-general-settings',
					'menu_slug'   => 'account-page-settings',
					'icon_url'    => 'dashicons-filter',
					'capability'  => 'edit_posts',
					'redirect'    => false,
				)
			);
		}
	}

	/**
	 * Clear User Settings Transients
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function clear_user_settings_transients() {
		if ( ! is_admin() ) {
			return;
		}

		$page = isset( $_GET['page'] ) ? sanitize_text_field( wp_unslash( $_GET['page'] ) ) : '';

		if ( $page === 'account-page-settings' ) {
			// TODO: REMOVE!
			error_log( ': ' . print_r( 'Is account page settings', true ) ); // phpcs:ignore
		}
	}
}
