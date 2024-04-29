<?php
/**
 * Init.
 *
 * @package    Kitces_Members
 * @subpackage Kitces_Members/Inlcudes/Classes
 * @author     Objectiv
 * @copyright  Copyright (c) 2021, Objectiv
 * @license    GNU General Public License v2 or later
 * @since      1.0.0
 */

namespace Kitces_Members\Includes\Classes;

use Kitces_Members\Includes\Classes\Utilities\Main as Utilities;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

if ( ! class_exists( __NAMESPACE__ . '\\Init' ) ) {

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
			new Utilities();
			new Login();
			new CeCredits();
			new ActiveCampaign\Main();
			new Shortcodes\Main();
			new Access\Main();
			new Ajax\Main();
			new MemberActions\Main();
			new Passwords();
		}
	}
}
