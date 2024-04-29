<?php
/**
 * Main
 *
 * @package    FpCore/
 * @subpackage FpCore/Actions
 * @author     Objectiv
 * @copyright  Copyright (c) 2020, Objectiv
 * @license    GPL-2.0
 * @version    1.0.0
 */

namespace FP_Core\InteractiveLists\Actions;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

/**
 * Main
 *
 * @author Jason Witt
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
		$this->init();
	}

	/**
	 * Init
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function init() {
		new SendToCRM();
		new SendFromClient();
		( new SendToCRM() )->get_instance();
		( new SendFromClient() )->get_instance();
	}
}
