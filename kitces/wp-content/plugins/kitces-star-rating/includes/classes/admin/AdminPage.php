<?php
/**
 * Admin Page.
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

if ( ! class_exists( __NAMESPACE__ . '\\AdminPage' ) ) {

	/**
	 * Admin Page.
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 */
	class AdminPage {

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
			add_action( 'admin_menu', array( $this, 'admin_menu' ) );
			add_filter( 'set-screen-option', array( $this, 'table_set_option' ), 10, 3 );
		}

		/**
		 * Admin Menu
		 *
		 * @author Jason Witt
		 * @since  1.0.0
		 *
		 * @return void
		 */
		public function admin_menu() {
			$hook = add_menu_page(
				__( 'Star Ratings', 'kitces_star_rating' ),
				__( 'Star Ratings', 'kitces_star_rating' ),
				'manage_options',
				'star-ratings-test',
				array( $this, 'render' ),
				'dashicons-star-half',
				1
			);

			add_action( "load-$hook", array( $this, 'add_options' ) );
		}

		/**
		 * Table set option
		 *
		 * @author Jason Witt
		 * @since  1.0.0
		 *
		 * @param boolean $status Whether to save or skip saving the screen option value. Default false.
		 * @param string  $option The option name.
		 * @param int     $value  The number of rows to use.
		 *
		 * @return int
		 */
		public function table_set_option( $status, $option, $value ) {
			return $value;
		}

		/**
		 * Add Options
		 *
		 * @author Jason Witt
		 * @since  1.0.0
		 *
		 * @return void
		 */
		public function add_options() {
			$option = 'per_page';
			$args   = array(
				'label'   => 'Posts Per Page',
				'default' => 10,
				'option'  => 'posts_per_page',
			);
			add_screen_option( $option, $args );
			$this->table = new ResultsTable();
		}

		/**
		 * Render
		 *
		 * @author Jason Witt
		 * @since  1.0.0
		 *
		 * @return void
		 */
		public function render() {
			$this->table->prepare_items();
			?>
			<div class="wrap">
				<h2><?php echo esc_html__( 'Star Ratings', 'kitces_star_rating' ); ?></h2>
				<?php $this->table->views(); ?>
				<form method="get">
					<input type="hidden" name="page" value="<?php echo esc_html( $_REQUEST['page'] ); // phpcs:ignore ?>" />
					<?php
					$this->table->search_box( __( 'Search', 'kitces_star_rating' ), 'search_id' );
					?>
				</form>
				<?php $this->table->display(); ?>
			</div>
			<?php
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

			foreach ( $array as $key => $value ) {

				$group_value = $value[ $group ];

				unset( $array[ $key ][ $group ] );

				if ( ! array_key_exists( $group_value, $temp ) ) {
					$temp[ $group_value ] = array();
				}

				$data = $array[ $key ];

				$temp[ $group_value ][] = $data;
			}

			return $temp;
		}
	}
}
