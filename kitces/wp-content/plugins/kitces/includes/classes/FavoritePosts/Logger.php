<?php
/**
 * Logger.
 *
 * @package    Kitces
 * @subpackage Kitces/Includes/Classes/FavoritePosts
 * @author     Objectiv
 * @copyright  Copyright (c) 2020, Objectiv
 * @license    GNU General Public License v2 or later
 * @since      1.0.0
 */

namespace Kitces\Includes\Classes\FavoritePosts;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

/**
 * Logger.
 *
 * @author Objectiv
 * @since  1.0.0
 */
class Logger {

	/**
	 * Table Name
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @var string
	 */
	protected $table_name = 'mk_favorite_posts';

	/**
	 * Initialize the class
	 *
	 * @author Objectiv
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function __construct() {}

	/**
	 * Log
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param int    $user_id  The user ID.
	 * @param int    $post_id  The post ID.
	 * @param string $action   The action.
	 * @param string $category The category.
	 * @param string $label    The post title.
	 *
	 * @return void
	 */
	public function log( $user_id, $post_id, $action, $category, $label ) {
		( new Table() )->insert( $user_id, $post_id, $action, $category, $label );
	}

	/**
	 * log Access
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function log_access() {
		$action  = ! empty( $_GET['action-log'] ) ? sanitize_text_field( wp_unslash( $_GET['action-log'] ) ) : 'false';
		$post_id = ! empty( $_GET['post-id'] ) ? sanitize_text_field( wp_unslash( $_GET['post-id'] ) ) : 'false';
		$user_id = get_current_user_id();

		if ( $action === 'false' || $post_id === 'false' || ! $user_id ) {
			return;
		}

		( new Table() )->insert( $user_id, $post_id, 'Click', 'Saved Article Access', get_the_title( $post_id ) );
	}
}
