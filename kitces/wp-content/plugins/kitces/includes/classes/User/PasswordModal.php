<?php
/**
 * Password Modal
 *
 * @package    Kitces
 * @subpackage Kitces/Includes/Classes/Utils
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
 * Password Modal
 *
 * @author Jason Witt
 * @since  1.0.0
 */
class PasswordModal {

	/**
	 * Initialize the class
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function __construct() {
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
		<div id="kitces-password-reset-modal-wrap" class="kitces-password-reset-modal__wrap kitces-cc-alert__wrap">
			<div id="kitces-password-reset-modal" class="kitces-password-reset-modal kitces-cc-alert" style="display: none">
				<br>Your Password Has Been Updated
			</div>
		</div>
		<?php
	}
}
