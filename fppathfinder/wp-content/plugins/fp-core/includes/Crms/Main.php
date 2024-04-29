<?php
/**
 * Main
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
	 * Set Crms
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return array
	 */
	public static function set_crms() {
		// The name of the CRM as you want it displayed.
		// Use underscore for spaces.
		return [
			'Wealthbox_CRM',
			'Redtail',
			'Salesforce',
			'XLR8',
		];
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
		new IntegrateCrms();
		new ContactLookup\Template();
		new ContactLookup\Lookup();
		new ContactLookup\ContactNameGetter();
		new NoteCreator();
	}
}
