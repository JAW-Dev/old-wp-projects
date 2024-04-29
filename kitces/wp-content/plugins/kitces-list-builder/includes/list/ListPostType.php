<?php

namespace MKLB;

class ListPostType {

	public function __construct() {
		add_action( 'init', array( $this, 'createListPostType' ) );
		add_action( 'acf/init', array( $this, 'addACFFieldsToListPostType' ) );
		add_filter( 'enter_title_here', array( $this, 'renameListPostTitleInput' ), 1 );
		add_action( 'admin_head-post.php', array( $this, 'flushPermalinks' ) );

		// Dynamically add select options for fields
		$acf_block_fields = array(
			'list_block_title_area',
			'list_block_sub_title_area',
			'list_block_header_line_area',
			'list_block_description',
			'list_block_header_detail',
			'list_block_left_detail',
			'list_block_right_detail',
			'single_block_title_area',
			'single_block_sub_title_area',
			'single_block_header_line_area',
			'single_block_description',
			'single_block_header_detail',
			'single_block_left_detail',
			'single_block_right_detail',
			'filters_filters',
		);

		foreach ( $acf_block_fields as $field ) {
			add_filter( 'acf/load_field/name=' . $field, array( $this, 'addSelectOptionsForFields' ) );
		}
	}

	public function createListPostType() {
		$labels = array(
			'name'                  => _x( 'Resources', 'Post Type General Name', 'mk' ),
			'singular_name'         => _x( 'List', 'Post Type Singular Name', 'mk' ),
			'menu_name'             => __( 'Resources', 'mk' ),
			'name_admin_bar'        => __( 'Resources', 'mk' ),
			'archives'              => __( 'Resources Archives', 'mk' ),
			'parent_item_colon'     => __( 'Parent Item:', 'mk' ),
			'all_items'             => __( 'All Resources', 'mk' ),
			'add_new_item'          => __( 'Add New List', 'mk' ),
			'add_new'               => __( 'Add New', 'mk' ),
			'new_item'              => __( 'New List', 'mk' ),
			'edit_item'             => __( 'Edit List', 'mk' ),
			'update_item'           => __( 'Update List', 'mk' ),
			'view_item'             => __( 'View List', 'mk' ),
			'search_items'          => __( 'Search Resources', 'mk' ),
			'not_found'             => __( 'Not found', 'mk' ),
			'not_found_in_trash'    => __( 'Not found in Trash', 'mk' ),
			'featured_image'        => __( 'Featured Image', 'mk' ),
			'set_featured_image'    => __( 'Set featured image', 'mk' ),
			'remove_featured_image' => __( 'Remove featured image', 'mk' ),
			'use_featured_image'    => __( 'Use as featured image', 'mk' ),
			'insert_into_item'      => __( 'Insert into List', 'mk' ),
			'uploaded_to_this_item' => __( 'Uploaded to this List', 'mk' ),
			'items_list'            => __( 'Resources list', 'mk' ),
			'items_list_navigation' => __( 'Resources list navigation', 'mk' ),
			'filter_items_list'     => __( 'Filter Lists list', 'mk' ),
		);
		$args   = array(
			'label'               => __( 'List', 'mk' ),
			'labels'              => $labels,
			'supports'            => array( 'title' ),
			'taxonomies'          => array(),
			'hierarchical'        => false,
			'public'              => false,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'menu_position'       => 5,
			'menu_icon'           => 'dashicons-feedback',
			'show_in_admin_bar'   => true,
			'show_in_nav_menus'   => true,
			'can_export'          => true,
			'has_archive'         => false,
			'exclude_from_search' => true,
			'publicly_queryable'  => true,
			'capability_type'     => 'page',
		);
		register_post_type( 'list', $args );
	}

	public function renameListPostTitleInput( $title ) {
		$screen = get_current_screen();

		if ( 'list' === $screen->post_type ) {
			$title = 'List Title';
		}

		return $title;
	}

	public function addACFFieldsToListPostType() {

		if ( function_exists( 'acf_add_local_field_group' ) ) {
			acf_add_local_field_group(
				array(
					'key'                   => 'group_5fdbbeeed5903',
					'title'                 => 'Kitces List Details',
					'fields'                => mkl_acf_list_field_settings(),
					'location'              => array(
						array(
							array(
								'param'    => 'post_type',
								'operator' => '==',
								'value'    => 'list',
							),
						),
					),
					'menu_order'            => 0,
					'position'              => 'normal',
					'style'                 => 'default',
					'label_placement'       => 'top',
					'instruction_placement' => 'label',
					'hide_on_screen'        => '',
					'active'                => true,
					'description'           => '',
				)
			);
		}
	}

	public function addSelectOptionsForFields( $field ) {

		$post_id     = null;
		$item_fields = null;

		if ( isset( $_GET['post'] ) ) {
			$post_id     = $_GET['post'];
			$item_fields = get_field( 'item_fields', $post_id );
		}

		$fields_options = $this->getSelectOptionsForFields( $item_fields );

		if ( is_array( $fields_options ) && ! empty( $fields_options ) ) {
			if ( array_key_exists( 'sub_fields', $field ) && is_array( $field['sub_fields'] ) ) {
				foreach ( $field['sub_fields'] as $key => $sub_field ) {
					if ( 'field' === $sub_field['name'] ) {
						$field['sub_fields'][ $key ]['choices'] = $fields_options;
					}
				}
			}
		}

		return $field;
	}

	public function getSelectOptionsForFields( $item_fields ) {
		$fields_choices = array(
			'post_title' => 'List Item Title',
		);

		if ( is_array( $item_fields ) && ! empty( $item_fields ) ) {
			foreach ( $item_fields as $option ) {
				$fields_choices[ $option['slug'] ] = $option['label'];
			}
		}

		return $fields_choices;
	}

	public function flushPermalinks() {
		global $post_type;

		if ( 'list' === $post_type ) {
			flush_rewrite_rules( false );
		}
	}
}
