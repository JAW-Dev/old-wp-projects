<?php
/**
 * Dashboard Page.
 *
 * @package    Custom_Reports
 * @subpackage Custom_Reports/Inlcudes/Classes
 * @author     Jason Witt
 * @copyright  Copyright (c) 2020, Jason Witt
 * @license    GPL-2.0
 * @since      1.0.0
 */

namespace CustomReports;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

if ( ! class_exists( __NAMESPACE__ . '\\DashboardPage' ) ) {

	/**
	 * Dashboard Page.
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 */
	class DashboardPage {

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
			$this->reports();
		}

		/**
		 * Init
		 *
		 * @author Jason Witt
		 * @since  1.0.0
		 *
		 * @return void
		 */
		public function reports() {
			new QuizTimeAverage\Main();
			new QuizTimeRaw\Main();
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
			add_action( 'admin_menu', array( $this, 'init' ) );
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
			$icon = 'PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAzODQgNTEyIj48cGF0aCBmaWxsPSJibGFjayIgZD0iTTM2MCAwSDI0QzEwLjcgMCAwIDEwLjcgMCAyNHY0NjRjMCAxMy4zIDEwLjcgMjQgMjQgMjRoMzM2YzEzLjMgMCAyNC0xMC43IDI0LTI0VjI0YzAtMTMuMy0xMC43LTI0LTI0LTI0ek0xMjggNDAwYzAgOC44LTcuMiAxNi0xNiAxNkg4MGMtOC44IDAtMTYtNy4yLTE2LTE2di0zMmMwLTguOCA3LjItMTYgMTYtMTZoMzJjOC44IDAgMTYgNy4yIDE2IDE2djMyem0wLTEyOGMwIDguOC03LjIgMTYtMTYgMTZIODBjLTguOCAwLTE2LTcuMi0xNi0xNnYtMzJjMC04LjggNy4yLTE2IDE2LTE2aDMyYzguOCAwIDE2IDcuMiAxNiAxNnYzMnptMC0xMjhjMCA4LjgtNy4yIDE2LTE2IDE2SDgwYy04LjggMC0xNi03LjItMTYtMTZ2LTMyYzAtOC44IDcuMi0xNiAxNi0xNmgzMmM4LjggMCAxNiA3LjIgMTYgMTZ2MzJ6bTE5MiAyNDhjMCA0LjQtMy42IDgtOCA4SDE2OGMtNC40IDAtOC0zLjYtOC04di0xNmMwLTQuNCAzLjYtOCA4LThoMTQ0YzQuNCAwIDggMy42IDggOHYxNnptMC0xMjhjMCA0LjQtMy42IDgtOCA4SDE2OGMtNC40IDAtOC0zLjYtOC04di0xNmMwLTQuNCAzLjYtOCA4LThoMTQ0YzQuNCAwIDggMy42IDggOHYxNnptMC0xMjhjMCA0LjQtMy42IDgtOCA4SDE2OGMtNC40IDAtOC0zLjYtOC04di0xNmMwLTQuNCAzLjYtOCA4LThoMTQ0YzQuNCAwIDggMy42IDggOHYxNnoiLz48L3N2Zz4=';

			add_menu_page(
				__( 'Custom Reports', 'custom-reports' ),
				__( 'Custom Reports', 'custom-reports' ),
				'manage_options',
				'custom-reports',
				array( $this, 'render' ),
				'data:image/svg+xml;base64,' . $icon
			);
		}

		/**
		 * Render
		 *
		 * @author Jason Witt
		 * @since  1.0.0
		 *
		 * @return void
		 */
		public function render() {
			?>
			<div class="wrap">
				<h1>Reports</h1>
				<div class="custom-reports">
					<?php
						( new QuizTimeAverage\Template() )->render();
						( new QuizTimeRaw\Template() )->render();
					?>
				</div>
			</div>
			<?php
		}
	}
}
