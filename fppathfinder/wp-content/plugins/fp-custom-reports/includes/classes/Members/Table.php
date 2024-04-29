<?php
/**
 * Table
 *
 * @package    Custom_Reports
 * @subpackage Custom_Reports/Includes/Classes/Enterprise
 * @author     Objectiv
 * @copyright  Copyright (c) 2021, Objectiv
 * @license    GPL-2.0
 * @version    1.0.0
 */

namespace CustomReports\Members;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

/**
 * WP_List_Table
 */
if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

/**
 * Table
 *
 * @author Jason Witt
 * @since  1.0.0
 */
class Table extends \WP_List_Table {

	/**
	 * Sho Posts Amount
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @var int
	 */
	protected $show_posts_amount = 25;

	/**
	 * Table Slug
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @var string
	 */
	protected $slug = 'member-report';

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
				'singular' => __( 'Member Report', ' custom-reports' ),
				'plural'   => __( 'Member Reports', ' custom-reports' ),
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
		foreach ( $data as $key => $value ) {
			$dates = [
				'activated_date',
				'trial_end_date',
				'renewed_date',
				'cancellation_date',
				'registered_date',
			];
			foreach ( $dates as $date ) {
				if ( isset( $value[ $date ] ) ) {
					$data[ $key ][ $date ] = ! empty( $value[ $date ] ) ? gmdate( 'F j, Y', strtotime( $value[ $date ] ) ) : '';
				}
			}
			$i++;
		}

		if ( $search_key ) {
			$data = $this->filter_table_data( $data, $search_key );
		}

		$per_page     = $this->get_items_per_page( 'posts_per_page', $this->show_posts_amount );
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
				?>
				<div class="alignleft actions">
					<div class="custom-reports__filters">
						<?php
						Filters::date_range( $this->slug );
						Filters::status( $this->slug );
						Filters::subscriptions( $this->slug );
						Filters::groups( $this->slug );
						Filters::activity( $this->slug );
						Filters::had_trial( $this->slug );
						Filters::upgraded( $this->slug );
						?>
						<input class="custom-reports__filters-submit button" type="submit" id="table-date-range-filter" value="Filter" class="button"/>
					</div>
				</div>
				<?php
				$this->pagination( $which );
			}

			if ( 'bottom' === $which ) {
				$this->pagination( $which );
				?>
				<form method="get" action="/">
					<button id="<?php echo esc_attr( $this->slug ); ?>" class="button" data-nonce="<?php echo esc_attr( $this->slug ); ?>">Download <?php echo esc_html( $this->title ); ?> CSV</button>
				</form>
				<?php
			}
			?>
		</div>
		<?php
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
		return Data::get_data();
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
			'ID'           => __( 'ID', 'custom-reports' ),
			'email'        => __( 'Email', 'custom-reports' ),
			'status'       => __( 'Status', 'custom-reports' ),
			'group'        => __( 'Group', 'custom-reports' ),
			'subscription' => __( 'Membership', 'custom-reports' ),
			'had_trial'    => __( 'Trial', 'custom-reports' ),
			'has_upgraded' => __( 'Upgraded', 'custom-reports' ),
		);

		$activity     = sanitize_text_field( wp_unslash( $_GET['activity'] ?? '' ) );
		$subscription = sanitize_text_field( wp_unslash( $_GET['subscriptions'] ?? null ) );
		$has_trialed  = sanitize_text_field( wp_unslash( $_GET['has-trialed'] ?? null ) );
		$upgraded     = sanitize_text_field( wp_unslash( $_GET['upgraded-from'] ?? null ) );

		if ( $upgraded ) {
			$columns['old_subscription'] = __( 'Upgraded From', 'custom-reports' );
		}

		if ( ! empty( $activity ) ) {
			switch ( $activity ) {
				case 'activated_date':
					$columns['activated_date'] = __( 'Activated Date', 'custom-reports' );
					break;
				case 'renewed_date':
					$columns['renewed_date'] = __( 'Renewed Date', 'custom-reports' );
					break;
				case 'cancellation_date':
					$columns['cancellation_date'] = __( 'Cancelation Date', 'custom-reports' );
					break;
				case 'trial_end_date':
					$columns['trial_end_date'] = __( 'Trial End Date', 'custom-reports' );
					break;
				case 'registered_date':
					$columns['registered_date'] = __( 'Registered Date', 'custom-reports' );
					break;
				default:
					break;
			}
		}

		if ( $activity === 'registered_date' ) {
			unset( $columns['email'] );
			unset( $columns['status'] );
			unset( $columns['group'] );
			unset( $columns['subscription'] );
			unset( $columns['had_trial'] );
			unset( $columns['has_upgraded'] );
		}

		if ( ! empty( $subscription ) ) {
			if ( $subscription === '9' ) {
				$columns['essentials_group'] = __( 'Essentials Group', 'custom-reports' );
			}
		}

		if ( ! empty( $has_trialed ) && ! $upgraded ) {
			$columns['trial_end_date'] = __( 'Trial End Date', 'custom-reports' );
		}

		if ( $upgraded ) {
			$columns['activated_date'] = __( 'Activated Date', 'custom-reports' );
		}

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
		$sortable = array(
			'ID'             => array( 'ID', true ),
			'email'          => array( 'email', true ),
			'status'         => array( 'status', true ),
			'group'          => array( 'group', true ),
			'subscription'   => array( 'subscription', true ),
			'trial_end_date' => array( 'trial_end_date', true ),
			'had_trial'      => array( 'had_trial', true ),
			'has_upgraded'   => array( 'has_upgraded', true ),
		);

		$upgraded = sanitize_text_field( wp_unslash( $_GET['upgraded-from'] ?? null ) );

		if ( $upgraded ) {
			$sortable['old_subscription'] = array( 'old_subscription', true );
		}

		$activity = sanitize_text_field( wp_unslash( $_GET['activity'] ?? '' ) );
		if ( ! empty( $activity ) ) {
			switch ( $activity ) {
				case 'activated_date':
					$sortable['activated_date'] = array( 'activated_date', true );
					break;
				case 'renewed_date':
					$sortable['renewed_date'] = array( 'renewed_date', true );
					break;
				case 'cancellation_date':
					$sortable['cancellation_date'] = array( 'cancellation_date', true );
					break;
				case 'trial_end_date':
					$sortable['trial_end_date'] = array( 'trial_end_date', true );
					break;
				case 'registered_date':
					$sortable['registered_date'] = array( 'registered_date', true );
					break;
				default:
					break;
			}
		}

		$subscription = sanitize_text_field( wp_unslash( $_GET['subscriptions'] ?? null ) );
		if ( ! empty( $subscription ) ) {
			if ( $subscription === '9' ) {
				$columns['essentials_group'] = array( 'essentials_group', true );
			}
		}

		$has_trialed = sanitize_text_field( wp_unslash( $_GET['has-trialed'] ?? null ) );
		if ( ! empty( $has_trialed ) ) {
			$sortable['trial_end_date'] = array( 'trial_end_date', true );
		}

		return $sortable;
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
			case 'ID':
			case 'email':
			case 'status':
			case 'group':
			case 'subscription':
			case 'old_subscription':
			case 'trial_end_date':
			case 'activated_date':
			case 'renewed_date':
			case 'cancellation_date':
			case 'registered_date':
			case 'had_trial':
			case 'has_upgraded':
			case 'essentials_group':
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
		$orderby = 'status';
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
