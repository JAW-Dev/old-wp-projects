<?php

namespace FP_Core\Group_Settings\Settings;

class EnableMemberEditBuisinessDisplayName extends Checkbox {
	public function __construct() {}

	public function get_label(): string {
		return 'Enable Member Edit Business Dispay Name';
	}

	public function add_hooks() {}
}
