<?php

namespace FP_Core\Group_Settings\Settings;

class Settings_Registrar {
	public function __construct() {}

	static public function init() {
		self::register_settings();
	}

	static public function get_settings() {
		return array(
			new GroupMembersDiscountCode(),
		);
	}

	static public function register_settings() {
		foreach ( self::get_settings() as $setting ) {
			self::register_setting( $setting );
		}
	}

	static public function register_setting( Setting $setting ) {
		$setting->add_hooks();
	}
}
