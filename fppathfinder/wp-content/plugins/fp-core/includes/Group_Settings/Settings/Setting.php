<?php

namespace FP_Core\Group_Settings\Settings;
use \FP_Core\Group_Settings\Database;

abstract class Setting {
	public function __construct() {}

	abstract public function output_field( int $group_id );
	abstract public function handle_submit( int $group_id );
	abstract public function add_hooks();

	public function get_name() {
		$class_and_namespace = get_class( $this );
		$exploded            = explode( '\\', $class_and_namespace );
		$class               = end( $exploded );

		return $class;
	}

	public function set( int $group_id, string $value ) {
		Database::set_group_setting( $group_id, $this->get_name(), $value );
	}

	public function get( int $group_id ): string {
		return Database::get_group_setting( $group_id, $this->get_name() );
	}
}
