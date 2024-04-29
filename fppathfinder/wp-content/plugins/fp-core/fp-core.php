<?php
/**
 *
 * Plugin Name: fpPathfinder Core
 * Description: Enables custom functionality and helper functions accross the site.
 * Version:     1.0.0
 * Author:      Objectiv
 * Author URI:  https://objectiv.co/
 * License:     GPLv2
 * Domain Path: /languages
 *
 * @package    FP_Core
 * @author     Objectiv
 * @copyright  Copyright (c) 2019, Objectiv
 * @license    GNU General Public License v2 or later
 * @version    1.0.0
 **/

namespace FP_Core;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

require dirname( __FILE__ ) . '/vendor/autoload.php';

// Check if unit tests are running and load appropriate Dotenv.
if ( defined( 'PHPUNIT_COMPOSER_INSTALL' ) ) {
	$dotenv = ( new \tad\WPBrowser\Polyfills\Dotenv\Dotenv( __DIR__ ) )->load();
} else {
	$dotenv = \Dotenv\Dotenv::createImmutable( __DIR__ )->load();
}

( new Main() )->init();

function add_ten_minutes_schedule( $schedules ) {
	$schedules['ten_minutes'] = array(
		'interval' => 600,
		'display'  => esc_html__( 'Every Ten Minutes' ),
	);

	return $schedules;
}

add_filter( 'cron_schedules', __NAMESPACE__ . '\add_ten_minutes_schedule' );

register_activation_hook( __FILE__, __NAMESPACE__ . '\fp_core_activate_crons' );

function fp_core_activate_crons() {
	if ( ! wp_next_scheduled( 'active_campaign_integration_process_contact_updates' ) ) {
		// ten_minutes interval is setup in Cron class.
		wp_schedule_event( current_time( 'timestamp' ), 'ten_minutes', 'active_campaign_integration_process_contact_updates' );  // phpcs:ignores
	}

	if ( ! wp_next_scheduled( 'resource_links_update' ) ) {
		wp_schedule_event( time(), 'daily', 'resource_links_update' );
	}

	if ( ! wp_next_scheduled( 'resource_links_mid_update' ) ) {
		wp_schedule_event( time(), 'daily', 'resource_links_mid_update' );
	}
}

register_activation_hook( __FILE__, __NAMESPACE__ . '\setup_tables' );

function setup_tables() {
	InteractiveLists\Tables\LinkShare::setup_table();
	Downloads\Bundles\Generator\Process_Datastore::setup_table();
}

register_deactivation_hook( __FILE__, __NAMESPACE__ . '\fp_core_deactivate_crons' );

function fp_core_deactivate_crons() {
	wp_clear_scheduled_hook( 'active_campaign_integration_process_contact_updates' );
	wp_clear_scheduled_hook( 'resource_links_update' );
	wp_clear_scheduled_hook( 'resource_links_mid_update' );
}
