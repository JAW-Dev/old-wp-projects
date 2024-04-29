<?php

namespace FP_Core\Downloads\Bundles\Generator;

use FP_PDF_Generator\Download;

class Generator {
	protected $bundle_post_id;
	protected $directory;
	protected $process;
	protected $bundle_dir_name;
	protected $temp_dir;

	public function __construct() {}

	static public function create( int $user_id, int $bundle_post_id ) {
		$generator = new self();
		$generator->set_user_id( $user_id );
		$generator->set_bundle_post_id( $bundle_post_id );

		return $generator;
	}

	public function set_user_id( int $user_id ) {
		$this->user_id = $user_id;
	}

	public function get_user_id() {
		return $this->user_id;
	}

	public function set_bundle_post_id( int $bundle_post_id ) {
		$this->bundle_post_id = $bundle_post_id;
	}

	public function get_bundle_post_id() {
		return $this->bundle_post_id;
	}

	public function set_first_run( bool $first_run ) {
		$this->first_run = $first_run;
	}

	public function get_first_run() {
		return $this->first_run;
	}

	public function set_process( Process $process ) {
		$this->process = $process;
	}

	public function get_process() {
		return $this->process;
	}

	/**
	 * Get Temp Dir Parent
	 *
	 * Get the parent directory for the temporary directory to be created in.
	 *
	 * @return string path to the parent directory
	 */
	private function get_temp_dir_parent( $temp_dir ) {
		return wp_get_upload_dir()['basedir'] . '/pdf-bundles-temporary/' . $temp_dir;
	}

	/**
	 * Generate Temp Dir
	 *
	 * Create and return the temporary directory for this bundle to use.
	 *
	 * @return string path to new directory
	 */
	private function create_directory( $temp_dir ) {
		$path = $this->get_temp_dir_parent( $temp_dir ) . $this->bundle_dir_name . '/';
		$dir  =  wp_mkdir_p( $path );

		if ( $dir ) {
			return $path;
		} else {
			throw new \Exception( "Couldn't generate temporary directory for pdf bundle" );
		}
	}

	/**
	 * Generate
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function generate( $args = [] ) {
		$defaults = [
			'post_id'         => 0,
			'bundle_dir'      => '',
			'first_run'       => 'false',
			'total_posts'     => 0,
			'all_posts'       => [],
			'remaining_posts' => [],
			'remaining_count' => 0,
			'pdfs'            => [],
			'temp_dir'        => '',
			'user_id'         => 0,
			'user_settings'   => [],
		];

		$data = wp_parse_args( $args, $defaults );

		$temp_dir = isset( $data['temp_dir'] ) ? $data['temp_dir'] : '';

		if ( empty( $data['temp_dir'] ) ) {
			$temp_dir = random_int( 1, 999999999 );
		}

		$bundle_dir = isset( $data['bundle_dir'] ) ? $data['bundle_dir'] : '';

		if ( empty( $data['bundle_dir'] ) ) {
			$bundle_dir = $this->create_directory( $temp_dir );
		}

		if ( empty( $bundle_dir ) ) {
			wp_die();
		}

		$this->directory  = $bundle_dir;
		$bundle_name      = sanitize_file_name( get_the_title( $this->bundle_post_id ) ) . '.zip';
		$full_bundle_path = $this->directory . $bundle_name;
		$files            = $data['remaining_posts'];
		$pdfs             = $data['pdfs'];
		$total_posts      = $data['total_posts'];
		$remaining_count  = $data['remaining_count'];
		$all_posts        = $data['all_posts'];

		if ( $data['first_run'] === 'true' ) {
			$posts       = $this->get_download_posts();
			$total_posts = $data['total_posts'] <= 0 ? count( $posts ) : $data['total_posts'];

			if ( ! empty( $posts ) ) {
				foreach ( $posts as $post ) {
					$files[] = $post->ID;
				}
			}
		}

		if ( ! empty( $files ) ) {
			$pdfs[] = $this->write_temporary_file_test( (int) $files[0], $data['user_id'], $data['user_settings'] );
			unset( $files[0] );

			$remaining_count = count( $files );
		}

		array_values( $files );

		if ( $remaining_count === 0 ) {
			$zip = new \ZipArchive();

			$zip->open( $full_bundle_path, \ZipArchive::CREATE );

			foreach ( $pdfs as $file_array ) {
				if ( ! empty( $file_array['path'] ) && ! empty( $file_array['name'] ) ) {
					$zip->addFile( $file_array['path'], $file_array['name'] );
				}
			}

			$zip->close();

			$this->clean_up_temporary_files( $temp_dir );
			$this->maybe_log_download();

			$return = [
				'complete'        => true,
				'bundle_name'     => $bundle_name,
				'bundle_dir'      => $this->directory,
				'zip'             => $full_bundle_path,
				'zip_file'        => home_url( 'wp-content/uploads/pdf-bundles-temporary/' . $temp_dir . '/' . $bundle_name ),
				'total_posts'     => $total_posts,
				'remaining_posts' => array_values( $files ),
				'remaining_count' => $remaining_count,
				'pdfs'            => $pdfs,
				'first_run'       => 'false',
				'temp_dir'        => $temp_dir,
				'all_posts'       => $all_posts,
			];

			echo wp_json_encode( $return );
			wp_die();
		}

		if ( ! $remaining_count || ! $total_posts ) {
			wp_die();
		}

		$return = [
			'complete'        => false,
			'bundle_name'     => $bundle_name,
			'bundle_dir'      => $this->directory,
			'zip'             => $full_bundle_path,
			'total_posts'     => $total_posts,
			'remaining_posts' => array_values( $files ),
			'remaining_count' => $remaining_count,
			'pdfs'            => $pdfs,
			'first_run'       => 'false',
			'temp_dir'        => $temp_dir,
			'all_posts'       => $all_posts,
		];

		echo wp_json_encode( $return );
		wp_die();
	}

	/**
	 * Maybe Log Download
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function maybe_log_download() {
		$bundle_id = $this->bundle_post_id;
		$log       = get_post_meta( $bundle_id, 'times_downloaded', true ) ? get_post_meta( $bundle_id, 'times_downloaded', true ) : array();

		$new_download = ! empty( $log ) ? $log + 1 : 1;

		update_post_meta( $bundle_id, 'times_downloaded', $new_download );
	}

	/**
	 * Clean Up Temporary Files
	 *
	 * Delete any temporary files used by this instance or left over from any previous instances.
	 *
	 * @return void
	 */
	private function clean_up_temporary_files( $temp_dir ) {
		$file_system = new \Symfony\Component\Filesystem\Filesystem();
		$finder      = new \Symfony\Component\Finder\Finder();

		$finder->directories();
		$finder->in( $this->get_temp_dir_parent( $temp_dir ) )->date( 'before yesterday' );

		$file_system->remove( $finder );
	}

	/**
	 * Write Temporary File
	 *
	 * Given a \WP_Post get the download info and write it to a file.
	 */
	private function write_temporary_file( \WP_Post $download_post ) {
		$download = new Download( $download_post->ID );
		$name     = $download->get_filename();
		$path     = $download->write_file( $this->directory );

		return array(
			'name' => $name,
			'path' => $path,
		);
	}

	private function write_temporary_file_test( $download_post, $user_id, $user_settings ) {
		$download = new Download( $download_post, $user_id, true, $user_settings );
		$name     = $download->get_filename();
		$path     = $download->write_file( $this->directory, $user_settings );

		return array(
			'name' => $name,
			'path' => $path,
		);
	}

	/**
	 * Get Download Posts
	 *
	 * Get the \WP_Posts for the downloads associated with this bundle.
	 *
	 * @return array of \WP_Post
	 */
	private function get_download_posts() {
		$bundle_type = get_field( 'bundle_type', $this->bundle_post_id );
		$posts       = [];

		if ( empty( $bundle_type ) ) {
			return $posts;
		}

		if ( 'categories' === $bundle_type ) {
			$posts = $this->get_categories_bundle();
		} else {
			$posts = $this->get_manual_bundle();
		}

		return $posts;
	}

	/**
	 * Get Manual Bundle
	 *
	 * Get the \WP_Posts from the manual select.
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return array
	 */
	public function get_manual_bundle(): array {
		$field_array = get_field( 'bundle_downloads', $this->bundle_post_id );
		$posts       = [];

		foreach ( $field_array as $array ) {
			$posts[] = $array['download'];
		}

		return $posts;
	}

	/**
	 * Get Categories Bundle
	 *
	 * Get the \WP_Posts from an array of categories.
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return array
	 */
	public function get_categories_bundle() {
		$bundle_categories = get_field( 'bundle_categories', $this->bundle_post_id );
		$posts             = [];

		if ( ! empty( $bundle_categories ) ) {
			$args              = array(
				'post_type'      => 'download',
				'posts_per_page' => -1,
				'tax_query'      => array(
					array(
						'taxonomy' => 'download-cat',
						'field'    => 'term_id',
						'terms'    => $bundle_categories,
					),
				),
			);

			$query = new \WP_Query( $args );
			$posts = $query->posts;
		}

		return $posts;
	}
}
