<?php
/**
 * Main
 *
 * @package    FP_Core
 * @subpackage FP_Core/Group_Settings
 * @author     Objectiv
 * @copyright  Copyright (c) 2020, Objectiv
 * @license    GPL-2.0
 * @version    1.0.0
 */

namespace FP_Core\Group_Settings;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

/**
 * Main
 *
 * @author John Geesey|Jason Witt
 * @since  1.0.0
 */
class Main {
	/**
	 * Initialize the class
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function __construct() {
		Database::init();
		Settings_Form_Registrar::init();
		Settings\Settings_Registrar::init();
	}
}

