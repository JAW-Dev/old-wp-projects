<?php
/**
 * AJAX.
 *
 * @package    Kitces_Star_Rating
 * @subpackage Kitces_Star_Rating/Inlcudes/Classes
 * @author     Objectiv
 * @copyright  Copyright (c) Plugin_Boilerplate_Date, Objectiv
 * @license    GPL-2.0
 * @since      1.0.0
 */

namespace KitcesStarRating\Includes\Classes;

use KitcesStarRating\Includes\Classes\Tables as Tables;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

if ( ! class_exists( __NAMESPACE__ . '\\AJAX' ) ) {

	/**
	 * AJAX.
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 */
	class AJAX {

		/**
		 * Post Ratings
		 *
		 * @author Jason Witt
		 * @since  1.0.0
		 *
		 * @var array
		 */
		protected $post_ratings;

		/**
		 * Rating Version
		 *
		 * @author Jason Witt
		 * @since  1.0.0
		 *
		 * @var string
		 */
		protected $rating_version;

		/**
		 * User Rating
		 *
		 * @author Jason Witt
		 * @since  1.0.0
		 *
		 * @var string
		 */
		protected $user_rating;

		/**
		 * User ID
		 *
		 * @author Jason Witt
		 * @since  1.0.0
		 *
		 * @var int
		 */
		protected $user_id;

		/**
		 * Post ID
		 *
		 * @author Jason Witt
		 * @since  1.0.0
		 *
		 * @var int
		 */
		protected $post_id;

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
			add_action( 'wp_ajax_kitces_rate_post', array( $this, 'rate_post' ) );
			add_action( 'wp_ajax_nopriv_kitces_rate_post', array( $this, 'rate_post' ) );
		}

		/**
		 * Rate Post
		 *
		 * @author Jason Witt
		 * @since  1.0.0
		 *
		 * @return void
		 */
		public function rate_post() {
			$post = Post::request();

			if ( ! empty( $post ) ) {
				$nonce = $post['nonce'] ? wp_unslash( sanitize_text_field( $post['nonce'] ) ) : '';

				if ( ! wp_verify_nonce( $nonce, 'user_star_rating' ) ) {
					wp_die();
				}

				$this->update_rating();

				echo $post['rating'];
				wp_die();
			}
		}

		/**
		 * Update Rating
		 *
		 * @author Jason Witt
		 * @since  1.0.0
		 *
		 * @return void
		 */
		public function update_rating() {
			if ( ! $this->is_valid_rating() ) {
				wp_die();
			}

			if ( $this->is_new_rating() ) {
				Tables\Query::add();
			} else {
				Tables\Query::update();
			};
		}

		/**
		 * Is Valid Rating
		 *
		 * @author Jason Witt
		 * @since  1.0.0
		 *
		 * @return bool
		 */
		public function is_valid_rating() {
			$post = Post::request();

			return ! empty( $post['rating'] ) && ! empty( $post['version'] ) && ! empty( $post['post_id'] );
		}

		/**
		 * Is New Rating
		 *
		 * @author Jason Witt
		 * @since  1.0.0
		 *
		 * @return bool
		 */
		private function is_new_rating() {
			return empty( Tables\Query::get_entry() );
		}
	}
}
