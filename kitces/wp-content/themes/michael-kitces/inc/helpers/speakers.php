<?php

function objectiv_do_new_speakers_table( $speakers = null ) {
	if ( is_array( $speakers ) && ! empty( $speakers ) ) {
		echo "<div class='wide-wrap'>";
		objectiv_do_new_speakers_table_mobile( $speakers );
		objectiv_do_new_speakers_table_desk( $speakers );
		echo '</div>';
	}
}


function objectiv_do_new_speakers_table_mobile( $speakers = null ) {
	?>
	<div class="mobile-speakers-table-outer-wrap">
		<?php foreach ( $speakers as $speaker ) : ?>
			<?php
				$img          = $speaker['headshot']['sizes']['large-square'];
				$name         = $speaker['name'];
				$title        = $speaker['title'];
				$fee          = $speaker['speaker_fee'];
				$webinar_fee  = $speaker['webinar_fee'];
				$travels_from = $speaker['travels_from'];
				$specializes  = $speaker['specializes_in'];
				$bio          = $speaker['bio'];
				$name_class   = obj_id_from_string( $name, false );

			?>
			<div class="mobile-speakers-table__speaker <?php echo $name_class; ?>">
				<div class="mobile-speakers-table__image-wrap">
					<img src="<?php echo $img; ?>" alt="<?php echo $name; ?>" class="speaker-person-headshot">
				</div>
				<div class="mobile-speakers-table__details">
					<?php if ( ! empty( $name ) ) : ?>
						<div class="mobile-speakers-table__name"><?php echo $name; ?></div>
					<?php endif; ?>
					<?php if ( ! empty( $title ) ) : ?>
						<div class="mobile-speakers-table__title">Title: <?php echo $title; ?></div>
					<?php endif; ?>
					<?php if ( ! empty( $fee ) ) : ?>
						<div class="mobile-speakers-table__fee">In-Person Fee: <?php echo $fee; ?></div>
					<?php endif; ?>
					<?php if ( ! empty( $webinar_fee ) ) : ?>
						<div class="mobile-speakers-table__webinar-fee">Virtual Fee: <?php echo $webinar_fee; ?></div>
					<?php endif; ?>
					<?php if ( ! empty( $travels_from ) ) : ?>
						<div class="mobile-speakers-travels">Travels From: <?php echo $travels_from; ?></div>
					<?php endif; ?>
					<?php if ( ! empty( $specializes ) ) : ?>
						<div class="mobile-speakers-table__specializes">Specializes In: <?php echo $specializes; ?></div>
					<?php endif; ?>
					<?php if ( ! empty( $bio ) ) : ?>
						<div class="mobile-speakers-table__bio lmb0">Bio: <?php echo $bio; ?></div>
					<?php endif; ?>
				</div>
			</div>
		<?php endforeach; ?>
	</div>
	<?php
}

function objectiv_do_new_speakers_table_desk( $speakers = null ) {
	?>
		<div class="speakers-table-outer-wrap">
			<!-- Top Row -->
			<div class="speakers-table-row speakers-table-top-row">
				<div class="speakers-table-title-block top-row"></div>
				<?php foreach ( $speakers as $speaker ) : ?>
					<?php
					$img        = $speaker['headshot']['sizes']['large-square'];
					$name       = $speaker['name'];
					$name_class = obj_id_from_string( $name, false );

					?>
					<div class="speaker-person-block speaker-top <?php echo $name_class; ?>">
						<img src="<?php echo $img; ?>" alt="<?php echo $name; ?>" class="speaker-person-headshot">
						<span class="speaker-person-name"><?php echo $name; ?></span>
					</div>
				<?php endforeach; ?>
			</div>

			<!-- Title Row -->
			<div class="speakers-table-row">
				<div class="speakers-table-title-block">Title</div>
				<?php foreach ( $speakers as $speaker ) : ?>
					<?php
					$title      = $speaker['title'];
					$name       = $speaker['name'];
					$name_class = obj_id_from_string( $name, false );
					?>
					<div class="speaker-person-block speaker-title <?php echo $name_class; ?>">
						<span class="speaker-person-title"><?php echo $title; ?></span>
					</div>
				<?php endforeach; ?>
			</div>

			<!-- Speaker Fee Row -->
			<div class="speakers-table-row">
				<div class="speakers-table-title-block">In-Person Fee</div>
				<?php foreach ( $speakers as $speaker ) : ?>
					<?php
					$fee        = $speaker['speaker_fee'];
					$name       = $speaker['name'];
					$name_class = obj_id_from_string( $name, false );
					?>
					<div class="speaker-person-block speaker-fee <?php echo $name_class; ?>">
						<span class="speaker-person-fee"><?php echo $fee; ?></span>
					</div>
				<?php endforeach; ?>
			</div>

			<!-- Webinar Fee Row -->
			<?php
			$has_webinar = false;
			foreach ( $speakers as $speaker ) {
				if ( ! empty( $speaker['webinar_fee'] ) ) {
					$has_webinar = true;
					break;
				}
			}
			if ( $has_webinar ) {
				?>
				<div class="speakers-table-row">
					<div class="speakers-table-title-block">Virtual Fee</div>
					<?php foreach ( $speakers as $speaker ) : ?>
						<?php
						$webinar_fee = $speaker['webinar_fee'];
						$name        = $speaker['name'];
						$name_class  = obj_id_from_string( $name, false );
						?>
						<div class="speaker-person-block webinar-fee <?php echo $name_class; ?>">
							<span class="speaker-webinar-fee"><?php echo $webinar_fee; ?></span>
						</div>
					<?php endforeach; ?>
				</div>
			<?php } ?>

			<!-- Travels From Row -->
			<div class="speakers-table-row">
				<div class="speakers-table-title-block">Travels From</div>
				<?php foreach ( $speakers as $speaker ) : ?>
					<?php
					$travels    = $speaker['travels_from'];
					$name       = $speaker['name'];
					$name_class = obj_id_from_string( $name, false );
					?>
					<div class="speaker-person-block speaker-travels <?php echo $name_class; ?>">
						<span class="speaker-person-travels"><?php echo $travels; ?></span>
					</div>
				<?php endforeach; ?>
			</div>

			<!-- Specializes In Row -->
			<div class="speakers-table-row">
				<div class="speakers-table-title-block">Specializes In</div>
				<?php foreach ( $speakers as $speaker ) : ?>
					<?php
					$specializes = $speaker['specializes_in'];
					$name        = $speaker['name'];
					$name_class  = obj_id_from_string( $name, false );
					?>
					<div class="speaker-person-block speaker-specializes <?php echo $name_class; ?>">
						<span class="speaker-person-specializes"><?php echo $specializes; ?></span>
					</div>
				<?php endforeach; ?>
			</div>

			<!-- Bio Row -->
			<div class="speakers-table-row">
				<div class="speakers-table-title-block">Bio</div>
				<?php foreach ( $speakers as $speaker ) : ?>
					<?php
					$bio        = $speaker['bio'];
					$name       = $speaker['name'];
					$name_class = obj_id_from_string( $name, false );
					?>
					<div class="speaker-person-block speaker-bio <?php echo $name_class; ?>">
						<span class="speaker-person-bio lmb0"><?php echo $bio; ?></span>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
		<?php
}
