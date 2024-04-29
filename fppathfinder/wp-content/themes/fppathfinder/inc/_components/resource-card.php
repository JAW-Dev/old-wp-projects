<?php

/**
 * FP Resource Card
 *
 * Output the markup for a resource card.
 *
 * @param \WP_Post $resource
 *
 * @return void
 */
function fp_resource_card( \WP_Post $resource ) {
	$type = function_exists( 'get_field' ) ? get_field( 'type', $resource->ID ) : '';

	if ( $type === 'interactive-flowchart' && ! fp_is_feature_active( 'flowcharts' ) ) {
		return;
	}

	$title             = $resource->post_title;
	$long_title        = 60 < strlen( $title );
	$fields            = get_fields( $resource->ID );
	$short_description = $fields['short_card_description'] ?? 'IRA Contribution Rules';
	$flags             = $fields['card_flags'] ?? false;
	$type              = $fields['type'] ?? 'flowchart';
	$type              = $type === 'interactive-flowchart' ? 'flowchart' : $type;
	$url               = get_permalink( $resource );
	$locked            = ! rcp_user_can_access( 0, $resource->ID );
	?>
	<div class="resource-card-outer">
		<a href="<?php echo $url; ?>" class="resource-card <?php echo $locked ? 'locked' : ''; ?>">
			<div class="short-description"><?php echo $short_description; ?></div>
			<div class="title <?php echo $long_title ? 'long' : ''; ?>"><?php echo $title; ?></div>
			<div class="card-footer">

				<div class="flags">
					<?php if ( $flags ) : ?>
						<?php foreach ( $flags as $flag ) : ?>
							<div class="flag <?php echo $flag; ?>-flag"><?php echo $flag; ?></div>
						<?php endforeach; ?>
					<?php endif; ?>
				</div>
				<?php fp_get_card_icon( $type ); ?>
			</div>
		</a>
	</div>
	<?php
}

/**
 * FP Get Card Icon
 *
 * @param string $name
 *
 * @return string
 */
function fp_get_card_icon( string $name ) {
	$src = '/wp-content/themes/fppathfinder/assets/icons/src/' . $name . '.png';
	?>
	<img src="<?php echo $src; ?>">
	<?php
}
