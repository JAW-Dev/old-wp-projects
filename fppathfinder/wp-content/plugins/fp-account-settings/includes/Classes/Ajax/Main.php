<?php
/**
 * Ajax Main.
 *
 * @package    Fp_Account_Settings
 * @subpackage Fp_Account_Settings/Inlcudes/Classes/Ajax
 * @author     Objectiv
 * @copyright  Copyright (c) 2021, Objectiv
 * @license    GNU General Public License v2 or later
 * @since      1.0.0
 */

namespace FpAccountSettings\Includes\Classes\Ajax;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

/**
 * Ajax Main.
 *
 * @author Objectiv
 * @since  1.0.0
 */
class Main {

	/**
	 * Initialize the class
	 *
	 * @author Objectiv
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function __construct() {
		$this->init();
	}

	/**
	 * Hooks
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function init() {
		new SaveGroupSettingsOther();
		new SaveGroupSettingsPermissions();
		new SaveGroupSettingsWhitelabelSettings();
		new SaveGroupSettingsShareLinkSettings();
		new SaveShareLinkSettings();
		new SaveWhitelabelSettings();
		new ActivateCrm();
		new CurrentUserTransients();
		new AllUserTransients();
		new RemoveSharedLinks();
	}
}
