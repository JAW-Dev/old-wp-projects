<?php

namespace MKLB;

class ListItemPostTypeManager {

	private $list_post_types = array();

	public function __construct() {
		add_action( 'acf/init', array( $this, 'createListItemPostTypes' ) );
		add_action( 'acf/init', array( $this, 'createListItemPostACFFields' ) );
		add_filter( 'enter_title_here', array( $this, 'renameIndividualListItemPostTitleInput' ) );
	}

	public function createListItemPostTypes() {

		$lists = mkl_get_all_lists();

		if ( ! empty( $lists ) && is_array( $lists ) ) {
			foreach ( $lists as $list_post ) {
				$post_id       = $list_post->ID;
				$list_details  = mkl_get_list_details( $post_id );
				$post_type_key = function_exists( 'mk_key_value' ) ? mk_key_value( $list_details, 'post_type_key' ) : '';

				$this->list_post_types[ $post_type_key ] = $list_details;

				// Go ahead and set up each individual post type
				$this->createIndividualListItemPostType( $list_details );
			}
		}
	}

	public function createIndividualListItemPostType( $list_details = null ) {

		$name_singular = function_exists( 'mk_key_value' ) ? mk_key_value( $list_details, 'name_singular' ) : '';
		$name_plural   = function_exists( 'mk_key_value' ) ? mk_key_value( $list_details, 'name_plural' ) : '';
		$post_type_key = function_exists( 'mk_key_value' ) ? mk_key_value( $list_details, 'post_type_key' ) : '';
		$slug          = function_exists( 'mk_key_value' ) ? mk_key_value( $list_details, 'slug' ) : '';

		$create_post_type = $name_singular && $name_plural && $post_type_key && $slug;

		if ( $create_post_type ) {
			$labels = array(
				'name'                  => _x( $name_plural, 'Post Type General Name', 'mk' ),
				'singular_name'         => _x( $name_singular, 'Post Type Singular Name', 'mk' ),
				'menu_name'             => __( $name_plural, 'mk' ),
				'name_admin_bar'        => __( $name_plural, 'mk' ),
				'archives'              => __( $name_plural . ' Archives', 'mk' ),
				'parent_item_colon'     => __( 'Parent Item:', 'mk' ),
				'all_items'             => __( 'All ' . $name_plural, 'mk' ),
				'add_new_item'          => __( 'Add New ' . $name_singular, 'mk' ),
				'add_new'               => __( 'Add New', 'mk' ),
				'new_item'              => __( 'New ' . $name_singular, 'mk' ),
				'edit_item'             => __( 'Edit ' . $name_singular, 'mk' ),
				'update_item'           => __( 'Update ' . $name_singular, 'mk' ),
				'view_item'             => __( 'View ' . $name_singular, 'mk' ),
				'search_items'          => __( 'Search ' . $name_plural, 'mk' ),
				'not_found'             => __( 'Not found', 'mk' ),
				'not_found_in_trash'    => __( 'Not found in Trash', 'mk' ),
				'featured_image'        => __( 'Featured Image', 'mk' ),
				'set_featured_image'    => __( 'Set featured image', 'mk' ),
				'remove_featured_image' => __( 'Remove featured image', 'mk' ),
				'use_featured_image'    => __( 'Use as featured image', 'mk' ),
				'insert_into_item'      => __( 'Insert into ' . $name_singular, 'mk' ),
				'uploaded_to_this_item' => __( 'Uploaded to this ' . $name_singular, 'mk' ),
				'items_list'            => __( $name_plural . ' list', 'mk' ),
				'items_list_navigation' => __( $name_plural . ' list navigation', 'mk' ),
				'filter_items_list'     => __( 'Filter ' . $name_plural . ' list', 'mk' ),
			);
			$args   = array(
				'label'               => __( $name_singular, 'mk' ),
				'labels'              => $labels,
				'supports'            => array( 'title' ),
				'taxonomies'          => array(),
				'hierarchical'        => false,
				'public'              => true,
				'show_ui'             => true,
				'show_in_menu'        => true,
				'menu_position'       => 5,
				'menu_icon'           => 'dashicons-editor-ul',
				'show_in_admin_bar'   => true,
				'show_in_nav_menus'   => true,
				'can_export'          => true,
				'has_archive'         => true,
				'exclude_from_search' => true,
				'publicly_queryable'  => true,
				'capability_type'     => 'page',
				'rewrite'             => array(
					'with_front' => false,
					'slug'       => $slug,
				),
			);
			register_post_type( $post_type_key, $args );
		}
	}

	public function renameIndividualListItemPostTitleInput() {

		if ( ! empty( $this->list_post_types ) ) {
			$screen = get_current_screen();

			foreach ( $this->list_post_types as $list_post_type ) {
				if ( $list_post_type['post_type_key'] === $screen->post_type ) {
					return $list_post_type['name_singular'] . ' Title';
				}
			}
		}

	}

	public function createListItemPostACFFields() {

		if ( ! empty( $this->list_post_types ) && function_exists( 'acf_add_local_field_group' ) ) {
			foreach ( $this->list_post_types as $list_post_type ) {
				if ( array_key_exists( 'item_fields', $list_post_type ) ) {
					$item_fields  = $list_post_type['item_fields'];
					$list_slug    = $list_post_type['slug'];
					$post_id      = $list_post_type['post_id'];
					$fields_array = array();

					if ( ! empty( $item_fields ) && is_array( $item_fields ) ) {

						// Go through and set up each ACF field from the parent list post
						foreach ( $item_fields as $raw_field ) {
							$set_up_field = array_key_exists( 'label', $raw_field ) && array_key_exists( 'slug', $raw_field ) && array_key_exists( 'type', $raw_field );

							if ( $set_up_field ) {
								$raw_slug = preg_replace( '/\s+/', '_', $raw_field['slug'] );

								$cooked_field = array(
									'key'               => 'field_' . $list_slug . '_' . $raw_slug,
									'label'             => $raw_field['label'],
									'name'              => $raw_slug,
									'type'              => $raw_field['type'],
									'instructions'      => '',
									'required'          => 0,
									'conditional_logic' => 0,
									'wrapper'           => array(
										'width' => '',
										'class' => '',
										'id'    => '',
									),
									'default_value'     => '',
									'placeholder'       => '',
									'prepend'           => '',
									'append'            => '',
									'maxlength'         => '',
								);

								if ( 'text-short' === $raw_field['type'] ) {
									$cooked_field['type'] = 'text'; // regular text
								} elseif ( 'csl' === $raw_field['type'] ) {
									$cooked_field['type'] = 'text'; // comma separated list
								} elseif ( 'text-long' === $raw_field['type'] ) {
									$cooked_field['type'] = 'textarea'; // Text area
								} elseif ( 'tf' === $raw_field['type'] ) {
									$cooked_field['type']          = 'true_false'; // True False
									$cooked_field['default_value'] = 0;
									$cooked_field['ui']            = 1;
									$cooked_field['ui_on_text']    = '';
									$cooked_field['ui_off_text']   = '';
								} elseif ( 'url' === $raw_field['type'] ) {
									$cooked_field['type'] = 'url';
								}

								array_push( $fields_array, $cooked_field );
							}
						}
					}

					acf_add_local_field_group(
						array(
							'key'                   => 'group_' . $list_slug . '_' . $post_id,
							'title'                 => $list_post_type['name_singular'] . ' Details',
							'fields'                => $fields_array,
							'location'              => array(
								array(
									array(
										'param'    => 'post_type',
										'operator' => '==',
										'value'    => $list_post_type['post_type_key'],
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
		}
	}
}
