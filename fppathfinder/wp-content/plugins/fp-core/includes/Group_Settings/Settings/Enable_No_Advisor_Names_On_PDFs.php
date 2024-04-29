<?php

namespace FP_Core\Group_Settings\Settings;

class Enable_No_Advisor_Names_On_PDFs extends Checkbox {
	public function __construct() {}

	public function get_label(): string {
		return 'Enable No Advisor Names On PDFs Setting';
	}

	public function add_hooks() {}
}
