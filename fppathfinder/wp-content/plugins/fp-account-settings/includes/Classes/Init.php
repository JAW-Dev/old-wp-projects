<?php
/**
 * Init.
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
 * Init.
 *
 * @author Objectiv
 * @since  1.0.0
 */
class Init {

	/**
	 * Initialize the class
	 *
	 * @author Objectiv
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function __construct() {
		$this->init_classes();
	}

	/**
	 * Init Classes
	 *
	 * @author Objectiv
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function init_classes() {
		new Enqueue();
		new PageTemplates( $this->templates() );
		new Acf();
		new Ajax\Main();
		new Admin\Main();
		new AdminBar\Main();
		if ( fp_is_feature_active( 'real_image_logos' ) ) {
			new Logo();
		}
	}

	/**
	 * Templates
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return array
	 */
	public function templates() {
		$prefix    = FP_ACCOUNT_SETTINGS_DIR_PATH . 'includes/Templates/';
		$templates = [
			$prefix . 'account-settings.php' => 'Account Settings',
		];

		return apply_filters( FP_ACCOUNT_SETTINGS_PREFIX . '_templates', $templates );
	}
}
