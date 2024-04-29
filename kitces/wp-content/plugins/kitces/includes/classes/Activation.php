<?php
/**
 * Activation.
 *
 * @package    Kitces
 * @subpackage Kitces/Inlcudes/Classes
 * @author     Objectiv
 * @copyright  Copyright (c) 2020, Objectiv
 * @license    GPL-2.0
 * @since      1.0.0
 */

namespace Kitces\Includes\Classes;

use Kitces\Includes\Classes\FavoritePosts\Table;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

/**
 * Activation.
 *
 * @author Objectiv
 * @since  1.0.0
 */
class Activation {

	/**
	 * Run
	 *
	 * @author Objectiv
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public static function run() {
		( new Table() )->maybe_create_table();
		flush_rewrite_rules();
	}
}
