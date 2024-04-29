<?php

namespace FP_Interactive_Checklists;

/**
 * Utility
 *
 * Utility functionality customizations
 */
class Utility {

	const CHECKLIST_FORM_ACTION = 'checklist_form';

	public function __construct() {}

	static function get_checklist_form_action_attribute() {
		return '/wp-admin/admin-ajax.php?action=' . self::CHECKLIST_FORM_ACTION;
	}
}
