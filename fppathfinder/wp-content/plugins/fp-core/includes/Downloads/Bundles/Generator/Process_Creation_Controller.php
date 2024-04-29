<?php

namespace FP_Core\Downloads\Bundles\Generator;


class Process_Creation_Controller {
	static public function handle_process_creation_request( int $bundle_post_id, int $user_id ) {
		if ( ! $bundle_post_id || ! self::is_allowed( $bundle_post_id, $user_id ) ) {
			return 'Permission Denied';
			exit;
		}

		$process    = Process::create( $user_id, $bundle_post_id );
		$process_id = $process->get_id();

		if ( ! $process_id ) {
			return 'process could not be retrieved';
		}

		return json_encode(
			array(
				'process_id' => $process->get_id(),
				'bundle_id'  => $bundle_post_id,
				'percent'    => $process->get_percent(),
				'file_url'   => null,
				'nonce'      => wp_create_nonce(),
			)
		);
	}

	static private function is_allowed( int $post_id, int $user_id ) {
		$sample_download_ids = get_field( 'sample_download_ids', 'option' );
		$is_sample_download  = $sample_download_ids && in_array( $post_id, $sample_download_ids );

		return $is_sample_download || rcp_user_can_access( $user_id, $post_id ) || current_user_can( 'administrator' );
	}
}
