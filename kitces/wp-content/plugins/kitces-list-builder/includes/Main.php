<?php

namespace MKLB;

use MKLB\ListPostType;
use MKLB\ListItemPostTypeManager;
use MKLB\ListTemplateManager;

class Main {

	public function __construct() {

		// Since we're relying on ACF lets make sure its installed before we do anything.
		if ( function_exists( 'get_field' ) ) {

			require MK_LIST_PLUGIN_DIR . '/includes/ListACFFields.php';

			// Load up the helpers that I'll use numerous places
			require MK_LIST_PLUGIN_DIR . '/includes/Helpers.php';

			new ListPostType();
			new ListItemPostTypeManager();
			new ListTemplateManager();
		}

	}
}
