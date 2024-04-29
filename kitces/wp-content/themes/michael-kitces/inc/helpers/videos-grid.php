<?php

function objective_videos_grid( $videos = null ) {
	if ( ! empty( $videos ) && is_array( $videos ) ) { ?>
		<div class="videos-grid-wrap">
			<?php foreach ( $videos as $vid ) : ?>
				<div class="videos-grid-row">
					<?php if ( ! empty( $vid['section_title'] ) ) : ?>
						<h3 class="videos-grid-row__title"><?php echo $vid['section_title']; ?></h3>
					<?php endif; ?>
					<?php if ( ! empty( $vid['videos'] ) && is_array( $vid['videos'] ) ) : ?>
						<div class="videos-grid-row__videos one23grid">
							<?php foreach ( $vid['videos'] as $video ) : ?>
								<?php
									$vid_url   = $video['video_url'];
									$vid_thumb = $video['video_thumbnail'];
									$vid_blurb = $video['blurb'];

								if ( is_array( $vid_thumb ) ) {
									$vid_thumb = $vid_thumb['sizes']['medium_large'];
								}
								?>
								<a class="video-modaal videos-grid-row__video" href="<?php echo $vid_url; ?>">
									<div class="videos-grid-row__video-thumbnail" style="background-image: url(<?php echo $vid_thumb; ?>)">
										<div class="play-button"></div>
									</div>

									<?php if ( ! empty( $vid_blurb ) ) : ?>
										<div class="videos-grid-row__blurb"><?php echo $vid_blurb; ?></div>
									<?php endif; ?>
								</a>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>
				</div>
			<?php endforeach; ?>
		</div>
	<?php
	}
}
