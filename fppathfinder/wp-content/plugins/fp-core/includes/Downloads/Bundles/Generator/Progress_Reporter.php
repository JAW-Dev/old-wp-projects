<?php

namespace FP_Core\Downloads\Bundles\Generator;

class Progress_Reporter {
	static public function get_progress( int $process_id, int $user_id ): string {
		$process = Process::get( $process_id );

		if ( ! $process ) {
			return 'false';
		}

		if ( $user_id !== $process->get_user_id() ) {
			return 'Unauthorized';
		}

		$id       = $process->get_id();
		$percent  = $process->get_percent();
		$file_url = $process->get_file_url();

		$response = array(
			'process_id' => $id,
			'percent'    => $percent,
			'file_url'   => $file_url,
			'nonce'      => wp_create_nonce(),
		);

		return json_encode( $response );
	}
}
