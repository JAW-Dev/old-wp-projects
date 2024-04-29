<?php
/**
 * Checklist Notification
 *
 * @package    FP_Core/
 * @subpackage FP_Core/Utilities/Checklist
 * @author     Objectiv
 * @copyright  Copyright (c) 2020, Objectiv
 * @license    GPL-2.0
 * @version    1.0.0
 */

namespace FP_Core\Utilities\Checklist;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

/**
 * Checklist Notification
 *
 * @author Jason Witt
 * @since  1.0.0
 */
class ChecklistNotification {
	/**
	 * Initialize the class
	 *
	 * @author John Geesey | Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function __construct() {}

	/**
	 * Add Notification
	 *
	 * @author John Geesey | Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	static public function add( string $message, bool $is_error = false ) {
		$add_notification = function() use ( $message, $is_error ) {
			?>
			<div class="interactive-resource-notification <?php echo $is_error ? 'error' : ''; ?>"><?php echo $message; ?></div>
			<?php
		};

		add_action( 'interactive_resource_notification', $add_notification );
	}
}
