<?php
/**
 * Is Checklist
 *
 * @package    FpCore
 * @subpackage FpCore/Shortcodes
 * @author     Objectiv
 * @copyright  Copyright (c) 2020, Objectiv
 * @license    GPL-2.0
 * @version    1.0.0
 */

namespace FP_Core\Shortcodes;

use FP_Core\InteractiveLists\Utilities\Page;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

/**
 * Is Checklist
 *
 * @author Jason Witt
 * @since  1.0.0
 */
class IsChecklist {

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
		add_shortcode( 'fp_is_checklist', [ $this, 'render' ] );
	}

	/**
	 * Render
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function render( $atts, $content = null ) {
		if ( ! Page::is_shared_link() ) {
			return $content;
		}

		return '';
	}
}
