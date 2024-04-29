<?php

namespace FP_Favorites;

class Main {
	public function __construct() {
		add_action( 'wp_enqueue_scripts', array( $this, 'scripts' ) );
		add_action( 'obj_before_resource_description', array( $this, 'add_favorite_toggle' ) );
		add_action( 'wp_ajax_favorite_callback', array( $this, 'favorite_callback' ) );
	}

	public function scripts() {
		wp_enqueue_script( 'main', PLUGIN_URL . 'assets/js/main.js', array( 'jquery' ), VERSION, true );
		wp_localize_script( 'main', 'favorite_data', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
	}

	public function add_favorite_toggle() {
		$post_id = get_the_ID();
		$user_id = get_current_user_id();

		if ( ! empty( $user_id ) ) {
			$post_type       = get_post_type( $post_id );
			$favorited_items = get_user_meta( $user_id, 'favorited_items', true );
			$favorited_class = '';
			$button_text     = 'Add to Favorites';
			$can_favorite    = function_exists( 'fp_user_can_favorite' ) && fp_user_can_favorite( $user_id );

			if ( $can_favorite ) {
				if ( isset( $favorited_items[ $post_id ] ) ) {
					$favorited_class = 'is-favorited';
					$button_text     = 'Remove from Favorites';
				}

				if ( is_user_logged_in() && 'resource' === $post_type ) {
					echo "<div class='favorite-wrap $favorited_class'>";
					echo "<button class='favorite-toggle-trigger' title='Toggle Favorite Status' data-post-id='$post_id'>";
					echo "<svg width='31' height='29' viewBox='0 0 31 29' fill='none' xmlns='http://www.w3.org/2000/svg'><path d='M15.5 2.205l3.898 7.66.236.463.515.073 8.655 1.227-6.24 5.895-.393.37.094.531 1.48 8.366-7.79-3.973-.455-.232-.454.232-7.79 3.973 1.479-8.366.094-.53-.392-.37-6.241-5.896L10.85 10.4l.515-.073.236-.464 3.898-7.66z' fill='currentColor' stroke='#cfaa4c' stroke-width='2'/></svg>";
					echo "<span class='button-text'>$button_text</span>";
					echo '</button>';
					echo '</div>';
				}
			}
		}

	}

	public function favorite_callback() {
		$post_id   = $_POST['postID'];
		$user_id   = get_current_user_id();
		$favorites = get_user_meta( $user_id, 'favorited_items', true );

		if ( ! is_array( $favorites ) ) {
			$favorites = array();
		}

		if ( isset( $favorites[ $post_id ] ) ) {
			unset( $favorites[ $post_id ] );
			update_user_meta( $user_id, 'favorited_items', $favorites );
			$result['status'] = 0;
		} else {
			$favorites[ $post_id ] = $post_id;
			update_user_meta( $user_id, 'favorited_items', $favorites );
			$result['status'] = 1;
		}

		$result['favorites'] = $favorites;

		echo json_encode( $result );
		wp_die();
	}

}
