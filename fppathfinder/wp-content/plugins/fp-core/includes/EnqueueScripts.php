<?php
/**
 * Enqueue Scripts
 *
 * @package    FP_Core/
 * @subpackage FP_Core/Includes/EnqueueScripts
 * @author     Objectiv
 * @copyright  Copyright (c) 2020, Objectiv
 * @license    GPL-2.0
 * @version    1.0.0
 */

namespace FP_Core;

use FP_Core\Utilities\Features\Feature;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

/**
 * Enqueue Scripts
 *
 * @author Jason Witt
 * @since  1.0.0
 */
class EnqueueScripts {

	/**
	 * File Path
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @var string
	 */
	protected $file_path;

	/**
	 * Handle
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @var string
	 */
	protected $handle;

	/**
	 * Localized
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @var array
	 */
	protected $localized;

	/**
	 * Localized Handle
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @var string
	 */
	protected $localized_handle;

	/**
	 * Initialize the class
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function __construct() {}

	/**
	 * FP Enqueqe Scripts
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param array $args The arguments.
	 *
	 * @return void
	 */
	public function fp_enqueue_scripts( array $args = [] ) {
		if ( empty( $args ) ) {
			return;
		}

		$defaults = [
			'filePath'         => '',
			'handle'           => '',
			'localized'        => [],
			'localized_handle' => '',
		];

		$args = wp_parse_args( $args, $defaults );

		if ( ! empty( $args['localized_handle'] ) ) {
			$args['localized']['ajaxUrl'] = admin_url( 'admin-ajax.php' );
		}

		$this->file_path        = $args['filePath'];
		$this->handle           = $args['handle'];
		$this->localized        = $args['localized'];
		$this->localized_handle = $args['localized_handle'];

		add_action( 'wp_enqueue_scripts', [ $this, 'scripts' ] );
	}

	/**
	 * Scripts
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function scripts() {
		$file_dir = FP_CORE_DIR_PATH . $this->file_path;
		$file_uri = FP_CORE_DIR_URL . $this->file_path;
		$version  = file_exists( $file_dir ) ? filemtime( $file_dir ) : '1.0.0';

		wp_register_script( $this->handle, $file_uri, array(), $version, true );
		wp_enqueue_script( $this->handle );

		wp_localize_script(
			$this->handle,
			$this->localized_handle,
			$this->localized
		);
	}
}
