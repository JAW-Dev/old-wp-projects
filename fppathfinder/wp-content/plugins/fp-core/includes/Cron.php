<?php

namespace FP_Core;

class Cron {
	static public function init() {
		self::add_schedules();
	}

	static public function add_schedules() {
		add_filter( 'cron_schedules', __CLASS__ . '::add_ten_minutes_schedule' );
	}

	static public function add_ten_minutes_schedule( $schedules ) {
		$schedules['ten_minutes'] = array(
			'interval' => 600,
			'display'  => esc_html__( 'Every Ten Minutes' ),
		);

		return $schedules;
	}
}
