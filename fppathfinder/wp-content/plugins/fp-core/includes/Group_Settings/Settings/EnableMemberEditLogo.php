<?php

namespace FP_Core\Group_Settings\Settings;

class EnableMemberEditLogo extends Checkbox {
	public function __construct() {}

	public function get_label(): string {
		return 'Enable Member Edit Logo';
	}

	public function add_hooks() {}
}
