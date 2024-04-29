<?php
/**
 * Markup.
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
 * Markup.
 *
 * @author Objectiv
 * @since  1.0.0
 */
class Markup {

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
	 * Render
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function render() {
		?>
		<div id="kitces-favorite-posts" class="kitces-favorite-posts kitces-favorite-posts__full">
			<?php $this->elements(); ?>
		</div>
		<?php
	}

	/**
	 * Render Mobile
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function render_mobile() {
		?>
		<div id="kitces-favorite-posts-mobile" class="kitces-favorite-posts kitces-favorite-posts__mobile">
			<?php $this->elements(); ?>
		</div>
		<?php
	}

	/**
	 * Elements
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function elements() {
		if ( is_user_logged_in() ) :
			$nonce = wp_create_nonce( 'favorite-posts' );

			$favorites = get_user_meta( get_current_user_id(), 'favorite_posts', true );
			$classes   = ' unselected';

			if ( ! empty( $favorites ) ) {
				foreach ( $favorites as $favorite ) {
					if ( get_the_ID() === $favorite['post_id'] ) {
						$classes = ' selected';
						break;
					}
				}
			}

			?>
			<div id="kitces-favorite-posts-select" class="kitces-favorite-posts__select<?php echo esc_attr( $classes ); ?>" data-nonce="<?php echo esc_attr( $nonce ); ?>" data-post="<?php echo esc_attr( get_the_ID() ); ?>" data-user="<?php echo esc_attr( get_current_user_id() ); ?>">
				<div class="kitces-favorite-posts__text">Save Article To My List</div>
				<div class="kitces-favorite-posts__icon kitces-favorite-posts__icon-unselected"><?php mk_get_svg( 'bookmark' ); ?></div>
				<div class="kitces-favorite-posts__icon kitces-favorite-posts__icon-selected"><?php mk_get_svg( 'bookmark-solid' ); ?></div>
			</div>
			<?php
		else :
			$target_url = add_query_arg( array( 'action-log' => 'true' ), get_the_permalink() );

			$url = add_query_arg(
				array(
					'redirect_to' => urlencode( $target_url ),
				),
				home_url( '/login/' )
			);
			?>
			<div class="kitces-favorite-posts__text logged-out">
				<a href="<?php echo esc_url( $url ); ?>" class="login">LOGIN</a> or <a href="/reader-account-sign-up" class="create-account">CREATE AN ACCOUNT</a> TO SAVE ARTICLE TO YOUR LIST
			</div>
			<?php
		endif;
	}
}
