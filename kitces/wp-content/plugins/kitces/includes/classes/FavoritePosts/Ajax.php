<?php
/**
 * Ajax.
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
 * Ajax.
 *
 * @author Objectiv
 * @since  1.0.0
 */
class Ajax {

	/**
	 * Initialize the class
	 *
	 * @author Objectiv
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
		add_action( 'wp_ajax_save_favorite_post', array( $this, 'save' ) );
		add_action( 'wp_ajax_nopriv_save_favorite_post', array( $this, 'save' ) );
		add_action( 'wp_ajax_remove_favorite_post', array( $this, 'remove' ) );
		add_action( 'wp_ajax_nopriv_remove_favorite_post', array( $this, 'remove' ) );
	}

	/**
	 * Save
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function save() {
		$request = ! empty( $_REQUEST ) ? $_REQUEST : array();

		if ( empty( $request ) ) {
			wp_die();
		}

		$nonce   = ! empty( $request['nonce'] ) ? sanitize_text_field( wp_unslash( $request['nonce'] ) ) : '';
		$post_id = ! empty( $request['post'] ) ? (int) sanitize_text_field( wp_unslash( $request['post'] ) ) : '';
		$user_id = ! empty( $request['user'] ) ? (int) sanitize_text_field( wp_unslash( $request['user'] ) ) : '';

		if ( ! wp_verify_nonce( $nonce, 'favorite-posts' ) ) {
			wp_die();
		}

		$favorite_posts = get_user_meta( $user_id, 'favorite_posts', true );

		if ( empty( $favorite_posts ) ) {
			$favorite_posts = array();
		}

		if ( in_array( $post_id, $favorite_posts, true ) ) {
			wp_die();
		}

		$favorite_posts[] = array(
			'post_id'    => $post_id,
			'category'   => 'Article Saved',
			'action'     => 'Save',
			'label'      => get_the_title( $post_id ),
			'date_saved' => gmdate( 'Y-m-d H:i:s' ),
		);

		update_user_meta( $user_id, 'favorite_posts', $favorite_posts );

		( new Logger() )->log( $user_id, $post_id, 'Save', 'Actricle Save', get_the_title( $post_id ) );

		echo 'success';
		wp_die();
	}

	/**
	 * Save
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function remove() {
		$request = ! empty( $_REQUEST ) ? $_REQUEST : array();

		if ( empty( $request ) ) {
			wp_die();
		}

		$nonce   = ! empty( $request['nonce'] ) ? sanitize_text_field( wp_unslash( $request['nonce'] ) ) : '';
		$post_id = ! empty( $request['post'] ) ? sanitize_text_field( wp_unslash( $request['post'] ) ) : '';
		$user_id = ! empty( $request['user'] ) ? sanitize_text_field( wp_unslash( $request['user'] ) ) : '';

		if ( ! wp_verify_nonce( $nonce, 'favorite-posts' ) ) {
			wp_die();
		}

		$favorite_posts = get_user_meta( $user_id, 'favorite_posts', true );

		if ( empty( $favorite_posts ) ) {
			wp_die();
		}

		foreach ( $favorite_posts as $key => $value ) {
			if ( (int) $value['post_id'] === (int) $post_id ) {
				unset( $favorite_posts[ $key ] );
			}
		}

		update_user_meta( $user_id, 'favorite_posts', $favorite_posts );

		( new Logger() )->log( $user_id, $post_id, 'Unsave', 'Actricle Save', get_the_title( $post_id ) );

		echo 'success';
		wp_die();
	}
}
