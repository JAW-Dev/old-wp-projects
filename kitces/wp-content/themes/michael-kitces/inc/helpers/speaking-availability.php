<?php

function objectiv_speaking_availability_table() {
	$persons = get_field( 'remain_persons', 'option' );

	if ( is_array( $persons ) && ! empty( $persons ) ) {
		echo "<div class='wide-wrap'>";
		objectiv_do_mobile_speaking_availability_table( $persons );
		objectiv_do_desktop_speaking_availability_table( $persons );
		echo '</div>';
	}
}

function objectiv_do_desktop_speaking_availability_table( $persons = null ) {
	?>
	<div class="speaker-availability-table">
		<!-- Title Row -->
		<div class="speaker-availability-table-row">
			<div class="speaker-availability-title-block top-row"></div>
			<?php foreach ( $persons as $p ) : ?>
				<?php $speaker_class = obj_id_from_string( $p['name'], false ); ?>
				<div class="speaker-availability-inner-block <?php echo $speaker_class; ?>">
					<span class="speaker-person-name">
						<?php echo wp_kses_post( $p['name'] ); ?>
					</span>
				</div>
			<?php endforeach; ?>
		</div>

		<!-- Total In-Person Available Row -->
		<div class="speaker-availability-table-row">
			<div class="speaker-availability-title-block">Total In-Person Available</div>
			<?php foreach ( $persons as $p ) : ?>
				<?php $speaker_class = obj_id_from_string( $p['name'], false ); ?>
				<div class="speaker-availability-inner-block <?php echo $speaker_class; ?>">
					<?php echo esc_html( $p['total_availability'] ); ?>
				</div>
			<?php endforeach; ?>
		</div>

		<!-- Remaining Available Row -->
		<?php
		$has_remaining = false;
		foreach ( $persons as $person ) {
			if ( ! empty( $person['remaining_availability'] ) && empty( $person['remaining_availability_virtual'] ) ) {
				$has_remaining = true;
				break;
			}
		}
		if ( $has_remaining ) {
			?>
			<div class="speaker-availability-table-row">
				<div class="speaker-availability-title-block">In-Person Available</div>
				<?php foreach ( $persons as $person ) : ?>
					<?php $speaker_class = obj_id_from_string( $person['name'], false ); ?>
					<div class="speaker-availability-inner-block <?php echo $speaker_class; ?>">
						<?php echo esc_html( $person['remaining_availability'] ); ?>
					</div>
				<?php endforeach; ?>
			</div>
		<?php } ?>

		<!-- Personal Remaining Available Row -->
		<?php
		$has_virtual_remaining = false;
		foreach ( $persons as $person ) {
			if ( ! empty( $person['remaining_availability'] ) && ! empty( $person['remaining_availability_virtual'] ) ) {
				$has_virtual_remaining = true;
				break;
			}
		}
		if ( $has_virtual_remaining ) {
			?>
			<div class="speaker-availability-table-row">
				<div class="speaker-availability-title-block">In-Person Available</div>
				<?php foreach ( $persons as $person ) : ?>
					<?php $speaker_class = obj_id_from_string( $person['name'], false ); ?>
					<div class="speaker-availability-inner-block <?php echo $speaker_class; ?>">
						<?php echo esc_html( $person['remaining_availability'] ); ?>
					</div>
				<?php endforeach; ?>
			</div>

			<div class="speaker-availability-table-row">
				<div class="speaker-availability-title-block">Virtual Remaining</div>
				<?php foreach ( $persons as $person ) : ?>
					<?php $speaker_class = obj_id_from_string( $person['name'], false ); ?>
					<div class="speaker-availability-inner-block <?php echo $speaker_class; ?>">
						<?php echo esc_html( $person['remaining_availability_virtual'] ); ?>
					</div>
				<?php endforeach; ?>
			</div>
		<?php } ?>
	</div>
	<?php
}

function objectiv_do_mobile_speaking_availability_table( $persons = null ) {
	?>
	<div class="mobile-speaker-availability-table one2grid">
		<?php foreach ( $persons as $p ) : ?>
			<?php $speaker_class = obj_id_from_string( $p['name'], false ); ?>
			<div class="mobile-speaker-availability-block tac <?php echo $speaker_class; ?>">
				<?php if ( ! empty( $p['name'] ) ) : ?>
					<h3 class="mobile-speaker-availability-block__title"><?php echo $p['name']; ?></h3>
				<?php endif; ?>

				<?php if ( ! empty( $p['total_availability'] ) ) : ?>
					<div class="mobile-speaker-availability-block__total-available">Total In-Person Available: <?php echo $p['total_availability']; ?></div>
				<?php endif; ?>

				<?php if ( ! empty( $p['remaining_availability'] ) ) : ?>
					<div class="mobile-speaker-availability-block__remaining">Remaining: <?php echo $p['remaining_availability']; ?></div>
				<?php endif; ?>
			</div>
		<?php endforeach; ?>
	</div>
	<?php

}
