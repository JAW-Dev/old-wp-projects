<?php

namespace FP_Core;

class Constants {
	/**
	 * Initialize the class
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function __construct() {
		$this->init();
	}

	/**
	 * Init
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public static function init() {
		define( 'FP_ESSENTIALS_ID', '1' );
		define( 'FP_DELUXE_ID', '2' );
		define( 'FP_FIRM_WIDE_DELUXE_ID', '3' );
		define( 'FP_ENTERPRISE_ESSENTIALS_ID', '5' );
		define( 'FP_ENTERPRISE_DELUXE_ID', '4' );
		define( 'FP_PREMIER_ID', '6' );
		define( 'FP_FIRM_WIDE_PREMIER_ID', '7' );
		define( 'FP_ENTERPRISE_PREMIER_ID', '8' );
		define( 'FP_ESSENTIALS_WITH_TRIAL_ID', '9' );
		define( 'FP_CORE_DIR_URL', trailingslashit( plugin_dir_url( dirname( __FILE__ ) ) ) );
		define( 'FP_CORE_DIR_PATH', trailingslashit( plugin_dir_path( dirname( __FILE__ ) ) ) );
		define( 'FP_SALSFORCE_DEV_KEY', $_ENV['SALSFORCE_DEV_KEY'] );
		define( 'FP_SLASEFORCE_DEV_SECRET', $_ENV['SLASEFORCE_DEV_SECRET'] );
		define( 'FP_SALSFORCE_PROD_KEY', $_ENV['SALSFORCE_PROD_KEY'] );
		define( 'FP_SALESFORCE_PROD_SECRET', $_ENV['SLASEFORCE_PROD_SECRET'] );

		if ( ! defined( 'FP_CORE_PREFIX' ) ) {
			define( 'FP_CORE_PREFIX', 'fp_core' );
		}
	}
}
