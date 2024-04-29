<?php

namespace FP_Core;

register_activation_hook( dirname( __FILE__ ) . '/fp-core.php', 'fp_core_activate_crons' );

function fp_core_activate_crons() {
	if ( ! wp_next_scheduled( 'active_campaign_integration_process_contact_updates' ) ) {
		wp_schedule_event( current_time( 'timestamp' ), 'ten_minutes', 'active_campaign_integration_process_contact_updates' ); // ten_minutes interval is setup in Cron class
	}
}

register_deactivation_hook( dirname( __FILE__ ) . '/fp-core.php', 'fp_core_deactivate_crons' );

function fp_core_deactivate_crons() {
	wp_clear_scheduled_hook( 'active_campaign_integration_process_contact_updates' );
}
