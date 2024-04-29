<?php

function mkl_get_all_lists() {

	$args = array(
		'numberposts' => -1,
		'post_type'   => 'list',
		'post_status' => 'publish',
		'orderby'     => 'title',
		'order'       => 'ASC',
	);

	return get_posts( $args );

}

function mkl_get_all_posts_for_list( $post_type ) {
	$args = array(
		'post_type'      => $post_type,
		'posts_per_page' => '-1',
		'order'          => 'ASC',
		'orderby'        => 'title',
		'post_status'    => 'publish',
	);

	return get_posts( $args );
}

function mkl_get_list_details( $post_id = null ) {

	if ( empty( $post_id ) ) {
		return null;
	}

	$list_details = array();

	$list_details['post_id']       = $post_id;
	$list_details['post_type_key'] = 'list-' . $post_id;
	$list_details['name_singular'] = get_field( 'item_name_singular', $post_id );
	$list_details['name_plural']   = get_field( 'item_name_plural', $post_id );
	$list_details['slug']          = get_field( 'slug', $post_id );
	$list_details['item_fields']   = get_field( 'item_fields', $post_id );

	$list_details['list_view_page_title']  = get_field( 'list_view_page_title', $post_id );
	$list_details['list_view_sub_title']   = get_field( 'list_view_sub_title', $post_id );
	$list_details['list_view_intro_blurb'] = get_field( 'list_view_intro_blurb', $post_id );

	$list_details['list_block_details'] = array(
		'title'         => get_field( 'list_block_title_area', $post_id ),
		'sub_title'     => get_field( 'list_block_sub_title_area', $post_id ),
		'header_line'   => get_field( 'list_block_header_line_area', $post_id ),
		'description'   => get_field( 'list_block_description', $post_id ),
		'header_detail' => get_field( 'list_block_header_detail', $post_id ),
		'left_detail'   => get_field( 'list_block_left_detail', $post_id ),
		'right_detail'  => get_field( 'list_block_right_detail', $post_id ),
	);

	$use_block_setting_from_archive                  = get_field( 'use_block_settings_from_archive', $post_id );
	$list_details['use_block_settings_from_archive'] = $use_block_setting_from_archive;

	if ( $use_block_setting_from_archive ) {
		$list_details['single_block_details'] = $list_details['list_block_details'];
	} else {
		$list_details['single_block_details'] = array(
			'title'         => get_field( 'single_block_title_area', $post_id ),
			'sub_title'     => get_field( 'single_block_sub_title_area', $post_id ),
			'header_line'   => get_field( 'single_block_header_line_area', $post_id ),
			'description'   => get_field( 'single_block_description', $post_id ),
			'header_detail' => get_field( 'single_block_header_detail', $post_id ),
			'left_detail'   => get_field( 'single_block_left_detail', $post_id ),
			'right_detail'  => get_field( 'single_block_right_detail', $post_id ),
		);
	}

	$list_details['disable_filters'] = get_field( 'disable_filters', $post_id );
	$list_details['filters_title']   = get_field( 'filters_title', $post_id );
	$list_details['filters_filters'] = get_field( 'filters_filters', $post_id );

	return $list_details;

}

// Note, this has to be run a bit carefully because
// it has to happen after the lists are set up
function mkl_get_lists_with_details() {
	$all_lists    = mkl_get_all_lists();
	$return_array = array();

	if ( is_array( $all_lists ) && ! empty( $all_lists ) ) {
		foreach ( $all_lists as $list ) {
			$post_id       = $list->ID;
			$list_details  = mkl_get_list_details( $post_id );
			$post_type_key = mk_key_value( $list_details, 'post_type_key' );

			$return_array[ $post_type_key ] = $list_details;
		}
	}
}


function mkl_get_field( $selector = null, $post_id = null, $format_value = true, $use_acf = false ) {
	$result = null;

	if ( $use_acf ) {
		// Allow for just using ACF
		$result = get_field( $selector, $post_id, $format_value );
	} else {

		// A helpful little function that doesn't do too much but returns an id we can use
		$post_id = acf_get_valid_post_id( $post_id );

		// Grab options setting if that is what is set, otherwise get post meta
		if ( $post_id == 'options' ) {
			$result = get_option( 'options_' . $selector );
		} else {
			$result = get_post_meta( $post_id, $selector, true );
		}

		// Fall back to ACF field selector if we don't have anything
		if ( empty( $result ) ) {
			$result = get_field( $selector, $post_id, $format_value );
		}
	}

	if ( empty( $result ) ) {
		$result = false;
	}

	return $result;
}

function mkl_list_post_id( $post_type = null ) {

	if ( empty( $post_type ) ) {
		return null;
	}

	return str_replace( 'list-', '', $post_type );
}

function mkl_archive_hero( $title = null, $sub_title = null ) {
	if ( ! empty( $title ) ) {
		?>
			<section class="page-section spt spb bg-light-blue">
				<div class="wrap">
					<div class="head-content tac mw-800 mlra">
						<?php if ( ! empty( $title ) ) : ?>
							<h1 class="head-title wt fwb f48"><?php echo $title; ?></h1>
						<?php endif; ?>
						<?php if ( ! empty( $sub_title ) ) : ?>
							<div class="head-sub-title wt fwm f26 last-child-margin-bottom-0"><?php echo $sub_title; ?></div>
						<?php endif; ?>
					</div>
				</div>
			</section>
		<?php
	}
}

function mkl_archive_content( $content = null ) {
	if ( ! empty( $content ) ) {
		?>
			<section class="page-section spt spb">
				<div class="wrap ml0 mw-970 mlra">
					<?php echo wpautop( $content ); ?>
				</div>
			</section>
		<?php
	}
}

function mkl_archive_filters_section( $filters_markup = null, $filter_title = null ) {
	if ( ! empty( $filters_markup ) ) {
		?>
		<section class="page-section spt spb bg-light-gray list-filter-section">
			<div class="wrap ml0 mw-970 mlra">
				<div class="list-filter-section-inner">
				<?php if ( ! empty( $filter_title ) ) : ?>
						<div class="f18 fwb tac"><?php echo $filter_title; ?></div>
					<?php endif; ?>
					<div class="list-filters-wrap">
					<?php echo $filters_markup; ?>
						<div class="list-filter-reset">
							Reset Filters
						</div>
					</div>
				</div>
			</div>
		</section>
			<?php
	}
}

function mkl_get_filters_data( $list_details = null ) {
	if ( empty( $list_details ) ) {
		return null;
	}

	$list_filters = mk_key_value( $list_details, 'filters_filters' );
	$list_fields  = mk_key_value( $list_details, 'item_fields' );

	$filters_data = array();

	if ( ! empty( $list_filters ) && is_array( $list_filters ) ) {
		foreach ( $list_filters as $filter ) {
			$filter_field = mk_key_value( $filter, 'field' );

			foreach ( $list_fields as $field ) {
				$field_slug = mk_key_value( $field, 'slug' );

				if ( $filter_field === $field_slug ) {
					$filters_data[ $filter_field ]['field']  = $field;
					$filters_data[ $filter_field ]['filter'] = $filter;
				}
			}
		}
	}

	return $filters_data;
}

function mkl_lists_get_filter_data_string_for_post( $post_id = null, $filter_data = null ) {
	$filter_data_array = array();

	if ( ! empty( $post_id ) && ! empty( $filter_data ) && is_array( $filter_data ) ) {
		foreach ( $filter_data as $filter_key => $details ) {

			$field_details = mk_key_value( $details, 'field' );
			$filter_type   = mk_key_value( $field_details, 'type' );
			$field_value   = mk_get_field( $filter_key, $post_id );

			$filter_multi = false;
			if ( 'csl' === $filter_type ) {
				$filter_multi = true;
			}

			if ( 'csl' === $filter_type || 'text-short' === $filter_type ) {
				if ( $filter_multi ) {
					$pieces = explode( ',', $field_value );

					foreach ( $pieces as $piece_name ) {
						$piece_name = trim( $piece_name );
						$piece_slug = sanitize_title_with_dashes( $piece_name );

						if ( ! empty( $piece_name ) ) {
							array_push( $filter_data_array, $filter_key . '_' . $piece_slug );
						}
					}
				} else {
					$piece_name = trim( $field_value );
					$piece_slug = sanitize_title_with_dashes( $piece_name );

					if ( ! empty( $piece_name ) ) {
						array_push( $filter_data_array, $filter_key . '_' . $piece_slug );
					}
				}
			}
		}
	}

	return implode( ' ', $filter_data_array );
}


function mkl_lists_list( $posts = null, $list_details = null ) {

	$display_list = ! empty( $posts ) && ! empty( $list_details );

	if ( $display_list ) {
		$filters_data   = mkl_get_filters_data( $list_details );
		$block_settings = mk_key_value( $list_details, 'list_block_details' );
		?>
		<section class="page-section spt spb mk-list-section">
			<div class="wrap">
				<div class="first-child-margin-top-0">
					<?php foreach ( $posts as $post ) : ?>
						<?php
						$post_id        = $post->ID;
						$filter_classes = mkl_lists_get_filter_data_string_for_post( $post_id, $filters_data );
						?>
						<div class="mk-list-block active mt1 <?php echo $filter_classes; ?>">
							<?php mkl_list_block_inner( $post_id, $block_settings, false, $list_details ); ?>
						</div>
					<?php endforeach; ?>
					<div class="list-error-none tac f22">Sorry, there are no matching items, try a broader filter.</div>
				</div>

			</div>
		</section>
		<?php
	}

}

function mkl_list_block_inner( $post_id = null, $list_block_settings = null, $title_h1 = false, $list_details = null ) {
	$item_fields           = mk_key_value( $list_details, 'item_fields' );
	$title_details         = mk_key_value( $list_block_settings, 'title' );
	$sub_title_details     = mk_key_value( $list_block_settings, 'sub_title' );
	$header_line_details   = mk_key_value( $list_block_settings, 'header_line' );
	$description_details   = mk_key_value( $list_block_settings, 'description' );
	$header_detail_details = mk_key_value( $list_block_settings, 'header_detail' );
	$left_detail_details   = mk_key_value( $list_block_settings, 'left_detail' );
	$right_detail_details  = mk_key_value( $list_block_settings, 'right_detail' );

	?>
	<?php if ( $left_detail_details ) : ?>
		<div class="left-detail">
			<?php mkl_list_item_details( $post_id, $left_detail_details, $item_fields ); ?>
		</div>
	<?php endif; ?>
	<div class="right-details">
		<div class="list-block-header">
			<div class="left">
				<?php if ( $title_details ) : ?>
					<?php if ( $title_h1 ) : ?>
						<h1 class="title f24 mb0"><?php mkl_list_item_details( $post_id, $title_details, $item_fields ); ?></h1>
					<?php else : ?>
						<h3 class="title f24 mb0"><?php mkl_list_item_details( $post_id, $title_details, $item_fields ); ?></h3>
					<?php endif; ?>
				<?php endif; ?>
				<?php if ( $sub_title_details ) : ?>
					<div class="sub-title"><?php mkl_list_item_details( $post_id, $sub_title_details, $item_fields ); ?></div>
				<?php endif; ?>
				<?php if ( $header_line_details ) : ?>
					<div class="header-line">
						<?php mkl_list_item_details( $post_id, $header_line_details, $item_fields ); ?>
					</div>
				<?php endif; ?>
			</div>
			<div class="right">
				<?php if ( $header_detail_details ) : ?>
					<div class="header-detail"><?php mkl_list_item_details( $post_id, $header_detail_details, $item_fields ); ?></div>
				<?php endif; ?>
			</div>
		</div>
		<?php if ( $description_details || $right_detail_details ) : ?>
			<div class="desc-wrap">
				<?php if ( $description_details ) : ?>
					<div class="left last-child-margin-bottom-0">
						<?php mkl_list_item_details( $post_id, $description_details, $item_fields ); ?>
					</div>
				<?php endif; ?>
				<?php if ( $right_detail_details ) : ?>
					<div class="right">
						<?php mkl_list_item_details( $post_id, $right_detail_details, $item_fields ); ?>
					</div>
				<?php endif; ?>
			</div>
		<?php endif; ?>
	</div>
	<?php
}

function mkl_list_item_details( $post_id = null, $details_array = null, $item_fields = null ) {
	if ( ! empty( $post_id ) && is_array( $details_array ) ) {
		foreach ( $details_array as $detail ) {
			$type        = mk_key_value( $detail, 'type' );
			$field       = mk_key_value( $detail, 'field' );
			$label       = mk_key_value( $detail, 'label' );
			$field_value = mkl_list_item_field_value( $post_id, $field );

			$multi = false;
			foreach ( $item_fields as $field ) {
				$field_slug = mk_key_value( $field, 'slug' );
				$field_type = mk_key_value( $field, 'type' );

				if ( $field_slug === $field && 'csl' === $field_type ) {
					$multi = true;
				}
			}

			if ( ! empty( $field_value ) ) {
				echo "<div class='list-item-detail'>";
				if ( $label ) {
					echo "<span class='label fwb'>$label&nbsp;</span>";
				}
				if ( 'link' === $type ) {
					mkl_list_item_link( $post_id, $field_value, $detail );
				} elseif ( 'text' === $type ) {
					mkl_list_item_text( $post_id, $field_value, $detail, $multi );
				}
				echo '</div>';
			}
		}
	}
}

function mkl_list_item_field_value( $post_id = null, $field ) {
	if ( 'post_title' === $field ) {
		return get_the_title( $post_id );
	}

	return mk_get_field( $field, $post_id );
}

function mkl_list_item_link( $post_id = null, $field_value = null, $detail = null ) {
	$link_to  = mk_key_value( $detail, 'link_to' );
	$new_tab  = mk_key_value( $detail, 'new_tab' );
	$link_url = null;

	if ( 'field' === $link_to ) {
		$link_url = $field_value;
	}

	if ( 'post_url' === $link_to ) {
		$link_url = get_permalink( $post_id );
	}

	if ( ! empty( $field_value ) && ! empty( $link_url ) ) {
		?>
		<?php if ( $new_tab ) : ?>
			<div class="external-link-wrap">
				<a class="external-link" target="_blank" href="<?php echo $link_url; ?>"><?php echo $field_value; ?></a>
			</div>
		<?php else : ?>
			<a href="<?php echo $link_url; ?>"><?php echo $field_value; ?></a>
		<?php endif; ?>

		<?php
	}
}

function mkl_list_item_text( $post_id, $field_value, $multi = false ) {
	if ( $multi ) {
		$pieces       = explode( ',', $field_value );
		$final_pieces = array();

		foreach ( $pieces as $piece_name ) {
			$piece_name = trim( $piece_name );

			if ( ! empty( $piece_name ) ) {
				array_push( $final_pieces, $piece_name );
			}
		}

		echo implode( ', ', $final_pieces );
	} else {
		echo $field_value;
	}

}
