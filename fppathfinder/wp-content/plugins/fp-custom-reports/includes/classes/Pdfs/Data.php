<?php
/**
 * Data
 *
 * @package    Custom_Reports
 * @subpackage Custom_Reports/Includes/Classes/Enterprise
 * @author     Objectiv
 * @copyright  Copyright (c) 2021, Objectiv
 * @license    GPL-2.0
 * @version    1.0.0
 */

namespace CustomReports\Pdfs;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

/**
 * Data
 *
 * @author Jason Witt
 * @since  1.0.0
 */
class Data {

	/**
	 * Initialize the class
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function __construct() {}

	/**
	 * Init
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return array
	 */
	public static function get_data() {
		$data = self::get_logs();

		return $data;
	}

	/**
	 * Get Trial Members
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return array
	 */
	public static function get_logs() {
		return self::query_logs();
	}

	/**
	 * Query Members
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return array
	 */
	public static function query_logs() {
		$args = [
			'post_type'      => 'download',
			'posts_per_page' => 99999999,
			'fields'         => 'ids',
		];

		$query = new \WP_Query( $args );

		$data = array();

		foreach ( $query->posts as $post_id ) {
			$meta_data = get_post_meta( $post_id, 'times_downloaded', true );

			if ( empty( $meta_data ) ) {
				$data[] = array(
					'ID'        => $post_id,
					'title'     => get_the_title( $post_id ),
					'downloads' => '0',
				);

				continue;
			}

			$data[] = array(
				'ID'        => $post_id,
				'title'     => get_the_title( $post_id ),
				'downloads' => (string) count( $meta_data ),
			);
		}

		$columns = array_column( $data, 'downloads' );

		array_multisort( $columns, SORT_DESC, $data );

		return $data;
	}
}
