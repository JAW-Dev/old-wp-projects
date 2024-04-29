<?php

function objectiv_output_link_image( $link_img = null, $class = null ) {
	if ( is_array( $link_img ) && ! empty( $link_img ) ) {
		$image       = $link_img['image']['sizes']['large'];
		$image_name  = $link_img['image']['name'];
		$link        = $link_img['link'];
		$link_img    = false;
		$link_title  = null;
		$link_url    = null;
		$link_target = null;

		if ( is_array( $link ) && ! empty( $link ) ) {
			$link_img    = true;
			$link_title  = $link['title'];
			$link_url    = $link['url'];
			$link_target = $link['target'];
		}

		if ( $link_img ) {
			echo '<a href="' . $link_url . '" target="' . $link_target . '" class="' . $class . '">';
		} else {
			echo '<div class="' . $class . '">';
		}
		echo '<img class="' . $image_name . '" src="' . $image . '" alt="' . $link_title . '" >';
		if ( $link_img ) {
			echo '</a>';
		} else {
			echo '</div>';
		}
	}
}

function objectiv_ouput_icons_grid( $icons = null ) {
	?>
		<?php if ( ! empty( $icons ) ) : ?>
			<div class="icons-grid-wrap">
				<?php foreach ( $icons as $i ) : ?>
					<?php
					$icon    = $i['image']['url'];
					$desc    = $i['image']['description'];
					$link    = $i['link'];
					$l_title = $link['title'];
					$l_url   = $link['url'];
				?>
					<?php if ( ! empty( $link ) ) : ?>
						<a href="<?php echo $l_url; ?>" class="icon-grid-link">
							<?php if ( ! empty( $icon ) ) : ?>
								<div class="icon-wrapper">
									<?php echo obj_svg( $icon, false, $desc ); ?>
								</div>
							<?php endif; ?>
							<?php if ( ! empty( $l_title ) ) : ?>
								<div class="icon-block-title f24"><?php echo $l_title; ?></div>
							<?php endif; ?>
						</a>
					<?php endif; ?>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	<?php
}
