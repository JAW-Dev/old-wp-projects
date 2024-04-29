<?php

function mk_team_member_block( $tmb = null ) {
	if ( empty( $tmb ) ) {
		exit;
	}

	$type             = mk_key_value( $tmb, 'type' );
	$image            = mk_key_value( $tmb, 'image' );
	$name             = mk_key_value( $tmb, 'name' );
	$certifications   = mk_key_value( $tmb, 'certifications' );
	$job_title        = mk_key_value( $tmb, 'job_title' );
	$strengths_finder = mk_key_value( $tmb, 'strengths_finder' );
	$blurb            = mk_key_value( $tmb, 'blurb' );
	$full_bio_image   = mk_key_value( $tmb, 'full_bio_image' );
	$full_bio         = mk_key_value( $tmb, 'full_bio' );
	$headline         = mk_key_value( $tmb, 'headline' );
	$position         = mk_key_value( $tmb, 'position' );
	$available_text   = mk_key_value( $tmb, 'available_text' );
	$link             = mk_key_value( $tmb, 'link' );
	$modal_id         = uniqid();

	if ( $image ) {
		$image = wp_get_attachment_image( $image['ID'], 'large-square' );
	}

	if ( $full_bio_image ) {
		$full_bio_image = wp_get_attachment_image( $full_bio_image['ID'], 'large-square' );
	} else {
		$full_bio_image = $image;
	}

	?>
	<div class="team-member-block soft-shadow <?php echo $type; ?> tac bg-white">
		<?php if ( ! empty( $full_bio ) && ! empty( $modal_id ) && $type === 'member' ) : ?>
			<a href="#<?php echo $modal_id; ?>" class="team-member-block-bio-link mthalf f16 no-slide">
		<?php endif; ?>
		<?php if ( ! empty( $image ) ) : ?>
			<?php echo $image; ?>
		<?php endif; ?>

		<div class="pt2 pb2 pl1 pr1 first-child-margin-top-0 last-child-margin-bottom-0 mw-400 mlra">
			<?php if ( $type === 'member' ) : ?>
				<h3 class="mbhalf f24 no-transform fwb"><?php echo $name; ?></h3>

				<?php if ( ! empty( $certifications ) ) : ?>
					<div class="mthalf f16"><?php echo $certifications; ?></div>
				<?php endif; ?>

				<?php if ( ! empty( $job_title ) ) : ?>
					<div class="mthalf f16 fwb"><?php echo $job_title; ?></div>
				<?php endif; ?>

				<?php if ( ! empty( $strengths_finder ) ) : ?>
					<div class="mthalf f16"><b>StrengthsFinder:</b> <?php echo $strengths_finder; ?></div>
				<?php endif; ?>

				<?php if ( ! empty( $blurb ) ) : ?>
					<div class="mthalf f16"><b>Nerd Cred:</b> <?php echo $blurb; ?></div>
				<?php endif; ?>

				<?php if ( ! empty( $full_bio ) ) : ?>
					<div class="team-member-block-bio-fake-link mthalf f16 no-slide">Bio</div>

					<div id="<?php echo $modal_id; ?>" class="hidden" >
						<div class="tac">
							<?php if ( ! empty( $full_bio_image ) ) : ?>
								<div class="mw-200 mlra">
									<?php echo $full_bio_image; ?>
								</div>
							<?php endif; ?>

							<h3 class="mbhalf mt1 f24 no-transform fwb"><?php echo $name; ?></h3>

							<?php if ( ! empty( $certifications ) ) : ?>
								<div class="mthalf f16"><?php echo $certifications; ?></div>
							<?php endif; ?>

							<?php if ( ! empty( $job_title ) ) : ?>
								<div class="mthalf f16"><?php echo $job_title; ?></div>
							<?php endif; ?>

							<?php if ( ! empty( $strengths_finder ) ) : ?>
								<div class="mthalf f16"><b>StrengthsFinder:</b> <?php echo $strengths_finder; ?></div>
							<?php endif; ?>
						</div>

						<div class="mthalf">
							<?php echo $full_bio; ?>
						</div>
					</div>
				<?php endif; ?>
			<?php endif; ?>

			<?php if ( $type === 'opening' ) : ?>
				<h3 class="mbhalf f24 no-transform fwb"><?php echo $headline; ?></h3>

				<?php if ( ! empty( $position ) ) : ?>
					<div class="mthalf f16 fwb"><?php echo $position; ?></div>
				<?php endif; ?>

				<?php if ( ! empty( $available_text ) ) : ?>
					<div class="mthalf f16"><?php echo $available_text; ?></div>
				<?php endif; ?>

				<?php if ( ! empty( $link ) ) : ?>
					<a href="<?php echo $link['url']; ?>" target="<?php echo $link['target']; ?>" class="mthalf f16"><?php echo $link['title']; ?></a>
				<?php endif; ?>

			<?php endif; ?>
		</div>

		<?php if ( ! empty( $full_bio ) && ! empty( $modal_id ) && $type === 'member' ) : ?>
			</a>
		<?php endif; ?>
	</div>
	<?php
}
