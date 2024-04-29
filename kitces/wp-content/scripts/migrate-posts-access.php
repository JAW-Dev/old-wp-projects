<?php

set_time_limit( 0 );
require '../../wp/wp-load.php';
require '../vendor/autoload.php';

/**
 * Migrate Posts
 *
 * @author Jason Witt
 * @since  1.0.0
 */
class MigratePosts {

	/**
	 * Initialize the class
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function __construct() {
		$this->init();
	}

	/**
	 * Init
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function init() {
		$this->update_posts();
	}

	/**
	 * Update Posts
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function update_posts() {
		$args = array(
			'posts_per_page' => -1,
			'post_type'      => array( 'post', 'page' ),
			'fields'         => 'ids',
		);

		$post_ids = get_posts( $args );

		foreach ( $post_ids as $post_id ) {
			$memb_levels = get_post_meta( $post_id, '_memberium_membership_levels', true );

			if ( empty( $memb_levels ) ) {
				continue;
			}

			$levels = explode( ',', $memb_levels );

			add_post_meta( $post_id, '_members_access_role', 'administrator' );

			foreach ( $levels as $level ) {

				if ( count( $levels ) === 1 && $level[0] === '0' ) {
					continue;
				}

				if ( $level === '5' ) {
					add_post_meta( $post_id, '_members_access_role', 'basic' );
				}

				if ( $level === '514' ) {
					add_post_meta( $post_id, '_members_access_role', 'student' );
				}

				if ( $level === '8' ) {
					add_post_meta( $post_id, '_members_access_role', 'premier' );
				}
			}
		}
	}
}

new MigratePosts();

