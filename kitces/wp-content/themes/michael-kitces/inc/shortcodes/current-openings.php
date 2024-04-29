<?php

function mkitces_current_openings( $atts ) {
	$current_openings = mk_get_field( 'current_openings', get_the_ID(), true, true );
	ob_start();
	?>
	<?php if ( ! empty( $current_openings ) && is_array( $current_openings ) ) : ?>
		<div class="current-openings-wrap">
			<?php foreach ( $current_openings as $co ) : ?>
				<?php
					$title        = mk_key_value( $co, 'title' );
					$blurb        = mk_key_value( $co, 'blurb' );
					$full_details = mk_key_value( $co, 'full_details' );
					$opening_id   = uniqid();
				?>
				<div class="current-opening bg-light-gray mt2 mb2 bpa bbr">
					<h2 class="opening-title"><?php echo $title; ?></h2>

					<div class="opening-blurb last-child-margin-bottom-0">
						<?php echo $blurb; ?>
					</div>

					<?php if ( ! empty( $full_details ) ) : ?>
						<div class="mt1">
							<a class="opening-view-more no-slide" href="#<?php echo $opening_id; ?>">Learn More</a>
							<div id="<?php echo $opening_id; ?>" class="opening-full-details hidden">
								<h2 class="opening-title"><?php echo $title; ?></h2>
								<?php echo $full_details; ?>
							</div>
						</div>
					<?php endif; ?>
				</div>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>
	<?php
	return ob_get_clean();
}

add_shortcode( 'current-openings', 'mkitces_current_openings' );
