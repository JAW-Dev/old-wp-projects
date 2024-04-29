<?php

namespace FP_Core\Integrations\ProfitWell;

use FP_Core\Integrations\ProfitWell\Logger;

class ProfitWell_Integration {
	static public function init() {
		if ( ! self::should_init() ) {
			return;
		}

		Controller::add_hooks();
		( new Logger() )->maybe_create_table();
	}

	static private function should_init(): bool {
		return (bool) ProfitWell_API::get_api_key();
	}

	static public function is_dev_mode(): bool {
		return defined( 'OBJECTIV_DEV_SITE' ) && OBJECTIV_DEV_SITE;
	}
}
