<?php

namespace FP_Core\Group_Settings\Settings;

class EnableMemberdEditColorScheme extends Checkbox {
	public function __construct() {}

	public function get_label(): string {
		return 'Enable Member Edit Color Scheme';
	}

	public function add_hooks() {}
}
