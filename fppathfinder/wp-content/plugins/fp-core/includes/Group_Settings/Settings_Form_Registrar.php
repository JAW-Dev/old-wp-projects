<?php

namespace FP_Core\Group_Settings;

class Settings_Form_Registrar {
	static public function get_settings_forms(): array {
		return array(
			new Admin_Group_Settings(),
			new Front_End_Group_Settings(),
		);
	}

	static public function init() {
		foreach ( self::get_settings_forms() as $settings_form ) {
			self::register_settings_form( $settings_form );
		}
	}

	static public function register_settings_form( Settings_Form $form ) {
		$form->init();
	}
}
