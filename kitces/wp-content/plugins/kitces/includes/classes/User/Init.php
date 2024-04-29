<?php
/**
 * Init.
 *
 * @package    Kitces
 * @subpackage Kitces/Includes/Classes/User
 * @author     Objectiv
 * @copyright  Copyright (c) 2020, Objectiv
 * @license    GNU General Public License v2 or later
 * @since      1.0.0
 */

namespace Kitces\Includes\Classes\User;

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
		$this->hooks();

		new Username();
		new AcSync();
		new LoggedInUser();
		new UserMeta();
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
		add_action( 'admin_enqueue_scripts', array( $this, 'scripts' ) );
	}

	/**
	 * Scripts
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function scripts( $hook ) {

		if ( $hook !== 'user-edit.php' ) {
			return;
		}

		$file        = 'src/js/acsync.js';
		$file_path   = KITCES_DIR_PATH . $file;
		$file_url    = KITCES_DIR_URL . $file;
		$file_exists = file_exists( $file_path );
		$file_time   = $file_exists ? filemtime( $file_path ) : '1.0.0';
		$handle      = 'acsync';

		wp_register_script( $handle, $file_url, array(), $file_time, true );
		wp_enqueue_script( $handle );
	}
}
