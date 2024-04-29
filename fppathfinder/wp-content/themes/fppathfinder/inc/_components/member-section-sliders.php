<?php

/**
 * FP Member Section Sliders
 *
 * Output the category sliders for the member section.
 *
 * @return void
 */
function fp_member_section_sliders() {

	/**
	 * When we get the info for $sliders_fields, the 'resources' key on each array is uneccesarily nested.
	 * This function unnests them.
	 */
	$prepare_slider_details = function( array $field ) {
		if ( $field['resources'] ) {
			$field['resources'] = array_map(
				function( array $resources ) {
					return $resources['resource'];
				},
				$field['resources']
			);
		}

		return $field;
	};

	$sliders_fields           = get_field( 'member_section_sliders', 'option' );
	$display_favorites_slider = get_field( 'member_section_display_favorites_slider', 'option' );
	$sliders                  = array_map( $prepare_slider_details, $sliders_fields );

	if ( ! rcp_user_has_active_membership() ) {
		array_unshift( $sliders, fp_get_free_resources_slider() );
	} else {
		$user_id      = get_current_user_id();
		$can_favorite = function_exists( 'fp_user_can_favorite' ) && fp_user_can_favorite( $user_id );

		if ( $display_favorites_slider && $can_favorite ) {
			array_unshift( $sliders, fp_get_favorite_resources_slider() );
		}
	}
	?>
		<div class="member-section-resources facetwp-template">
			<?php array_map( 'fp_resource_slider', $sliders ); ?>
		</div>
	<?php
}

/**
 * FP Get Free Resources
 *
 * @return array \WP_Post
 */
function fp_get_free_resources() {
	$args           = array(
		'post_type'      => 'resource',
		'post_status'    => 'publish',
		'posts_per_page' => -1,
	);
	$resources      = get_posts( $args );
	$is_free        = function ( \WP_Post $resource ) {
		return ! rcp_is_restricted_content( $resource->ID );
	};
	$free_resources = array_filter( $resources, $is_free );

	return $free_resources;
}

/**
 * FP Get Free Resources Slider
 *
 * Get the details to generate the slider of free resources.
 *
 * @return array
 */
function fp_get_free_resources_slider() {
	return array(
		'type'      => 'manual',
		'category'  => false,
		'heading'   => 'Free Resources',
		'resources' => fp_get_free_resources(),
	);
}

function fp_get_favorite_resources() {

	if ( ! is_user_logged_in() ) {
		return null;
	}

	$user_id         = get_current_user_id();
	$favorited_items = get_user_meta( $user_id, 'favorited_items', true );

	if ( empty( $favorited_items ) ) {
		return null;
	}

	$args      = array(
		'post_type'      => 'resource',
		'post_status'    => 'publish',
		'posts_per_page' => -1,
		'post__in'       => array_keys( $favorited_items ),
	);
	$resources = get_posts( $args );

	return $resources;
}

function fp_get_favorite_resources_slider() {
	return array(
		'type'      => 'manual',
		'category'  => false,
		'heading'   => get_field( 'member_section_favorites_title', 'option' ),
		'resources' => fp_get_favorite_resources(),
	);
}
