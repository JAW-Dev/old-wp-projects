<?php
/**
 * Missed Articles.
 *
 * @package    Kitces
 * @subpackage Kitces/Includes/Classes/Shortcodes
 * @author     Objectiv
 * @copyright  Copyright (c) 2020, Objectiv
 * @license    GNU General Public License v2 or later
 * @since      1.0.0
 */

namespace Kitces\Includes\Classes\Shortcodes;

use WP_Query;
use Kitces\Includes\Classes\Exponea;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

/**
 * Missed Articles.
 *
 * @author Objectiv
 * @since  1.0.0
 */
class MissedArticles {

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
		add_shortcode( 'mk_missed_articles', array( $this, 'markup' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'styles' ) );
	}

	/**
	 * Markup
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return string
	 */
	public function markup( $atts = false ) {
		$title            = ! empty( mk_key_value( $atts, 'title' ) ) ? mk_key_value( $atts, 'title' ) : 'Recent Unread Posts';
		$subtitle         = ! empty( mk_key_value( $atts, 'subtitle' ) ) ? mk_key_value( $atts, 'subtitle' ) : 'Title';
		$noposts          = ! empty( mk_key_value( $atts, 'noposts' ) ) ? mk_key_value( $atts, 'noposts' ) : 'No Unread Posts!';
		$not_viewed_posts = ( new Exponea\Customer() )->get_not_viewed_posts();

		ob_start();
		?>
		<div>
			<div class="mk-missed-articles">
				<h3 class="mk-missed-articles__title"><?php echo esc_html( $subtitle ); ?></h3>
				<?php
				if ( empty( $not_viewed_posts ) ) {
					?>
					<p class="mk-missed-articles__no-posts"><?php echo esc_html( $noposts ); ?></p>
					<?php
				} else {
					foreach ( $not_viewed_posts as $post ) {
						?>
						<div class="mk-missed-articles__post">
							<div class="mk-missed-articles__icon entry-header__icon">
								<?php echo wp_kses_post( $this->get_post_icon( $post->ID ) ); ?>
							</div>
							<div class="mk-missed-articles__link">
								<a href="<?php echo esc_html( get_the_permalink( $post->ID ) ); ?>" target="_blank">
									<?php echo esc_html( get_the_title( $post->ID ) ); ?>
								</a>
							</div>
						</div>
						<?php
					}
				}
				?>
			</div>
		</div>
		<?php

		return ob_get_clean();
	}

	/**
	 * Get Post Icon
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return string
	 */
	public function get_post_icon( $post_id ) {
		$post_terms = get_the_terms( $post_id, 'category' );
		$post_term  = ! empty( $post_terms[0] ) ? $post_terms[0] : array();
		$tax_term   = ! empty( $post_term ) ? $post_term->taxonomy . '_' . $post_term->term_id : '';
		$icon_type  = ! empty( $tax_term ) && function_exists( 'get_field' ) ? get_field( 'kitces_category_icon_type', $tax_term ) : '';
		$icon       = '';

		if ( ! empty( $icon_type ) ) {
			$icon = '';

			if ( 'font' === $icon_type ) {
				$icon = function_exists( 'get_field' ) ? get_field( 'kitces_category_icon_font_icon', $tax_term ) : '';
			} elseif ( 'image' === $icon_type ) {
				$image_id = function_exists( 'get_field' ) ? get_field( 'kitces_category_icon_image_icon', $tax_term ) : '';
				$icon     = wp_get_attachment_image( $image_id, 'full', true );
			}
		}

		return $icon;
	}

	/**
	 * Styles
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function styles() {
		global $post;

		if ( ! is_a( $post, 'WP_Post' ) && ! has_shortcode( $post->post_content, 'mk_missed_articles' ) ) {
			return;
		}

		$file        = 'src/css/missed-articles.css';
		$file_path   = KITCES_DIR_PATH . $file;
		$file_url    = KITCES_DIR_URL . $file;
		$file_exists = file_exists( $file_path );
		$file_time   = $file_exists ? filemtime( $file_path ) : '1.0.0';
		$handle      = 'kitces-missed-articles';

		if ( $file_exists ) {
			wp_register_style( $handle, $file_url, array(), $file_time, 'all' );
			wp_enqueue_style( $handle );
		}
	}
}
