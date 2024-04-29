<?php
/**
 * Init.
 *
 * @package    Kitces_Quizzes
 * @subpackage Kitces_Quizzes/Inlcudes/Classes
 * @author     Objectiv
 * @copyright  Copyright (c) 2022, Objectiv
 * @license    GNU General Public License v2 or later
 * @since      1.0.0
 */

namespace KitcesQuizzes\Includes\Classes;

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
			new Fields();
		}
	}
}
