<?php

namespace FP_Core;

class InteractiveChecklistsNotification {
	public function __construct() {}

	static public function add( string $message, bool $is_error = false ) {
		$add_notification = function() use ( $message, $is_error ) {
			?>
			<div class="interactive-resource-notification <?php echo $is_error ? 'error' : ''; ?>"><?php echo $message; ?></div>
			<?php
		};

		add_action( 'interactive_resource_notification', $add_notification );
	}
}
