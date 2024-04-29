<section id="<?php echo esc_attr( $id ); ?>" class="feature-comparison-checklist-chart <?php echo $padding_classes; ?>">
	<div class="wrap">
		<?php if ( $title ) : ?>
			<?php obj_section_header( $title ); ?>
		<?php endif; ?>

		<div class="feature-comparison-checklist-inner">
			<div class="tier-labels">
				<div class="essentials-label tier-label">Essentials</div>
				<div class="deluxe-label tier-label">Deluxe</div>
				<div class="premier-label tier-label">Premier</div>
			</div>
			<?php foreach ( $features as $feature ) : ?>
				<?php
				$tooltip_key    = bin2hex( random_bytes( 8 ) );
				$tooltip_button = $feature['tooltip'] ? '<svg class="tooltip-button" data-tooltip-key="' . $tooltip_key . '" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M10 20a10 10 0 1 1 0-20 10 10 0 0 1 0 20zm2-13c0 .28-.21.8-.42 1L10 9.58c-.57.58-1 1.6-1 2.42v1h2v-1c0-.29.21-.8.42-1L13 9.42c.57-.58 1-1.6 1-2.42a4 4 0 1 0-8 0h2a2 2 0 1 1 4 0zm-3 8v2h2v-2H9z"/></svg>' : '';
				?>
				<div class="feature">
					<div class="text">
						<p><?php echo $feature['text'] . $tooltip_button; ?></p>
						<?php if ( $feature['tooltip'] ) : ?>
							<p class="tooltip-content" id="<?php echo $tooltip_key; ?>" style="display: none;"><?php echo $feature['tooltip']; ?></p>
						<?php endif; ?>
					</div>
					<div class="box" data-tier="Essentials">
						<?php if ( in_array( 'essentials', $feature['tiers'] ) ) : ?>
							<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M0 11l2-2 5 5L18 3l2 2L7 18z"/></svg>
						<?php endif; ?>
					</div>
					<div class="box" data-tier="Deluxe">
						<?php if ( in_array( 'deluxe', $feature['tiers'] ) ) : ?>
							<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M0 11l2-2 5 5L18 3l2 2L7 18z"/></svg>
						<?php endif; ?>
					</div>
					<div class="box" data-tier="Premier">
						<?php if ( in_array( 'premier', $feature['tiers'] ) ) : ?>
							<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M0 11l2-2 5 5L18 3l2 2L7 18z"/></svg>
						<?php endif; ?>
					</div>
				</div>
			<?php endforeach; ?>
			<div class="tier-column-end-caps">
				<div class="essentials-end-cap tier-end-cap"></div>
				<div class="deluxe-end-cap tier-end-cap"></div>
				<div class="premier-end-cap tier-end-cap"></div>
			</div>
		</div>
	</div>
</section>
