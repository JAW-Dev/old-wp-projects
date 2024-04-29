<?php
/**
 * Results Table.
 *
 * @package    Kitces_Star_Rating
 * @subpackage Kitces_Star_Rating/Inlcudes/Classes
 * @author     Objectiv
 * @copyright  Copyright (c) Plugin_Boilerplate_Date, Objectiv
 * @license    GPL-2.0
 * @since      1.0.0
 */

namespace KitcesStarRating\Includes\Classes\Admin;

use KitcesStarRating\Includes\Classes\Tables as Tables;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

/**
 * WP_List_Table
 */
if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

if ( ! class_exists( __NAMESPACE__ . '\\ResultsTable' ) ) {

	/**
	 * Results Table.
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 */
	class ResultsTable extends \WP_List_Table {

		/**
		 * Initialize the class
		 *
		 * @author Jason Witt
		 * @since  1.0.0
		 *
		 * @return void
		 */
		public function __construct() {
			parent::__construct(
				array(
					'singular' => __( 'Star Ratings', 'kitces_star_rating' ),
					'plural'   => __( 'Star Ratings', 'kitces_star_rating' ),
					'ajax'     => false,
				)
			);
		}

		/**
		 * Prepare Items
		 *
		 * @author Jason Witt
		 * @since  1.0.0
		 *
		 * @return void
		 */
		public function prepare_items() {
			$search_key = isset( $_REQUEST['s'] ) ? wp_unslash( trim( $_REQUEST['s'] ) ) : ''; // phpcs:ignore
			$columns    = $this->get_columns();
			$hidden     = $this->get_hidden_columns();
			$sortable   = $this->get_sortable_columns();
			$data       = $this->table_data();

			usort( $data, array( $this, 'sort_data' ) );

			$i = 0;
			foreach ( $data as $key ) {
				if ( isset( $key['date'] ) ) {
					$data[ $i ]['date'] = date( 'F j, Y', strtotime( $key['date'] ) );
				}
				$i++;
			}

			if ( $search_key ) {
				$data = $this->filter_table_data( $data, $search_key );
			}

			$per_page     = $this->get_items_per_page( 'posts_per_page', 10 );
			$current_page = $this->get_pagenum();
			$total_items  = count( $data );

			$this->set_pagination_args(
				array(
					'total_items' => $total_items,
					'per_page'    => $per_page,
				)
			);

			$data = array_slice( $data, ( ( $current_page - 1 ) * $per_page ), $per_page );

			$this->_column_headers = array( $columns, $hidden, $sortable );

			$this->items = $data;
		}

		/**
		 * Display Table Nav
		 *
		 * @author Jason Witt
		 * @since  1.0.0
		 *
		 * @param string $which The setion of the table (top, bottom).
		 *
		 * @return void
		 */
		public function display_tablenav( $which ) {
			?>
			<div class="tablenav <?php echo esc_attr( $which ); ?>">
				<?php
				if ( 'top' === $which ) {
					$this->filter_by_date( $which );
				}

				$this->pagination( $which );

				if ( 'bottom' === $which ) {
					$entries              = Tables\Query::get_entries();
					$header_ratings       = 0;
					$header_ratings_count = 0;
					$header_total         = 0;
					$stars_ratings        = 0;
					$stars_ratings_count  = 0;
					$stars_total          = 0;
					$nerds_ratings        = 0;
					$nerds_ratings_count  = 0;
					$nerds_total          = 0;

					foreach ( $entries as $entry ) {
						if ( $entry->version === 'header' ) {
							$header_ratings += $entry->ratings_average;
							$header_total   += $entry->ratings_count;
							$header_ratings_count++;
						}

						if ( $entry->version === 'stars' ) {
							$stars_ratings += $entry->ratings_average;
							$stars_total   += $entry->ratings_count;
							$stars_ratings_count++;
						}

						if ( $entry->version === 'nerds' ) {
							$nerds_ratings += $entry->ratings_average;
							$nerds_total   += $entry->ratings_count;
							$nerds_ratings_count++;
						}
					}

					$header_total_average = round( $header_ratings / $header_ratings_count, 2 );
					$stars_total_average  = round( $stars_ratings / $stars_ratings_count, 2 );
					$nerds_total_average  = round( $nerds_ratings / $nerds_ratings_count, 2 );

					?>
					<div style="display: flex;">
						<div style="margin-right: 2rem;">
							<p style="font-size: 1rem;"><strong>Header Total Votes</strong>: <?php echo esc_html( $header_total ); ?></p>
							<p style="font-size: 1rem;"><strong>Header Total Average Rating</strong>: <?php echo esc_html( $header_total_average ); ?></p>
						</div>
						<div style="margin-right: 2rem;">
							<p style="font-size: 1rem;"><strong>Stars Total Votes</strong>: <?php echo esc_html( $stars_total ); ?></p>
							<p style="font-size: 1rem;"><strong>Stars Total Average Rating</strong>: <?php echo esc_html( $stars_total_average ); ?></p>
						</div>
						<div>
							<p style="font-size: 1rem;"><strong>Nerds Total Votes</strong>: <?php echo esc_html( $nerds_total ); ?></p>
							<p style="font-size: 1rem;"><strong>Nerds Total Average Rating</strong>: <?php echo esc_html( $stars_total_average ); ?></p>
						<div>
					</div>
					<?php
				}
				?>
				<br class="clear" />
			</div>
			<?php
		}

		/**
		 * Filter Table Data
		 *
		 * @author Jason Witt
		 * @since  1.0.0
		 *
		 * @param array  $table_data The table data array.
		 * @param string $search_key The search term.
		 *
		 * @return array
		 */
		public function filter_table_data( $table_data, $search_key ) {
			$filtered_table_data = array_values(
				array_filter(
					$table_data,
					function( $row ) use ( $search_key ) {
						foreach ( $row as $row_val ) {
							if ( stripos( $row_val, $search_key ) !== false ) {
								return true;
							}
						}
					}
				)
			);
			return $filtered_table_data;
		}

		/**
		 * Get Columns
		 *
		 * @author Jason Witt
		 * @since  1.0.0
		 *
		 * @return array
		 */
		public function get_columns() {
			$columns = array(
				'title'    => __( 'Title', 'kitces_star_rating' ),
				'date'     => __( 'Post Date', 'kitces_star_rating' ),
				'versions' => __( 'Versions', 'kitces_star_rating' ),
				'total'    => __( 'Total Ratings', 'kitces_star_rating' ),
				'averages' => __( 'Total Averages', 'kitces_star_rating' ),
			);

			return $columns;
		}

		/**
		 * Get Hidden Columns
		 *
		 * @author Jason Witt
		 * @since  1.0.0
		 *
		 * @return array
		 */
		public function get_hidden_columns() {
			return array();
		}

		/**
		 * Get Sortable Columns
		 *
		 * @author Jason Witt
		 * @since  1.0.0
		 *
		 * @return array
		 */
		public function get_sortable_columns() {
			return array(
				'date'     => array( 'date', true ),
				'total'    => array( 'total', true ),
				'averages' => array( 'averages', true ),
			);
		}

		/**
		 * Table Data
		 *
		 * @author Jason Witt
		 * @since  1.0.0
		 *
		 * @return array
		 */
		public function table_data() {
			$data = array();

			$entries = $this->group_array( Tables\Query::get_entries(), 'post_id' );

			if ( ! empty( $entries ) ) {
				foreach ( $entries as $entries_key => $entries_value ) {
					$ratings = array();

					foreach ( $entries_value as $item ) {
						if ( $item['version'] === 'header' ) {
							$ratings[0] = $item;
						}
						if ( $item['version'] === 'stars' ) {
							$ratings[1] = $item;
						}
						if ( $item['version'] === 'nerds' ) {
							$ratings[2] = $item;
						}
					}

					ksort( $ratings );

					$post_id       = $entries_key;
					$the_post      = get_post( $post_id );
					$total_ratings = 0;

					$versions = '<div class="post-star-ratings__wrapper">';

					$averages = 0;
					$count    = 0;

					foreach ( $ratings as $key => $value ) {

						$version = ucwords( str_replace( '-', ' ', $value['version'] ) );
						$total   = $value['ratings_count'];
						$average = $this->get_average( $value );

						$versions .= $this->card( $version, $total, $average );

						$total_ratings += $total;

						$averages += $average;
						$count++;
					}

					$total_average = round( $averages / $count, 2 );

					$versions .= '</div>';

					if ( ! empty( $the_post ) && $total_ratings >= 5 ) {
						$data[] = array(
							'title'    => '<a href="' . get_edit_post_link( $the_post->ID ) . '">' . $the_post->post_title . '</a>',
							'date'     => $the_post->post_date,
							'versions' => $versions,
							'total'    => $total_ratings,
							'averages' => $total_average,
						);
					}
				}
			}

			$return = array_column( $data, 'total' );
			array_multisort( $return, SORT_DESC, $data );

			return $data;
		}

		/**
		 * Card
		 *
		 * @author Jason Witt
		 * @since  1.0.0
		 *
		 * @param string $version The ratings version name.
		 * @param int    $total   The total rating for the post.
		 * @param int    $average The average rating for the post.
		 *
		 * @return string
		 */
		public function card( $version, $total, $average ) {
			return '<div class="post-star-ratings__card">
				<div class="post-star-ratings__heading">
					<strong>' . $version . '</strong>
				</div>
				<div class="post-star-ratings__total">
					<strong>Total Ratings Submitted</strong>: ' . $total . '
				</div>
				<div class="post-star-ratings__average">
					<strong>Average Rating</strong>: ' . $average . '
				</div>
			</div>';
		}

		/**
		 * Get Average
		 *
		 * @author Jason Witt
		 * @since  1.0.0
		 *
		 * @param array $value The ratings array.
		 *
		 * @return float
		 */
		public function get_average( $value ) {
			$levels = array(
				'total_one'   => 1,
				'total_two'   => 2,
				'total_three' => 3,
				'total_four'  => 4,
				'total_five'  => 5,
			);

			$rates = array(
				'total_one'   => $value['total_one'] * $levels['total_one'],
				'total_two'   => $value['total_two'] * $levels['total_two'],
				'total_three' => $value['total_three'] * $levels['total_three'],
				'total_four'  => $value['total_four'] * $levels['total_four'],
				'total_five'  => $value['total_five'] * $levels['total_five'],
			);

			$levels_totals = $rates['total_one'] + $rates['total_two'] + $rates['total_three'] + $rates['total_four'] + $rates['total_five'];
			$rates_total   = $value['total_one'] + $value['total_two'] + $value['total_three'] + $value['total_four'] + $value['total_five'];

			return round( $levels_totals / $rates_total, 2 );
		}

		/**
		 * Group Array
		 *
		 * @author Jason Witt
		 * @since  1.0.0
		 *
		 * @param array  $array The emtries array.
		 * @param string $group What key to group the entries by.
		 *
		 * @return array
		 */
		public function group_array( $array, $group ) {
			$temp  = array();
			$array = wp_json_encode( $array );
			$array = json_decode( $array, true );

			if ( is_array( $array ) ) {
				foreach ( $array as $key => $value ) {

					$group_value = $value[ $group ];

					unset( $array[ $key ][ $group ] );

					if ( ! array_key_exists( $group_value, $temp ) ) {
						$temp[ $group_value ] = array();
					}

					$data = $array[ $key ];

					$temp[ $group_value ][] = $data;
				}
			}

			return $temp;
		}

		/**
		 * Column Defualt
		 *
		 * @author Jason Witt
		 * @since  1.0.0
		 *
		 * @param  array  $item        The data for the item.
		 * @param  string $column_name Current column name.
		 *
		 * @return mixed
		 */
		public function column_default( $item, $column_name ) {
			switch ( $column_name ) {
				case 'title':
				case 'date':
				case 'versions':
				case 'total':
				case 'averages':
					return $item[ $column_name ];
				default:
					return print_r( $item, true ) ; // phpcs:ignore
			}
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
			// Set defaults.
			$orderby = 'total';
			$order   = 'asc';

			// If orderby is set, use this as the sort column.
			if ( ! empty( $_GET['orderby'] ) ) { // phpcs:ignore
				$orderby = $_GET['orderby']; // phpcs:ignore
			}

			// If order is set use this as the order.
			if ( ! empty( $_GET['order'] ) ) { // phpcs:ignore
				$order = $_GET['order']; // phpcs:ignore
			}

			$result = strnatcmp( $a[ $orderby ], $b[ $orderby ] );

			if ( 'desc' === $order ) {
				return $result;
			}

			return -$result;
		}
	}
}
