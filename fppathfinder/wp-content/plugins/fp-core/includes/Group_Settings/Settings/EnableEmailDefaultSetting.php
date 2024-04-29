<?php

namespace FP_Core\Group_Settings\Settings;

class EnableEmailDefaultSetting extends Checkbox {
	public function __construct() {}

	public function get_label(): string {
		return 'Enable Email Default Setting';
	}

	public function add_hooks() {}
}
