<?php

namespace FP_REST_API;

class Checklists_Endpoint extends Custom_Endpoint {

	public function __construct() {}

	public function get_endpoint_text() {
		return 'checklists';
	}

	public function get_methods() {
		return array( 'GET' );
	}

	public function handle_request( array $token, \WP_REST_Request $wp_rest_request ) {
		parent::validate_scope( 'read_checklists', $token['scope'] );

		$data     = $this->get_response_data( $token, $wp_rest_request );
		$response = new \WPOAuth2\Response( $data );
		$response->send();
		exit;
	}

	private function get_response_data( array $token, \WP_REST_Request $wp_rest_request ) {
		$category   = sanitize_text_field( $_GET['category'] );
		$search     = sanitize_text_field( $_GET['search'] );
		$tax_query  = ! $category ? false : array(
			array(
				'taxonomy' => 'checklist-cat',
				'terms'    => $category,
				'field'    => 'slug',
			),
		);
		$query_args = array(
			'post_type' => 'checklist',
			'nopaging'  => true,
			'tax_query' => $tax_query,
			's'         => $search,
		);
		$items      = get_posts( array_filter( $query_args ) );
		$checklists = array_map( array( $this, 'prepare_checklist_post_for_rest_response' ), $items );

		return  array( 'checklists' => $checklists );
	}

	private function prepare_checklist_post_for_rest_response( \WP_Post $post ) {
		$name       = get_the_title( $post->ID );
		$url        = get_permalink( $post->ID );
		$categories = $this->get_checklist_categories( $post->ID );

		return array(
			'name'       => $name,
			'categories' => $categories,
			'url'        => $url,
		);
	}

	private function get_checklist_categories( $post_id ) {
		$categories = get_the_terms( $post_id, 'checklist-cat' );

		if ( ! $categories ) {
			return false;
		}

		$prepare_category = function ( \WP_Term $term ) {
			return array(
				'name' => $term->name,
				'slug' => $term->slug,
			);
		};

		return array_map( $prepare_category, $categories );
	}
}
