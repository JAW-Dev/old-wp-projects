<?php

namespace FP_Core\Integrations\Active_Campaign;

class Cron_Contact_Updater {
	public function __construct() {}

	static public function init() {
		add_action( self::get_action_hook(), self::get_action_callback_name() );
	}

	static public function get_action_hook(): string {
		return 'active_campaign_integration_process_contact_updates';
	}

	static public function get_action_callback_name(): string {
		return __CLASS__ . '::process_updates';
	}

	static public function process_updates() {
		$settings = get_option( 'rcp_activecampaign_settings' );

		if ( empty( $settings ) || empty( $settings['api_url'] ) || empty( $settings['api_key'] ) ) {
			return false;
		}

		$active_campaign = new \ActiveCampaign( $settings['api_url'], $settings['api_key'] );

		$all_updates = Cron_Contact_Update_Queue::get_updates();

		foreach ( self::map_updates_to_emails( $all_updates ) as $email => $updates ) {
			set_time_limit( 0 );
			self::update_contact( $email, $updates, $active_campaign );
			sleep( 1 );
		}
	}

	static public function map_updates_to_emails( array $updates ): array {
		$map = array();

		foreach ( $updates as $update ) {
			$map[ $update->email ][] = $update;
		}

		return $map;
	}

	static public function update_contact( string $email, array $updates, \ActiveCampaign $active_campaign ) {
		$unique_updates = self::deduplicate_updates( $updates );

		$data = array( 'email' => $email );

		foreach ( $unique_updates as $update ) {
			$data[ $update->field_key ] = $update->field_value;
		}

		if ( defined( 'OBJECTIV_DEV_SITE' ) && OBJECTIV_DEV_SITE ) {
			return;
		}

		if ( empty( $data['p[1]'] ) ) {
			$data['p[1]'] = '1';
		}

		$result = $active_campaign->api( 'contact/sync', $data );

		if ( 1 !== $result->result_code ) {
			return;
		}

		foreach ( $unique_updates as $update ) {
			Cron_Contact_Update_Queue::dequeue_update( $update->id );
		}
	}

	static public function deduplicate_updates( array $updates ): array {
		$latest_unique_updates = array();

		foreach ( $updates as $update ) {
			if ( ! empty( $latest_unique_updates[ $update->field_key ] ) ) {
				Cron_Contact_Update_Queue::dequeue_update( $latest_unique_updates[ $update->field_key ]->id );
			}

			$latest_unique_updates[ $update->field_key ] = $update;
		}

		return $latest_unique_updates;
	}
}
