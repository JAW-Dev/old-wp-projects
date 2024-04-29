<?php

/**
 * FP Resource Slider
 *
 * Output markup for a slider of resources
 *
 * @return void
 */
function fp_resource_slider( array $details ) {
	if ( 'manual' === $details['type'] ) {
		$heading   = $details['heading'];
		$resources = $details['resources'];
	} elseif ( 'category' === $details['type'] ) {

		$args = array(
			'post_type'      => 'resource',
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			'tax_query'      => array(
				array(
					'taxonomy' => 'resource-cat',
					'field'    => 'term_id',
					'terms'    => $details['category'],
				),
			),
		);

		$resources   = get_posts( $args );
		$term        = get_term( $details['category'] );
		$term_name   = $term->name;
		$term_slug   = $term->slug;
		$heading_url = add_query_arg( 'fwp_resource_category', $term_slug );
		$heading     = "<a href=\"{$heading_url}\">{$term_name}</a>";
	}

	if ( isset( $heading ) && ! empty( $resources ) ) {
		?>
		<div class="resources-slider-outer">
			<div class="wrap">
				<div class="heading"><?php echo $heading; ?></div>
				<div class="resource-slick-slider">
					<?php array_map( 'fp_resource_card', $resources ); ?>
				</div>
			</div>
		</div>
		<?php
	}
}
