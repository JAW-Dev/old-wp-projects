<?php

namespace FP_Core\Downloads\Bundles\Generator;


class Bundle_Generator_Controller {

	static public function handle_bundle_generation_request( $args = [] ) {
		$defaults = [
			'post_id'         => 0,
			'bundle_dir'      => '',
			'first_run'       => 'false',
			'total_posts'     => 0,
			'remaining_posts' => [],
			'remaining_count' => 0,
			'pdfs'            => [],
			'temp_dir'        => '',
			'user_id'         => 0,
			'user_settings'   => [],
		];

		$data = wp_parse_args( $args, $defaults );

		if ( ! $data['post_id'] || ! self::is_allowed( $data['post_id'], $data['user_id'] ) ) {
			return 'Permission Denied';
			exit;
		}

		do_action( 'download_bundle_pre_generate', $data['post_id'], $data['user_id'] );

		$generator = new Generator();
		$generator->set_user_id( $data['user_id'] );
		$generator->set_bundle_post_id( $data['post_id'] );
		$generator->set_first_run( (bool) $data['first_run'] );
		$generator->generate( $data );

		return;
	}

	static private function is_allowed( int $post_id, int $user_id ) {
		$sample_download_ids = get_field( 'sample_download_ids', 'option' );
		$is_sample_download  = $sample_download_ids && in_array( $post_id, $sample_download_ids, true );

		return $is_sample_download || rcp_user_can_access( $user_id, $post_id ) || current_user_can( 'administrator' );
	}
}
