<?php

namespace MKLB;

class ListTemplateManager {

	public function __construct() {
		add_filter( 'template_include', array( $this, 'singleListPost' ) );
	}

	public function singleListPost( $template ) {
		$post_type = get_post_type();

		// Return the template in the template folder.
		if ( 'list-' === substr( $post_type, 0, 5 ) ) {
			if ( is_singular() ) {
				return MK_LIST_PLUGIN_DIR . 'includes/Templates/list-single.php';
			} elseif ( is_archive() ) {
				return MK_LIST_PLUGIN_DIR . 'includes/Templates/list-archive.php';
			}
		}

		return $template;
	}
}
