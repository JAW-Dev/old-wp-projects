<?php

namespace FP_Core;

/**
 * Checklist Controller
 */
class ChecklistController {
	public function __construct() {
		add_filter( 'checklist_tooltip', array( $this, 'make_all_links_open_in_new_window' ), 10, 3 );
	}

	public function make_all_links_open_in_new_window( $tooltip_text ) {
		$sans_targets = preg_replace( '/\<a([^>]*)target\=[\"\'][^\"\'\>]*[\"\']/', '<a$1', $tooltip_text );
		return preg_replace( '/\<a/', '<a target="_blank" ', $sans_targets );
	}
}
