<?php

function objectiv_do_speaking_pricing_table( $table_deets = null ) {
	if ( is_array( $table_deets ) && ! empty( $table_deets ) ) {
		echo "<div class='wide-wrap'>";
		objectiv_do_mobile_speaking_pricing_table( $table_deets );
		objectiv_do_desktop_speaking_pricing_table( $table_deets );
		echo '</div>';
	}
}

function objectiv_do_desktop_speaking_pricing_table( $table_deets = null ) {
	?>
		<div class="speaking-pricing-table-outer">
			<div class="speaking-pricing-table-row">
				<div class="speaking-pricing-title-block top-row"></div>
				<?php foreach ( $table_deets as $td ) : ?>
					<?php $speaker_class = obj_id_from_string( $td['name'], false ); ?>
					<div class="speaking-pricing-table-inner-block <?php echo $speaker_class; ?>">
						<span class="speaker-person-name">
							<?php echo $td['name']; ?>
						</span>
					</div>
				<?php endforeach; ?>
			</div>

			<!-- Fee Range Row -->
			<div class="speaking-pricing-table-row">
				<div class="speaking-pricing-title-block">In-Person Fee</div>
				<?php foreach ( $table_deets as $td ) : ?>
					<?php $speaker_class = obj_id_from_string( $td['name'], false ); ?>
					<div class="speaking-pricing-table-inner-block <?php echo $speaker_class; ?>">
						<?php echo $td['fee_range']; ?>
					</div>
				<?php endforeach; ?>
			</div>

			<!-- Travels From Row -->
			<div class="speaking-pricing-table-row">
				<div class="speaking-pricing-title-block">Travels From</div>
				<?php foreach ( $table_deets as $td ) : ?>
					<?php $speaker_class = obj_id_from_string( $td['name'], false ); ?>
					<div class="speaking-pricing-table-inner-block <?php echo $speaker_class; ?>">
						<?php echo $td['travels_from']; ?>
					</div>
				<?php endforeach; ?>
			</div>

			<!-- Webinar Pricing Row -->
			<div class="speaking-pricing-table-row">
				<div class="speaking-pricing-title-block">Virtual Fee</div>
				<?php foreach ( $table_deets as $td ) : ?>
					<?php $speaker_class = obj_id_from_string( $td['name'], false ); ?>
					<div class="speaking-pricing-table-inner-block <?php echo $speaker_class; ?>">
						<?php echo $td['webinar_pricing']; ?>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	<?php
}

function objectiv_do_mobile_speaking_pricing_table( $table_deets = null ) {
	?>
		<div class="mobile-speaking-pricing-table-outer one2grid">
			<?php foreach ( $table_deets as $td ) : ?>
				<?php
					$speaker_class = obj_id_from_string( $td['name'], false );
				?>
				<div class="mobile-speaking-pricing-table__block <?php echo $speaker_class; ?>">
					<?php if ( ! empty( $td['name'] ) ) : ?>
						<h3 class="mobile-speaking-pricing-table__title"><?php echo $td['name']; ?></h3>
					<?php endif; ?>
					<?php if ( ! empty( $td['fee_range'] ) ) : ?>
						<div class="mobile-speaking-pricing-table__fee-range">In-Person Fee: <?php echo $td['fee_range']; ?></div>
					<?php endif; ?>
					<?php if ( ! empty( $td['travels_from'] ) ) : ?>
						<div class="mobile-speaking-pricing-table__travels-from">Travels From: <?php echo $td['travels_from']; ?></div>
					<?php endif; ?>
					<?php if ( ! empty( $td['webinar_pricing'] ) ) : ?>
						<div class="mobile-speaking-pricing-table__webinar-pricing">Virtual Fee: <?php echo $td['webinar_pricing']; ?></div>
					<?php endif; ?>
				</div>
			<?php endforeach; ?>
		</div>
	<?php

}
