<?php
/**
 * Activation.
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

if ( ! class_exists( __NAMESPACE__ . '\\Activation' ) ) {

	/**
	 * Activation.
	 *
	 * @author Objectiv
	 * @since  1.0.0
	 */
	class Activation {

		/**
		 * Run
		 *
		 * @author Objectiv
		 * @since  1.0.0
		 *
		 * @return void
		 */
		public static function run() {
			// Install table.
			require_once ABSPATH . 'wp-admin/includes/upgrade.php';
			Tables\Table::activate();
			flush_rewrite_rules();
		}
	}
}
