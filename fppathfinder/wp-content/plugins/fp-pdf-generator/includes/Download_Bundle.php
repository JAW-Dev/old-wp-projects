<?php

namespace FP_PDF_Generator;

class Download_Bundle {
	/**
	 * @var int
	 */
	private $post_id = null;

	/**
	 * @var int
	 */
	private $temporary_files_dir;

	/**
	 * Construct
	 *
	 * @param int $bundle_post_id The post id of the bundle.
	 */
	public function __construct( int $bundle_post_id ) {
		$this->post_id = $bundle_post_id;
	}


	/**
	 * Get URL
	 *
	 * Get a url that will download this bundle
	 *
	 * @return string the link to download this bundle
	 */
	public function get_url() {
		return "/wp-admin/admin-ajax.php?action=generate_bundle&id=$this->post_id";
	}

	/**
	 * Generate
	 *
	 * Generate a zip file and download it.
	 *
	 * @return void
	 */
	public function generate() {

		set_time_limit( 0 );

		$this->temporary_files_dir = $this->generate_temp_dir();
		$bundle_name               = sanitize_file_name( get_the_title( $this->post_id ) ) . '.zip';
		$full_bundle_path          = $this->temporary_files_dir . $bundle_name;
		$files                     = $this->write_temporary_files();
		$zip                       = new \ZipArchive();

		$zip->open( $full_bundle_path, \ZipArchive::CREATE );

		foreach ( $files as $file_array ) {
			$zip->addFile( $file_array['path'], $file_array['name'] );
		}

		$zip->close();

		$this->download_zip( $full_bundle_path, $bundle_name );
		$this->clean_up_temporary_files();
		exit;
	}

	/**
	 * Download Zip
	 *
	 * Send the headers and the file contents to the browser.
	 */
	private function download_zip( string $full_path, string $filename ) {
		if ( file_exists( $full_path ) ) {
			header( 'Content-type: application/zip' );
			header( "Content-Disposition: attachment; filename=$filename" );
			header( 'Content-length: ' . filesize( $full_path ) );
			header( 'Pragma: no-cache' );
			header( 'Expires: 0' );
			readfile( $full_path );
		}
	}

	/**
	 * Clean Up Temporary Files
	 *
	 * Delete any temporary files used by this instance or left over from any previous instances.
	 *
	 * @return void
	 */
	private function clean_up_temporary_files() {
		$file_system = new \Symfony\Component\Filesystem\Filesystem();

		// cleanup this bundle's files
		$file_system->remove( $this->temporary_files_dir );

		// cleanup any old files
		$finder = new \Symfony\Component\Finder\Finder();
		$finder->directories();
		$finder->in( $this->get_temp_dir_parent() )->date( 'before yesterday' );

		$file_system->remove( $finder );
	}

	/**
	 * Write Temporary Files
	 *
	 * Create the temporary files to be used in this bundle.
	 *
	 * @return array array or arrays with keys 'name' and 'path'.
	 */
	private function write_temporary_files() {
		return array_map( array( $this, 'write_temporary_file' ), $this->get_download_posts() );
	}

	/**
	 * Write Temporary File
	 *
	 * Given a \WP_Post get the download info and write it to a file.
	 */
	private function write_temporary_file( $download_post ) {
		if ( empty( $download_post ) ) {
			return;
		}

		$download = new Download( $download_post->ID );
		$name     = $download->get_filename();
		$path     = $download->write_file( $this->temporary_files_dir );

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
		$field_array = get_field( 'bundle_downloads', $this->post_id );
		$posts       = array_map(
			function( array $array ) {
				return $array['download'];
			},
			$field_array
		);
		return $posts;
	}

	/**
	 * Generate Temp Dir
	 *
	 * Create and return the temporary directory for this bundle to use.
	 *
	 * @return string path to new directory
	 */
	private function generate_temp_dir() {
		$path = $this->get_temp_dir_parent() . random_int( 1, 999999999 ) . '/';

		if ( wp_mkdir_p( $path ) ) {
			return $path;
		} else {
			throw new \Exception( "Couldn't generate temporary directory for pdf bundle" );
		}
	}

	/**
	 * Get Temp Dir Parent
	 *
	 * Get the parent directory for the temporary directory to be created in.
	 *
	 * @return string path to the parent directory
	 */
	private function get_temp_dir_parent() {
		return wp_get_upload_dir()['basedir'] . '/pdf-bundles-temporary/';
	}
}
