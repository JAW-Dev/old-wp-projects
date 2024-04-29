<?php
/**
 * Settings Cache
 *
 * @package    Fp_Account_Settings/
 * @subpackage Fp_Account_Settings/Includes/AdminBar
 * @author     Objectiv
 * @copyright  Copyright (c) 2020, Objectiv
 * @license    GPL-2.0
 * @version    1.0.0
 */

namespace FpAccountSettings\Includes\Classes\AdminBar;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

/**
 * Settings Cache
 *
 * @author Jason Witt
 * @since  1.0.0
 */
class SettingsCache {

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
		add_action( 'after_setup_theme', [ $this, 'show' ] );
		add_action( 'admin_bar_menu', [ $this, 'clear_user_transients' ], 99 );
	}

	/**
	 * Show
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function show() {
		if ( ! is_admin() && ! current_user_can( 'administrator' ) && ! $this->was_admin() ) {
			show_admin_bar( false );
		}
	}

	/**
	 * Clear User Transients
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function clear_user_transients() {
		if ( current_user_can( 'administrator' ) || $this->was_admin() ) {

			global $wp_admin_bar;

			$menu_id         = 'user-actions';
			$current_user_id = get_current_user_id();

			$wp_admin_bar->add_menu(
				array(
					'title' => __( 'User Cache' ),
					'id'    => $menu_id . '-user-cache',
					'meta'  => [
						'class' => 'transient-user-cache',
					],
				)
			);

			$wp_admin_bar->add_menu(
				array(
					'parent' => $menu_id . '-user-cache',
					'title'  => __( 'Clear All User Cache' ),
					'id'     => $menu_id . '-all',
					'meta'   => [
						'class' => 'transient-user-clear transient-user-all',
					],
				)
			);

			if ( ! is_admin() ) {
				$wp_admin_bar->add_menu(
					array(
						'parent' => $menu_id . '-user-cache',
						'title'  => __( 'Clear Current User Cache' ),
						'id'     => $menu_id . '-' . $current_user_id,
						'meta'   => [
							'class' => 'transient-user-clear transient-user-current',
						],
					)
				);
			}
		};
	}

	/**
	 * Was Admin After Swtich
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return bool
	 */
	public function was_admin(): bool {
		$was_admin = false;
		$old_user  = \user_switching::get_old_user();

		if ( $old_user ) {
			$was_admin = in_array( 'administrator', $old_user->roles, true );
		}

		return $was_admin;
	}
}
