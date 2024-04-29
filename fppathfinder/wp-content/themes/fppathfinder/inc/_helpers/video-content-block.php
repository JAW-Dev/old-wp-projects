<?php

function obj_do_video_content_block( $block = null, $light_theme = true ) {
	if ( ! empty( $block ) && is_array( $block ) ) {
		$video                   = obj_key_value( $block, 'video' );
		$video_play_button       = obj_key_value( $block, 'video_play_button' );
		$initial_image           = obj_key_value( $block, 'initial_image' );
		$content_position        = obj_key_value( $block, 'content_position' );
		$title_size              = obj_key_value( $block, 'title_size' );
		$title                   = obj_key_value( $block, 'title' );
		$blurb                   = obj_key_value( $block, 'blurb' );
		$button                  = obj_key_value( $block, 'button' );
		$text_color_class        = '';
		$button_color_class      = '';
		$play_button_color_class = 'light-play';
		$content_side_class      = '';

		if ( 'left' === $content_position ) {
			$content_side_class = 'content-left';
		}

		if ( ! $light_theme ) {
			$button_color_class = 'red-button';
			$text_color_class   = 'light-text';
		}

		if ( 'dark' === $video_play_button ) {
			$play_button_color_class = 'dark-play';
		}

		?>
		<div class=" video-content-flex-block <?php echo $text_color_class; ?> <?php echo $content_side_class; ?>">
			<?php if ( ! empty( $initial_image ) && is_array( $initial_image ) ) : ?>
				<div class="image-side">
					<?php if ( ! empty( $video ) ) : ?>
						<a href="<?php echo $video; ?>" class="modaal-video">
					<?php endif; ?>
						<div class="image-wrap <?php echo $play_button_color_class; ?>">
							<?php echo wp_get_attachment_image( $initial_image['id'], 'large' ); ?>
							<?php if ( ! empty( $video ) ) : ?>
								<div class="play-wrap"><svg fill="none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52"><path fill-rule="evenodd" clip-rule="evenodd" d="M26 51.6A25.6 25.6 0 1026 .4a25.6 25.6 0 000 51.2zm-1.424-34.662A3.2 3.2 0 0019.6 19.6v12.8a3.2 3.2 0 004.976 2.662l9.6-6.4a3.2 3.2 0 000-5.324l-9.6-6.4z" fill="currentColor"/></svg></div>
							<?php endif; ?>
						</div>
					<?php if ( ! empty( $video ) ) : ?>
						</a>
					<?php endif; ?>
				</div>
			<?php endif; ?>
			<div class="content-side">
				<?php if ( ! empty( $title ) ) : ?>
					<?php if ( $title_size ) : ?>
						<h1 class=""><?php echo $title; ?></h1>
					<?php else : ?>
						<h3 class=""><?php echo $title; ?></h3>
					<?php endif; ?>
				<?php endif; ?>
				<?php if ( ! empty( $blurb ) ) : ?>
					<div class=" fmt0 lmb0"><?php echo wpautop( $blurb ); ?></div>
				<?php endif; ?>
				<?php if ( ! empty( $button ) ) : ?>
					<div class=" basemt">
						<?php echo objectiv_link_button( $button, 'button ' . $button_color_class ); ?>
					</div>
				<?php endif; ?>
			</div>
		</div>
		<?php
	}
}
