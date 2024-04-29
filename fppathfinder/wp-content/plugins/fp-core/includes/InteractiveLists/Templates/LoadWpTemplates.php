<?php
/**
 * Load WP Templates
 *
 * @package    FP_Core/
 * @subpackage FP_Core/Templates
 * @author     Objectiv
 * @copyright  Copyright (c) 2020, Objectiv
 * @license    GPL-2.0
 * @version    1.0.0
 */

namespace FP_Core\InteractiveLists\Templates;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

/**
 * Load WP Templates
 *
 * @author Jason Witt
 * @since  1.0.0
 */
class LoadWpTemplates {

	/**
	 * Singles Files
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @var array
	 */
	protected $singles_files = array(
		array(
			'file'      => 'single-checklist.php',
			'dir'       => 'Checklist',
			'post_type' => 'checklist',
		),
		array(
			'file'      => 'single-flowchart.php',
			'dir'       => 'Flowchart',
			'post_type' => 'flowchart',
		),
	);

	/**
	 * Initialize the class
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function __construct() {
		$this->hooks();
	}

	/**
	 * Hooks
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function hooks() {
		add_filter( 'single_template', array( $this, 'singles' ) );
	}

	/**
	 * Singles
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param string $template The template file name.
	 *
	 * @return void
	 */
	public function singles( $template ) {
		global $post;

		foreach ( $this->singles_files as $singles_file ) {
			if ( $singles_file['post_type'] === $post->post_type && locate_template( $singles_file['file'] ) !== $template ) {
				return FP_CORE_DIR_PATH . 'includes/InteractiveLists/Templates/' . $singles_file['dir'] . '/' . $singles_file['file'];
			}
		}

		return $template;
	}
}
