<?php
/**
 * SavedArticles.
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

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

/**
 * SavedArticles.
 *
 * @author Objectiv
 * @since  1.0.0
 */
class SavedArticles {

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

		if ( function_exists( 'mk_log_access' ) ) {
			mk_log_access();
		}
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
		add_shortcode( 'mk_saved_articles', array( $this, 'markup' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'scripts' ) );
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
	public function markup() {
		$user_id        = get_current_user_id();
		$raw_articles   = get_user_meta( $user_id, 'favorite_posts', true );
		$articles       = $this->format_posts_data( $raw_articles );
		$filters        = ! empty( $this->get_filters() ) ? $this->get_filters() : array();
		$articles       = $this->search_filter( $articles, $filters );
		$articles       = $this->category_filter( $articles, $filters );
		$title_order    = ! empty( $filters['title_order'] ) ? ' ' . $filters['title_order'] : '';
		$category_order = ! empty( $filters['category_order'] ) ? ' ' . $filters['category_order'] : '';
		$date_order     = ! empty( $filters['date_order'] ) ? ' ' . $filters['date_order'] : '';
		$search_value   = ! empty( $filters['search'] ) ? $filters['search'] : '';

		usort( $articles, array( $this, 'sort_data' ) );

		ob_start();
		?>
		<div id="kitces-saved-articles-filters" class="kitces-saved-articles__filters">
			<!-- <div id="kitces-saved-articles-filters-search" class="kitces-saved-articles__search">
				<input type="text" id="kitces-saved-articles-search-field" class="kitces-saved-articles__search-field" name="search" placeholder="Search Saved Articles" value="<?php echo esc_attr( $search_value ); ?>">
				<button type="submit" id="kitces-saved-articles-search-button" class="search-form-submit kitces-saved-articles__search-button" name="submit"></button>
			</div> -->
			<div id="kitces-saved-articles-filters-categories" class="kitces-saved-articles__categories">
				<!-- <div class="kitces-saved-articles__categories-label">Filter:</div> -->
				<select id="kitces-saved-articles-categories-select" class="kitces-saved-articles__categories-select">
					<option value=""><?php echo esc_html( strtoupper( 'Category' ) ); ?></option>
					<?php
					$categories = $this->get_unique_catagories( $this->format_posts_data( $raw_articles ) );

					foreach ( $categories as $category ) {
						$selected = selected( $filters['category'], $category['category_id'] ) ? 'selected="selected"' : '';
						?>
							<option value="<?php echo esc_attr( $category['category_id'] ); ?>" <?php echo esc_html( $selected ); ?>>
								<?php echo esc_html( $category['category_name'] ); ?>
							</option>
						<?php
					}
					?>
				</select>
			</div>
		</div>
		<table id="kitces-saved-articles" class="kitces-saved-articles kitces-quiz-list">
			<thead>
				<tr>
					<td id="kitces-saved-articles-head-post" class="kitces-saved-articles__head-post">
						<div class="kitces-saved-articles__head-post-wrapper">
							<?php
							$svg = $this->get_caret_orientation( $title_order );
							$url = $this->get_filter_url( 'title-order', $title_order );
							?>
							<a href="<?php echo esc_url( $url ); ?>">
								Article Name<span class="kitces-saved-articles__head-caret <?php echo esc_attr( $svg ); ?>"><?php mk_get_svg( $svg ); ?></span>
							</a>
						</div>
					</td>
					<td id="kitces-saved-articles-head-category" class="kitces-saved-articles__head-category">
						<div class="kitces-saved-articles__head-post-wrapper">
							<?php
							$svg = $this->get_caret_orientation( $category_order );
							$url = $this->get_filter_url( 'category-order', $category_order );
							?>
							<a href="<?php echo esc_url( $url ); ?>">
								Category<span class="kitces-saved-articles__head-caret" <?php echo esc_attr( $svg ); ?>><?php mk_get_svg( $svg ); ?></span>
							</a>
						</div>
					</td>
					<td id="kitces-saved-articles-head-date" class="kitces-saved-articles__head-date">
						<div class="kitces-saved-articles__head-post-wrapper">
							<?php
							$svg = $this->get_caret_orientation( $date_order );
							$url = $this->get_filter_url( 'date-order', $date_order );
							?>
							<a href="<?php echo esc_url( $url ); ?>">
								Date<span class="kitces-saved-articles__head-caret" <?php echo esc_attr( $svg ); ?>><?php mk_get_svg( $svg ); ?></span>
							</a>
						</div>
					</td>
					<td id="kitces-saved-articles-head-trash" class="kitces-saved-articles__head-trash">
						Remove
					</td>
				</tr>
			</thead>
			<tbody>
				<?php
				$items = 20;
				$total = count( $articles );

				$this->get_rows( $articles, $items, $total, $filters );
				?>
			</tbody>
		</table>
		<div class="archive-pagination pagination">
			<?php $this->pagination( ceil( $total / $items ) ); ?>
		</div>
		<?php

		return ob_get_clean();
	}

	/**
	 * Get Categories
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return array
	 */
	public function get_unique_catagories( $articles ) {
		$temp  = array_unique( array_column( $articles, 'category_id' ) );
		$array = array_intersect_key( $articles, $temp );

		return $array;
	}

	/**
	 * Format Posts Data
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param array $posts The posts array.
	 *
	 * @return array
	 */
	public function format_posts_data( $posts ) {
		if ( empty( $posts ) ) {
			return array();
		}

		$array = array();

		foreach ( $posts as $post ) {
			$category = $this->get_first_category( (int) $post['post_id'] );

			$array[] = array(
				'post_id'       => (int) $post['post_id'],
				'post_title'    => get_the_title( $post['post_id'] ),
				'category_id'   => (int) $category['category_id'],
				'category_name' => $category['category_name'],
				'date_saved'    => $post['date_saved'],
			);
		}

		return $array;
	}

	/**
	 * Get First Category
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param int $post_id The post ID.
	 *
	 * @return array
	 */
	public function get_first_category( $post_id ) {
		$categories = get_the_category( $post_id );
		$category   = ! empty( $categories ) && ! empty( $categories[0] ) ? $categories[0] : '';
		$array      = array();

		if ( ! empty( $category ) ) {
			$array = array(
				'category_id'   => $category->term_id,
				'category_name' => $category->name,
			);
		}

		return $array;
	}

	/**
	 * Get Table Data
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param string $data The saved article data.
	 *
	 * @return array
	 */
	public function get_table_data( $data ) {
		$title    = get_the_title( (int) $data['post_id'] );
		$category = $this->get_first_category( (int) $data['post_id'] );

		$array = array(
			'post_id'       => (int) $data['post_id'],
			'post_title'    => $title,
			'post_url'      => get_the_permalink( (int) $data['post_id'] ),
			'category_name' => $category['category_name'],
			'category_url'  => get_category_link( $category['category_id'] ),
			'date_saved'    => $data['date_saved'],
		);

		return $array;
	}

	/**
	 * Get Rows
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param array $articles      The articles data.
	 * @param int   $item_per_page The total number of items to show per page.
	 * @param int   $total         The total number of items.
	 * @param array $filters       The filters if applicable.
	 *
	 * @return void
	 */
	public function get_rows( $articles, $item_per_page, $total, $filters ) {
		$nonce  = wp_create_nonce( 'favorite-posts' );
		$page   = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;
		$offset = ( ( $page * $item_per_page ) - $item_per_page );
		$min    = $offset + 1;
		$max    = ( $min + $item_per_page ) - 1;
		$row    = $offset;

		for ( $i = $offset; $i < $total; $i++ ) {
			if ( ! isset( $articles[ $i ] ) ) {
				continue;
			}

			$data = $this->get_table_data( $articles[ $i ] );
			$row++;

			if ( empty( $data ) || $row < $min ) {
				continue;
			}

			if ( $row > $max ) {
				break;
			}

			?>
			<tr>
				<td class="kitces-saved-articles__post">
					<a href="<?php echo esc_url( $data['post_url'] . '?action-log=true&post-id=' . $data['post_id'] ); ?>">
						<?php echo esc_html( $data['post_title'] ); ?>
					</a>
				</td>
				<td class="kitces-saved-articles__category">
					<a href="<?php echo esc_url( $data['category_url'] ); ?>">
						<?php echo esc_html( $data['category_name'] ); ?>
					</a>
				</td>
				<td class="kitces-saved-articles__date" style="width: 120px;">
					<?php echo esc_html( gmdate( 'm/d/Y', strtotime( $data['date_saved'] ) ) ); ?>
				</td>
				<td class="kitces-saved-articles__trash">
					<div
						class="kitces-saved-articles__trash-wrapper"
						data-title="<?php echo esc_attr( $data['post_title'] ); ?>"
						data-nonce="<?php echo esc_attr( $nonce ); ?>"
						data-post="<?php echo esc_attr( $data['post_id'] ); ?>"
						data-user="<?php echo esc_attr( get_current_user_id() ); ?>">
						<?php mk_get_svg( 'trash' ); ?>
					</div>
				</td>
			</tr>
			<?php
		}
	}

	/**
	 * Get URL Params
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return array
	 */
	public function get_filters() {
		$title_order_filter    = ! empty( $_GET['title-order'] ) ? sanitize_text_field( wp_unslash( $_GET['title-order'] ) ) : '';
		$category_order_filter = ! empty( $_GET['category-order'] ) ? sanitize_text_field( wp_unslash( $_GET['category-order'] ) ) : '';
		$date_order_filter     = ! empty( $_GET['date-order'] ) ? sanitize_text_field( wp_unslash( $_GET['date-order'] ) ) : '';
		$category_filter       = ! empty( $_GET['category'] ) ? sanitize_text_field( wp_unslash( $_GET['category'] ) ) : '';
		$search_filter         = ! empty( $_GET['search'] ) ? sanitize_text_field( wp_unslash( $_GET['search'] ) ) : '';

		$array = array(
			'title_order'    => $title_order_filter,
			'category_order' => $category_order_filter,
			'date_order'     => $date_order_filter,
			'category'       => $category_filter,
			'search'         => $search_filter,
		);

		return $array;
	}

	/**
	 * Category Filter
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param array $array   The array of saved posts.
	 * @param array $filters The filter URL params.
	 *
	 * @return array
	 */
	public function category_filter( $array, $filters ) {
		if ( ! empty( $filters['category'] ) ) {
			foreach ( $array as $key => $value ) {
				if ( (int) $value['category_id'] !== (int) $filters['category'] ) {
					unset( $array[ $key ] );
				}
			}
		}

		return $array;
	}

	/**
	 * Search Filter
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param array $array   The array of saved posts.
	 * @param array $filters The filter URL params.
	 *
	 * @return array
	 */
	public function search_filter( $array, $filters ) {
		if ( ! empty( $filters['search'] ) ) {
			$array_ids = wp_list_pluck( $array, 'post_id' );
			$query     = new WP_Query(
				array(
					'posts-per-page' => -1,
					's'              => $filters['search'],
					'post__in'       => $array_ids,
					'fields'         => 'ids',
				)
			);

			$query_ids = $query->posts;

			foreach ( $array as $key => $value ) {
				if ( ! in_array( $value['post_id'], $query_ids, true ) ) {
					unset( $array[ $key ] );
				}
			}
		}

		return $array;
	}

	/**
	 * Sort Data
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param string $a Item A.
	 * @param string $b Item B.
	 *
	 * @return array
	 */
	public function sort_data( $a, $b ) {
		$orderby = 'date_saved';
		$order   = 'desc';

		$filters        = ! empty( $this->get_filters() ) ? $this->get_filters() : array();
		$title_order    = ! empty( $filters['title_order'] ) ? $filters['title_order'] : '';
		$category_order = ! empty( $filters['category_order'] ) ? $filters['category_order'] : '';
		$date_order     = ! empty( $filters['date_order'] ) ? $filters['date_order'] : '';

		if ( ! empty( $title_order ) ) {
			$orderby = 'post_title';
			$order   = $title_order;
		}

		if ( ! empty( $category_order ) ) {
			$orderby = 'category_name';
			$order   = $category_order;
		}

		if ( ! empty( $date_order ) ) {
			$orderby = 'date_saved';
			$order   = $date_order;
		}

		$result = strnatcmp( $a[ $orderby ], $b[ $orderby ] );

		if ( 'desc' === $order ) {
			return -$result;
		}

		return $result;
	}

	/**
	 * Get Caret Orientation
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param string $column_order The column.
	 *
	 * @return string
	 */
	public function get_caret_orientation( $column_order ) {
		return empty( $column_order ) || trim( $column_order ) === 'desc' ? 'caret-down' : 'caret-up';
	}

	/**
	 * Get Filter URL
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param string $column The column to sort.
	 * @param string $column_order The column.
	 *
	 * @return string
	 */
	public function get_filter_url( $column, $column_order ) {
		$order = $this->get_the_order( trim( $column_order ) );

		return ! empty( $order ) ? add_query_arg( $column, $order, $this->get_clean_url() ) : $this->get_clean_url();
	}

	/**
	 * Get Clean URL
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return string
	 */
	public function get_clean_url() {
		$protocol = isset( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
		$host     = isset( $_SERVER['HTTP_HOST'] ) ? sanitize_text_field( wp_unslash( $_SERVER['HTTP_HOST'] ) ) : '';
		$request  = isset( $_SERVER['REQUEST_URI'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '';
		$base_url = ! empty( $host ) ? $protocol . '://' . $host : '';
		$url      = ! empty( $base_url ) ? $base_url . $request : '';

		if ( empty( $url ) ) {
			return get_the_permalink();
		}

		$parsed = wp_parse_url( $url );
		$query  = ! empty( $parsed['query'] ) ? $parsed['query'] : '';

		parse_str( $query, $params );

		$excludes = array(
			'title-order',
			'category-order',
			'date-order',
		);

		foreach ( $excludes as $exclude ) {
			unset( $params[ $exclude ] );
		}

		$string = get_the_permalink() . '?' . http_build_query( $params );

		return $string;
	}

	/**
	 * Get the Order
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param string $order The order.
	 *
	 * @return string
	 */
	public function get_the_order( $order ) {
		if ( empty( $order ) ) {
			return 'desc';
		}

		$new_order = '';

		switch ( $order ) {
			case 'desc':
				$new_order = 'asc';
				break;
			case 'asc':
				$new_order = 'desc';
				break;
			default:
				$new_order = 'desc';
		}

		return $new_order;
	}

	/**
	 * Pagination
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param int $total The total number of posts.
	 *
	 * @return void
	 */
	public function pagination( $total ) {
		$big = 9999999999999;

		$links = paginate_links( array(
			'base'    => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
			'format'  => '?paged=%#%',
			'current' => max( 1, get_query_var( 'paged' ) ),
			'total'   => $total,
			'type'    => 'array',
		) );

		echo wp_kses_post( '<div class="pagination-wrap"><ul class="pagination">' );

		if ( ! empty( $links ) ) {
			foreach ( $links as $link ) {

				$classes = '';

				if ( strpos( $link, 'prev page-numbers' ) !== false ) {
					$classes = 'pagination-previous';
				}

				if ( strpos( $link, 'current' ) !== false ) {
					$classes = 'active';
					$link    = str_replace( 'span', 'a', $link );
				}

				if ( strpos( $link, 'next page-numbers' ) !== false ) {
					$classes = 'pagination-next';
				}

				echo wp_kses_post( "<li class='$classes'>$link</li>" );
			}
		}

		echo wp_kses_post( '</ul></div>' );
	}

	/**
	 * Scripts
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function scripts() {
		global $post;

		if ( ! is_a( $post, 'WP_Post' ) && ! has_shortcode( $post->post_content, 'mk_saved_articles' ) ) {
			return;
		}

		$file        = 'src/js/favorite-posts-table.js';
		$file_path   = KITCES_DIR_PATH . $file;
		$file_url    = KITCES_DIR_URL . $file;
		$file_exists = file_exists( $file_path );
		$file_time   = $file_exists ? filemtime( $file_path ) : '1.0.0';
		$handle      = 'kitces-favorite-posts';

		wp_register_script( $handle, $file_url, array(), $file_time, true );
		wp_enqueue_script( $handle );

		wp_localize_script(
			$handle,
			KITCES_PRFIX . 'AdminAjax',
			admin_url( 'admin-ajax.php' )
		);
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

		if ( ! is_a( $post, 'WP_Post' ) && ! has_shortcode( $post->post_content, 'mk_saved_articles' ) ) {
			return;
		}

		$file        = 'src/css/favorite-posts-table.css';
		$file_path   = KITCES_DIR_PATH . $file;
		$file_url    = KITCES_DIR_URL . $file;
		$file_exists = file_exists( $file_path );
		$file_time   = $file_exists ? filemtime( $file_path ) : '1.0.0';
		$handle      = 'kitces-favorite-posts';

		if ( $file_exists ) {
			wp_register_style( $handle, $file_url, array(), $file_time, 'all' );
			wp_enqueue_style( $handle );
		}
	}
}
