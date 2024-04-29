<?php
/**
 * Endpoint.
 *
 * @package    Kitces_Star_Rating
 * @subpackage Kitces_Star_Rating/Inlcudes/Classes
 * @author     Objectiv
 * @copyright  Copyright (c) Plugin_Boilerplate_Date, Objectiv
 * @license    GPL-2.0
 * @since      1.0.0
 */

namespace KitcesStarRating\Includes\Classes;

use KitcesStarRating\Includes\Classes\Tables;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}


/**
 * Endpoint.
 *
 * @author Jason Witt
 * @since  1.0.0
 */
class Endpoint {

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
		add_action( 'rest_api_init', [ $this, 'endpoint' ] );
	}

	/**
	 * Endpoint
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function endpoint() {
		register_rest_route(
			'mk/v1',
			'/postratings',
			[
				'method'   => \WP_REST_Server::READABLE,
				'callback' => [ $this, 'get' ],
				'args'     => [
					'date' => [
						'required' => false,
						'type'     => 'string',
					],
					'date_from' => [
						'required' => false,
						'type'     => 'string',
					],
					'date_to'   => [
						'required' => false,
						'type'     => 'string',
					],
					'number'   => [
						'required' => false,
						'type'     => 'integer',
					],
					'order'   => [
						'required' => false,
						'type'     => 'string',
					],
				],
			]
		);
	}

	/**
	 * Get
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param object $request The API request.
	 *
	 * @return string
	 */
	public function get( $request ) {
		$date      = ! empty( $request->get_param( 'date' ) ) ? $request->get_param( 'date' ) : '';
		$date_from = ! empty( $request->get_param( 'date_from' ) ) ? $request->get_param( 'date_from' ) : '';
		$date_to   = ! empty(  $request->get_param( 'date_to' ) ) ? $request->get_param( 'date_to' ) : '';
		$number    = ! empty(  $request->get_param( 'number' ) ) ? $request->get_param( 'number' ) : 99;
		$order     = ! empty(  $request->get_param( 'order' ) ) ? $request->get_param( 'order' ) : 'DESC';

		$args = [
			'post_type'      => 'post',
			'posts_per_page' => $number,
			'order'          => $order,
		];

		if ( ! empty( $date_from ) && ! empty( $date_to ) ) {
			$args['date_query'] = [
				'column' => 'post_date',
				'after'  => $date_from,
				'before' => $date_to,
			];
		}

		if ( ! empty( $date_from ) && empty( $date_to ) ) {
			$args['date_query'] = [
				'column' => 'post_date',
				'after'  => $date_from,
			];
		}

		if ( empty( $date_from ) && ! empty( $date_to ) ) {
			$args['date_query'] = [
				'column' => 'post_date',
				'before' => $date_to,
			];
		}

		if ( empty( $date_from ) && empty( $date_to ) && ! empty( $date ) ) {
			$args['date_query'] = [
				'column' => 'post_date',
				'before' => $date,
				'after'  => $date,
				'inclusive' => true,
			];
		}

		$query     = new \WP_Query( $args );
		$posts     = $query->posts;
		$new_array = [];

		foreach ( $posts as $post ) {
			$get_categories = wp_get_post_categories( $post->ID );
			$categories     = [];
			$data           = [];

			foreach ( $get_categories as $category ) {
				$cat          = get_category( $category );
				$categories[] = $cat->name;
			}

			$entries = $this->group_array( Tables\Query::get_post_entries( $post->ID ), 'post_id' );

			if ( ! empty( $entries ) ) {
				foreach ( $entries as $entries_key => $entries_value ) {

					$ratings = [];
					$version = isset( $item['version'] ) ? $item['version'] : '';

					foreach ( $entries_value as $item ) {
						if ( $version === 'header' ) {
							$ratings[0] = $item;
						}
						if ( $version === 'stars' ) {
							$ratings[1] = $item;
						}
						if ( $version === 'nerds' ) {
							$ratings[2] = $item;
						}
					}

					ksort( $ratings );

					$total_ratings = 0;
					$averages      = 0;
					$count         = 0;

					foreach ( $ratings as $key => $value ) {
						$total   = $value['ratings_count'];
						$average = $this->get_average( $value );

						$total_ratings += $total;

						$averages += $average;
						$count++;
					}

					if ( $averages === 0 || $count ) {
						$total_average = 0;
					} else {
						$total_average = round( $averages / $count, 2 );
					}

					$data[] = array(
						'total'    => $total_ratings,
						'averages' => $total_average,
					);
				}
			}

			$return = array_column( $data, 'total' );
			array_multisort( $return, SORT_DESC, $data );

			$new_array[] = [
				'ID'          => $post->ID,
				'post_author' => $post->post_author,
				'post_date'   => $post->post_date,
				'post_title'  => $post->post_title,
				'categories'  => $categories,
				'ratings'     => isset( $data[0] ) ? $data[0] : [
					'total'    => 0,
					'averages' => 0,
				],
			];
		}

		return wp_json_encode( $new_array );
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
			'total_one'   => $value['total_one'] ?? 0 * $levels['total_one'],
			'total_two'   => $value['total_two'] ?? 0 * $levels['total_two'],
			'total_three' => $value['total_three'] ?? 0 * $levels['total_three'],
			'total_four'  => $value['total_four'] ?? 0 * $levels['total_four'],
			'total_five'  => $value['total_five'] ?? 0 * $levels['total_five'],
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
	 * @param array  $array The entries array.
	 * @param string $group What key to group the entries by.
	 *
	 * @return array
	 */
	public function group_array( $array, $group ) {
		$temp = array();

		// Doing this to convert all nested items to arrays.
		$array = wp_json_encode( $array );
		$array = json_decode( $array, true );

		if ( is_array( $array ) ) {
			foreach ( $array as $key => $value ) {

				$group_value = isset( $value[ $group ] ) ? $value[ $group ] : '';

				if ( empty( $group_value ) ) {
					return $array;
				}

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
}
