<?php
/**
 * Enqueue.
 *
 * @package    Fp_Account_Settings
 * @subpackage Fp_Account_Settings/Inlcudes/Classes
 * @author     Objectiv
 * @copyright  Copyright (c) 2021, Objectiv
 * @license    GNU General Public License v2 or later
 * @since      1.0.0
 */

namespace FpAccountSettings\Includes\Classes;

use FpAccountSettings\Includes\Classes\Conditionals;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

/**
 * Enqueue.
 *
 * @author Objectiv
 * @since  1.0.0
 */
class Enqueue {

	/**
	 * Template File
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @var string
	 */
	protected $template_file = FP_ACCOUNT_SETTINGS_DIR_PATH . 'includes/Templates/account-settings.php';

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
		add_action( 'wp_enqueue_scripts', array( $this, 'global_scripts' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'global_scripts' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'account_sttings_scripts' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'global_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'global_styles' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'account_settings_styles' ) );
	}

	/**
	 * Global Scripts
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function global_scripts() {
		$filename = 'src/js/global.js';
		$file     = FP_ACCOUNT_SETTINGS_DIR_PATH . $filename;
		$version  = file_exists( $file ) ? filemtime( $file ) : '1.0.0';
		$handle   = 'fp-account-settings-global';

		wp_register_script( $handle, FP_ACCOUNT_SETTINGS_DIR_URL . $filename, array(), $version, true );
		wp_enqueue_script( $handle );

		wp_localize_script(
			$handle,
			FP_ACCOUNT_SETTINGS_PREFIX . 'AdminAjax',
			admin_url( 'admin-ajax.php' )
		);
	}

	/**
	 * Account Settings Scripts
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function account_sttings_scripts() {
		if ( ! is_page_template( $this->template_file ) ) {
			return;
		}

		$filename = 'src/js/index.js';
		$file     = FP_ACCOUNT_SETTINGS_DIR_PATH . $filename;
		$version  = file_exists( $file ) ? filemtime( $file ) : '1.0.0';
		$handle   = 'fp-account-settings';

		wp_enqueue_script( 'spectrum-js', FP_ACCOUNT_SETTINGS_DIR_URL . 'assets/js/scripts/spectrum.js', array( 'jquery' ), $version, true );
		wp_enqueue_script( 'jodit', FP_ACCOUNT_SETTINGS_DIR_URL . 'assets/js/scripts/jodit.min.js', array(), $version, true );

		wp_register_script( $handle, FP_ACCOUNT_SETTINGS_DIR_URL . $filename, array( 'spectrum-js' ), $version, true );
		wp_enqueue_script( $handle );

		$member_data             = fp_get_user_settings();
		$get_can_save_whitelable = \FP_PDF_Generator\Customization_Controller::user_can_save_white_label_settings( get_current_user_id() );
		$can_save_whitelable     = $get_can_save_whitelable ? $get_can_save_whitelable : 0;

		$member_data['defaultLogo']       = 'data:image/png;base64,' . base64_encode( file_get_contents( get_attached_file( get_field( 'pdf_generator_default_logo', 'option' )['ID'] ) ) ); // phpcs:ignore
		$member_data['canSaveWhitelable'] = $can_save_whitelable;

		$group_id        = fp_get_group_id();
		$is_group_member = $group_id;
		$can_administer  = Conditionals::can_administer_group();

		if ( $is_group_member && ! $can_administer ) {
			$member_data['useGroupLogo'] = true;
		}

		wp_localize_script(
			$handle,
			FP_ACCOUNT_SETTINGS_PREFIX . 'Data',
			$member_data
		);
	}

	/**
	 * Global Styles
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function global_styles() {
		$filename = 'src/css/global.css';
		$file     = FP_ACCOUNT_SETTINGS_DIR_PATH . $filename;
		$version  = file_exists( $file ) ? filemtime( $file ) : '1.0.0';
		$handle   = 'fp-account-settings-global';

		wp_register_style( $handle, FP_ACCOUNT_SETTINGS_DIR_URL . $filename, array(), $version, 'all' );
		wp_enqueue_style( $handle );
	}

	/**
	 * Account Settings Styles
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function account_settings_styles() {
		if ( ! is_page_template( $this->template_file ) ) {
			return;
		}

		$filename = 'src/css/index.css';
		$file     = FP_ACCOUNT_SETTINGS_DIR_PATH . $filename;
		$version  = file_exists( $file ) ? filemtime( $file ) : '1.0.0';
		$handle   = 'fp-account-settings';

		wp_register_style( $handle, FP_ACCOUNT_SETTINGS_DIR_URL . $filename, array(), $version, 'all' );
		wp_enqueue_style( $handle );

		wp_enqueue_style( 'jodit', FP_ACCOUNT_SETTINGS_DIR_URL . 'assets/css/scripts/jodit.min.css', array(), $version, 'all' );
		wp_enqueue_style( 'spectrum', FP_ACCOUNT_SETTINGS_DIR_URL . 'assets/css/scripts/spectrum.css', array(), $version, 'all' );
	}
}
